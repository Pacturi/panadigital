<?php
/**
 * Plantilla Avanzada — inicio + detalle de producto.
 *
 * @var string $page  'inicio' | 'producto'
 * @var array $business
 * @var list<array> $products
 * @var list<array> $brands
 * @var ?array $product
 */
$page = $page ?? 'inicio';
$products = $products ?? [];
$brands = $brands ?? [];
$product = $product ?? null;
$isHome = $page === 'inicio' || $page === 'catalogo';
$title = $isHome
    ? ($business['name'].' · Inicio')
    : (($product['name'] ?? 'Producto').' · '.$business['name']);
$description = $business['description'] ?? $business['name'];
$heroTitle = $business['hero_title'] ?? $business['name'];
$heroSubtitle = $business['hero_subtitle'] ?? ($business['description'] ?? null);
$logoUrl = $business['logo_url'] ?? null;
$productsUrl = $business['products_url'] ?? ($business['catalog_url'].'/productos');
$e = static fn (?string $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$initial = function_exists('mb_substr')
    ? mb_strtoupper(mb_substr((string) ($business['name'] ?? ''), 0, 1, 'UTF-8'), 'UTF-8')
    : strtoupper(substr((string) ($business['name'] ?? ''), 0, 1));
$productCount = count($products);
$productWord = $productCount === 1 ? 'producto' : 'productos';
$showMobileDock = ! $isHome && ! empty($product['whatsapp_url']);
$navActive = $isHome ? 'inicio' : 'producto';

$chatIcon = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="h-[1.05em] w-[1.05em] shrink-0"><path d="M12 2.25c-5.66 0-10.25 4.15-10.25 9.27 0 2.73 1.31 5.19 3.42 6.9-.12 1.15-.47 2.42-1.24 3.72a.5.5 0 00.58.74c2.18-.6 3.82-1.42 4.95-2.16.8.18 1.64.28 2.54.28 5.66 0 10.25-4.15 10.25-9.27S17.66 2.25 12 2.25z"/></svg>';
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($title) ?></title>
    <?php if ($isHome): ?>
        <meta name="description" content="<?= $e($description) ?>">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#16161d',
                        mute: '#6c6f7e',
                        paper: '#f4f5f7',
                        line: '#e3e4ea',
                        accent: { DEFAULT: '#c6862e', dark: '#a16b21', light: '#f4e7d0' },
                        whatsapp: { DEFAULT: '#25D366', dark: '#1dae57' },
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['Fraunces', 'ui-serif', 'Georgia', 'serif'],
                    },
                    boxShadow: {
                        card: '0 1px 2px rgba(22,22,29,0.04), 0 8px 24px -12px rgba(22,22,29,0.12)',
                        cardHover: '0 4px 10px rgba(22,22,29,0.05), 0 20px 40px -16px rgba(22,22,29,0.22)',
                        dock: '0 -8px 24px -8px rgba(22,22,29,0.12)',
                    },
                },
            },
        };
    </script>
    <style>
        [data-reveal] {
            opacity: 0;
            transform: translateY(14px);
            transition: opacity .6s cubic-bezier(.16,.8,.24,1), transform .6s cubic-bezier(.16,.8,.24,1);
            transition-delay: var(--reveal-delay, 0ms);
        }
        [data-reveal].is-visible { opacity: 1; transform: translateY(0); }
        .brand-rail { -ms-overflow-style: none; scrollbar-width: none; }
        .brand-rail::-webkit-scrollbar { display: none; }
        .brand-rail-mask {
            -webkit-mask-image: linear-gradient(to right, transparent, black 24px, black calc(100% - 24px), transparent);
            mask-image: linear-gradient(to right, transparent, black 24px, black calc(100% - 24px), transparent);
        }
        @media (prefers-reduced-motion: reduce) {
            [data-reveal] { opacity: 1 !important; transform: none !important; transition: none !important; }
            * { animation-duration: .001ms !important; animation-iteration-count: 1 !important; transition-duration: .001ms !important; scroll-behavior: auto !important; }
        }
    </style>
</head>
<body class="flex min-h-screen flex-col bg-paper font-sans text-ink antialiased<?= $showMobileDock ? ' pb-20 lg:pb-0' : '' ?>">

