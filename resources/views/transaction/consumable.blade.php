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
                            <h3 class="text-black font-semibold text-center flex-1">
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
                let id = '{{ $id }}';

                if (search !== '') {
                    fetch(`{{ route('consumable.search') }}?search=${search}&id=${id}`)
                        .then(response => response.json())
                        .then(data => {
                            modalContent.innerHTML = ''; // Clear previous modal content
                            console.log(data);

                            if (data.length > 0) {
                                data.forEach((item, index) => {
                                    const div = document.createElement('div');
                                    div.classList.add('p-4', 'rounded-md', 'shadow-md',
                                        'bg-violet-500', 'hover:bg-violet-600', 'mb-4');

                                    const h2 = document.createElement('h2');
                                    h2.classList.add('text-lg', 'text-white', 'font-semibold');
                                    h2.textContent = item.Cb_number;

                                    const h5 = document.createElement('h5');
                                    h5.classList.add('text-lg', 'text-white', 'font-semibold');
                                    h5.textContent = `(${item.Cb_desc})`;

                                    const formGroup = document.createElement('div');
                                    formGroup.classList.add('mt-4');

                                    const label = document.createElement('label');
                                    label.classList.add('block', 'text-sm', 'text-white',
                                        'font-medium');
                                    label.textContent = 'Quantity';

                                    const quantityDiv = document.createElement('div');
                                    quantityDiv.classList.add('flex', 'items-center', 'mt-2');

                                    const minusButton = document.createElement('button');
                                    minusButton.type = 'button';
                                    minusButton.classList.add('bg-red-500', 'text-white',
                                        'px-4', 'py-2', 'rounded-full', 'hover:bg-red-600');
                                    minusButton.textContent = '-';
                                    quantityDiv.appendChild(minusButton);

                                    const quantityInput = document.createElement('input');
                                    quantityInput.type = 'number';
                                    quantityInput.value = 0; // Default value for quantity
                                    quantityInput.min = 0;
                                    quantityInput.classList.add('w-16', 'text-center',
                                        'bg-violet-500', 'text-white', 'font-bold',
                                        'text-lg', 'quantity-input');
                                    quantityDiv.appendChild(quantityInput);

                                    const plusButton = document.createElement('button');
                                    plusButton.type = 'button';
                                    plusButton.classList.add('bg-green-500', 'text-white',
                                        'px-4', 'py-2', 'rounded-full', 'hover:bg-green-600'
                                        );
                                    plusButton.textContent = '+';
                                    quantityDiv.appendChild(plusButton);

                                    formGroup.appendChild(label);
                                    formGroup.appendChild(quantityDiv);
                                    div.appendChild(h2);
                                    div.appendChild(h5);
                                    div.appendChild(formGroup);
                                    modalContent.appendChild(div);

                                    // Add hidden input for consumable ID and quantity
                                    const consumableIndex =
                                    `consumables${index + 1}`; // Use index to generate unique name for each consumable

                                    const hiddenQuantityInput = document.createElement('input');
                                    hiddenQuantityInput.type = 'hidden';
                                    hiddenQuantityInput.name = `${consumableIndex}[id]`;
                                    hiddenQuantityInput.value = item
                                    ._id; // ID of the consumable
                                    modalForm.appendChild(hiddenQuantityInput);

                                    const hiddenQuantitysInput = document.createElement(
                                    'input');
                                    hiddenQuantitysInput.type = 'hidden';
                                    hiddenQuantitysInput.name = `${consumableIndex}[Cb_number]`;
                                    hiddenQuantitysInput.value = item
                                    .Cb_number; // Cb_number of the consumable
                                    modalForm.appendChild(hiddenQuantitysInput);

                                    const hiddenQuantityValueInput = document.createElement(
                                        'input');
                                    hiddenQuantityValueInput.type = 'hidden';
                                    hiddenQuantityValueInput.name =
                                        `${consumableIndex}[quantity]`;
                                    hiddenQuantityValueInput.value = quantityInput
                                    .value; // Dynamically set initial value
                                    console.log('Initial Hidden Quantity:',
                                        hiddenQuantityValueInput.value); // Debugging
                                    modalForm.appendChild(hiddenQuantityValueInput);

                                    // Event listeners for increment and decrement buttons
                                    minusButton.addEventListener('click', function() {
                                        let currentQuantity = parseInt(quantityInput
                                            .value, 10);
                                        if (currentQuantity > 0) {
                                            quantityInput.value = currentQuantity - 1;
                                            console.log('Quantity after decrement:',
                                                quantityInput.value); // Debugging
                                            updateHiddenQuantity();
                                        }
                                    });

                                    plusButton.addEventListener('click', function() {
                                        let currentQuantity = parseInt(quantityInput
                                            .value, 10);
                                        quantityInput.value = currentQuantity + 1;
                                        console.log('Quantity after increment:',
                                            quantityInput.value); // Debugging
                                        updateHiddenQuantity();
                                    });

                                    // Update hidden quantity input value
                                    function updateHiddenQuantity() {
                                        console.log('Updating hidden input with value:',
                                            quantityInput.value); // Debugging
                                        hiddenQuantityValueInput.value = quantityInput.value;
                                    }
                                });
                            } else {
                                consumableModal.classList.add('hidden');
                                const noResults = document.createElement('p');
                                noResults.classList.add('text-white');
                                noResults.textContent = 'No matching consumables found.';
                                modalContent.appendChild(noResults);
                            }

                            consumableModal.classList.remove('hidden');
                        });
                } else {
                    consumableModal.classList.add('hidden');
                    console.log('dasds');
                }
            });

            // Close modal
            closeModalButton.addEventListener('click', function() {
                consumableModal.classList.add('hidden');
            });
            consumableModal.addEventListener('click', function(event) {
                if (event.target === consumableModal) {
                    consumableModal.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
