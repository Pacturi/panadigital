<?php
/**
 * Plantilla Básica — listado de productos con filtros.
 *
 * @var array $business
 * @var list<array> $products
 * @var list<array> $categories
 */
$products = $products ?? [];
$categories = $categories ?? [];
$e = static fn (?string $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$prices = array_map(static fn (array $p): float => (float) ($p['price'] ?? 0), $products);
$minPrice = count($prices) ? floor(min($prices)) : 0;
$maxPrice = count($prices) ? ceil(max($prices)) : 0;
$productCount = count($products);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($business['name']) ?> · Productos</title>
    <meta name="description" content="<?= $e('Productos de '.$business['name']) ?>">
    <style>
:root {
    --bg: #f3f7f5;
    --surface: #ffffff;
    --ink: #1c2a24;
    --muted: #5b6b63;
    --line: #d7e3dc;
    --accent: #0f766e;
    --accent-soft: #d9f3ef;
    --shadow: 0 10px 30px rgba(28, 42, 36, 0.08);
    --radius: 18px;
    --font: "Segoe UI", system-ui, -apple-system, sans-serif;
}
* { box-sizing: border-box; }
html { scroll-behavior: smooth; }
body {
    margin: 0;
    font-family: var(--font);
    color: var(--ink);
    background:
        radial-gradient(circle at top left, rgba(15, 118, 110, 0.12), transparent 40%),
        linear-gradient(180deg, #eef6f3 0%, var(--bg) 40%, #eef2f0 100%);
    min-height: 100vh;
}
a { color: inherit; text-decoration: none; }
img { display: block; max-width: 100%; height: auto; }
.container { width: min(100% - 2rem, 720px); margin-inline: auto; }

.topbar {
    position: sticky;
    top: 0;
    z-index: 40;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--line);
}
.topbar__inner {
    width: min(100% - 2rem, 720px);
    margin-inline: auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    min-height: 3.5rem;
}
.brand {
    font-weight: 800;
    font-size: 1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.nav-desktop { display: none; gap: 0.35rem; }
.nav-desktop a {
    padding: 0.45rem 0.8rem;
    border-radius: 999px;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--muted);
}
.nav-desktop a.is-active {
    background: var(--ink);
    color: #fff;
}
.nav-toggle {
    border: 1px solid var(--line);
    background: #fff;
    border-radius: 999px;
    width: 2.5rem;
    height: 2.5rem;
    display: grid;
    place-items: center;
    cursor: pointer;
}
.nav-mobile {
    display: none;
    border-top: 1px solid var(--line);
    background: #fff;
}
.nav-mobile.is-open { display: block; }
.nav-mobile a {
    display: block;
    padding: 0.9rem 1rem;
    font-weight: 600;
    color: var(--muted);
    border-radius: 12px;
}
.nav-mobile a.is-active {
    background: var(--accent-soft);
    color: var(--accent);
}

.page-head { padding: 1.5rem 0 1rem; }
.page-head h1 { margin: 0; font-size: clamp(1.6rem, 5vw, 2rem); }
.page-head p { margin: 0.4rem 0 0; color: var(--muted); font-size: 0.95rem; }

.filters {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 0.9rem;
    box-shadow: var(--shadow);
    margin-bottom: 1rem;
}
.filters__row { display: grid; gap: 0.75rem; }
.filters label {
    display: block;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 0.35rem;
}
.filters input,
.filters select {
    width: 100%;
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 0.7rem 0.8rem;
    font: inherit;
    background: #f8fbf9;
}
.price-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
.chip-row { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.chip {
    border: 1px solid var(--line);
    background: #fff;
    border-radius: 999px;
    padding: 0.35rem 0.7rem;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--muted);
    cursor: pointer;
}
.chip.is-active {
    background: var(--ink);
    border-color: var(--ink);
    color: #fff;
}
.reset-btn {
    width: 100%;
    margin-top: 0.35rem;
    border: 1px solid var(--line);
    background: #fff;
    border-radius: 999px;
    padding: 0.7rem;
    font-weight: 700;
    color: var(--muted);
    cursor: pointer;
}

.product-grid {
    list-style: none;
    margin: 0;
    padding: 0 0 2rem;
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}
.product-card {
    display: grid;
    grid-template-columns: 108px 1fr;
    gap: 0.9rem;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 0.75rem;
    box-shadow: var(--shadow);
}
.product-item.is-hidden { display: none; }
.product-card__media {
    width: 108px;
    height: 108px;
    border-radius: 14px;
    overflow: hidden;
    background: var(--accent-soft);
}
.product-card__media img { width: 100%; height: 100%; object-fit: cover; }
.product-card__placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--accent-soft), #f7faf8 60%);
}
.product-card__body { display: flex; flex-direction: column; justify-content: center; gap: 0.3rem; min-width: 0; }
.product-card__body h2 { margin: 0; font-size: 1rem; line-height: 1.3; }
.cat-label { margin: 0; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); font-weight: 700; }
.price { margin: 0; color: var(--accent); font-weight: 800; }
.empty {
    background: var(--surface);
    border: 1px dashed var(--line);
    border-radius: var(--radius);
    padding: 1.5rem;
    text-align: center;
    color: var(--muted);
}
.empty.is-hidden { display: none; }
.site-footer { padding: 1.5rem 0 2rem; color: var(--muted); font-size: 0.9rem; }

