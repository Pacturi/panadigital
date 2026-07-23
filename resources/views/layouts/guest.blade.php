<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Paña Digital') }} — Acceso</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --cream: #FBF7F0;
            --alt: #F5F1E9;
            --white: #FFFFFF;
            --indigo: #5B54D6;
            --indigo-hover: #4A44C0;
            --indigo-soft: #EDEBFC;
            --indigo-ink: #2E2A6B;
            --apricot: #FFA85C;
            --apricot-soft: #FFF0DE;
            --stock-green: #2F9E63;
            --stock-green-soft: #E7F5EC;
            --text: #232134;
            --text-muted: #6E6B82;
            --border: rgba(35, 33, 52, 0.10);
            --font-display: 'Baloo 2', system-ui, sans-serif;
            --font-body: 'Inter', system-ui, sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body.auth-shell {
            margin: 0;
            min-height: 100vh;
            font-family: var(--font-body);
            color: var(--text);
            background: var(--cream);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .auth-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .auth-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(72px);
        }

        .auth-blob--indigo {
            width: 420px;
            height: 420px;
            top: -120px;
            right: -60px;
            background: rgba(91, 84, 214, 0.28);
        }

        .auth-blob--apricot {
            width: 380px;
            height: 380px;
            bottom: -100px;
            left: -80px;
            background: rgba(255, 168, 92, 0.32);
        }

        .auth-blob--soft {
            width: 300px;
            height: 300px;
            top: 42%;
            left: 48%;
            transform: translate(-50%, -50%);
            background: rgba(237, 235, 252, 0.9);
        }

        .auth-top {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 28px;
        }

        .auth-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text);
        }

        .auth-brand-mark {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--indigo);
            display: grid;
            place-items: center;
            box-shadow: 0 8px 20px -8px rgba(91, 84, 214, 0.55);
        }

        .auth-brand-name {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 18px;
            letter-spacing: -0.01em;
        }

        .auth-brand-name b {
            color: var(--apricot);
            font-weight: 800;
        }

        .auth-back {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            transition: color .15s ease;
        }

        .auth-back:hover { color: var(--indigo); }

        .auth-main {
            position: relative;
            z-index: 1;
            min-height: calc(100vh - 88px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px 20px 48px;
        }

        .auth-glass {
            width: 100%;
            max-width: 420px;
            padding: 36px 32px 32px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.62);
            border: 1px solid rgba(255, 255, 255, 0.72);
            box-shadow:
                0 24px 64px -28px rgba(35, 33, 52, 0.22),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
        }

        @media (max-width: 480px) {
            .auth-top { padding: 18px 18px; }
            .auth-glass { padding: 28px 20px 24px; border-radius: 24px; }
        }
    </style>
</head>
<body class="auth-shell antialiased">
    <div class="auth-bg" aria-hidden="true">
        <div class="auth-blob auth-blob--indigo"></div>
        <div class="auth-blob auth-blob--apricot"></div>
        <div class="auth-blob auth-blob--soft"></div>
    </div>

    <header class="auth-top">
        <a href="{{ route('home') }}" class="auth-brand" aria-label="{{ config('app.name', 'Paña Digital') }} — inicio">
            <span class="auth-brand-mark">
                <svg viewBox="0 0 24 24" fill="none" width="18" height="18" aria-hidden="true">
                    <path d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm11 0h1v1h-1v-1Zm3 0h1v1h-1v-1Zm-3 3h1v1h-1v-1Zm3 0h1v3h-2v-2h1v-1Zm-3 3h1v1h-1v-1Z" stroke="#fff" stroke-width="1.4" stroke-linejoin="round"/>
                </svg>
            </span>
            <span class="auth-brand-name">Paña<b>Digital</b></span>
        </a>
        <a href="{{ route('home') }}" class="auth-back">← Volver al inicio</a>
    </header>

    <main class="auth-main">
        <div class="auth-glass">
            {{ $slot }}
        </div>
    </main>
</body>
</html>
