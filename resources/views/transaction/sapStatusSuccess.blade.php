<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('List SAP Status Success') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4">
            <!-- Print Button & Search (Left) -->
            <div class="flex items-center space-x-4 mb-2 md:mb-0">
                <form id="printForm" action="{{ route('sap.print') }}" method="POST" target="_blank">
                    @csrf
                    <input hidden name="selected_orders" id="selectedOrders">
                </form>

                <button id="mass-print" type="submit"
                    class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-md">
                    {{ __('Print') }}
                </button>

                <input type="text" id="searchInput" value="{{ $search }}" placeholder="Search by name"
                    class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
            </div>

            <!-- Filter Date (Right) -->
            <div class="flex items-center space-x-4">
                <input type="date" id="fromDate"
                    class="border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                <input type="date" id="toDate"
                    class="border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                <button id="filterBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Filter
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto min-w-full text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3"><input type="checkbox" id="select-all"></th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('No Order') }}</th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Request By') }}</th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Detail Approval') }}</th>
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
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg w-11/12 md:w-3/4">
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

        document.getElementById('searchInput').addEventListener('input', function() {
            const search = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('search', search);

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const updatedTable = doc.querySelector('table');
                    const updatedPagination = doc.querySelector('.mt-4');

                    if (updatedTable && updatedPagination) {
                        document.querySelector('table').replaceWith(updatedTable);
                        document.querySelector('.mt-4').replaceWith(updatedPagination);
                    }

                    // Rebind the open modal button event
                    bindOpenModalEvent();
                })
                .catch(error => console.error('Error fetching search results:', error));
        });

        // Function to bind the event again after search
        function bindOpenModalEvent() {
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

                                    const consumable = item.consumable || {};
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

                            if (tableBody) {
                                tableBody.innerHTML = rows;
                            }
                        }
                        modal.classList.remove('hidden');
                    }
                });
            });
            document.getElementById('select-all').addEventListener('click', function() {
                let checkboxes = document.querySelectorAll('.select-item');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            // Mass Print Listener
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

            closeModalButton.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        }

        // Initial binding
        bindOpenModalEvent();
    </script>
    <script>
        document.getElementById('filterBtn').addEventListener('click', function() {
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;
            const url = new URL(window.location.href);

            if (fromDate) url.searchParams.set('from_date', fromDate);
            if (toDate) url.searchParams.set('to_date', toDate);

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const updatedTable = doc.querySelector('table');
                    const updatedPagination = doc.querySelector('.mt-4');

                    if (updatedTable && updatedPagination) {
                        document.querySelector('table').replaceWith(updatedTable);
                        document.querySelector('.mt-4').replaceWith(updatedPagination);
                    }
                    bindOpenModalEvent();
                })
                .catch(error => console.error('Error fetching filtered results:', error));
        });
    </script>


</x-app-layout>