<header class="sticky top-0 z-40 border-b border-line/80 bg-white/90 backdrop-blur-md">
    <div class="mx-auto flex h-14 max-w-7xl items-center justify-between gap-3 px-4 sm:h-16 sm:px-6">
        <a href="<?= $e($business['catalog_url']) ?>" class="group flex min-w-0 items-center gap-2.5">
            <?php if (! empty($logoUrl)): ?>
                <img src="<?= $e($logoUrl) ?>" alt="<?= $e($business['name']) ?>" class="h-8 w-8 shrink-0 rounded-full object-cover ring-1 ring-line">
            <?php else: ?>
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-ink text-xs font-semibold text-white"><?= $e($initial) ?></span>
            <?php endif; ?>
            <span class="truncate font-display text-base font-semibold tracking-tight sm:text-lg"><?= $e($business['name']) ?></span>
        </a>

        <nav class="hidden items-center gap-1 md:flex" aria-label="Principal">
            <a href="<?= $e($business['catalog_url']) ?>" class="rounded-full px-3.5 py-2 text-sm font-medium transition <?= $navActive === 'inicio' ? 'bg-ink text-white font-semibold' : 'text-mute hover:bg-paper hover:text-ink' ?>"<?= $navActive === 'inicio' ? ' aria-current="page"' : '' ?>>Inicio</a>
            <a href="<?= $e($productsUrl) ?>" class="rounded-full px-3.5 py-2 text-sm font-medium text-mute transition hover:bg-paper hover:text-ink">Productos</a>
            <a href="<?= $e($business['catalog_url']) ?>#contacto" class="rounded-full px-3.5 py-2 text-sm font-medium text-mute transition hover:bg-paper hover:text-ink">Contacto</a>
        </nav>

        <button type="button" id="nav-toggle" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-line bg-white text-ink md:hidden" aria-expanded="false" aria-controls="nav-mobile" aria-label="Abrir menú">
            <svg id="nav-icon-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            <svg id="nav-icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="hidden h-5 w-5"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </div>
    <div id="nav-mobile" class="hidden border-t border-line bg-white md:hidden" hidden>
        <nav class="mx-auto flex max-w-7xl flex-col gap-1 px-4 py-3" aria-label="Móvil">
            <a href="<?= $e($business['catalog_url']) ?>" class="rounded-xl px-3 py-3 text-sm font-medium <?= $navActive === 'inicio' ? 'bg-accent-light font-semibold text-accent-dark' : 'text-mute hover:bg-paper hover:text-ink' ?>">Inicio</a>
            <a href="<?= $e($productsUrl) ?>" class="rounded-xl px-3 py-3 text-sm font-medium text-mute hover:bg-paper hover:text-ink">Productos</a>
            <a href="<?= $e($business['catalog_url']) ?>#contacto" class="rounded-xl px-3 py-3 text-sm font-medium text-mute hover:bg-paper hover:text-ink">Contacto</a>
        </nav>
    </div>
</header>

