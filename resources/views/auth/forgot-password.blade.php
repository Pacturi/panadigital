<x-guest-layout>

    <div class="mb-8">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-5" style="background: rgba(27,58,66,0.08)">
            <svg class="w-6 h-6" fill="none" stroke="#1B3A42" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
            </svg>
        </div>
        <h1 class="text-2xl sm:text-[1.75rem] font-extrabold tracking-tight" style="color: #1A1A1A">
            Recuperar contraseña
        </h1>
        <p class="mt-2 text-sm leading-relaxed" style="color: #5A5A5A">
            Ingresá el correo de tu cuenta y te enviamos un enlace para crear una nueva contraseña.
        </p>
    </div>

    <x-auth-session-status
        class="mb-5 px-4 py-3 rounded-xl text-sm font-medium"
        style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #1A1A1A">
                Correo electrónico
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="#9A9A9A" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                </span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="tu@negocio.com"
                    class="block w-full pl-10 pr-4 py-3 text-sm rounded-xl outline-none transition-all duration-200"
                    style="background: white; border: 1.5px solid {{ $errors->get('email') ? '#EF4444' : '#E0E0DC' }}; color: #1A1A1A; {{ $errors->get('email') ? 'background:#FEF2F2;' : '' }}"
                    onfocus="this.style.borderColor='#1B3A42'; this.style.boxShadow='0 0 0 3px rgba(27,58,66,0.12)'"
                    onblur="this.style.borderColor='{{ $errors->get('email') ? '#EF4444' : '#E0E0DC' }}'; this.style.boxShadow='none'"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs" />
        </div>

        <button
            type="submit"
            class="w-full py-3.5 px-6 text-white font-semibold text-sm rounded-xl transition-all duration-200"
            style="background: #1A1A1A; box-shadow: 0 4px 16px rgba(26,26,26,0.20)"
            onmouseover="this.style.background='#1B3A42'; this.style.boxShadow='0 8px 24px rgba(27,58,66,0.30)'; this.style.transform='translateY(-1px)'"
            onmouseout="this.style.background='#1A1A1A'; this.style.boxShadow='0 4px 16px rgba(26,26,26,0.20)'; this.style.transform='translateY(0)'"
        >
            Enviar enlace de recuperación
        </button>
    </form>

    <p class="mt-7 text-center text-sm" style="color: #5A5A5A">
        <a
            href="{{ route('login') }}"
            class="font-semibold transition-colors"
            style="color: #1B3A42"
            onmouseover="this.style.color='#2A5560'"
            onmouseout="this.style.color='#1B3A42'"
        >
            ← Volver al inicio de sesión
        </a>
    </p>

</x-guest-layout>
