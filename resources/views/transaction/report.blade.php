<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  dark:text-gray-200 leading-tight">
            Reports
        </h2>
    </x-slot>

    <div class="p-6 dark:text-black dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6  border-b ">
                    <form class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <!-- Date Range -->
                        <div class="col-span-2 flex items-center gap-2">
                            <input type="date" placeholder="dd/mm/yyyy" class="form-input w-full rounded-md ">
                            <span class="dark:text-gray-200 ">To</span>
                            <input type="date" placeholder="dd/mm/yyyy" class="form-input w-full rounded-md ">
                        </div>

                        <!-- Select Segment -->
                        <div>
                            <select class="form-select w-full rounded-md ">
                                <option>--Select Segment--</option>
                                <!-- Tambahkan opsi lain jika diperlukan -->
                            </select>
                        </div>

                        <!-- Select Line -->
                        <div>
                            <select class="form-select w-full rounded-md ">
                                <option>--Select Line--</option>
                                <!-- Tambahkan opsi lain jika diperlukan -->
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