<?php if ($isHome): ?>

    <?php if (! empty($business['banner_url'])): ?>
        <section class="relative isolate overflow-hidden" aria-label="Destacado">
            <img src="<?= $e($business['banner_url']) ?>" alt="<?= $e($heroTitle) ?>" class="h-[56vh] min-h-[320px] w-full object-cover sm:h-[64vh]">
            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/30 to-black/10"></div>
            <div class="absolute inset-0 flex items-end">
                <div class="mx-auto w-full max-w-7xl space-y-4 px-4 py-10 sm:space-y-5 sm:px-6 sm:py-16">
                    <?php if ($productCount > 0): ?>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-accent-light" data-reveal>
                            <?= $productCount ?> <?= $e($productWord) ?> disponibles
                        </p>
                    <?php endif; ?>
                    <h1 class="max-w-2xl font-display text-4xl font-semibold tracking-tight text-white sm:text-5xl lg:text-6xl" data-reveal style="--reveal-delay:60ms">
                        <?= $e($heroTitle) ?>
                    </h1>
                    <?php if (! empty($heroSubtitle)): ?>
                        <p class="max-w-xl text-base leading-relaxed text-white/85 sm:text-lg" data-reveal style="--reveal-delay:120ms">
                            <?= $e($heroSubtitle) ?>
                        </p>
                    <?php endif; ?>
                    <div data-reveal style="--reveal-delay:180ms">
                        <a href="<?= $e($productsUrl) ?>" class="group inline-flex items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-ink transition hover:bg-accent hover:text-white">
                            Ver productos
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-20">
            <div class="max-w-2xl space-y-4">
                <span class="block h-1 w-12 rounded-full bg-accent"></span>
                <?php if ($productCount > 0): ?>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-accent-dark">
                        <?= $productCount ?> <?= $e($productWord) ?> disponibles
                    </p>
                <?php endif; ?>
                <h1 class="font-display text-3xl font-semibold tracking-tight sm:text-4xl lg:text-5xl"><?= $e($heroTitle) ?></h1>
                <?php if (! empty($heroSubtitle)): ?>
                    <p class="text-base leading-relaxed text-mute sm:text-lg"><?= $e($heroSubtitle) ?></p>
                <?php endif; ?>
                <a href="<?= $e($productsUrl) ?>" class="inline-flex items-center justify-center gap-2 rounded-full bg-ink px-6 py-3 text-sm font-semibold text-white transition hover:bg-accent">
                    Ver productos
                </a>
            </div>
        </section>
    <?php endif; ?>

    <?php if (count($brands) > 0): ?>
        <section class="space-y-6 py-12 sm:py-16" aria-label="Marcas">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <h2 class="font-display text-2xl font-semibold tracking-tight sm:text-3xl">Marcas</h2>
            </div>
            <div class="brand-rail-mask mx-auto max-w-7xl">
                <ul class="brand-rail flex snap-x snap-mandatory gap-4 overflow-x-auto px-4 pb-2 sm:gap-6 sm:px-6">
                    <?php foreach ($brands as $brand): ?>
                        <li class="flex h-20 w-32 shrink-0 snap-start items-center justify-center rounded-2xl border border-line/70 bg-white p-4 shadow-sm sm:h-24 sm:w-40" title="<?= $e($brand['name']) ?>">
                            <?php if (! empty($brand['image_url'])): ?>
                                <img src="<?= $e($brand['image_url']) ?>" alt="<?= $e($brand['name']) ?>" loading="lazy" class="max-h-12 max-w-full object-contain grayscale transition hover:grayscale-0 sm:max-h-14">
                            <?php else: ?>
                                <span class="text-center text-xs font-medium text-mute"><?= $e($brand['name']) ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    <?php endif; ?>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14">
        <div class="flex flex-col items-start justify-between gap-4 rounded-[2rem] border border-line bg-white p-6 shadow-card sm:flex-row sm:items-center sm:p-8">
            <div class="space-y-1">
                <h2 class="font-display text-2xl font-semibold tracking-tight">Explorá el catálogo</h2>
                <p class="text-sm text-mute">Filtrá por categoría, precio o buscá por nombre.</p>
            </div>
            <a href="<?= $e($productsUrl) ?>" class="inline-flex shrink-0 items-center justify-center rounded-full bg-ink px-6 py-3 text-sm font-semibold text-white transition hover:bg-accent">
                Ir a productos
            </a>
        </div>
    </section>

