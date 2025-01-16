<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Line Group Management') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex justify-between mb-4">
            <a href="{{ route('LineGroup.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md mb-4 inline-block">
                {{ __('Add New Line Group') }}
            </a>

            <!-- Search Input -->
            <input type="text" id="searchInput" value="{{ $search }}"
                placeholder="Search by code, plan, cost center, or line"
                class="mt-1 block w-1/3 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto min-w-full text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Code Line Group') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Plant ') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Cost Center') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Line') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Dept.Head') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Section.Head') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Group') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Sloc') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($lineGroups as $lineGroup)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->Lg_code }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->plan->Pl_name }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $lineGroup->costCenter->Cs_code }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->line->Ln_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $lineGroup->leader->name ?? ' ' }}</td>

                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $lineGroup->section->name ?? ' ' }}</td>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->group->Gr_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->Lg_slocId }}</td>
                            <td class="px-6 py-4 flex justify-center items-center space-x-4">
                                <a href="{{ route('LineGroup.edit', $lineGroup->_id) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">
                                    {{ __('Edit') }}
                                </a>

                                <form action="{{ route('LineGroup.destroy', $lineGroup->_id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this line group?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $lineGroups->links() }}
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');

            // Dynamic search
            searchInput.addEventListener('input', function() {
                const search = this.value;
                const url = new URL(window.location.href);
                url.searchParams.set('search', search);
                window.history.pushState({}, '', url);
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTableBody = doc.querySelector('tbody');
                        const newPagination = doc.querySelector('.mt-4');
                        document.querySelector('tbody').innerHTML = newTableBody.innerHTML;
                        document.querySelector('.mt-4').innerHTML = newPagination.innerHTML;
                    });
            });
        });
    </script>
</x-app-layout>
