<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('List Line') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <!-- Pencarian -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Search by Material Number"
                    class="mt-1 block w-1/3 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
            </div>
        </div>

        <!-- Daftar Material -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="materialList">
            @if ($lg->isEmpty())
                <div class="col-span-2 flex justify-center items-center">
                    <p class="text-lg font-semibold">Tidak ada data material yang tersedia.</p>
                </div>
            @else
                @foreach ($lg as $lgs)
                    <a href="{{ route('listConsumable', ['line' => $lgs->Ln_lgId, 'material' => $lgs->_id]) }}"
                        class="p-4 rounded-md shadow-md bg-violet-500 hover:bg-violet-600">
                        <div class="col-span-2 flex flex-col justify-center items-center">
                            <h2 class="text-lg text-white font-semibold">{{ $lgs->Ln_name }}</h2>

                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const materialList = document.getElementById('materialList');
            const id = "{{ $id }}"; // Pastikan id dari Blade diterima dengan benar

            searchInput.addEventListener('input', function() {
                const search = this.value.trim().toLowerCase();

                // Display loading indicator
                materialList.innerHTML = '<p class="text-gray-500">Loading...</p>';

                // Gunakan fetch untuk AJAX request ke Laravel
                fetch(`/Transaction/${id}/line?search=${encodeURIComponent(search)}`, {
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "application/json"
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json(); // Convert response ke JSON
                    })
                    .then(data => {
                        materialList.innerHTML = '';

                        if (data.length === 0) {
                            const noDataDiv = document.createElement('div');
                            noDataDiv.classList.add('col-span-2', 'flex', 'justify-center',
                                'items-center');
                            noDataDiv.innerHTML =
                                '<p class="text-lg font-semibold">Tidak ada data material yang tersedia.</p>';
                            materialList.appendChild(noDataDiv);
                        } else {
                            data.forEach(material => {
                                const link = document.createElement('a');
                                link.href = `/Transaction/${material.Ln_lgId}/${material._id}`;
                                link.classList.add('p-4', 'rounded-md', 'shadow-md',
                                    'bg-violet-500', 'hover:bg-violet-600');

                                const div = document.createElement('div');
                                div.classList.add('col-span-2', 'flex', 'flex-col',
                                    'justify-center', 'items-center');

                                const h2Desc = document.createElement('h2');
                                h2Desc.classList.add('text-lg', 'text-white', 'font-semibold');
                                h2Desc.textContent = material.Ln_name;

                                const h2Number = document.createElement('h2');
                                h2Number.classList.add('text-lg', 'text-white',
                                    'font-semibold');
                                h2Number.textContent = material.Mt_number;

                                div.appendChild(h2Desc);
                                div.appendChild(h2Number);
                                link.appendChild(div);
                                materialList.appendChild(link);
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching data:", error);
                        materialList.innerHTML = '<p class="text-gray-500">Error loading data.</p>';
                    });
            });
        });
    </script>


</x-app-layout>
