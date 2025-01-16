<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Material Management') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex justify-between mb-4">
            <button id="openCreateModal"
                class="inline-block bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-md">
                {{ __('Add New Material') }}
            </button>

            <!-- Search Input -->
            <input type="text" id="searchInput" value="{{ $search }}" placeholder="Search by description"
                class="mt-1 block w-1/3 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto min-w-full text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('QR Code') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Line Segment') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Material Number') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Description') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($materials as $material)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td style="width: 13rem; height: 4rem;" class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <div
                                    style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; background: white;">
                                    {!! DNS2D::getBarcodeHTML($material->Mt_number, 'QRCODE', 2, 2) !!}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <div>{{ $material->masterLineGroup->line->Ln_name }}</div>
                                <div>({{ $material->masterLineGroup->plan->Pl_name }})</div>
                            </td>
                            <td style="width: 13rem; height: 4rem;" class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $material->Mt_number }}
                            </td>
                            <td style="width: 13rem; height: 4rem;" class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $material->Mt_desc }}
                            </td>
                            <td class="px-6 py-4 flex justify-center items-center space-x-4">
                                <button
                                    class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md editMaterialBtn"
                                    data-id="{{ $material->Mt_number }}" data-desc="{{ $material->Mt_desc }}"
                                    data-lgid="{{ $material->Mt_lgId }}">
                                    {{ __('Edit') }}
                                </button>

                                <form action="{{ route('Material.destroy', $material->Mt_number) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this material?');">
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
            {{ $materials->links() }}
        </div>
    </div>

    <!-- Modal for Create New Material -->
    <div id="createMaterialModal"
        class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-h-full overflow-y-auto">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Add New Material') }}</h2>

            <form id="createMaterialForm" method="POST" action="{{ route('Material.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="Mt_number"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Material Number') }}</label>
                        <input type="text" name="Mt_number" id="Mt_number"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                    </div>
                    <div>
                        <label for="Mt_lgId"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Line Group') }}</label>
                        <select name="Mt_lgId" id="Mt_lgId"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                            @foreach ($lineGroups as $lineGroup)
                                <option value="{{ $lineGroup->_id }}">{{ $lineGroup->_id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="Mt_desc"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                        <textarea name="Mt_desc" id="Mt_desc"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required></textarea>
                    </div>
                </div>

                <div class="flex justify-between mt-4">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        {{ __('Add Material') }}
                    </button>
                    <button type="button" id="closeCreateModal"
                        class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        {{ __('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Edit Material -->
    <div id="editMaterialModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Edit Material') }}</h2>

            <form id="editMaterialForm" method="POST" action="{{ route('Material.update', ':id') }}">
                @csrf
                @method('PUT')
                <input type="hidden" id="editMaterialId" name="Mt_number" value="" readonly>

                <div class="space-y-4">
                    <div>
                        <label for="editMt_lgId"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Line Group') }}</label>
                        <select name="Mt_lgId" id="editMt_lgId"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                            @foreach ($lineGroups as $lineGroup)
                                <option value="{{ $lineGroup->_id }}">{{ $lineGroup->_id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="editMt_desc"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                        <textarea name="Mt_desc" id="editMt_desc"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required></textarea>
                    </div>
                </div>

                <div class="flex justify-between mt-4">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        {{ __('Update Material') }}
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
            const createModal = document.getElementById('createMaterialModal');
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
            const editButtons = document.querySelectorAll('.editMaterialBtn');
            const editModal = document.getElementById('editMaterialModal');
            const closeEditModalButton = document.getElementById('closeEditModal');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const materialId = this.getAttribute('data-id');
                    const materialDesc = this.getAttribute('data-desc');
                    const materialLgId = this.getAttribute('data-lgid');

                    // Set the data in the edit modal
                    document.getElementById('editMaterialId').value = materialId;
                    document.getElementById('editMt_desc').value = materialDesc;
                    document.getElementById('editMt_lgId').value = materialLgId;

                    // Set the form action to include the material id
                    const formAction = document.getElementById('editMaterialForm').action.replace(
                        ':id', materialId);
                    document.getElementById('editMaterialForm').action = formAction;

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
