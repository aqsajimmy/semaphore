{{-- @php
    dd(public_path('invoice_files'));
@endphp --}}
<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice-{{ $data->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <!-- Invoice -->
    <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto my-4 sm:my-10">
        <div class="sm:w-11/12 lg:w-3/4 mx-auto">
            <!-- Card -->
            <div class="flex flex-col p-4 sm:p-10 bg-white shadow-md rounded-xl dark:bg-neutral-800">
                <!-- Grid -->
                <div class="flex justify-between">
                    <div>
                        <img src="{{ public_path('Semaphore.png') }}" alt="Semaphore Bordir" width="200" />

                        <h1 class="mt-2 text-lg md:text-xl font-semibold text-blue-600 dark:text-white text-center">
                            Bordir &
                            Konveksi
                        </h1>
                    </div>
                    <!-- Col -->

                    <div class="text-end">
                        <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 dark:text-neutral-200">Invoice
                            {{ $data->id }}#
                        </h2>

                        <address class="mt-4 not-italic text-gray-800 dark:text-neutral-200">
                            Toko Semaphore Bordir dan Konveksi<br>
                            Jalan Sutan Syahrir No. 54 Tarok Dipo Bukittinggi.<br>
                            0813-6313-0824<br>
                            0822-5972-6779<br>
                        </address>
                    </div>
                    <!-- Col -->
                </div>
                <!-- End Grid -->

                <!-- Grid -->
                <div class="mt-8 grid sm:grid-cols-2 gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Penerima:</h3>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                            {{ ucwords($data->nama_pelanggan) }}</h3>
                    </div>
                    <!-- Col -->

                    <div class="sm:text-end space-y-2">
                        <!-- Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2">
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Tanggal
                                    Transaksi :
                                </dt>
                                <dd class="col-span-2 text-gray-500 dark:text-neutral-500">
                                    {{ \Carbon\Carbon::parse($data->updated_at)->translatedFormat('D/d/m/Y') }}</dd>
                            </dl>
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Kasir :</dt>
                                <dd class="col-span-2 text-gray-500 dark:text-neutral-500">
                                    {{ ucwords($data->kasir->name) }}</dd>
                            </dl>
                        </div>
                        <!-- End Grid -->
                    </div>
                    <!-- Col -->
                </div>
                <!-- End Grid -->

                <!-- Table -->
                <div class="mt-6">
                    <div class="border border-gray-200 p-4 rounded-lg space-y-4 dark:border-neutral-700">
                        <div class="hidden sm:grid sm:grid-cols-5">
                            <div
                                class="sm:col-span-2 text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                Nama Barang</div>
                            <div class="text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                Qty</div>
                            <div class="text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                Harga Satuan</div>
                            <div class="text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                Subtotal</div>
                        </div>
                        @forelse ($data->penjualan_detail as $item)
                            <div class="hidden sm:block border-b border-gray-200 dark:border-neutral-700"></div>
                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                                <div class="col-span-full sm:col-span-2">
                                    <p class="text-gray-800 dark:text-neutral-200">{{ $item->nama_barang }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-800 dark:text-neutral-200">
                                        {{ $item->kuantitas . ' ' . $item->satuan }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-800 dark:text-neutral-200">
                                        Rp. {{ number_format($item->harga_satuan, 0) }}</p>
                                </div>
                                <div>
                                    <p class="sm:text-end text-gray-800 dark:text-neutral-200">
                                        Rp. {{ number_format($item->subtotal, 0) }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full sm:col-span-2">
                                <p class="text-gray-800 dark:text-neutral-200">No Item</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <!-- End Table -->

                <!-- Flex -->
                <div class="mt-8 flex sm:justify-end">
                    <div class="w-full max-w-2xl sm:text-end space-y-2">
                        <!-- Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2">
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Subtotal :</dt>
                                <dd class="col-span-2 text-gray-500 dark:text-neutral-500">
                                    Rp. {{ number_format($data->total_harga, 0) ?? '0' }}
                            </dl>

                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Biaya Lainnya :
                                </dt>
                                <dd class="col-span-2 text-gray-500 dark:text-neutral-500">Rp. 0</dd>
                            </dl>

                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Total Belanja :
                                </dt>
                                <dd class="col-span-2 text-gray-500 dark:text-neutral-500">Rp.
                                    {{ number_format($data->total_harga, 0) }}</dd>
                            </dl>

                            {{-- <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Amount paid:
                                </dt>
                                <dd class="col-span-2 text-gray-500 dark:text-neutral-500">$2789.00</dd>
                            </dl> --}}

                            {{-- <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Due balance:
                                </dt>
                                <dd class="col-span-2 text-gray-500 dark:text-neutral-500">$0.00</dd>
                            </dl> --}}
                        </div>
                        <!-- End Grid -->
                    </div>
                </div>
                <!-- End Flex -->

                <div class="mt-8 sm:mt-12">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Terimakasih, Telah Berbelanja
                        di Semaphore!</h4>
                </div>

                <p class="mt-5 text-sm text-gray-500 dark:text-neutral-500">© 2024 Semaphore.</p>
            </div>
            <!-- End Card -->

        </div>
    </div>
    <!-- End Invoice -->
</body>

</html>
