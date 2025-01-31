<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">


    <x-sidebar.link title="Dashboard" href="{{ route('Admin.dashboard') }}" :isActive="request()->routeIs('Admin.dashboard')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    @if (auth()->user()->role->id === 1 || auth()->user()->role->id === 5)
        <x-sidebar.link title="Create E-Consumable" href="{{ route('listGroup') }}" :isActive="request()->routeIs(' listGroup')">
            <x-slot name="icon">
                <x-heroicon-o-plus-circle class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link>
    @endif
    <x-sidebar.link title="Reports" href="{{ route('index.report') }}" :isActive="request()->routeIs(' index.report')">
        <x-slot name="icon">
            <x-heroicon-o-chart-pie class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>







    @if (auth()->user()->role->id === 1 || auth()->user()->role->id === 2 || auth()->user()->role->id === 3)
        <x-sidebar.dropdown title="Master Data" :active="Str::startsWith(request()->route()->uri(), 'MasterLine')">
            <x-slot name="icon">
                <x-heroicon-o-view-grid class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

            <x-sidebar.sublink title="Line" href="{{ route('MasterLine.index') }}" :active="request()->routeIs('MasterLine.index')" />
            <x-sidebar.sublink title="Plant" href="{{ route('Plan.index') }}" :active="request()->routeIs('Plan.index')" />
            <x-sidebar.sublink title="User" href="{{ route('User.index') }}" :active="request()->routeIs('User.index')" />
            <x-sidebar.sublink title="Role" href="{{ route('Role.index') }}" :active="request()->routeIs('Role.index')" />
            <x-sidebar.sublink title="Cost Center" href="{{ route('Cost.index') }}" :active="request()->routeIs('Cost.index')" />
            <x-sidebar.sublink title="Group Segment" href="{{ route('Group.index') }}" :active="request()->routeIs('Group.index')" />
            {{-- <x-sidebar.sublink title="Sloc" href="{{ route('Sloc.index') }}" :active="request()->routeIs('Sloc.index')" /> --}}
            {{-- <x-sidebar.sublink title="Type" href="{{ route('Type.index') }}" :active="request()->routeIs('Type.index')" /> --}}
            <x-sidebar.sublink title="Line Group" href="{{ route('LineGroup.index') }}" :active="request()->routeIs('LineGroup.index')" />
            {{-- <x-sidebar.sublink title="Material" href="{{ route('Material.index') }}" :active="request()->routeIs('Material.index')" /> --}}
            <x-sidebar.sublink title="Consumable" href="{{ route('Consumable.index') }}" :active="request()->routeIs('Consumable.index')" />
            {{-- <x-sidebar.sublink title="Icon button" href="{{ route('buttons.icon') }}" :active="request()->routeIs('buttons.icon')" />
        <x-sidebar.sublink title="Text with icon" href="{{ route('buttons.text-icon') }}" :active="request()->routeIs('buttons.text-icon')" /> --}}
        </x-sidebar.dropdown>
        <x-sidebar.link title="Register New User" href="{{ route('register') }}" :isActive="request()->routeIs('register')">
            <x-slot name="icon">
                <x-fas-user-plus class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link>
    @endif

    <x-sidebar.link title="Profil Setting" href="{{ route('profile.edit') }}" :isActive="request()->routeIs('profile.edit')">
        <x-slot name="icon">
            <x-heroicon-o-user-circle class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>


    <x-sidebar.link title="Approval {{ $apprCount }}" href="{{ route('approvalConfirmation.index') }}"
        :isActive="request()->routeIs('approvalConfirmation.index')">
        <x-slot name="icon">
            <x-fas-file-signature class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>








</x-perfect-scrollbar>
