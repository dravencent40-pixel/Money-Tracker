@php
    $uid = $uid ?? 'page';
    $compact = $compact ?? false;
    $type = old('type', $transaction->type ?? 'expense');
    $walletId = old('wallet_id', $transaction->wallet_id ?? '');
    $categoryId = old('category_id', $transaction->category_id ?? '');
@endphp

<form id="{{ $uid }}-form" method="POST" action="{{ $action }}" class="{{ $compact ? 'space-y-3' : 'space-y-4' }}">
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif
    @if ($redirect ?? null)
        <input type="hidden" name="redirect" value="{{ $redirect }}">
    @endif

    <div>
        <span class="label">Tipe</span>
        <div class="grid grid-cols-2 gap-1 rounded-xl bg-slate-100 p-1" role="radiogroup" aria-label="Tipe transaksi">
            <label class="cursor-pointer">
                <input type="radio" name="type" value="expense" class="peer sr-only" @checked($type === 'expense')>
                <span class="block rounded-lg px-3 py-2 text-center text-sm font-medium text-slate-500 transition peer-checked:bg-white peer-checked:text-cost-600 peer-checked:shadow-sm">Pengeluaran</span>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="type" value="income" class="peer sr-only" @checked($type === 'income')>
                <span class="block rounded-lg px-3 py-2 text-center text-sm font-medium text-slate-500 transition peer-checked:bg-white peer-checked:text-cash-600 peer-checked:shadow-sm">Pemasukan</span>
            </label>
        </div>
        @error('type')
            <p class="mt-1 text-xs text-cost-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $uid }}-wallet" class="label">Dompet</label>
        <select id="{{ $uid }}-wallet" name="wallet_id" required class="input">
            @foreach ($wallets as $w)
                <option value="{{ $w->id }}" @selected($walletId == $w->id)>
                    {{ $w->name }}{{ $w->current_balance != 0 ? ' — Rp ' . number_format($w->current_balance, 0, ',', '.') : '' }}
                </option>
            @endforeach
        </select>
        @error('wallet_id')
            <p class="mt-1 text-xs text-cost-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $uid }}-category" class="label">Kategori</label>
        <select id="{{ $uid }}-category" name="category_id" class="input">
            <option value="">— Tanpa kategori —</option>
            @foreach ($categories as $c)
                <option value="{{ $c->id }}" data-type="{{ $c->type }}" @selected($categoryId == $c->id)>
                    {{ $c->icon ? $c->icon . ' ' : '' }}{{ $c->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="{{ $uid }}-amount" class="label">Nominal (Rp)</label>
        <input id="{{ $uid }}-amount" type="number" name="amount" step="0.01" min="0" required
               value="{{ old('amount', $transaction->amount ?? '') }}" placeholder="0" class="input money">
        @error('amount')
            <p class="mt-1 text-xs text-cost-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $uid }}-date" class="label">Tanggal</label>
        <input id="{{ $uid }}-date" type="date" name="date" required
               value="{{ old('date', isset($transaction) ? $transaction->date->toDateString() : now()->toDateString()) }}"
               class="input">
        @error('date')
            <p class="mt-1 text-xs text-cost-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $uid }}-note" class="label">Catatan (opsional)</label>
        <input id="{{ $uid }}-note" type="text" name="note" maxlength="255"
               value="{{ old('note', $transaction->note ?? '') }}" placeholder="mis. Makan siang di kantin"
               class="input">
    </div>

    <button type="submit" class="btn-primary w-full">
        {{ $method === 'PUT' ? 'Simpan Perubahan' : 'Simpan Transaksi' }}
    </button>
</form>

<script>
    (function () {
        const form = document.getElementById('{{ $uid }}-form');
        if (!form) return;

        const categorySelect = form.querySelector('#{{ $uid }}-category');
        const options = Array.from(categorySelect.options);

        function filterCategories() {
            const type = form.querySelector('input[name="type"]:checked')?.value ?? 'expense';
            options.forEach(opt => {
                opt.hidden = opt.value !== '' && opt.dataset.type !== type;
            });
            if (categorySelect.selectedOptions[0]?.hidden) {
                const firstVisible = options.find(o => !o.hidden);
                if (firstVisible) categorySelect.value = firstVisible.value;
            }
        }

        form.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.addEventListener('change', filterCategories);
        });

        filterCategories();
    })();
</script>
