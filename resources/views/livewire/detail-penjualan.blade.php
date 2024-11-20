<div>
    @if (session()->has('message'))
        <div class="p-4 text-green-500 text-center">
            {{ session('message') }}
        </div>
    @endif
    <div class="flex justify-between gap-6 mt-6 mx-6">
        <div class="w-full">
            <div class="max-w-auto sm:px-6 lg:px-8">
                <div
                    class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-stretch gap-4 mb-4">
                        <div class="">
                            <x-input-label>
                                Tanggal
                            </x-input-label>
                            <x-text-input type="date" class="w-auto block" name="tanggal" id="tanggal"
                                value="{{ optional($detail->tanggal)->format('Y-m-d') }}" readonly></x-text-input>
                        </div>
                        <div class="">
                            <x-input-label>
                                No. Transaksi
                            </x-input-label>
                            <x-text-input type="text" class="w-auto block" name="no_transaksi" id="no_transaksi"
                                value="{{ $detail->id }}" readonly></x-text-input>
                        </div>
                        <div class="">
                            <x-input-label>
                                Nama Pelanggan
                            </x-input-label>
                            <x-text-input type="text" class="w-auto block" name="nama_pelanggan" id="nama_pelanggan"
                                value="{{ $detail->nama_pelanggan }}" readonly></x-text-input>
                        </div>
                        <div class="">
                            <x-input-label>
                                No. Whatsapp
                            </x-input-label>
                            <x-text-input type="number" class="w-auto block" name="whatsapp" id="whatsapp"
                                value="{{ $detail->whatsapp }}" readonly></x-text-input>
                        </div>
                        <div class="">
                            <x-input-label>
                                Nama Kasir
                            </x-input-label>
                            <x-text-input type="text" class="w-auto block" value="{{ $detail->kasir->name }}"
                                readonly></x-text-input>
                        </div>
                    </div>
                    <div
                        class="p-6 text-gray-900 dark:text-gray-100 overflow-auto bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mt-6">
                        <table class="table table-auto min-w-full">
                            <thead class="font-medium text-lg text-gray-700 dark:text-gray-300">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Barang / Item</th>
                                    <th>Kuatitas</th>
                                    <th>Satuan</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($detail->penjualan_detail as $item)
                                    <tr class="hover:bg-gray-700 rounded">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ number_format($item->kuantitas, 0) }}</td>
                                        <td>{{ $item->satuan }}</td>
                                        <td>{{ number_format($item->harga_satuan, 0) }}</td>
                                        <td>{{ number_format($item->subtotal, 0) }}</td>
                                        <td>
                                            <x-danger-button wire:click="destroy('{{ $item->id }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </x-danger-button>
                                        </td>
                                    </tr>
                                @empty
                                    <td colspan="6" class="text-center">Data Item Belum Ada</td>
                                @endforelse
                                <tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-1/4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg max-w-auto sm:px-6 lg:p-6">
                <div class="">
                    <x-input-label>
                        <span class="text-green-500">Total Belanja</span>
                    </x-input-label>
                    <x-text-input type="text" class="w-full block"
                        value="{{ number_format($detail->total_harga, 0,'',',') }}" readonly></x-text-input>
                </div>
                <div class="mt-3">
                    <x-input-label>
                        <span class="text-yellow-500">Tunai / DP (Cash)</span>
                    </x-input-label>
                    <x-text-input type="number" class="w-full block" name="tunai" id="tunai" wire:model="tunai" value="{{ number_format($detail->tunai, 0,'',',') }}"
                        readonly></x-text-input>
                    @error('tunai')
                        <span class="text-red-800 error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-3">
                    <x-input-label>
                        <span class="text-yellow-500">Debit / DP (Transfer/QRIS)</span>
                    </x-input-label>
                    <x-text-input type="number" class="w-full block" name="debit" id="debit" wire:model="debit" value="{{ number_format($detail->debit, 0,'',',') }}"
                        readonly></x-text-input>
                    @error('debit')
                        <span class="text-red-800 error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-3">
                    <x-input-label>
                        <span class="text-blue-500">Sisa Hutang (Kredit)</span>
                    </x-input-label>
                    <x-text-input type="text" class="w-full block" name="kredit" id="kredit" wire:model="kredit" value="{{ number_format($detail->kredit, 0,'',',') }}"
                        readonly></x-text-input>
                    @error('kredit')
                        <span class="text-red-800 error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-3 flex justify-between gap-4">
                    {{-- <div>
                        <x-input-label>
                            Simpan Transaksi
                        </x-input-label>
                        <x-success-button class="mt-3" loading="Menyimpan..." wire:loading.attr="disabled"
                            wire:click.prevent="">
                            Simpan
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                            </svg>

                        </x-success-button>
                    </div>
                    <div>
                        <x-input-label>
                            Batal Transaksi
                        </x-input-label>
                        <x-danger-button class="mt-3" loading="Menghapus.." wire:loading.attr="disabled"
                            wire:click.prevent="">
                            Batal
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                        </x-danger-button>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
