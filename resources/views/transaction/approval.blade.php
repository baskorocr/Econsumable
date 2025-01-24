<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('List Approval') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">


        <div class="overflow-x-auto">
            <table class="table-auto min-w-full text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('No Order') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Material Consumable') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Jumlah') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Di request oleh') }}
                        </th>
                        @if (auth()->user()->role->id === 1 ||
                                auth()->user()->role->id === 2 ||
                                auth()->user()->role->id === 3 ||
                                auth()->user()->role->id === 4)
                            <th
                                class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($apprs as $appr)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td style="width: 13rem; height: 4rem;" class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <div>
                                    {{ $appr->orderSegment->noOrder }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <div>{{ $appr->consumable->Cb_desc }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <div>{{ $appr->jumlah }}</div>
                            </td>
                            <td style="width: 13rem; height: 4rem;" class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $appr->user->name }}
                            </td>

                            @if (auth()->user()->role->id === 1 ||
                                    auth()->user()->role->id === 2 ||
                                    auth()->user()->role->id === 3 ||
                                    auth()->user()->role->id === 4)
                                <td class="px-6 py-4 flex justify-center items-center space-x-4">
                                    <a href="{{ route('approvalConfirmation.acc', $appr->_id) }}"
                                        class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md ">
                                        {{ __('Approv') }}
                                    </a>
                                    <a href="{{ route('approvalConfirmation.reject', $appr->_id) }}"
                                        class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-md ">
                                        {{ __('Reject') }}
                                    </a>

                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $apprs->links() }}
        </div>
    </div>




    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Search dynamic
            const searchInput = document.getElementById('searchInput');
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

                        // Reattach event listeners after dynamic search result update
                        attachEditEventListeners();
                    });
            });

            // Mass upload material
            const openUploadModalButton = document.getElementById('openUploadModal');
            const uploadModal = document.getElementById('uploadExcelModal');
            const closeUploadModalButton = document.getElementById('closeUploadModal');

            // Show Upload Modal
            openUploadModalButton.addEventListener('click', () => {
                uploadModal.classList.remove('hidden');
            });

            // Close Upload Modal
            closeUploadModalButton.addEventListener('click', () => {
                uploadModal.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>
