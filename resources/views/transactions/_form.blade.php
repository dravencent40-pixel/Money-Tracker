<form method="POST" action="{{ $action }}" class="bg-white border border-slate-200 rounded-lg border border-slate-200 p-5 max-w-md space-y-4">
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Tipe</label>
        <select name="type" id="type" required class="w-full rounded-md border-slate-300 bg-slate-100 text-slate-700 text-sm">
            <option value="expense" {{ old('type', $transaction->type ?? 'expense') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
            <option value="income" {{ old('type', $transaction->type ?? '') === 'income' ? 'selected' : '' }}>Pemasukan</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Dompet</label>
        <select name="wallet_id" required class="w-full rounded-md border-slate-300 bg-slate-100 text-slate-700 text-sm">
            @foreach ($wallets as $w)
                <option value="{{ $w->id }}" {{ old('wallet_id', $transaction->wallet_id ?? '') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
            @endforeach
        </select>
    </div>

    <div id="category-field">
        <label class="block text-xs font-medium text-slate-500 mb-1">Kategori</label>
        <select name="category_id" id="category_id" class="w-full rounded-md border-slate-300 bg-slate-100 text-slate-700 text-sm">
            @foreach ($categories as $c)
                <option value="{{ $c->id }}" data-type="{{ $c->type }}"
                    {{ old('category_id', $transaction->category_id ?? '') == $c->id ? 'selected' : '' }}>
                    {{ $c->name }} ({{ $c->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Nominal (Rp)</label>
        <input type="number" name="amount" step="0.01" min="0" required
               value="{{ old('amount', $transaction->amount ?? '') }}" class="w-full rounded-md border-slate-300 bg-slate-100 text-slate-700 text-sm">
    </div>

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Tanggal</label>
        <input type="date" name="date" required
               value="{{ old('date', isset($transaction) ? $transaction->date->toDateString() : now()->toDateString()) }}"
               class="w-full rounded-md border-slate-300 bg-slate-100 text-slate-700 text-sm">
    </div>

    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Catatan (opsional)</label>
        <input type="text" name="note" value="{{ old('note', $transaction->note ?? '') }}" class="w-full rounded-md border-slate-300 bg-slate-100 text-slate-700 text-sm">
    </div>

    @error('amount')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror

    <button type="submit" class="bg-amber-500 text-zinc-950 font-medium text-sm px-4 py-2 rounded-md hover:bg-amber-400">Simpan</button>
</form>

<script>
    const typeEl = document.getElementById('type');
    const categorySelect = document.getElementById('category_id');
    const options = Array.from(categorySelect.options);

    function filterCategories() {
        const type = typeEl.value;
        options.forEach(opt => {
            opt.hidden = opt.dataset.type !== type;
        });
        if (categorySelect.selectedOptions[0]?.hidden) {
            const firstVisible = options.find(o => !o.hidden);
            if (firstVisible) categorySelect.value = firstVisible.value;
        }
    }
    typeEl.addEventListener('change', filterCategories);
    filterCategories();
</script>