@media (min-width: 640px) {
    .nav-desktop { display: flex; }
    .nav-toggle { display: none; }
    .product-grid { grid-template-columns: repeat(2, 1fr); }
    .product-card { grid-template-columns: 1fr; }
    .product-card__media { width: 100%; height: 180px; }
    .filters__row { grid-template-columns: 1.4fr 1fr; }
}
    </style>
</head>
<body>
<header class="topbar">
    <div class="topbar__inner">
        <a class="brand" href="<?= $e($business['catalog_url']) ?>"><?= $e($business['name']) ?></a>
        <nav class="nav-desktop" aria-label="Principal">
            <a href="<?= $e($business['catalog_url']) ?>">Inicio</a>
            <a class="is-active" href="<?= $e($business['products_url']) ?>" aria-current="page">Productos</a>
            <a href="<?= $e($business['catalog_url']) ?>#contacto">Contacto</a>
        </nav>
        <button type="button" class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="nav-mobile" aria-label="Abrir menú">☰</button>
    </div>
    <div class="nav-mobile" id="nav-mobile" hidden>
        <div class="container" style="padding:0.5rem 0 0.75rem">
            <a href="<?= $e($business['catalog_url']) ?>">Inicio</a>
            <a class="is-active" href="<?= $e($business['products_url']) ?>" aria-current="page">Productos</a>
            <a href="<?= $e($business['catalog_url']) ?>#contacto">Contacto</a>
        </div>
    </div>
</header>

<main class="container">
    <div class="page-head">
        <h1>Productos</h1>
        <p><span id="results-count"><?= $productCount ?></span> resultados</p>
    </div>

    <section class="filters" aria-label="Filtros">
        <div class="filters__row">
            <div>
                <label for="filter-q">Buscar</label>
                <input id="filter-q" type="search" placeholder="Nombre…" autocomplete="off">
            </div>
            <div>
                <label for="filter-category">Categoría</label>
                <select id="filter-category">
                    <option value="">Todas</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category['id'] ?>"><?= $e($category['name']) ?> (<?= (int) $category['count'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?php if (count($categories) > 0): ?>
            <div style="margin-top:0.85rem">
                <label>Acceso rápido</label>
                <div class="chip-row" id="chip-categories">
                    <button type="button" class="chip is-active" data-category="">Todas</button>
                    <?php foreach ($categories as $category): ?>
                        <button type="button" class="chip" data-category="<?= (int) $category['id'] ?>"><?= $e($category['name']) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($maxPrice > $minPrice): ?>
            <div class="price-grid" style="margin-top:0.85rem">
                <div>
                    <label for="filter-min">Precio desde</label>
                    <input id="filter-min" type="number" min="<?= $minPrice ?>" max="<?= $maxPrice ?>" value="<?= $minPrice ?>">
                </div>
                <div>
                    <label for="filter-max">Precio hasta</label>
                    <input id="filter-max" type="number" min="<?= $minPrice ?>" max="<?= $maxPrice ?>" value="<?= $maxPrice ?>">
                </div>
            </div>
        <?php endif; ?>

        <button type="button" class="reset-btn" id="filter-reset">Limpiar filtros</button>
    </section>

    <?php if ($productCount === 0): ?>
        <p class="empty">Todavía no hay productos publicados.</p>
    <?php else: ?>
        <ul class="product-grid" id="product-grid">
            <?php foreach ($products as $item): ?>
                <li
                    class="product-item"
                    data-name="<?= $e(mb_strtolower($item['name'] ?? '', 'UTF-8')) ?>"
                    data-price="<?= $e((string) ($item['price'] ?? 0)) ?>"
                    data-category="<?= $e((string) ($item['category']['id'] ?? '')) ?>"
                >
                    <a class="product-card" href="<?= $e($item['url']) ?>">
                        <div class="product-card__media">
                            <?php if (! empty($item['image_url'])): ?>
                                <img src="<?= $e($item['image_url']) ?>" alt="<?= $e($item['name']) ?>" loading="lazy">
                            <?php else: ?>
                                <div class="product-card__placeholder" aria-hidden="true"></div>
                            <?php endif; ?>
                        </div>
                        <div class="product-card__body">
                            <?php if (! empty($item['category']['name'])): ?>
                                <p class="cat-label"><?= $e($item['category']['name']) ?></p>
                            <?php endif; ?>
                            <h2><?= $e($item['name']) ?></h2>
                            <p class="price"><?= $e($item['price_formatted']) ?></p>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <p class="empty is-hidden" id="empty-filtered">No hay productos con esos filtros.</p>
    <?php endif; ?>
