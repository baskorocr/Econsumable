<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Line Group Management') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <a href="{{ route('LineGroup.create') }}"
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md mb-4 inline-block">
            {{ __('Add New Line Group') }}
        </a>

        <div class="overflow-x-auto">
            <table class="table-auto min-w-full text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Code Line Group') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Plant ') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Cost Center') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Line') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Dept.Head') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Section.Head') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Group') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Sloc') }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach ($lineGroups as $lineGroup)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->Lg_code }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->plan->Pl_name }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->Lg_csId }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->line->Ln_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->leader->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->section->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->group->Gr_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lineGroup->Lg_slocId }}</td>
                            <td class="px-6 py-4 flex justify-center items-center space-x-4">
                                <a href="{{ route('LineGroup.edit', $lineGroup->Lg_code) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">
                                    {{ __('Edit') }}
                                </a>

                                <form action="{{ route('LineGroup.destroy', $lineGroup->Lg_code) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this line group?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $lineGroups->links() }}
        </div>
    </div>
</x-app-layout>
