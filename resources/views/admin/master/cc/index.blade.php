<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Const Center Management') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex justify-between mb-4">
            <button id="openCreateModal"
                class="inline-block bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-md">
                {{ __('Add New Cost Center') }}
            </button>

            <!-- Search Input -->
            <input type="text" id="searchInput" value="{{ $search }}" placeholder="Search by Cost Center"
                class="mt-1 block w-1/3 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto min-w-full text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('CS_Code') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Name') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($costs as $cost)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td style="width: 13rem; height: 4rem;" class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $cost->Cs_code }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $cost->Cs_name }}
                            </td>
                            <td class="px-6 py-4 flex justify-center items-center space-x-4">
                                <button
                                    class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md editLineBtn"
                                    data-id="{{ $cost->_id }}" data-name="{{ $cost->Cs_name }}"
                                    data-code="{{ $cost->Cs_code }}">
                                    {{ __('Edit') }}
                                </button>

                                <form action="{{ route('Cost.destroy', $cost->_id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this line?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-md">
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
            {{ $costs->links() }}
        </div>
    </div>

    <!-- Modal for Create New Line -->
    <div id="createLineModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-h-full overflow-y-auto">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Add New Lines') }}</h2>

            <form id="createLineForm" method="POST" action="{{ route('Cost.store') }}">
                @csrf
                <div id="lineFieldsContainer" class="space-y-4">
                    <div>
                        <label for="cost"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cost Code') }}</label>
                        <input type="text" name="Cs_code[]" id="Cs_code"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                    </div>
                    <div>
                        <label for="cost name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cost Name') }}</label>
                        <input type="text" name="Cs_name[]" id="Cs_name"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                    </div>
                </div>

                <button type="button" id="addMoreLines"
                    class="mt-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                    {{ __('Add More Lines') }}
                </button>

                <div class="flex justify-between mt-4">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        {{ __('Add Lines') }}
                    </button>
                    <button type="button" id="closeCreateModal"
                        class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        {{ __('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Edit Line -->
    <div id="editLineModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Edit Line') }}</h2>

            <form id="editLineForm" method="POST" action="{{ route('Cost.update', ':id') }}">
                @csrf
                @method('PUT')
                <input type="hidden" id="editLineId" name="Cs_code" value="">

                <div class="space-y-4">
                    <div>
                        <label for="editCs_code"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cost Code') }}</label>
                        <input type="text" name="Cs_code" id="editCs_code"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                    </div>
                    <div>
                        <label for="editNameLine"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cost Name') }}</label>
                        <input type="text" name="Cs_name" id="editNameLine"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                    </div>

                    <div class="flex justify-between">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                            {{ __('Update Cost') }}
                        </button>
                        <button type="button" id="closeEditModal"
                            class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                            {{ __('Close') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openCreateModalButton = document.getElementById('openCreateModal');
            const createModal = document.getElementById('createLineModal');
            const closeCreateModalButton = document.getElementById('closeCreateModal');
            const lineFieldsContainer = document.getElementById('lineFieldsContainer');
            const addMoreLinesButton = document.getElementById('addMoreLines');

            // Show Create Modal
            openCreateModalButton.addEventListener('click', () => {
                createModal.classList.remove('hidden');
            });

            // Close Create Modal
            closeCreateModalButton.addEventListener('click', () => {
                createModal.classList.add('hidden');
            });

            // Add more line fields
            addMoreLinesButton.addEventListener('click', () => {
                const newLineField = document.createElement('div');
                newLineField.innerHTML =
                    `<div id="lineFieldsContainer" class="space-y-4">
            <div>
                <label for="cost"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cost Code') }}</label>
                <input type="text" name="Cs_code[]" id="Cs_code"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                    required>
            </div>
            <div>
                <label for="cost name"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cost Name') }}</label>
                <input type="text" name="Cs_name[]" id="Cs_name"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                    required>
            </div>
        </div>`;
                lineFieldsContainer.appendChild(newLineField);
            });

            // Edit modal functionality
            const editModal = document.getElementById('editLineModal');
            const closeEditModalButton = document.getElementById('closeEditModal');

            function attachEditEventListeners() {
                const editButtons = document.querySelectorAll('.editLineBtn');
                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const lineId = this.getAttribute('data-id');
                        const lineCode = this.getAttribute('data-code');
                        const lineName = this.getAttribute('data-name');

                        // Set the data in the edit modal
                        document.getElementById('editLineId').value = lineId;
                        document.getElementById('editCs_code').value = lineCode;
                        document.getElementById('editNameLine').value = lineName;

                        // Set the form action to include the line id
                        const formAction = document.getElementById('editLineForm').action.replace(
                            ':id', lineId);
                        document.getElementById('editLineForm').action = formAction;

                        editModal.classList.remove('hidden');
                    });
                });
            }

            // Attach edit event listeners initially
            attachEditEventListeners();

            // Close Edit Modal
            closeEditModalButton.addEventListener('click', () => {
                editModal.classList.add('hidden');
            });

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
        });
    </script>
</x-app-layout>