</main>

<footer class="site-footer" id="contacto">
    <div class="container">
        <p><?= $e($business['name']) ?></p>
        <?php if (! empty($business['whatsapp_url'])): ?>
            <p><a href="<?= $e($business['whatsapp_url']) ?>" target="_blank" rel="noopener">WhatsApp</a></p>
        <?php endif; ?>
    </div>
</footer>

<script>
(function () {
    var toggle = document.getElementById('nav-toggle');
    var mobile = document.getElementById('nav-mobile');
    if (toggle && mobile) {
        toggle.addEventListener('click', function () {
            var open = !mobile.classList.contains('is-open');
            mobile.classList.toggle('is-open', open);
            mobile.hidden = !open;
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }

    var q = document.getElementById('filter-q');
    var categorySelect = document.getElementById('filter-category');
    var minInput = document.getElementById('filter-min');
    var maxInput = document.getElementById('filter-max');
    var reset = document.getElementById('filter-reset');
    var cards = Array.prototype.slice.call(document.querySelectorAll('#product-grid > .product-item'));
    var countEl = document.getElementById('results-count');
    var emptyEl = document.getElementById('empty-filtered');
    var defaultMin = minInput ? Number(minInput.value) : 0;
    var defaultMax = maxInput ? Number(maxInput.value) : Number.MAX_SAFE_INTEGER;

    function setChipActive(value) {
        document.querySelectorAll('#chip-categories .chip').forEach(function (chip) {
            chip.classList.toggle('is-active', (chip.getAttribute('data-category') || '') === value);
        });
    }

    function applyFilters() {
        var query = (q && q.value ? q.value : '').trim().toLowerCase();
        var category = categorySelect ? categorySelect.value : '';
        var min = minInput ? Number(minInput.value) : defaultMin;
        var max = maxInput ? Number(maxInput.value) : defaultMax;
        if (Number.isNaN(min)) min = defaultMin;
        if (Number.isNaN(max)) max = defaultMax;
        if (min > max) { var t = min; min = max; max = t; }

        var visible = 0;
        cards.forEach(function (card) {
            var name = card.getAttribute('data-name') || '';
            var price = Number(card.getAttribute('data-price') || 0);
            var cat = card.getAttribute('data-category') || '';
            var show = (!query || name.indexOf(query) !== -1)
                && (!category || cat === category)
                && price >= min && price <= max;
            card.classList.toggle('is-hidden', !show);
            if (show) visible++;
        });
        if (countEl) countEl.textContent = String(visible);
        if (emptyEl) emptyEl.classList.toggle('is-hidden', visible !== 0 || cards.length === 0);
    }

    if (q) q.addEventListener('input', applyFilters);
    if (categorySelect) {
        categorySelect.addEventListener('change', function () {
            setChipActive(categorySelect.value);
            applyFilters();
        });
    }
    document.querySelectorAll('#chip-categories .chip').forEach(function (chip) {
        chip.addEventListener('click', function () {
            var value = chip.getAttribute('data-category') || '';
            if (categorySelect) categorySelect.value = value;
            setChipActive(value);
            applyFilters();
        });
    });
    if (minInput) minInput.addEventListener('input', applyFilters);
    if (maxInput) maxInput.addEventListener('input', applyFilters);
    if (reset) {
        reset.addEventListener('click', function () {
            if (q) q.value = '';
            if (categorySelect) categorySelect.value = '';
            setChipActive('');
            if (minInput) minInput.value = String(defaultMin);
            if (maxInput) maxInput.value = String(defaultMax);
            applyFilters();
        });
    }
})();
</script>
</body>
</html>
