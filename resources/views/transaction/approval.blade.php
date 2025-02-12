<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('List Approval') }}
        </h2>
    </x-slot>

    <div class="p-4 md:p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <!-- Controls Section -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0 mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <form id="printForm" action="{{ route('massApprove') }}" method="POST">
                    @csrf
                    <input hidden name="selected_orders" id="selectedOrders">
                </form>

                <form id="rejectForm" action="{{ route('massReject') }}" method="POST">
                    @csrf
                    <input hidden name="selected_orders" id="selectedOrdersReject">
                </form>

                <button id="mass-print" type="button"
                    class="w-full sm:w-auto bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-md">
                    {{ __('Mass Approve') }}
                </button>

                <button id="mass-reject" type="button"
                    class="w-full sm:w-auto bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-md">
                    {{ __('Mass Reject') }}
                </button>
            </div>

            <input type="text" id="searchInput" value="{{ $search }}" placeholder="Search by name"
                class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
        </div>

        <!-- Responsive Table -->
        <div class="overflow-x-auto -mx-4 md:mx-0">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="relative px-4 py-3">
                                    <input type="checkbox" id="select-all" class="absolute left-4 top-1/2 -mt-2">
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    {{ __('No') }}
                                </th>
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    {{ __('No Order') }}
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    {{ __('Request By') }}
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    {{ __('Detail') }}
                                </th>
                                @if (auth()->user()->role->id === 2 || auth()->user()->role->id === 3 || auth()->user()->role->id === 4)
                                    <th scope="col"
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        {{ __('Actions') }}
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($apprs as $index => $appr)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="select-item" value="{{ $appr->_id }}">
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        {{ $appr->noOrder }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        {{ $appr->user->name }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <button
                                            class="inline-block bg-purple-500 hover:bg-purple-600 dark:bg-purple-600 dark:hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm open-modal-btn"
                                            data-id="{{ $appr->_id }}">
                                            {{ __('open') }}
                                        </button>
                                    </td>
                                    @if (auth()->user()->role->id === 2 || auth()->user()->role->id === 3 || auth()->user()->role->id === 4)
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <div
                                                class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-2">
                                                <a href="{{ route('approvalConfirmation.acc', $appr->_id) }}"
                                                    class="w-full sm:w-auto bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm">
                                                    {{ __('Approve') }}
                                                </a>
                                                <a href="{{ route('approvalConfirmation.reject', $appr->_id) }}"
                                                    class="w-full sm:w-auto bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                                                    {{ __('Reject') }}
                                                </a>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $apprs->links() }}
        </div>

        <!-- Responsive Modal -->
        <div id="mstrApprsModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-800 bg-opacity-50" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-900 rounded-lg shadow-xl">
                    <div class="flex justify-between items-center border-b pb-4">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-200">Master Approval Data</h3>
                        <button id="closeMstrApprsModal"
                            class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 text-2xl">&times;</button>
                    </div>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>

                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        No Order</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Consumable Name</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Jumlah</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Status</th>
                                    @if (auth()->user()->idRole == 4)
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="mstrApprsTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Data will be dynamically filled here -->
                            </tbody>
                        </table>
                    </div>
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
                                @if (auth()->user()->idRole == 4)
                                <a href="${approvalAccUrl}" class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded-md">
                                    edit
                                </a>
                                 @endif
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
