<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Group Segment Management') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex justify-between mb-4">
            <button id="openCreateModal"
                class="inline-block bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-md">
                {{ __('Add New Group') }}
            </button>

            <!-- Search Input -->
            <input type="text" id="searchInput" value="{{ $search }}" placeholder="Search by name or segment"
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
                            {{ __('Group Name') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Group Segment') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($groups as $group)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td style="width: 13rem; height: 4rem;" class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $group->_id }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $group->Gr_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $group->Gr_segment }}
                            </td>
                            <td class="px-6 py-4 flex justify-center items-center space-x-4">
                                <button
                                    class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md editGroupBtn"
                                    data-id="{{ $group->_id }}" data-name="{{ $group->Gr_name }}"
                                    data-segment="{{ $group->Gr_segment }}">
                                    {{ __('Edit') }}
                                </button>

                                <form action="{{ route('Group.destroy', $group->_id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this group?');">
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
            {{ $groups->links() }}
        </div>

    </div>

    <!-- Modal for Create New Group -->
    <div id="createGroupModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-h-full overflow-y-auto">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Add New Group') }}</h2>

            <form id="createGroupForm" method="POST" action="{{ route('Group.store') }}">
                @csrf
                <div id="groupFieldsContainer" class="space-y-4">
                    <div>
                        <label for="Gr_name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
                        <input type="text" name="Gr_name[]" id="Gr_name"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                        <label for="Gr_segment"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Segment') }}</label>
                        <input type="text" name="Gr_segment[]" id="Gr_segment"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                    </div>
                </div>

                <button type="button" id="addMoreGroups"
                    class="mt-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                    {{ __('Add More Group') }}
                </button>

                <div class="flex justify-between mt-4">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        {{ __('Add Group') }}
                    </button>
                    <button type="button" id="closeCreateModal"
                        class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        {{ __('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Edit Group -->
    <div id="editGroupModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Edit Group') }}</h2>

            <form id="editGroupForm" method="POST" action="{{ route('Group.update', ':id') }}">
                @csrf
                @method('PUT')
                <input type="hidden" id="editGroupId" name="group_id" value="">

                <div class="space-y-4">
                    <div>
                        <label for="editGroupName"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Group Name') }}</label>
                        <input type="text" name="Gr_name" id="editGroupName"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                        <label for="editGroupSegment"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Group Segment') }}</label>
                        <input type="text" name="Gr_segment" id="editGroupSegment"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                    </div>

                    <div class="flex justify-between">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                            {{ __('Update Group') }}
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
                    `<label for="Gr_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
            <input type="text" name="Gr_name[]" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" required>
            <label for="Gr_segment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Segment') }}</label>
            <input type="text" name="Gr_segment[]" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" required>`;
                groupFieldsContainer.appendChild(newGroupField);
            });

            // Edit modal functionality
            const editModal = document.getElementById('editGroupModal');
            const closeEditModalButton = document.getElementById('closeEditModal');

            function attachEditEventListeners() {
                const editButtons = document.querySelectorAll('.editGroupBtn');
                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const groupId = this.getAttribute('data-id');
                        const groupName = this.getAttribute('data-name');
                        const groupSegment = this.getAttribute('data-segment');

                        // Set the data in the edit modal
                        document.getElementById('editGroupId').value = groupId;
                        document.getElementById('editGroupName').value = groupName;
                        document.getElementById('editGroupSegment').value = groupSegment;

                        // Set the form action to include the group id
                        const formAction = document.getElementById('editGroupForm').action.replace(
                            ':id', groupId);
                        document.getElementById('editGroupForm').action = formAction;

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
