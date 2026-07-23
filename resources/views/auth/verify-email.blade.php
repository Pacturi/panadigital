<x-guest-layout>

    <div class="mb-8">
        <h1 class="text-2xl sm:text-[1.75rem] font-extrabold tracking-tight" style="color: #1A1A1A">
            Verificá tu email
        </h1>
        <p class="mt-1.5 text-sm leading-relaxed" style="color: #5A5A5A">
            Te enviamos un enlace de verificación. Revisá tu bandeja de entrada y hacé clic para activar tu cuenta.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 px-4 py-3 rounded-xl text-sm font-medium" style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46">
            Te enviamos un nuevo enlace de verificación al correo que registraste.
        </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-3">
        <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
            @csrf
            <button
                type="submit"
                class="w-full py-3.5 px-6 text-white font-semibold text-sm rounded-xl transition-all duration-200"
                style="background: #1A1A1A; box-shadow: 0 4px 16px rgba(26,26,26,0.20)"
            >
                Reenviar email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="w-full sm:w-auto py-3.5 px-6 font-semibold text-sm rounded-xl transition-all duration-200"
                style="background: white; border: 1.5px solid #E0E0DC; color: #5A5A5A"
            >
                Cerrar sesión
            </button>
        </form>
    </div>

</x-guest-layout>
