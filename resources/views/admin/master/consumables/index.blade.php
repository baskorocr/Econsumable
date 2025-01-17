<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Consumable Management') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex justify-between mb-4">
            <button id="openCreateModal"
                class="inline-block bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-md">
                {{ __('Add New Consumable') }}
            </button>

            <!-- Search Input -->
            <input type="text" id="searchInput" value="{{ $search }}" placeholder="Search by name"
                class="mt-1 block w-1/3 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto min-w-full text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Consumable Number') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Description') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Material Number') }}
                        </th>


                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($consumables as $consumable)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $consumable->Cb_number }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $consumable->Cb_desc }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $consumable->material->Mt_number ?? 'N/A' }}</td>

                            <td class="px-6 py-4 flex justify-center items-center space-x-4">
                                <button
                                    class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md editConsumableBtn"
                                    data-id="{{ $consumable->_id }}" data-no="{{ $consumable->Cb_number }}"
                                    data-desc="{{ $consumable->Cb_desc }}" data-mtid="{{ $consumable->Cb_mtId }}">
                                    {{ __('Edit') }}
                                </button>

                                <form action="{{ route('Consumable.destroy', $consumable->_id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this consumable?');">
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

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $consumables->links() }}
        </div>
    </div>

    <!-- Modal for Create New Consumable -->
    <div id="createConsumableModal"
        class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-h-full overflow-y-auto">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Add New Consumable') }}</h2>

            <form id="createConsumableForm" method="POST" action="{{ route('Consumable.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="Cb_number"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Consumable Number') }}</label>
                        <input type="text" name="Cb_number" id="Cb_number"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                    </div>
                    <div>
                        <label for="Cb_mtId"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Material Number') }}</label>
                        <select name="Cb_mtId" id="Cb_mtId"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                            @foreach ($materials as $material)
                                <option value="{{ $material->_id }}">{{ $material->Mt_desc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="Cb_desc"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                        <textarea name="Cb_desc" id="Cb_desc"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required></textarea>
                    </div>
                </div>

                <div class="flex justify-between mt-4">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        {{ __('Add Consumable') }}
                    </button>
                    <button type="button" id="closeCreateModal"
                        class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        {{ __('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Edit Consumable -->
    <div id="editConsumableModal"
        class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Edit Consumable') }}</h2>

            <form id="editConsumableForm" method="POST" action="{{ route('Consumable.update', ':id') }}">
                @csrf
                @method('PUT')
                <input type="hidden" id="editConsumableId" name="Cb_number" value="">

                <div class="space-y-4">
                    <div>
                        <label for="editCb_mtId"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Material Number') }}</label>
                        <input type="text" name="Cb_mtId" id="editCb_mtId"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            readonly>

                    </div>
                    <div>
                        <label for="editCb_desc"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                        <textarea name="Cb_desc" id="editCb_desc"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required></textarea>
                    </div>
                </div>

                <div class="flex justify-between mt-4">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        {{ __('Update Consumable') }}
                    </button>
                    <button type="button" id="closeEditModal"
                        class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        {{ __('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openCreateModalButton = document.getElementById('openCreateModal');
            const createModal = document.getElementById('createConsumableModal');
            const closeCreateModalButton = document.getElementById('closeCreateModal');

            // Show Create Modal
            openCreateModalButton.addEventListener('click', () => {
                createModal.classList.remove('hidden');
            });

            // Close Create Modal
            closeCreateModalButton.addEventListener('click', () => {
                createModal.classList.add('hidden');
            });

            // Edit modal functionality
            const editButtons = document.querySelectorAll('.editConsumableBtn');
            const editModal = document.getElementById('editConsumableModal');
            const closeEditModalButton = document.getElementById('closeEditModal');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const consumableId = this.getAttribute('data-id');
                    const consumableDesc = this.getAttribute('data-desc');
                    const consumableMtId = this.getAttribute('data-mtId');

                    // Set the data in the edit modal
                    document.getElementById('editConsumableId').value = consumableId;
                    document.getElementById('editCb_desc').value = consumableDesc;
                    document.getElementById('editCb_mtId').value = consumableMtId;

                    // Set the form action to include the consumable id
                    const formAction = document.getElementById('editConsumableForm').action.replace(
                        ':id', consumableId);
                    document.getElementById('editConsumableForm').action = formAction;

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
