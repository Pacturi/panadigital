<x-guest-layout>
<style>
    .login-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 18px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid rgba(35, 33, 52, 0.08);
        box-shadow: 0 8px 20px -12px rgba(35, 33, 52, 0.18);
        display: grid;
        place-items: center;
        color: #232134;
    }

    .login-title {
        font-family: 'Baloo 2', system-ui, sans-serif;
        font-size: 1.7rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        text-align: center;
        color: #232134;
        line-height: 1.15;
        margin: 0;
    }

    .login-sub {
        margin: 10px auto 0;
        max-width: 30ch;
        text-align: center;
        font-size: 14px;
        line-height: 1.55;
        color: #6E6B82;
    }

    .login-status {
        margin-top: 20px;
        padding: 12px 14px;
        border-radius: 14px;
        font-size: 13.5px;
        font-weight: 500;
        background: #E7F5EC;
        border: 1px solid #B7E4C7;
        color: #1B5C2E;
    }

    .login-form {
        margin-top: 28px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .login-field {
        position: relative;
    }

    .login-field .ico-left {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: #9A97B0;
        pointer-events: none;
    }

    .login-field .ico-right {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        display: grid;
        place-items: center;
        border: none;
        background: transparent;
        color: #9A97B0;
        cursor: pointer;
        border-radius: 10px;
        transition: color .15s ease, background .15s ease;
    }

    .login-field .ico-right:hover {
        color: #5B54D6;
        background: rgba(91, 84, 214, 0.08);
    }

    .login-input {
        width: 100%;
        padding: 14px 16px 14px 46px;
        border-radius: 999px;
        border: 1.5px solid transparent;
        background: rgba(245, 241, 233, 0.92);
        color: #232134;
        font-size: 14.5px;
        font-family: inherit;
        outline: none;
        transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
    }

    .login-input::placeholder { color: #9A97B0; }

    .login-input:focus {
        background: #fff;
        border-color: #5B54D6;
        box-shadow: 0 0 0 4px rgba(91, 84, 214, 0.14);
    }

    .login-input.is-invalid {
        background: #FEF2F2;
        border-color: #F87171;
    }

    .login-input.has-toggle { padding-right: 48px; }

    .login-error {
        margin-top: 6px;
        padding-left: 16px;
        font-size: 12.5px;
        color: #DC2626;
    }

    .login-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 2px 4px 0;
    }

    .login-remember {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        color: #6E6B82;
        cursor: pointer;
        user-select: none;
    }

    .login-remember input {
        width: 16px;
        height: 16px;
        accent-color: #5B54D6;
        cursor: pointer;
    }

    .login-forgot {
        font-size: 13.5px;
        font-weight: 600;
        color: #6E6B82;
        text-decoration: none;
        transition: color .15s ease;
    }

    .login-forgot:hover { color: #5B54D6; }

    .login-submit {
        margin-top: 6px;
        width: 100%;
        padding: 15px 20px;
        border: none;
        border-radius: 999px;
        background: #5B54D6;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        box-shadow: 0 12px 28px -10px rgba(91, 84, 214, 0.5);
        transition: background .18s ease, transform .18s ease, box-shadow .18s ease;
    }

    .login-submit:hover {
        background: #4A44C0;
        transform: translateY(-1px);
        box-shadow: 0 16px 32px -10px rgba(91, 84, 214, 0.55);
    }

    .login-footer {
        margin-top: 24px;
        text-align: center;
        font-size: 13.5px;
        color: #6E6B82;
    }

    .login-footer a {
        color: #5B54D6;
        font-weight: 700;
        text-decoration: none;
    }

    .login-footer a:hover { text-decoration: underline; }
</style>

    <div class="login-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
            <path d="M10 17l5-5-5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M15 12H3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M21 3v18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
    </div>

    <h1 class="login-title">Iniciá sesión</h1>
    <p class="login-sub">
        Entrá a tu panel para gestionar stock, catálogo y pedidos de tu pañalera.
    </p>

    @if (session('status'))
        <div class="login-status" role="status">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        <div>
            <div class="login-field">
                <svg class="ico-left" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                </svg>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Correo electrónico"
                    class="login-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="login-error" />
        </div>

        <div>
            <div class="login-field" x-data="{ show: false }">
                <svg class="ico-left" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
                <input
                    id="password"
                    :type="show ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Contraseña"
                    class="login-input has-toggle {{ $errors->has('password') ? 'is-invalid' : '' }}"
                >
                <button type="button" class="ico-right" @click="show = !show" tabindex="-1" :aria-label="show ? 'Ocultar contraseña' : 'Mostrar contraseña'">
                    <svg x-show="!show" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <svg x-cloak x-show="show" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="login-error" />
        </div>

        <div class="login-row">
            <label for="remember_me" class="login-remember">
                <input id="remember_me" type="checkbox" name="remember">
                Recordarme
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="login-forgot">¿Olvidaste tu contraseña?</a>
            @endif
        </div>

        <button type="submit" class="login-submit">
            Ingresar al panel
        </button>
    </form>

    <p class="login-footer">
        ¿Necesitás una cuenta?
        <a href="{{ route('home') }}">Contactanos</a>
    </p>
</x-guest-layout>
