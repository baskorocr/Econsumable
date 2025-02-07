<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('List SAP Status Fails') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <!-- Search & Filter Container -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <!-- Search & Date Filters (Left) -->
            <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
                <input type="text" id="searchInput" value="{{ $search }}" placeholder="Search by no order"
                    class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <input type="date" id="fromDate"
                        class="border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white w-full md:w-auto px-3 py-2">
                    <input type="date" id="toDate"
                        class="border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white w-full md:w-auto px-3 py-2">
                </div>
            </div>

            <!-- Buttons (Right) -->
            <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
                <button id="filterBtn"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md w-full md:w-auto">
                    Filter
                </button>


            </div>
        </div>

        <!-- Table Wrapper -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3">No Order</th>
                        <th class="px-4 py-3">Request By</th>
                        <th class="px-4 py-3">Detail Approval</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($status as $st)
                        <tr>
                            <td class="px-4 py-3">{{ $st->noOrder }}</td>
                            <td class="px-4 py-3">{{ $st->user->name }}</td>
                            <td class="px-4 py-3">
                                <button class="open-modal-btn bg-purple-500 text-white px-4 py-2 rounded-md"
                                    data-id="{{ $st->_id }}" data-no-order="{{ $st->noOrder }}">
                                    Open
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
            class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center px-4">
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg max-w-md w-full">
                <div class="flex justify-between items-center border-b px-6 py-4">
                    <h3 class="text-lg">Master Approval Data</h3>
                    <button id="closeMstrApprsModal" class="text-gray-500">&times;</button>
                </div>
                <div class="p-4 overflow-x-auto">
                    <table class="table-auto w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>No Order</th>
                                <th>Consumable Name</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="mstrApprsTableBody">
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t">
                    <form id="resendForm" action="{{ route('sap.resend') }}" method="POST">
                        @csrf
                        <input type="hidden" name="no_order" id="no_order">
                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md w-full">
                            Resend
                        </button>
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
            const resendForm = document.getElementById('resendForm');
            const selectAllCheckbox = document.getElementById('select-all');

            const apprData = @json($status->items());

            openModalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const apprId = button.getAttribute('data-id');
                    const appr = apprData.find(item => item._id === apprId);

                    if (appr) {
                        const mstrApprs = appr.mstr_apprs;
                        tableBody.innerHTML = '';
                        noOrderInput.value = '';

                        if (mstrApprs.length > 0) {
                            let rows = '';
                            mstrApprs.forEach(item => {
                                if (item.sap_fails && item.sap_fails.length > 0) {
                                    let statusDisplay = item.status === 1 ?
                                        'Waiting for approval' : item.sap_fails[0]
                                        .Desc_message;

                                    rows += `
                <tr>
                    <td><input type="checkbox" class="select-checkbox" data-no-order="${item.no_order}"></td>
                    <td>${appr.noOrder}</td>
                    <td>${item.consumable?.Cb_desc || '-'}</td>
                    <td>${item.jumlah || 0}</td>
                    <td>${statusDisplay}</td>
                </tr>`;
                                }
                            });
                            tableBody.innerHTML = rows;
                        }
                        modal.classList.remove('hidden');

                        document.querySelectorAll('.select-checkbox').forEach(checkbox => {
                            checkbox.addEventListener('change', updateNoOrderInput);
                        });
                    }
                });
            });

            closeModalButton.addEventListener('click', function() {
                modal.classList.add('hidden');
            });

            function updateNoOrderInput() {
                const selectedNoOrders = Array.from(document.querySelectorAll('.select-checkbox:checked'))
                    .map(checkbox => checkbox.getAttribute('data-no-order'));
                noOrderInput.value = selectedNoOrders.join(',');
            }

            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.select-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateNoOrderInput();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk search dinamis
            document.getElementById('searchInput').addEventListener('input', function() {
                const search = this.value;
                const url = new URL(window.location.href);
                url.searchParams.set('search', search);

                updateTableContent(url);
            });

            // Event listener untuk filter date
            document.getElementById('filterBtn').addEventListener('click', function() {
                const fromDate = document.getElementById('fromDate').value;
                const toDate = document.getElementById('toDate').value;
                const url = new URL(window.location.href);

                if (fromDate) url.searchParams.set('from_date', fromDate);
                if (toDate) url.searchParams.set('to_date', toDate);

                updateTableContent(url);
            });

            // Fungsi untuk update konten tabel
            function updateTableContent(url) {
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

                        // Rebind event modal
                        bindModalEvents();
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Fungsi untuk bind event modal
            function bindModalEvents() {
                const openModalButtons = document.querySelectorAll('.open-modal-btn');
                const modal = document.getElementById('mstrApprsModal');
                const closeModalButton = document.getElementById('closeMstrApprsModal');
                const tableBody = document.getElementById('mstrApprsTableBody');
                const noOrderInput = document.getElementById('no_order');
                const selectAllCheckbox = document.getElementById('select-all');

                const apprData = @json($status->items());

                openModalButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const apprId = button.getAttribute('data-id');
                        const appr = apprData.find(item => item._id === apprId);

                        if (appr) {
                            const mstrApprs = appr.mstr_apprs;
                            tableBody.innerHTML = '';
                            noOrderInput.value = '';

                            if (mstrApprs.length > 0) {
                                let rows = '';
                                mstrApprs.forEach(item => {
                                    if (item.sap_fails && item.sap_fails.length > 0) {
                                        let statusDisplay = item.status === 1 ?
                                            'Waiting for approval' : item.sap_fails[0]
                                            .Desc_message;

                                        rows += `
                                <tr>
                                    <td><input type="checkbox" class="select-checkbox" data-no-order="${item.no_order}"></td>
                                    <td>${appr.noOrder}</td>
                                    <td>${item.consumable?.Cb_desc || '-'}</td>
                                    <td>${item.jumlah || 0}</td>
                                    <td>${statusDisplay}</td>
                                </tr>`;
                                    }
                                });
                                tableBody.innerHTML = rows;
                            }
                            modal.classList.remove('hidden');

                            document.querySelectorAll('.select-checkbox').forEach(checkbox => {
                                checkbox.addEventListener('change', updateNoOrderInput);
                            });
                        }
                    });
                });

                closeModalButton.addEventListener('click', function() {
                    modal.classList.add('hidden');
                });

                function updateNoOrderInput() {
                    const selectedNoOrders = Array.from(document.querySelectorAll('.select-checkbox:checked'))
                        .map(checkbox => checkbox.getAttribute('data-no-order'));
                    noOrderInput.value = selectedNoOrders.join(',');
                }

                selectAllCheckbox.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.select-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                    updateNoOrderInput();
                });
            }

            // Initial binding event modal
            bindModalEvents();
        });
    </script>


</x-app-layout>
