<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sloc Management') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex justify-between mb-4">
            <button id="openCreateModal"
                class="inline-block bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-md">
                {{ __('Add New Sloc') }}
            </button>

            <!-- Search Input -->
            <input type="text" id="searchInput" value="{{ $search }}" placeholder="Search by id"
                class="mt-1 block w-1/3 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
        </div>


        <div class="overflow-x-auto">
            <table class="table-auto min-w-full text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('ID') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Sloc Name') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($slocs as $sloc)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $sloc->Tp_mtCode }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $sloc->Tp_name }}</td>
                            <td class="px-6 py-4 flex justify-center items-center space-x-4">
                                <button
                                    class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md editSlocBtn"
                                    data-id="{{ $sloc->Tp_mtCode }}" data-code="{{ $sloc->Tp_mtCode }}"
                                    data-name="{{ $sloc->Tp_name }}">
                                    {{ __('Edit') }}
                                </button>

                                <form action="{{ route('Sloc.destroy', $sloc->Tp_mtCode) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this sloc?');">
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
            {{ $slocs->links() }}
        </div>
    </div>

    <!-- Modal for Create New Sloc -->
    <div id="createGroupModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-h-full overflow-y-auto">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Add New Sloc') }}</h2>

            <form id="createGroupForm" method="POST" action="{{ route('Sloc.store') }}">
                @csrf
                <div id="groupFieldsContainer" class="space-y-4">
                    <div>
                        <label for="Tp_mtCode"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Id') }}</label>
                        <input type="text" name="Tp_mtCode[]" id="Tp_mtCode"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required inputmode="numeric" pattern="\d*">
                        <label for="Tp_name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tp_name') }}</label>
                        <input type="text" name="Tp_name[]" id="Tp_name"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                    </div>
                </div>

                <button type="button" id="addMoreGroups"
                    class="mt-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                    {{ __('Add More Sloc') }}
                </button>

                <div class="flex justify-between mt-4">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        {{ __('Add Sloc') }}
                    </button>
                    <button type="button" id="closeCreateModal"
                        class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        {{ __('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Edit Sloc -->
    <div id="editGroupModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Edit Sloc') }}</h2>

            <form id="editGroupForm" method="POST" action="{{ route('Sloc.update', ':id') }}">
                @csrf
                @method('PUT')
                <input type="hidden" id="editGroupId" name="group_id" value="">

                <div class="space-y-4">
                    <div>
                        <label for="editTp_mtCode"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Group TP MtCode') }}</label>
                        <input type="text" name="Tp_mtCode" id="editTp_mtCode"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                        <label for="editTp_name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tp_name') }}</label>
                        <input type="text" name="Tp_name" id="editTp_name"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                    </div>

                    <div class="flex justify-between">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                            {{ __('Update Sloc') }}
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
            const createModal = document.getElementById('createGroupModal');
            const closeCreateModalButton = document.getElementById('closeCreateModal');
            const groupFieldsContainer = document.getElementById('groupFieldsContainer');
            const addMoreGroupsButton = document.getElementById('addMoreGroups');

            // Show Create Modal
            openCreateModalButton.addEventListener('click', () => {
                createModal.classList.remove('hidden');
            });

            // Close Create Modal
            closeCreateModalButton.addEventListener('click', () => {
                createModal.classList.add('hidden');
            });

            // Add more group fields
            addMoreGroupsButton.addEventListener('click', () => {
                const newGroupField = document.createElement('div');
                newGroupField.innerHTML =
                    `<label for="Tp_mtCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Id') }}</label>
                    <input type="text" name="Tp_mtCode[]" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" required inputmode="numeric" pattern="\d*">
                    <label for="Tp_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tp_name') }}</label>
                    <input type="text" name="Tp_name[]" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" required>`;
                groupFieldsContainer.appendChild(newGroupField);
            });

            // Edit modal functionality
            const editButtons = document.querySelectorAll('.editSlocBtn');
            const editModal = document.getElementById('editGroupModal');
            const closeEditModalButton = document.getElementById('closeEditModal');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const slocId = this.getAttribute('data-id');
                    const slocCode = this.getAttribute('data-code');
                    const slocName = this.getAttribute('data-name');

                    // Set the data in the edit modal
                    document.getElementById('editGroupId').value = slocId;
                    document.getElementById('editTp_mtCode').value = slocCode;
                    document.getElementById('editTp_name').value = slocName;

                    // Set the form action to include the sloc id
                    const formAction = document.getElementById('editGroupForm').action.replace(
                        ':id', slocId);
                    document.getElementById('editGroupForm').action = formAction;

                    editModal.classList.remove('hidden');
                });
            });

            // Close Edit Modal
            closeEditModalButton.addEventListener('click', () => {
                editModal.classList.add('hidden');
            });

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
