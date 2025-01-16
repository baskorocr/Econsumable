<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Daftar Line') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <!-- Pencarian -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <div class="flex-1">
                <input type="text" placeholder="Cari berdasarkan nama"
                    class="w-full text-black px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex-1">
                <input type="text" placeholder="Cari berdasarkan kode"
                    class="w-full text-black px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
        </div>

        <!-- Daftar Line -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if ($lines->isEmpty())
                <div class="col-span-2 flex justify-center items-center">
                    <p class="text-lg font-semibold">Tidak ada data line yang tersedia.</p>
                </div>
            @else
                @foreach ($lines as $line)
                    <a href="{{ route('listMaterial', $line->Lg_code) }}"
                        class="p-4 rounded-md shadow-md bg-violet-500 hover:bg-violet-600">
                        <div class="col-span-2 flex flex-col justify-center items-center">
                            <h2 class="text-lg text-white font-semibold">{{ $line->line->Ln_name }}</h2>
                            <h5 class="text-lg text-white font-semibold">{{ '(' . $line->group->Gr_name . ')' }}</h5>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
