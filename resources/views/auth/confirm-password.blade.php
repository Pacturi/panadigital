<x-guest-layout>

    <div class="mb-8">
        <h1 class="text-2xl sm:text-[1.75rem] font-extrabold tracking-tight" style="color: #1A1A1A">
            Confirmar contraseña
        </h1>
        <p class="mt-1.5 text-sm" style="color: #5A5A5A">
            Por seguridad, confirmá tu contraseña antes de continuar.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #1A1A1A">
                Contraseña
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="block w-full px-4 py-3 text-sm rounded-xl outline-none transition-all duration-200"
                style="background: white; border: 1.5px solid {{ $errors->get('password') ? '#EF4444' : '#E0E0DC' }}; color: #1A1A1A;"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs" />
        </div>

        <button
            type="submit"
            class="w-full py-3.5 px-6 text-white font-semibold text-sm rounded-xl transition-all duration-200"
            style="background: #1A1A1A; box-shadow: 0 4px 16px rgba(26,26,26,0.20)"
        >
            Confirmar
        </button>
    </form>

</x-guest-layout>
