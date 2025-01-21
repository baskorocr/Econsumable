<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Line List') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex justify-between mb-4">
            <!-- Search Input -->
            <input type="text" id="searchInput" placeholder="Search by group name"
                class="mt-1 block w-1/3 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
        </div>

        <!-- Daftar Line -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="lineList">
            @if ($lines->isEmpty())
                <div class="col-span-2 flex justify-center items-center">
                    <p class="text-lg font-semibold">Tidak ada data line yang tersedia.</p>
                </div>
            @else
                @foreach ($lines as $line)
                    <a href="{{ route('listMaterial', $line->_id) }}"
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const lineList = document.getElementById('lineList');

            searchInput.addEventListener('input', function() {
                const search = this.value.toLowerCase();

                fetch(`{{ route('line.search') }}?search=${search}`)
                    .then(response => response.json())
                    .then(data => {
                        lineList.innerHTML = '';

                        if (data.length === 0) {
                            const noDataDiv = document.createElement('div');
                            noDataDiv.classList.add('col-span-2', 'flex', 'justify-center',
                                'items-center');
                            noDataDiv.innerHTML =
                                '<p class="text-lg font-semibold">Tidak ada data line yang tersedia.</p>';
                            lineList.appendChild(noDataDiv);
                        } else {
                            data.forEach(line => {
                                const link = document.createElement('a');
                                link.href = `{{ url('Transaction') }}/${line._id}/material`;
                                link.classList.add('p-4', 'rounded-md', 'shadow-md',
                                    'bg-violet-500', 'hover:bg-violet-600');

                                const div = document.createElement('div');
                                div.classList.add('col-span-2', 'flex', 'flex-col',
                                    'justify-center', 'items-center');

                                const h2 = document.createElement('h2');
                                h2.classList.add('text-lg', 'text-white', 'font-semibold');
                                h2.textContent = line.line.Ln_name;

                                const h5 = document.createElement('h5');
                                h5.classList.add('text-lg', 'text-white', 'font-semibold');
                                h5.textContent = `(${line.group.Gr_name})`;

                                div.appendChild(h2);
                                div.appendChild(h5);
                                link.appendChild(div);
                                lineList.appendChild(link);
                            });
                        }
                    });
            });
        });
    </script>
</x-app-layout>
