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
            <input type="text" placeholder="Search By Component Name"
                class="w-full text-black px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
            <input type="text" placeholder="Search By Scan Qrcode Component"
                class="w-full text-black px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
        </div>

        <!-- Materials List -->
        <div class="p-4 rounded-md">

            @if ($materials->isEmpty())
                <div class="flex justify-center items-center">
                    <p class="text-lg font-semibold">Tidak ada data material yang tersedia.</p>
                </div>
            @else
                <ul class="space-y-4">
                    @foreach ($materials as $material)
                        <li class="p-4 rounded-md shadow">
                            <!-- Collapse Header -->
                            <button
                                class="flex items-center justify-between w-full text-black text-xl font-bold toggle-collapse"
                                type="button" data-target="#collapse-{{ $material->id }}">
                                <span
                                    class="collapse-icon text-xl font-bold bg-violet-500 hover:bg-violet-600 text-white rounded-md px-5 py-1">
                                    +
                                </span>
                                <h3 class="text-black font-semibold text-center flex-1">
                                    {{ $material->Mt_desc }}
                                </h3>
                            </button>

                            <!-- Collapse Content -->
                            <form action="{{ route('preview') }}" method="POST">
                                @csrf
                                <div id="collapse-{{ $material->id }}" class="collapse-content hidden mt-4">
                                    @if ($material->consumables->isEmpty())
                                        <div
                                            class="flex bg-white p-4 rounded-md items-center justify-between mb-4 mt-5">
                                            <p class="text-black text-center">
                                                Tidak ada data yang ditemukan
                                            </p>
                                        </div>
                                    @else
                                        <input type="hidden" name="idMt" value="{{ $material->_id }}">
                                        @foreach ($material->consumables as $index => $consumable)
                                            <div
                                                class="flex bg-violet-500 p-4 rounded-md items-center justify-between mb-4 mt-5">
                                                <input type="hidden" name="consumables{{ $loop->iteration }}[id]"
                                                    value="{{ $consumable->_id }}">



                                                <!-- Consumable Description -->
                                                <p class="text-white">
                                                    {{ $consumable->Cb_desc }}.
                                                </p>

                                                <!-- Quantity Controls -->
                                                <div class="flex items-center space-x-4">
                                                    <button type="button"
                                                        class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 increment">
                                                        +
                                                    </button>
                                                    <input type="number"
                                                        name="consumables{{ $loop->iteration }}[quantity]"
                                                        value="0" min="0"
                                                        class="w-16 text-center bg-violet-500  text-white font-bold text-lg quantity-input">
                                                    <button type="button"
                                                        class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 decrement">
                                                        -
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
                    @endforeach
                </ul>
            @endif
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
    </script>
</x-app-layout>
