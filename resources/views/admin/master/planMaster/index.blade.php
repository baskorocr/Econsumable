<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Plan Management') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex justify-between mb-4">
            <button id="openCreateModal"
                class="inline-block bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-md">
                {{ __('Add New Plan') }}
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
                            {{ __('Plan Code') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Plan Name') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($plan as $plans)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $plans->Pl_code }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $plans->Pl_name }}</td>
                            <td class="px-6 py-4 flex justify-center items-center space-x-4">
                                <button
                                    class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md editPlanBtn"
                                    data-id="{{ $plans->_id }}" data-name="{{ $plans->Pl_name }}">
                                    {{ __('Edit') }}
                                </button>

                                <form action="{{ route('Plan.destroy', $plans->_id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this plan?');">
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
            {{ $plan->links() }}
        </div>
    </div>

    <!-- Modal for Create New plans -->
    <div id="createplansModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-h-full overflow-y-auto">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Add New plan') }}</h2>

            <form id="createplansForm" method="POST" action="{{ route('Plan.store') }}">
                @csrf
                <div id="plansFieldsContainer" class="space-y-4">
                    <div>
                        <label for="PlCode"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Plan Code') }}</label>
                        <input type="text" name="PlanCode[]" id="PlanCode"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required inputmode="numeric" pattern="\d*">
                        <label for="PlanName"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('plans Name') }}</label>
                        <input type="text" name="PlanName[]" id="PlanName"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                    </div>
                </div>

                <button type="button" id="addMoreplan"
                    class="mt-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                    {{ __('Add More plan') }}
                </button>

                <div class="flex justify-between mt-4">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        {{ __('Add plan') }}
                    </button>
                    <button type="button" id="closeCreateModal"
                        class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        {{ __('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Edit plans -->
    <div id="editplansModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Edit plans') }}</h2>

            <form id="editplansForm" method="POST" action="{{ route('Plan.update', ':id') }}">
                @csrf
                @method('PUT')
                <input type="hidden" id="editplansId" name="plans_id" value="">

                <div class="space-y-4">
                    <div>
                        <label for="editNameplans"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('plans Name') }}</label>
                        <input type="text" name="Pl_name" id="editNameplans"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            required>
                    </div>

                    <div class="flex justify-between">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                            {{ __('Update plans') }}
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
            const createModal = document.getElementById('createplansModal');
            const closeCreateModalButton = document.getElementById('closeCreateModal');
            const plansFieldsContainer = document.getElementById('plansFieldsContainer');
            const addMoreplanButton = document.getElementById('addMoreplan');

            // Show Create Modal
            openCreateModalButton.addEventListener('click', () => {
                createModal.classList.remove('hidden');
            });

            // Close Create Modal
            closeCreateModalButton.addEventListener('click', () => {
                createModal.classList.add('hidden');
            });

            // Add more plans fields
            addMoreplanButton.addEventListener('click', () => {
                const newplansField = document.createElement('div');
                newplansField.innerHTML =
                    `
                        <label for="PlCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Plan Code') }}</label>
                        <input type="text" name="PlanCode[]" id="PlanCode" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" required>
                        <label for="PlanName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('plans Name') }}</label>
                        <input type="text" name="PlanName[]" id="PlanName" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" required>
                    `;
                plansFieldsContainer.appendChild(newplansField);
            });

            // Edit modal functionality
            const editButtons = document.querySelectorAll('.editPlanBtn');
            const editModal = document.getElementById('editplansModal');
            const closeEditModalButton = document.getElementById('closeEditModal');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const plansId = this.getAttribute('data-id');
                    const plansName = this.getAttribute('data-name');

                    // Set the data in the edit modal
                    document.getElementById('editplansId').value = plansId;
                    document.getElementById('editNameplans').value = plansName;

                    // Set the form action to include the plans id
                    const formAction = document.getElementById('editplansForm').action.replace(
                        ':id', plansId);
                    document.getElementById('editplansForm').action = formAction;

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
