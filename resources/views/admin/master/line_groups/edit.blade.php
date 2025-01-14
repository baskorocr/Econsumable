<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Line Group') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <form method="POST" action="{{ route('LineGroup.update', $lineGroup->Lg_code) }}">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="Lg_code"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Code Line Group') }}</label>
                    <input type="text" name="Lg_code" id="Lg_code"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        readonly value="{{ $lineGroup->Lg_code }}">
                </div>
                <div>
                    <label for="Lg_plId"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Select Plant') }}</label>
                    <select name="Lg_plId" id="Lg_plId"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        required>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->Pl_code }}"
                                {{ $lineGroup->Lg_plId == $plan->Pl_code ? 'selected' : '' }}>
                                {{ $plan->Pl_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="Lg_csId"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Select Cost Center') }}</label>
                    <select name="Lg_csId" id="Lg_csId"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        required>
                        @foreach ($costCenters as $costCenter)
                            <option value="{{ $costCenter->Cs_code }}"
                                {{ $lineGroup->Lg_csId == $costCenter->Cs_code ? 'selected' : '' }}>
                                {{ $costCenter->Cs_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="Lg_lineId"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Select Line') }}</label>
                    <select name="Lg_lineId" id="Lg_lineId"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        required>
                        @foreach ($lines as $line)
                            <option value="{{ $line->id }}"
                                {{ $lineGroup->Lg_lineId == $line->id ? 'selected' : '' }}>{{ $line->Ln_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="Lg_groupId"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Select Group') }}</label>
                    <select name="Lg_groupId" id="Lg_groupId"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        required>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}"
                                {{ $lineGroup->Lg_groupId == $group->id ? 'selected' : '' }}>{{ $group->Gr_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="Lg_slocId"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Select Sloc') }}</label>
                    <select name="Lg_slocId" id="Lg_slocId"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        required>
                        @foreach ($slocs as $sloc)
                            <option value="{{ $sloc->Tp_mtCode }}"
                                {{ $lineGroup->Lg_slocId == $sloc->Tp_mtCode ? 'selected' : '' }}>{{ $sloc->Tp_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="NpkLeader"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dept. Head') }}</label>


                    <select name="NpkLeader" id="Lg_slocId"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        required>
                        @foreach ($usersDepts as $usersDept)
                            <option value="{{ $usersDept->npk }}"
                                {{ $lineGroup->NpkLeader == $usersDept->npk ? 'selected' : '' }}>
                                {{ $usersDept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="NpkSection"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Sect. Head') }}</label>


                    <select name="NpkSection" id="Lg_slocId"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        required>
                        @foreach ($usersSects as $usersSect)
                            <option value="{{ $usersSect->npk }}"
                                {{ $lineGroup->NpkSection == $usersSect->npk ? 'selected' : '' }}>
                                {{ $usersSect->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="NpkPjStock"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('PJ Stock') }}</label>



                    <select name="NpkPjStock" id="Lg_slocId"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        required>
                        @foreach ($pjs as $pj)
                            <option value="{{ $pj->npk }}"
                                {{ $lineGroup->NpkPjStock == $pj->npk ? 'selected' : '' }}>{{ $pj->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-between mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md">
                    {{ __('Update Line Group') }}
                </button>
                <a href="{{ route('LineGroup.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
