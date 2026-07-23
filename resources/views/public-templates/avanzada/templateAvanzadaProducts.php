<?php
/**
 * Plantilla Avanzada — listado de productos con filtros.
 *
 * @var string $page
 * @var array $business
 * @var list<array> $products
 * @var list<array> $categories
 */
$products = $products ?? [];
$categories = $categories ?? [];
$e = static fn (?string $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$logoUrl = $business['logo_url'] ?? null;
$initial = function_exists('mb_substr')
    ? mb_strtoupper(mb_substr((string) ($business['name'] ?? ''), 0, 1, 'UTF-8'), 'UTF-8')
    : strtoupper(substr((string) ($business['name'] ?? ''), 0, 1));

$prices = array_map(static fn (array $p): float => (float) ($p['price'] ?? 0), $products);
$minPrice = count($prices) ? floor(min($prices)) : 0;
$maxPrice = count($prices) ? ceil(max($prices)) : 0;
$productCount = count($products);

$chatIcon = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="h-[1.05em] w-[1.05em] shrink-0"><path d="M12 2.25c-5.66 0-10.25 4.15-10.25 9.27 0 2.73 1.31 5.19 3.42 6.9-.12 1.15-.47 2.42-1.24 3.72a.5.5 0 00.58.74c2.18-.6 3.82-1.42 4.95-2.16.8.18 1.64.28 2.54.28 5.66 0 10.25-4.15 10.25-9.27S17.66 2.25 12 2.25z"/></svg>';
$navActive = 'productos';
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($business['name']) ?> · Productos</title>
    <meta name="description" content="<?= $e('Productos de '.$business['name']) ?>">
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
                    },
                },
            },
        };
    </script>
</head>
<body class="flex min-h-screen flex-col bg-paper font-sans text-ink antialiased">

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
            <a href="<?= $e($business['catalog_url']) ?>" class="rounded-full px-3.5 py-2 text-sm font-medium text-mute transition hover:bg-paper hover:text-ink">Inicio</a>
            <a href="<?= $e($business['products_url']) ?>" class="rounded-full bg-ink px-3.5 py-2 text-sm font-semibold text-white" aria-current="page">Productos</a>
            <a href="<?= $e($business['catalog_url']) ?>#contacto" class="rounded-full px-3.5 py-2 text-sm font-medium text-mute transition hover:bg-paper hover:text-ink">Contacto</a>
        </nav>

        <button type="button" id="nav-toggle" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-line bg-white text-ink md:hidden" aria-expanded="false" aria-controls="nav-mobile" aria-label="Abrir menú">
            <svg id="nav-icon-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            <svg id="nav-icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="hidden h-5 w-5"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </div>
    <div id="nav-mobile" class="hidden border-t border-line bg-white md:hidden" hidden>
        <nav class="mx-auto flex max-w-7xl flex-col gap-1 px-4 py-3" aria-label="Móvil">
            <a href="<?= $e($business['catalog_url']) ?>" class="rounded-xl px-3 py-3 text-sm font-medium text-mute hover:bg-paper hover:text-ink">Inicio</a>
            <a href="<?= $e($business['products_url']) ?>" class="rounded-xl bg-accent-light px-3 py-3 text-sm font-semibold text-accent-dark" aria-current="page">Productos</a>
            <a href="<?= $e($business['catalog_url']) ?>#contacto" class="rounded-xl px-3 py-3 text-sm font-medium text-mute hover:bg-paper hover:text-ink">Contacto</a>
        </nav>
    </div>
</header>

