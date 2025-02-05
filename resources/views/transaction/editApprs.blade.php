<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Approval') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <form method="POST" action="{{ route('updateAppr') }}">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <input type="text" hidden name="_id" value="{{ $edit->_id }}">
                    <label for="Lg_code"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Consumable Name') }}</label>
                    <input type="text" name="Lg_code" id="Lg_code"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        readonly value="{{ $edit->consumable->Cb_desc }}">
                </div>
                <div>

                    <label for="Lg_code"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('') }}</label>
                    <input type="text" name="qty" id="qty"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        value="{{ $edit->jumlah }}" inputmode="numeric" pattern="[0-9]*" />
                </div>


                <div class="flex justify-end gap-2 mt-4">

                    <a href="{{ url()->previous() }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md">
                        {{ __('Update') }}
                    </button>
                </div>
        </form>
    </div>
</x-app-layout>
