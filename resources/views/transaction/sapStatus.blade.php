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
                                        data-id="{{ $st->_id }}" data-no-order="{{ $st->noOrder }}">
                                        {{ __('open') }}
                                    </button>
                                </div>
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

                <!-- Send Form -->
                <div class="px-6 py-4 border-t">
                    <form action="" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" id="order_id">
                        <input type="hidden" name="no_order" id="no_order">
                        <!-- You can add more hidden fields for necessary data -->
                        <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md w-full">
                            {{ __('Resend') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openModalButtons = document.querySelectorAll('.open-modal-btn');
            const openSendFormButtons = document.querySelectorAll('.open-send-form-btn');
            const modal = document.getElementById('mstrApprsModal');
            const closeModalButton = document.getElementById('closeMstrApprsModal');
            const tableBody = document.getElementById('mstrApprsTableBody');
            const orderIdInput = document.getElementById('order_id');
            const noOrderInput = document.getElementById('no_order');

            // Ensure apprData is an array
            const apprData = @json($status).data; // Pass all $apprs data to JS

            // Open modal and populate data
            openModalButtons.forEach((button) => {
                button.addEventListener('click', function() {
                    const apprId = button.getAttribute('data-id');
                    const appr = apprData.find(item => item._id === apprId);

                    if (appr) {
                        const mstrApprs = appr.mstr_apprs;

                        if (mstrApprs.length > 0) {
                            let rows = '';
                            mstrApprs.forEach(item => {


                                if (item.sap_fails && item.sap_fails.length > 0) {
                                    console.log(item.sap_fails[0].Desc_message);
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
                                            statusDisplay = item.sap_fails[0].Desc_message;
                                    }

                                    rows += `
                                    <tr>
                                        <td class="px-4 py-2">${appr.noOrder}</td>
                                        <td class="px-4 py-2">${consumable.Cb_desc}</td>
                                        <td class="px-4 py-2">${item.jumlah}</td>
                                        <td class="px-4 py-2">${statusDisplay}</td>
                                    </tr>
                                    `;
                                }
                            });


                            tableBody.innerHTML = rows;

                        } else {
                            tableBody.innerHTML =
                                `<tr><td colspan="4" class="px-4 py-2 text-center">No data available</td></tr>`;
                        }

                        modal.classList.remove('hidden');
                    }
                });
            });

            // Open send form and populate data
            openSendFormButtons.forEach((button) => {
                button.addEventListener('click', function() {
                    const apprId = button.getAttribute('data-id');
                    const appr = apprData.find(item => item._id === apprId);

                    if (appr) {
                        orderIdInput.value = appr._id;
                        noOrderInput.value = appr.noOrder;
                    }
                });
            });

            // Close modal
            closeModalButton.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        });
    </script>

</x-app-layout>