<main class="mx-auto w-full max-w-7xl flex-1 px-4 py-8 sm:px-6 sm:py-12">
    <div class="mb-8 space-y-2">
        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-accent-dark">Catálogo</p>
        <h1 class="font-display text-3xl font-semibold tracking-tight sm:text-4xl">Productos</h1>
        <p class="text-sm text-mute"><span id="results-count"><?= $productCount ?></span> resultados</p>
    </div>

    <div class="grid gap-8 lg:grid-cols-[260px_1fr] lg:items-start">
        <aside class="space-y-5 rounded-[1.5rem] border border-line/80 bg-white p-4 shadow-card sm:p-5 lg:sticky lg:top-24">
            <div class="flex items-center justify-between gap-3 lg:block">
                <h2 class="text-sm font-semibold">Filtros</h2>
                <button type="button" id="filters-toggle" class="rounded-full border border-line px-3 py-1.5 text-xs font-semibold text-mute lg:hidden" aria-expanded="false" aria-controls="filters-panel">
                    Mostrar
                </button>
            </div>

            <div id="filters-panel" class="hidden space-y-5 lg:block" hidden>
                <div class="space-y-2">
                    <label for="filter-q" class="text-xs font-semibold uppercase tracking-[0.12em] text-mute">Buscar</label>
                    <input id="filter-q" type="search" placeholder="Nombre del producto…" class="w-full rounded-xl border border-line bg-paper px-3 py-2.5 text-sm outline-none ring-accent focus:ring-2" autocomplete="off">
                </div>

                <?php if (count($categories) > 0): ?>
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-mute">Categoría</p>
                        <div class="flex flex-wrap gap-2" id="filter-categories" role="group" aria-label="Categorías">
                            <button type="button" data-category="" class="filter-cat is-active rounded-full border border-ink bg-ink px-3 py-1.5 text-xs font-semibold text-white">Todas</button>
                            <?php foreach ($categories as $category): ?>
                                <button
                                    type="button"
                                    data-category="<?= (int) $category['id'] ?>"
                                    class="filter-cat rounded-full border border-line bg-white px-3 py-1.5 text-xs font-semibold text-mute transition hover:border-accent/40 hover:text-ink"
                                >
                                    <?= $e($category['name']) ?>
                                    <span class="opacity-60">(<?= (int) $category['count'] ?>)</span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($maxPrice > $minPrice): ?>
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-mute">Precio</p>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="filter-min" class="mb-1 block text-[11px] text-mute">Desde</label>
                                <input id="filter-min" type="number" min="<?= $minPrice ?>" max="<?= $maxPrice ?>" value="<?= $minPrice ?>" step="1" class="w-full rounded-xl border border-line bg-paper px-3 py-2 text-sm outline-none ring-accent focus:ring-2">
                            </div>
                            <div>
                                <label for="filter-max" class="mb-1 block text-[11px] text-mute">Hasta</label>
                                <input id="filter-max" type="number" min="<?= $minPrice ?>" max="<?= $maxPrice ?>" value="<?= $maxPrice ?>" step="1" class="w-full rounded-xl border border-line bg-paper px-3 py-2 text-sm outline-none ring-accent focus:ring-2">
                            </div>
                        </div>
                        <input id="filter-range" type="range" min="<?= $minPrice ?>" max="<?= $maxPrice ?>" value="<?= $maxPrice ?>" class="w-full accent-accent">
                    </div>
                <?php endif; ?>

                <button type="button" id="filter-reset" class="w-full rounded-full border border-line px-3 py-2.5 text-sm font-semibold text-mute transition hover:border-ink hover:text-ink">
                    Limpiar filtros
                </button>
            </div>
        </aside>

        <section aria-label="Listado de productos">
            <?php if ($productCount === 0): ?>
                <div class="rounded-3xl border border-dashed border-accent/30 bg-white/60 px-6 py-16 text-center text-mute">
                    Todavía no hay productos publicados.
                </div>
            <?php else: ?>
                <ul id="product-grid" class="grid grid-cols-2 gap-3.5 sm:grid-cols-3 sm:gap-6 lg:grid-cols-3 xl:grid-cols-4">
                    <?php foreach ($products as $item): ?>
                        <li
                            class="product-card group relative flex flex-col overflow-hidden rounded-[28px] border border-line/70 bg-white shadow-card transition duration-300 hover:-translate-y-1 hover:border-accent/30 hover:shadow-cardHover"
                            data-name="<?= $e(mb_strtolower($item['name'] ?? '', 'UTF-8')) ?>"
                            data-price="<?= $e((string) ($item['price'] ?? 0)) ?>"
                            data-category="<?= $e((string) ($item['category']['id'] ?? '')) ?>"
                        >
                            <a href="<?= $e($item['url']) ?>" class="relative block aspect-square overflow-hidden bg-neutral-100">
                                <?php if (! empty($item['image_url'])): ?>
                                    <img src="<?= $e($item['image_url']) ?>" alt="<?= $e($item['name']) ?>" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]">
                                <?php else: ?>
                                    <div class="h-full w-full bg-gradient-to-br from-neutral-100 to-neutral-200" aria-hidden="true"></div>
                                <?php endif; ?>
                                <?php if (! empty($item['price_formatted'])): ?>
                                    <span class="absolute left-2.5 top-2.5 rounded-lg border border-line bg-white/95 px-2.5 py-1 text-xs font-semibold tabular-nums shadow-sm sm:text-sm">
                                        <?= $e($item['price_formatted']) ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                            <div class="flex flex-1 flex-col gap-2.5 p-3.5 sm:p-5">
                                <?php if (! empty($item['category']['name'])): ?>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-mute"><?= $e($item['category']['name']) ?></p>
                                <?php endif; ?>
                                <a href="<?= $e($item['url']) ?>" class="line-clamp-2 text-sm font-medium leading-snug sm:text-base"><?= $e($item['name']) ?></a>
                                <?php if (! empty($item['whatsapp_url'])): ?>
                                    <a href="<?= $e($item['whatsapp_url']) ?>" target="_blank" rel="noopener" class="mt-auto inline-flex w-full items-center justify-center gap-1.5 rounded-full bg-whatsapp px-3 py-2.5 text-xs font-semibold text-white transition hover:bg-whatsapp-dark sm:text-sm">
                                        <?= $chatIcon ?>
                                        WhatsApp
                                    </a>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p id="empty-filtered" class="mt-8 hidden rounded-3xl border border-dashed border-line bg-white px-6 py-12 text-center text-mute">
                    No hay productos con esos filtros.
                </p>
            <?php endif; ?>
        </section>
    </div>
