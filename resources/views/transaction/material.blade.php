<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('List Material') }}
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
            @if ($materials->isEmpty())
                <div class="col-span-2 flex justify-center items-center">
                    <p class="text-lg font-semibold">Tidak ada data material yang tersedia.</p>
                </div>
            @else
                @foreach ($materials as $material)
                    <a href="{{ route('listConsumable', ['line' => $material->Mt_lgId, 'material' => $material->_id]) }}"
                        class="p-4 rounded-md shadow-md bg-violet-500 hover:bg-violet-600">
                        <div class="col-span-2 flex flex-col justify-center items-center">
                            <h2 class="text-lg text-white font-semibold">{{ $material->Mt_desc }}</h2>
                            <h2 class="text-lg text-white font-semibold">{{ $material->Mt_number }}</h2>
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
            const id = '{{ $id }}';
            searchInput.addEventListener('input', function() {
                const search = this.value.toLowerCase();

                // Ensure this route matches the correct URL in your web.php
                fetch(`{{ route('material.search') }}?search=${search}&id=${id}`)
                    .then(response => response.json()) // Expecting JSON response
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
                                link.href =
                                    `{{ url('Transaction') }}/${material.Mt_lgId}/${material._id}`;
                                link.classList.add('p-4', 'rounded-md', 'shadow-md',
                                    'bg-violet-500', 'hover:bg-violet-600');

                                const div = document.createElement('div');
                                div.classList.add('col-span-2', 'flex', 'flex-col',
                                    'justify-center', 'items-center');

                                const h2Desc = document.createElement('h2');
                                h2Desc.classList.add('text-lg', 'text-white', 'font-semibold');
                                h2Desc.textContent = material.Mt_desc;

                                const h2Number = document.createElement('h2');
                                h2Number.classList.add('text-lg', 'text-white',
                                    'font-semibold');
                                h2Number.textContent = material
                                    .Mt_number; // Add this line for Mt_number

                                div.appendChild(h2Desc);
                                div.appendChild(h2Number); // Append Mt_number
                                link.appendChild(div);
                                materialList.appendChild(link);
                            });
                        }
                    })
                    .catch(error => console.error('Error fetching materials:',
                        error)); // Added error handling
            });
        });
    </script>

</x-app-layout>
