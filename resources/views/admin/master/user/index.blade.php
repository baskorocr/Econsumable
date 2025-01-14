<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <a href="{{ route('register') }}"
            class="inline-block bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-md mb-4">
            {{ __('Add New User') }}
        </a>

        <div class="overflow-x-auto">
            <table class="table-auto min-w-full text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>

                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('NPK') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Name') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Email') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Np Hp') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Role') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($user as $users)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $users->npk }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $users->name }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $users->email }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $users->NoHp }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $users->role->NameRole }}</td>
                            <td class="px-6 py-4 flex justify-center items-center space-x-4">


                                <form action="{{ route('User.destroy', $users->npk) }}" method="POST"
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
        });
    </script>
</x-app-layout>
