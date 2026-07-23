<x-guest-layout>

    <div class="mb-8">
        <h1 class="text-2xl sm:text-[1.75rem] font-extrabold tracking-tight" style="color: #1A1A1A">
            Nueva contraseña
        </h1>
        <p class="mt-1.5 text-sm" style="color: #5A5A5A">
            Elegí una contraseña segura para tu cuenta.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #1A1A1A">
                Correo electrónico
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                autocomplete="username"
                class="block w-full px-4 py-3 text-sm rounded-xl outline-none transition-all duration-200"
                style="background: white; border: 1.5px solid {{ $errors->get('email') ? '#EF4444' : '#E0E0DC' }}; color: #1A1A1A;"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs" />
        </div>

        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #1A1A1A">
                Nueva contraseña
            </label>
            <div class="relative" x-data="{ show: false }">
                <input
                    id="password"
                    :type="show ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Mínimo 8 caracteres"
                    class="block w-full px-4 pr-10 py-3 text-sm rounded-xl outline-none transition-all duration-200"
                    style="background: white; border: 1.5px solid {{ $errors->get('password') ? '#EF4444' : '#E0E0DC' }}; color: #1A1A1A;"
                />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5" style="color: #9A9A9A" tabindex="-1">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #1A1A1A">
                Confirmar contraseña
            </label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Repetí tu contraseña"
                class="block w-full px-4 py-3 text-sm rounded-xl outline-none transition-all duration-200"
                style="background: white; border: 1.5px solid #E0E0DC; color: #1A1A1A;"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs" />
        </div>

        <button
            type="submit"
            class="w-full py-3.5 px-6 text-white font-semibold text-sm rounded-xl transition-all duration-200"
            style="background: #1A1A1A; box-shadow: 0 4px 16px rgba(26,26,26,0.20)"
        >
            Restablecer contraseña
        </button>
    </form>

</x-guest-layout>