</main>

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
                <a href="<?= $e($business['whatsapp_url']) ?>" target="_blank" rel="noopener" class="text-mute hover:text-ink">WhatsApp</a>
            <?php endif; ?>
            <?php if (! empty($business['instagram_url'])): ?>
                <a href="<?= $e($business['instagram_url']) ?>" target="_blank" rel="noopener" class="text-mute hover:text-ink">Instagram</a>
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
            openIcon.classList.toggle('hidden', open);
            closeIcon.classList.toggle('hidden', !open);
        });
    }

    var filtersToggle = document.getElementById('filters-toggle');
    var filtersPanel = document.getElementById('filters-panel');
    if (filtersToggle && filtersPanel) {
        filtersToggle.addEventListener('click', function () {
            var open = filtersPanel.classList.toggle('hidden') === false;
            filtersPanel.hidden = !open;
            filtersToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            filtersToggle.textContent = open ? 'Ocultar' : 'Mostrar';
        });
    }

    var q = document.getElementById('filter-q');
    var minInput = document.getElementById('filter-min');
    var maxInput = document.getElementById('filter-max');
    var range = document.getElementById('filter-range');
    var reset = document.getElementById('filter-reset');
    var cards = Array.prototype.slice.call(document.querySelectorAll('.product-card'));
    var countEl = document.getElementById('results-count');
    var emptyEl = document.getElementById('empty-filtered');
    var selectedCategory = '';
    var defaultMin = minInput ? Number(minInput.value) : 0;
    var defaultMax = maxInput ? Number(maxInput.value) : Number.MAX_SAFE_INTEGER;

    function applyFilters() {
        var query = (q && q.value ? q.value : '').trim().toLowerCase();
        var min = minInput ? Number(minInput.value) : defaultMin;
        var max = maxInput ? Number(maxInput.value) : defaultMax;
        if (Number.isNaN(min)) min = defaultMin;
        if (Number.isNaN(max)) max = defaultMax;
        if (min > max) { var tmp = min; min = max; max = tmp; }

        var visible = 0;
        cards.forEach(function (card) {
            var name = card.getAttribute('data-name') || '';
            var price = Number(card.getAttribute('data-price') || 0);
            var category = card.getAttribute('data-category') || '';
            var matchQ = !query || name.indexOf(query) !== -1;
            var matchCat = !selectedCategory || category === selectedCategory;
            var matchPrice = price >= min && price <= max;
            var show = matchQ && matchCat && matchPrice;
            card.classList.toggle('hidden', !show);
            if (show) visible++;
        });
        if (countEl) countEl.textContent = String(visible);
        if (emptyEl) emptyEl.classList.toggle('hidden', visible !== 0 || cards.length === 0);
    }

    document.querySelectorAll('.filter-cat').forEach(function (btn) {
        btn.addEventListener('click', function () {
            selectedCategory = btn.getAttribute('data-category') || '';
            document.querySelectorAll('.filter-cat').forEach(function (b) {
                var active = b === btn;
                b.classList.toggle('is-active', active);
                b.classList.toggle('bg-ink', active);
                b.classList.toggle('border-ink', active);
                b.classList.toggle('text-white', active);
                b.classList.toggle('bg-white', !active);
                b.classList.toggle('border-line', !active);
                b.classList.toggle('text-mute', !active);
            });
            applyFilters();
        });
    });

    if (q) q.addEventListener('input', applyFilters);
    if (minInput) minInput.addEventListener('input', applyFilters);
    if (maxInput) {
        maxInput.addEventListener('input', function () {
            if (range) range.value = maxInput.value;
            applyFilters();
        });
    }
    if (range && maxInput) {
        range.addEventListener('input', function () {
            maxInput.value = range.value;
            applyFilters();
        });
    }
    if (reset) {
        reset.addEventListener('click', function () {
            if (q) q.value = '';
            selectedCategory = '';
            document.querySelectorAll('.filter-cat').forEach(function (b, i) {
                var active = i === 0;
                b.classList.toggle('is-active', active);
                b.classList.toggle('bg-ink', active);
                b.classList.toggle('border-ink', active);
                b.classList.toggle('text-white', active);
                b.classList.toggle('bg-white', !active);
                b.classList.toggle('border-line', !active);
                b.classList.toggle('text-mute', !active);
            });
            if (minInput) minInput.value = String(defaultMin);
            if (maxInput) maxInput.value = String(defaultMax);
            if (range) range.value = String(defaultMax);
            applyFilters();
        });
    }
})();
</script>
</body>
</html>
