<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('List SAP Status') }}
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
                            {{ __('Request By ') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Detail Approval') }}
                        </th>
                        @if (auth()->user()->role->id === 2 || auth()->user()->role->id === 3 || auth()->user()->role->id === 4)
                            <th
                                class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($status as $st)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td style="width: 13rem; height: 4rem;" class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <div>
                                    {{ $st->noOrder }}
                                </div>
                            </td>
                            <td style="width: 13rem; height: 4rem;" class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <div>
                                    {{ $st->user->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <div>
                                    <button
                                        class="inline-block bg-purple-500 hover:bg-purple-600 dark:bg-purple-600 dark:hover:bg-purple-700 text-white px-4 py-2 rounded-md open-modal-btn"
                                        data-id="{{ $st->_id }}">
                                        {{ __('open') }}
                                    </button>
                                </div>
                            </td>


                            <td class="px-6 py-4 flex justify-center items-center space-x-4">
                                <a href="{{ route('approvalConfirmation.acc', $st->_id) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md ">
                                    {{ __('send') }}
                                </a>

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $status->links() }}
        </div>

        <!-- Modal -->
        <div id="mstrApprsModal"
            class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg w-3/4">
                <div class="flex justify-between items-center border-b px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-200">Master Approval Data</h3>
                    <button id="closeMstrApprsModal"
                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">&times;</button>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="table-auto min-w-full text-center text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-gray-700 dark:text-gray-300">No Order</th>
                                <th class="px-4 py-2 text-gray-700 dark:text-gray-300">Consumable Name</th>
                                <th class="px-4 py-2 text-gray-700 dark:text-gray-300">Jumlah</th>
                                <th class="px-4 py-2 text-gray-700 dark:text-gray-300">Status</th>
                            </tr>
                        </thead>
                        <tbody id="mstrApprsTableBody" class="divide-y divide-gray-200 dark:divide-gray-600">
                            <!-- Data will be dynamically filled here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find all open modal buttons
            const openModalButtons = document.querySelectorAll('.open-modal-btn');
            const modal = document.getElementById('mstrApprsModal');
            const closeModalButton = document.getElementById('closeMstrApprsModal');
            const tableBody = document.getElementById('mstrApprsTableBody');

            // Ensure apprData is an array
            const apprData = @json($status).data; // Pass all $apprs data to JS

            // Function to open modal and populate data
            openModalButtons.forEach((button) => {
                button.addEventListener('click', function() {
                    const apprId = button.getAttribute('data-id'); // Get the id of the appr
                    const appr = apprData.find(item => item._id ===
                        apprId); // Find the appr data by id

                    console.log(appr);
                    if (appr) {
                        // Access the mstr_apprs array
                        const mstrApprs = appr.mstr_apprs; // This is an array

                        // Check if mstr_apprs is not empty and populate the modal with its data
                        if (mstrApprs.length > 0) {
                            let rows = '';
                            mstrApprs.forEach(item => {
                                // Access consumable data
                                const consumable = item.consumable;
                                let statusDisplay;
                                switch (item.status) {
                                    case 1:
                                        statusDisplay = 'Waiting for approval';
                                        break;
                                    case 2:
                                    case 3:
                                        statusDisplay = 'Partially approved';
                                        break;
                                    case 4:
                                        statusDisplay = 'Fully approved';
                                        break;
                                    default:
                                        statusDisplay =
                                            'Unknown status'; // Fallback for any unexpected status
                                }

                                // Generate a row for each mstr_apprs item, including consumable data
                                rows += `
                                <tr>
                                    <td class="px-4 py-2">${appr.noOrder}</td>
                                    
                                    <td class="px-4 py-2">${consumable.Cb_desc}</td>
                                    <td class="px-4 py-2">${item.jumlah}</td>
                                    <td class="px-4 py-2">${statusDisplay}</td>
                                </tr>
                            `;
                            });
                            tableBody.innerHTML = rows; // Add the rows to the modal's table body
                        } else {
                            tableBody.innerHTML =
                                `<tr><td colspan="5" class="px-4 py-2 text-center">No data available</td></tr>`;
                        }

                        modal.classList.remove('hidden'); // Show the modal
                    } else {
                        console.error('Data not found for the selected ID');
                    }
                });
            });

            // Close the modal when clicking the close button
            closeModalButton.addEventListener('click', function() {
                modal.classList.add('hidden'); // Hide the modal
            });
        });
    </script>


</x-app-layout>
