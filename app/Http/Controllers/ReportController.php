<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', Carbon::now()->format('Y-m'));

        $totalIncome = Transaction::income()->inMonth($month)->sum('amount');
        $totalExpense = Transaction::expense()->inMonth($month)->sum('amount');
        $net = $totalIncome - $totalExpense;
        $savingsRate = $totalIncome > 0 ? round(($net / $totalIncome) * 100, 1) : null;

        $byCategory = Transaction::expense()
            ->inMonth($month)
            ->selectRaw('category_id, SUM(amount) as total, COUNT(*) as jumlah_transaksi')
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('total')
            ->get();

        $transactions = Transaction::with(['category', 'wallet'])
            ->inMonth($month)
            ->orderByDesc('date')
            ->get();

        return view('reports.index', compact(
            'month',
            'totalIncome',
            'totalExpense',
            'net',
            'savingsRate',
            'byCategory',
            'transactions'
        ));
    }

    public function export(Request $request)
    {
        $month = $request->query('month', Carbon::now()->format('Y-m'));

        $transactions = Transaction::with(['category', 'wallet'])
            ->inMonth($month)
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $stream = fopen('php://temp', 'r+');

        fputcsv($stream, ['Tanggal', 'Tipe', 'Kategori', 'Dompet', 'Nominal', 'Catatan']);

        foreach ($transactions as $t) {
            fputcsv($stream, [
                $t->date->toDateString(),
                $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                $t->category->name ?? '-',
                $t->wallet->name,
                $t->amount,
                $t->note ?? '',
            ]);
        }

        rewind($stream);
        $content = stream_get_contents($stream);
        fclose($stream);

        return response("\xEF\xBB\xBF".$content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-'.$month.'.csv"',
        ]);
    }
}