<?php else: ?>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-16">
        <div class="mb-6">
            <a href="<?= $e($productsUrl) ?>" class="inline-flex items-center gap-1.5 text-sm font-medium text-mute transition hover:text-ink">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M15 18l-6-6 6-6"/></svg>
                Volver a productos
            </a>
        </div>
        <div class="grid gap-8 lg:grid-cols-2 lg:items-start lg:gap-16">
            <div class="overflow-hidden rounded-[28px] border border-line/70 bg-neutral-100 shadow-card">
                <?php if (! empty($product['image_url'])): ?>
                    <img src="<?= $e($product['image_url']) ?>" alt="<?= $e($product['name']) ?>" class="aspect-square w-full object-cover">
                <?php else: ?>
                    <div class="aspect-square bg-gradient-to-br from-neutral-100 to-neutral-200" aria-hidden="true"></div>
                <?php endif; ?>
            </div>

            <div class="space-y-7 sm:space-y-8">
                <?php if (! empty($product['brand'])): ?>
                    <div class="inline-flex items-center gap-3 rounded-full border border-line bg-white py-1.5 pl-1.5 pr-4 shadow-sm">
                        <?php if (! empty($product['brand']['image_url'])): ?>
                            <img src="<?= $e($product['brand']['image_url']) ?>" alt="<?= $e($product['brand']['name']) ?>" class="h-8 w-8 rounded-full bg-paper object-contain">
                        <?php endif; ?>
                        <span class="text-sm font-medium"><?= $e($product['brand']['name']) ?></span>
                    </div>
                <?php endif; ?>

                <div class="space-y-2.5">
                    <h1 class="font-display text-3xl font-semibold tracking-tight sm:text-4xl"><?= $e($product['name']) ?></h1>
                    <p class="text-2xl font-semibold tracking-tight sm:text-3xl"><?= $e($product['price_formatted']) ?></p>
                </div>

                <?php if (! empty($product['description'])): ?>
                    <div class="space-y-2">
                        <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-mute">Descripción</h2>
                        <p class="text-base leading-relaxed text-neutral-700"><?= nl2br($e($product['description'])) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (! empty($product['attribute_values'])): ?>
                    <div class="space-y-2">
                        <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-mute">Detalles</h2>
                        <dl class="divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white">
                            <?php foreach ($product['attribute_values'] as $label => $value): ?>
                                <?php if ($value === null || $value === ''): continue; endif; ?>
                                <div class="flex items-center justify-between gap-4 px-4 py-3 sm:px-5">
                                    <dt class="text-sm capitalize text-mute"><?= $e((string) $label) ?></dt>
                                    <dd class="text-sm font-semibold"><?= $e((string) $value) ?></dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    </div>
                <?php endif; ?>

                <?php if (! empty($product['whatsapp_url'])): ?>
                    <a href="<?= $e($product['whatsapp_url']) ?>" target="_blank" rel="noopener" class="hidden w-full items-center justify-center gap-2 rounded-full bg-whatsapp px-6 py-4 text-base font-semibold text-white transition hover:bg-whatsapp-dark lg:inline-flex">
                        <?= $chatIcon ?>
                        Consultar por WhatsApp
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php if ($showMobileDock): ?>
        <div class="fixed inset-x-0 bottom-0 z-40 flex items-center justify-between gap-4 border-t border-line bg-white/95 px-4 pt-3 shadow-dock backdrop-blur-md lg:hidden" style="padding-bottom: max(0.75rem, env(safe-area-inset-bottom));">
            <div class="min-w-0">
                <p class="truncate text-xs text-mute">Precio</p>
                <p class="truncate text-lg font-semibold tabular-nums tracking-tight"><?= $e($product['price_formatted']) ?></p>
            </div>
            <a href="<?= $e($product['whatsapp_url']) ?>" target="_blank" rel="noopener" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-full bg-whatsapp px-5 py-3 text-sm font-semibold text-white">
                <?= $chatIcon ?>
                Consultar
            </a>
        </div>
    <?php endif; ?>

<?php endif; ?>

<footer id="contacto" class="mt-auto border-t border-line bg-white">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-10 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div class="flex items-center gap-2.5">
            <?php if (! empty($logoUrl)): ?>
                <img src="<?= $e($logoUrl) ?>" alt="" class="h-8 w-8 rounded-full object-cover ring-1 ring-line" aria-hidden="true">
            <?php endif; ?>
            <p class="font-display font-semibold tracking-tight"><?= $e($business['name']) ?></p>
        </div>
        <div class="flex flex-wrap gap-5 text-sm font-medium">
            <?php if (! empty($business['whatsapp_url'])): ?>
                <a href="<?= $e($business['whatsapp_url']) ?>" target="_blank" rel="noopener" class="group inline-flex items-center gap-1.5 text-mute transition hover:text-ink">
                    <?= $chatIcon ?>
                    <span>WhatsApp</span>
                </a>
            <?php endif; ?>
            <?php if (! empty($business['instagram_url'])): ?>
                <a href="<?= $e($business['instagram_url']) ?>" target="_blank" rel="noopener" class="text-mute transition hover:text-ink">Instagram</a>
            <?php endif; ?>
        </div>
    </div>
</footer>

<script>
(function () {
    var toggle = document.getElementById('nav-toggle');
    var panel = document.getElementById('nav-mobile');
    var openIcon = document.getElementById('nav-icon-open');
    var closeIcon = document.getElementById('nav-icon-close');
    if (toggle && panel) {
        toggle.addEventListener('click', function () {
            var open = panel.classList.toggle('hidden') === false;
            panel.hidden = !open;
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (openIcon) openIcon.classList.toggle('hidden', open);
            if (closeIcon) closeIcon.classList.toggle('hidden', !open);
        });
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    var els = document.querySelectorAll('[data-reveal]');
    if (!('IntersectionObserver' in window) || !els.length) return;
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    els.forEach(function (el) { io.observe(el); });
})();
</script>
</body>
</html>
