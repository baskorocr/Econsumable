<x-guest-layout>
    <x-auth-card>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="grid gap-6">
                <div class="space-y-2">
                    <x-form.label for="npk" :value="__('NPK')" />

                    <x-form.input-with-icon-wrapper>
                        <x-slot name="icon">
                            <x-fas-book-open-reader aria-hidden="true" class="w-5 h-5" />
                        </x-slot>

                        <x-form.input withicon id="npk" class="block w-full" type="text" name="npk" required
                            autofocus placeholder="{{ __('NPK') }}" />
                    </x-form.input-with-icon-wrapper>
                </div>
                <!-- Name -->
                <div class="space-y-2">
                    <x-form.label for="name" :value="__('Name')" />

                    <x-form.input-with-icon-wrapper>
                        <x-slot name="icon">
                            <x-heroicon-o-user aria-hidden="true" class="w-5 h-5" />
                        </x-slot>

                        <x-form.input withicon id="name" class="block w-full" type="text" name="name"
                            :value="old('name')" required autofocus placeholder="{{ __('Name') }}" />
                    </x-form.input-with-icon-wrapper>
                </div>
                <div class="space-y-2">
                    <x-form.label for="Nomer" :value="__('Nomer')" />

                    <x-form.input-with-icon-wrapper>
                        <x-slot name="icon">
                            <x-heroicon-s-phone aria-hidden="true" class="w-5 h-5" />
                        </x-slot>

                        <x-form.input withicon id="no" class="block w-full" type="number" name="no"
                            required autofocus placeholder="{{ __('No Whatsapp') }}" />
                    </x-form.input-with-icon-wrapper>
                </div>
                <div class="space-y-2">
                    <x-form.label for="Roles" :value="__('Role')" />

                    <select id="role"
                        class="block text-black w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-blue-300"
                        name="role" required>

                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->NameRole }}</option>
                        @endforeach

                    </select>
                </div>

                <!-- Email Address -->
                <div class="space-y-2">
                    <x-form.label for="email" :value="__('Email')" />

                    <x-form.input-with-icon-wrapper>
                        <x-slot name="icon">
                            <x-heroicon-o-mail aria-hidden="true" class="w-5 h-5" />
                        </x-slot>

                        <x-form.input withicon id="email" class="block w-full" type="email" name="email"
                            :value="old('email')" required placeholder="{{ __('Email') }}" />
                    </x-form.input-with-icon-wrapper>
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <x-form.label for="password" :value="__('Password')" />

                    <x-form.input-with-icon-wrapper>
                        <x-slot name="icon">
                            <x-heroicon-o-lock-closed aria-hidden="true" class="w-5 h-5" />
                        </x-slot>

                        <x-form.input withicon id="password" class="block w-full" type="password" name="password"
                            required autocomplete="new-password" placeholder="{{ __('Password') }}" />
                    </x-form.input-with-icon-wrapper>
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <x-form.label for="password_confirmation" :value="__('Confirm Password')" />

                    <x-form.input-with-icon-wrapper>
                        <x-slot name="icon">
                            <x-heroicon-o-lock-closed aria-hidden="true" class="w-5 h-5" />
                        </x-slot>

                        <x-form.input withicon id="password_confirmation" class="block w-full" type="password"
                            name="password_confirmation" required placeholder="{{ __('Confirm Password') }}" />
                    </x-form.input-with-icon-wrapper>
                </div>

                <div>
                    <x-button class="justify-center w-full gap-2">
                        <x-heroicon-o-user-add class="w-6 h-6" aria-hidden="true" />

                        <span>{{ __('Register') }}</span>
                    </x-button>
                </div>


            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
