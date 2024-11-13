<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <div class="py-6">
        <div class="flex flex-row gap-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl w-96 dark:bg-gray-800 shadow-xl">
                <!---->
                <div class="flex flex-col p-8">
                    <div class="text-2xl font-bold   dark:text-indigo-500 pb-6">Semua penjualan</div>
                    <div class=" text-lg   dark:text-gray-200">
                        @php
                            $totalpenjualanall = \App\Models\Penjualan::sum('total_harga');
                            echo 'Total Penjualan : Rp. ' . number_format($totalpenjualanall, 0, ',', '.');
                        @endphp
                    </div>
                    <!---->
                </div>
            </div>
            <div class="rounded-2xl w-96 dark:bg-gray-800 shadow-xl">
                <!---->
                <div class="flex flex-col p-8">
                    <div class="text-2xl font-bold   dark:text-green-500 pb-6">Penjualan hari ini</div>
                    <div class=" text-lg   dark:text-gray-200">
                        @php
                            $hariini = \App\Models\Penjualan::whereDate('updated_at', today())->sum('total_harga');
                            echo 'Total Penjualan : Rp. ' . number_format($hariini, 0, ',', '.');

                        @endphp
                    </div>
                    <!---->
                </div>
            </div>
            <div class="flex flex-col rounded-2xl w-96 dark:bg-gray-800 shadow-xl">
                <!---->
                <div class="flex flex-col p-8">
                    <div class="text-2xl font-bold   dark:text-yellow-500 pb-6">Total pengguna</div>
                    <div class=" text-lg   dark:text-gray-200">
                        @php
                            $pengguna = \App\Models\User::count('id');
                            echo 'Total kasir : ' . $pengguna . ' Pengguna';
                        @endphp
                    </div>
                    <!---->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
