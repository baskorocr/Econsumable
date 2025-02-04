<x-app-layout>
    <style>
        .quantity-input {
            -moz-appearance: textfield;
            /* Firefox */
            -webkit-appearance: none;
            /* Chrome/Safari */
            appearance: none;
            /* Modern browsers */
            border: none;
            /* Remove border */
            outline: none;
            /* Remove outline */
        }

        .quantity-input::-webkit-inner-spin-button,
        .quantity-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
            /* Ensure no extra margin */
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Transaction Consumable') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <!-- Search Fields -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <input type="text" id="searchInput" placeholder="Search By Consumable Number"
                class="w-full text-black px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />

        </div>

        <!-- Materials List -->
        <div class="p-4 rounded-md">

            @if ($materials->count() == 0)
                <div class="flex justify-center items-center">
                    <p class="text-lg font-semibold">Tidak ada data material yang tersedia.</p>
                </div>
            @else
                <ul class="space-y-4">


                    <li class="p-4 rounded-md shadow">
                        <!-- Collapse Header -->
                        <button
                            class="flex items-center justify-between w-full text-black text-xl font-bold toggle-collapse"
                            type="button" data-target="#collapse-{{ $materials->_id }}">
                            <span
                                class="collapse-icon text-xl font-bold bg-violet-500 hover:bg-violet-600 text-white rounded-md px-5 py-1">
                                +
                            </span>
                            <h3 class="text-black dark:text-white font-semibold text-center flex-1">
                                {{ $materials->Ln_name }}
                            </h3>
                        </button>

                        <!-- Collapse Content -->
                        <form action="{{ route('proses.store') }}" method="POST">
                            @csrf
                            <div id="collapse-{{ $materials->_id }}" class="collapse-content hidden mt-4">

                                @if ($materials->lineGroup->consumable->count() == 0)
                                    <div class="flex bg-white p-4 rounded-md items-center justify-between mb-4 mt-5">
                                        <p class="text-black text-center">
                                            Tidak ada data yang ditemukan
                                        </p>
                                    </div>
                                @else
                                    <input type="hidden" name="idMt" value="{{ $materials->_id }}">
                                    <input type="hidden" name="PlanCode"
                                        value="{{ $materials->lineGroup->plan->Pl_code }}">
                                    <input type="hidden" name="CsCode"
                                        value="{{ $materials->lineGroup->costCenter->Cs_code }}">
                                    <input type="hidden" name="SlocId" value="{{ $materials->lineGroup->Lg_slocId }}">


                                    @foreach ($materials->lineGroup->consumable as $index => $consumable)
                                        <div
                                            class="flex flex-col md:flex-row bg-violet-500 p-4 rounded-md items-center justify-between mb-4 mt-5">
                                            <input type="hidden" name="consumables{{ $loop->iteration }}[id]"
                                                value="{{ $consumable->_id }}">
                                            <input type="hidden" name="consumables{{ $loop->iteration }}[Cb_number]"
                                                value="{{ $consumable->Cb_number }}">

                                            <!-- Consumable Description -->
                                            <p class="text-white mb-2 md:mb-0">
                                                {{ $consumable->Cb_number . ' ' . '( ' . $consumable->Cb_desc . ' )' }}.
                                            </p>

                                            <!-- Quantity Controls -->
                                            <div class="flex items-center space-x-4">
                                                <button type="button"
                                                    class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 decrement">
                                                    -
                                                </button>

                                                <input type="number"
                                                    name="consumables{{ $loop->iteration }}[quantity]" value="0"
                                                    min="0"
                                                    class="w-16 text-center bg-violet-500 text-white font-bold text-lg quantity-input">

                                                <button type="button"
                                                    class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 increment">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Submit and Cancel Buttons -->
                            <div class="mt-6 flex justify-end space-x-4">
                                <a href="{{ url()->previous() }}"
                                    class="bg-red-500 text-white px-6 py-3 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="bg-violet-500 text-white px-6 py-3 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    Proses
                                </button>
                            </div>
                        </form>
                    </li>

                </ul>
            @endif
        </div>
    </div>
    <div id="consumableModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-h-full overflow-y-auto">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Consumable Details') }}</h2>
            <form id="modalForm" action="{{ route('proses.store') }}" method="POST">
                @csrf
                <input type="hidden" name="idMt" value="{{ $materials->_id }}">
                <input type="hidden" name="PlanCode" value="{{ $materials->lineGroup->plan->Pl_code }}">
                <input type="hidden" name="CsCode" value="{{ $materials->lineGroup->costCenter->Cs_code }}">
                <input type="hidden" name="SlocId" value="{{ $materials->lineGroup->Lg_slocId }}">

                <div id="modalContent">
                    <!-- Data will be populated here -->
                </div>
                <div class="flex justify-between mt-4">
                    <button type="button" id="closeModal"
                        class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        {{ __('Close') }}
                    </button>
                    <button type="submit"
                        class="bg-violet-500 text-white px-6 py-3 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Proses
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-collapse').forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-target');
                const target = document.querySelector(targetId);

                if (target.classList.contains('hidden')) {
                    target.classList.remove('hidden'); // Show content
                    button.querySelector('.collapse-icon').textContent = '-'; // Update icon
                } else {
                    target.classList.add('hidden'); // Hide content
                    button.querySelector('.collapse-icon').textContent = '+'; // Update icon
                }
            });
        });

        document.querySelectorAll('.increment').forEach(plusButton => {
            plusButton.addEventListener('click', () => {
                const quantityInput = plusButton.parentNode.querySelector('.quantity-input');
                let currentQuantity = parseInt(quantityInput.value, 10);
                quantityInput.value = currentQuantity + 1;
            });
        });

        document.querySelectorAll('.decrement').forEach(minusButton => {
            minusButton.addEventListener('click', () => {
                const quantityInput = minusButton.parentNode.querySelector('.quantity-input');
                let currentQuantity = parseInt(quantityInput.value, 10);
                if (currentQuantity > 0) {
                    quantityInput.value = currentQuantity - 1;
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const consumableModal = document.getElementById('consumableModal');
            const modalContent = document.getElementById('modalContent');
            const closeModalButton = document.getElementById('closeModal');
            const modalForm = document.getElementById('modalForm');

            // Search functionality
            searchInput.addEventListener('input', function() {
                const search = this.value.toLowerCase();

                if (search !== '') {
                    // Get all consumables from the main form
                    const mainFormConsumables = Array.from(document.querySelectorAll(
                        '.flex.flex-col.md\\:flex-row.bg-violet-500')).map(el => ({
                        id: el.querySelector('input[name$="[id]"]').value,
                        number: el.querySelector('input[name$="[Cb_number]"]').value,
                        desc: el.querySelector('p').textContent.split('(')[1].split(')')[0]
                            .trim(),
                        quantityInput: el.querySelector('.quantity-input')
                    }));

                    // Filter consumables based on search
                    const filteredConsumables = mainFormConsumables.filter(item =>
                        item.number.toLowerCase().includes(search) ||
                        item.desc.toLowerCase().includes(search)
                    );

                    // Clear previous modal content
                    modalContent.innerHTML = '';

                    if (filteredConsumables.length > 0) {
                        filteredConsumables.forEach((item, index) => {
                            const div = document.createElement('div');
                            div.classList.add('p-4', 'rounded-md', 'shadow-md', 'bg-violet-500',
                                'hover:bg-violet-600', 'mb-4');
                            div.innerHTML = `
                        <h2 class="text-lg text-white font-semibold">${item.number}</h2>
                        <h5 class="text-lg text-white font-semibold">(${item.desc})</h5>
                        <div class="mt-4">
                            <label class="block text-sm text-white font-medium">Quantity</label>
                            <div class="flex items-center mt-2">
                                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 decrement">-</button>
                                <input type="number" value="${item.quantityInput.value}" min="0" 
                                    data-main-input-id="${item.id}"
                                    class="w-16 text-center bg-violet-500 text-white font-bold text-lg quantity-input">
                                <button type="button" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 increment">+</button>
                            </div>
                        </div>
                    `;

                            // Add increment/decrement handlers
                            const quantityInput = div.querySelector('.quantity-input');
                            div.querySelector('.decrement').addEventListener('click', () => {
                                let value = parseInt(quantityInput.value);
                                if (value > 0) quantityInput.value = value - 1;
                            });

                            div.querySelector('.increment').addEventListener('click', () => {
                                let value = parseInt(quantityInput.value);
                                quantityInput.value = value + 1;
                            });

                            modalContent.appendChild(div);
                        });

                        consumableModal.classList.remove('hidden');
                    } else {
                        const noResults = document.createElement('p');
                        noResults.classList.add('text-gray-600', 'text-center');
                        noResults.textContent = 'No matching consumables found.';
                        modalContent.appendChild(noResults);
                        consumableModal.classList.remove('hidden');
                    }
                } else {
                    consumableModal.classList.add('hidden');
                }
            });

            // Replace form submission with direct quantity transfer
            modalForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Transfer quantities from modal to main form
                const modalInputs = modalContent.querySelectorAll('.quantity-input');
                modalInputs.forEach(input => {
                    const mainInputId = input.getAttribute('data-main-input-id');
                    const mainFormContainer = document.querySelector(
                        `input[value="${mainInputId}"]`).closest('.flex.flex-col.md\\:flex-row');
                    const mainQuantityInput = mainFormContainer.querySelector('.quantity-input');
                    mainQuantityInput.value = input.value;
                });

                // Close modal and clear search
                consumableModal.classList.add('hidden');
                searchInput.value = '';
            });

            // Close modal handlers
            closeModalButton.addEventListener('click', function() {
                consumableModal.classList.add('hidden');
                searchInput.value = '';
            });

            consumableModal.addEventListener('click', function(event) {
                if (event.target === consumableModal) {
                    consumableModal.classList.add('hidden');
                    searchInput.value = '';
                }
            });
        });
    </script>
</x-app-layout>
