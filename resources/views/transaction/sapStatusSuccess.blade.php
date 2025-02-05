<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('List SAP Status Success') }}
        </h2>

    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex flex-wrap items-center space-x-4 md:space-x-6">
            <form id="printForm" action="{{ route('sap.print') }}" method="POST" target="_blank">
                @csrf
                <input hidden name="selected_orders" id="selectedOrders">
            </form>

            <button id="mass-print" type="submit"
                class="inline-block bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-md">
                {{ __('Print') }}
            </button>


        </div>


        <div class="overflow-x-auto">
            <table class="table-auto min-w-full text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('No Order') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Request By') }}
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
                            <td class="px-4 py-4">
                                <input type="checkbox" class="select-item" value="{{ $st->_id }}">
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $st->noOrder }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $st->user->name }}</td>
                            <td class="px-6 py-4">
                                <button
                                    class="bg-purple-500 hover:bg-purple-600 dark:bg-purple-600 dark:hover:bg-purple-700 text-white px-4 py-2 rounded-md open-modal-btn"
                                    data-id="{{ $st->_id }}" data-no-order="{{ $st->noOrder }}">
                                    {{ __('Open') }}
                                </button>
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
                                <th class="px-4 py-2 text-gray-700 dark:text-gray-300">Matdoc_gi</th>
                                <th class="px-4 py-2 text-gray-700 dark:text-gray-300">Consumable Name</th>
                                <th class="px-4 py-2 text-gray-700 dark:text-gray-300">Jumlah</th>
                                <th class="px-4 py-2 text-gray-700 dark:text-gray-300">Status</th>
                            </tr>
                        </thead>
                        <tbody id="mstrApprsTableBody" class="divide-y divide-gray-200 dark:divide-gray-600">
                            <!-- Data dynamically inserted -->
                        </tbody>
                    </table>
                </div>

                <!-- Send Form -->
                <div class="px-6 py-4 border-t">
                    <form action="#" method="POST">
                        @csrf
                        <input type="hidden" name="no_order" id="no_order">

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openModalButtons = document.querySelectorAll('.open-modal-btn');
            const modal = document.getElementById('mstrApprsModal');
            const closeModalButton = document.getElementById('closeMstrApprsModal');
            const tableBody = document.getElementById('mstrApprsTableBody');
            const noOrderInput = document.getElementById('no_order');

            const apprData = @json($status->items());

            openModalButtons.forEach((button) => {
                button.addEventListener('click', function() {
                    const apprId = button.getAttribute('data-id');
                    const appr = apprData.find(item => item._id === apprId);

                    if (appr) {
                        const mstrApprs = appr.mstr_apprs;
                        console.log(appr);

                        // Set nilai noOrder ke input

                        noOrderInput.value = mstrApprs[0].no_order;


                        if (mstrApprs.length > 0) {
                            let rows = '';
                            mstrApprs.forEach(item => {
                                if (item.sap_fails && item.sap_fails.length > 0) {
                                    console.log(item.sap_fails[0].Desc_message);

                                    const consumable = item.consumable ||
                                    {}; // Mencegah error jika consumable undefined
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
                                            statusDisplay = 'All Process Success';
                                            break;
                                        default:
                                            statusDisplay = item.sap_fails[0].Desc_message;
                                    }

                                    rows += `
                <tr>
                    <td class="px-4 py-2">${appr.noOrder}</td>
                    <td class="px-4 py-2">${item.sap_fails[0].matdoc_gi}</td>
                    <td class="px-4 py-2">${consumable.Cb_desc || '-'}</td>
                    <td class="px-4 py-2">${item.jumlah || 0}</td>
                    <td class="px-4 py-2">${statusDisplay}</td>
                </tr>
                `;
                                }
                            });

                            // Menambahkan ke tabel jika ada rows yang ditemukan
                            const tableBody = document.getElementById('mstrApprsTableBody');
                            if (tableBody) {
                                tableBody.innerHTML = rows;
                            }
                        }
                        modal.classList.remove('hidden');
                    }

                });
            });

            closeModalButton.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        });
    </script>

    <script>
        document.getElementById('select-all').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.select-item');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        document.getElementById('mass-print').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah submit form langsung

            let selectedOrders = [];
            document.querySelectorAll('.select-item:checked').forEach(item => {
                selectedOrders.push(item.value);
            });

            if (selectedOrders.length > 0) {
                // Masukkan data ke input hidden
                document.getElementById('selectedOrders').value = JSON.stringify(selectedOrders);

                // Submit form
                document.getElementById('printForm').submit();
            } else {
                alert('No orders selected for printing.');
            }
        });
    </script>

</x-app-layout>
