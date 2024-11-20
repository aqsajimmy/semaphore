<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-100">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-auto">
                    <div class="flex justify-end mb-2">
                        <div><x-text-input type="text" placeholder="Cari Transaksi..."
                                wire:model.live="search"></x-text-input></div>
                    </div>
                    <table class="table table-auto min-w-full w-full">
                        <thead class="font-medium text-lg text-gray-700 dark:text-gray-300">
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>No. Trx</th>
                                <th>Pelanggan</th>
                                <th>Total Belanja</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penjualan as $item)
                                <tr class="hover:bg-gray-700 rounded" wire:key="{{ $item->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('D, d-m-y') }}</td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->nama_pelanggan }}</td>
                                    <td>Rp. {{ number_format($item->total_harga, 0, '', ',') }}</td>
                                    <td>
                                        <div class="flex gap-2">
                                            <form action="{{ route('download_invoice', $item->id) }}">
                                                <x-primary-button wire:loading.attr="disabled" type="submit"
                                                    wire:click="download('{{ $item->id }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                    </svg>

                                                    <x-loading wire:target="download('{{ $item->id }}')"> Proses...
                                                    </x-loading>
                                                </x-primary-button>
                                            </form>
                                            <x-success-button loading="Proses..." wire:loading.attr="disabled"
                                                wire:click.prevent="sendWa('{{ $item->id }}')">
                                                <svg class="size-4" fill="none" viewBox="0 0 48 48"
                                                    stroke-width="1.5" stroke="currentColor" id="Layer_2"
                                                    data-name="Layer 2" xmlns="http://www.w3.org/2000/svg">
                                                    <defs>
                                                        <style>
                                                            .cls-1 {
                                                                fill: none;
                                                                stroke: #000000;
                                                                stroke-linecap: round;
                                                                stroke-linejoin: round;
                                                            }
                                                        </style>
                                                    </defs>
                                                    <path class="cls-1"
                                                        d="M24,2.5A21.52,21.52,0,0,0,5.15,34.36L2.5,45.5l11.14-2.65A21.5,21.5,0,1,0,24,2.5ZM13.25,12.27h5.86a1,1,0,0,1,1,1,10.4,10.4,0,0,0,.66,3.91,1.93,1.93,0,0,1-.66,2.44l-2.05,2a18.6,18.6,0,0,0,3.52,4.79A18.6,18.6,0,0,0,26.35,30l2-2.05c1-1,1.46-1,2.44-.66a10.4,10.4,0,0,0,3.91.66,1.05,1.05,0,0,1,1,1v5.86a1.05,1.05,0,0,1-1,1,23.68,23.68,0,0,1-15.64-6.84,23.6,23.6,0,0,1-6.84-15.64A1.07,1.07,0,0,1,13.25,12.27Z" />
                                                </svg>

                                            </x-success-button>
                                            <x-a href="{{ route('detail_penjualan', $item->id) }}" wire:navigate>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </x-a>
                                            <x-danger-button loading="Proses..."
                                                wire:click.prevent="destroy('{{ $item->id }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </x-danger-button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="6" class="text-center mx-auto">Data Tidak Ada</td>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $penjualan->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
