<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('List Approval') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <form id="printForm" action="{{ route('massApprove') }}" method="POST">
                    @csrf
                    <input hidden name="selected_orders" id="selectedOrders">
                </form>

                <form id="rejectForm" action="{{ route('massReject') }}" method="POST">
                    @csrf
                    <input hidden name="selected_orders" id="selectedOrdersReject">
                </form>

                <button id="mass-print" type="button"
                    class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-md">
                    {{ __('Select Approve') }}
                </button>

                <button id="mass-reject" type="button"
                    class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-md">
                    {{ __('Select Reject') }}
                </button>

                <input type="text" id="searchInput" value="{{ $search }}" placeholder="Search by name"
                    class="w-2 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto min-w-full text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3"><input type="checkbox" id="select-all"></th>
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
                    @foreach ($apprs as $appr)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-4">
                                <input type="checkbox" class="select-item" value="{{ $appr->_id }}">
                            </td>
                            <td style="width: 13rem; height: 4rem;" class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <div>{{ $appr->noOrder }}</div>
                            </td>
                            <td style="width: 13rem; height: 4rem;" class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <div>{{ $appr->user->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                <div>
                                    <button
                                        class="inline-block bg-purple-500 hover:bg-purple-600 dark:bg-purple-600 dark:hover:bg-purple-700 text-white px-4 py-2 rounded-md open-modal-btn"
                                        data-id="{{ $appr->_id }}">
                                        {{ __('open') }}
                                    </button>
                                </div>
                            </td>
                            @if (auth()->user()->role->id === 2 || auth()->user()->role->id === 3 || auth()->user()->role->id === 4)
                                <td class="px-6 py-4 flex justify-center items-center space-x-4">
                                    <a href="{{ route('approvalConfirmation.acc', $appr->_id) }}"
                                        class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md">
                                        {{ __('Approve') }}
                                    </a>
                                    <a href="{{ route('approvalConfirmation.reject', $appr->_id) }}"
                                        class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                        {{ __('Reject') }}
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $apprs->links() }}
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
            const apprData = @json($apprs).data;

            // Select All Functionality
            const selectAllCheckbox = document.getElementById('select-all');
            selectAllCheckbox.addEventListener('click', function() {
                const checkboxes = document.querySelectorAll('.select-item');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            // Mass Approve Handler
            document.getElementById('mass-print').addEventListener('click', function(event) {
                event.preventDefault();
                const selectedOrders = getSelectedOrders();

                if (selectedOrders.length > 0) {
                    document.getElementById('selectedOrders').value = JSON.stringify(selectedOrders);
                    document.getElementById('printForm').submit();
                } else {
                    alert('No Approval is selected.');
                }
            });

            // Mass Reject Handler
            document.getElementById('mass-reject').addEventListener('click', function(event) {
                event.preventDefault();
                const selectedOrders = getSelectedOrders();

                if (selectedOrders.length > 0) {
                    if (confirm('Are you sure you want to reject these items?')) {
                        document.getElementById('selectedOrdersReject').value = JSON.stringify(
                            selectedOrders);
                        document.getElementById('rejectForm').submit();
                    }
                } else {
                    alert('No orders selected for rejection.');
                }
            });

            // Search Functionality
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

                        initializeModal();
                    })
                    .catch(error => console.error('Error fetching search results:', error));
            });

            // Modal Functionality
            function initializeModal() {
                const openModalButtons = document.querySelectorAll('.open-modal-btn');
                const modal = document.getElementById('mstrApprsModal');
                const closeModalButton = document.getElementById('closeMstrApprsModal');
                const tableBody = document.getElementById('mstrApprsTableBody');

                openModalButtons.forEach((button) => {
                    button.addEventListener('click', function() {
                        const apprId = button.getAttribute('data-id');
                        const appr = apprData.find(item => item._id === apprId);

                        if (appr && appr.mstr_apprs) {
                            const rows = generateModalRows(appr);
                            tableBody.innerHTML = rows ||
                                '<tr><td colspan="5" class="px-4 py-2 text-center">No data available</td></tr>';
                            modal.classList.remove('hidden');
                        }
                    });
                });

                closeModalButton.addEventListener('click', function() {
                    modal.classList.add('hidden');
                });
            }

            // Helper Functions
            function getSelectedOrders() {
                const selectedOrders = [];
                document.querySelectorAll('.select-item:checked').forEach(item => {
                    selectedOrders.push(item.value);
                });
                return selectedOrders;
            }

            function generateModalRows(appr) {
                if (!appr.mstr_apprs.length) return '';

                return appr.mstr_apprs.map(item => {
                    const consumable = item.consumable;
                    const statusDisplay = getStatusDisplay(item.status);
                    const approvalAccUrl = '{{ route('editAppr', ':id') }}'.replace(':id', item._id);

                    return `
                        <tr>
                            <td class="px-4 py-2">${appr.noOrder}</td>
                            <td class="px-4 py-2">${consumable.Cb_desc}</td>
                            <td class="px-4 py-2">${item.jumlah}</td>
                            <td class="px-4 py-2">${statusDisplay}</td>
                            <td class="px-4 py-2">
                                <a href="${approvalAccUrl}" class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md">
                                    edit
                                </a>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            function getStatusDisplay(status) {
                switch (status) {
                    case 1:
                        return 'Waiting for approval';
                    case 2:
                    case 3:
                        return 'Partially approved';
                    case 4:
                        return 'Fully approved';
                    default:
                        return 'Unknown status';
                }
            }

            // Initialize modal on page load
            initializeModal();
        });
    </script>
</x-app-layout>
