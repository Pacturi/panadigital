<?php
/**
 * Plantilla Básica — inicio + detalle de producto.
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
$productsUrl = $business['products_url'] ?? ($business['catalog_url'].'/productos');
$e = static fn (?string $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$navActive = $isHome ? 'inicio' : 'producto';
$productCount = count($products);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($title) ?></title>
    <?php if ($isHome): ?>
        <meta name="description" content="<?= $e($description) ?>">
    <?php endif; ?>
    <style>
:root {
    --bg: #f3f7f5;
    --surface: #ffffff;
    --ink: #1c2a24;
    --muted: #5b6b63;
    --line: #d7e3dc;
    --accent: #0f766e;
    --accent-soft: #d9f3ef;
    --whatsapp: #128c7e;
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

.site-header { padding: 1.5rem 0 1rem; }
.eyebrow {
    margin: 0 0 0.4rem;
    font-size: 0.75rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--accent);
    font-weight: 700;
}
.site-header h1 {
    margin: 0;
    font-size: clamp(1.75rem, 6vw, 2.4rem);
    line-height: 1.15;
}
.lead {
    margin: 0.75rem 0 0;
    color: var(--muted);
    font-size: 1rem;
    line-height: 1.5;
}
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
    padding: 0.85rem 1.15rem;
    border-radius: 999px;
    font-weight: 700;
    border: 0;
}
.btn-whatsapp { background: var(--whatsapp); color: #fff; }
.btn-primary { background: var(--ink); color: #fff; }
.btn-block { width: 100%; }
.cta-card {
    margin: 1.25rem 0 2rem;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 1.1rem;
    box-shadow: var(--shadow);
}
.cta-card p { margin: 0.35rem 0 0; color: var(--muted); font-size: 0.95rem; }
.back-link {
    display: inline-block;
    margin-bottom: 0.75rem;
    color: var(--muted);
    font-size: 0.95rem;
}
.product-detail {
    padding: 0.5rem 0 2.5rem;
    display: grid;
    gap: 1.25rem;
}
.product-detail__media {
    border-radius: var(--radius);
    overflow: hidden;
    background: var(--surface);
    border: 1px solid var(--line);
    box-shadow: var(--shadow);
}
.product-detail__media img {
    width: 100%;
    aspect-ratio: 1;
    object-fit: cover;
}
.product-card__placeholder {
    width: 100%;
    min-height: 280px;
    background: linear-gradient(135deg, var(--accent-soft), #f7faf8 60%);
}
.product-detail__info h1 {
    margin: 0;
    font-size: clamp(1.5rem, 5vw, 2rem);
    line-height: 1.2;
}
.price {
    margin: 0;
    color: var(--accent);
    font-weight: 800;
    font-size: 1.05rem;
}
.price--lg { font-size: 1.6rem; margin-top: 0.35rem; }
.product-detail__description {
    margin-top: 1rem;
    color: var(--muted);
    line-height: 1.6;
}
.attrs {
    margin: 1.25rem 0 0;
    padding: 0;
    display: grid;
    gap: 0.65rem;
}
.attrs > div {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.7rem 0.85rem;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: 12px;
}
.attrs dt { margin: 0; color: var(--muted); text-transform: capitalize; }
.attrs dd { margin: 0; font-weight: 700; }
.site-footer { padding: 1.5rem 0 2rem; color: var(--muted); font-size: 0.9rem; }

@media (min-width: 640px) {
    .nav-desktop { display: flex; }
    .nav-toggle { display: none; }
    .product-detail {
        grid-template-columns: 1fr 1fr;
        align-items: start;
        gap: 1.75rem;
    }
}
    </style>
</head>
<body>
<header class="topbar">
    <div class="topbar__inner">
        <a class="brand" href="<?= $e($business['catalog_url']) ?>"><?= $e($business['name']) ?></a>
        <nav class="nav-desktop" aria-label="Principal">
            <a href="<?= $e($business['catalog_url']) ?>" class="<?= $navActive === 'inicio' ? 'is-active' : '' ?>"<?= $navActive === 'inicio' ? ' aria-current="page"' : '' ?>>Inicio</a>
            <a href="<?= $e($productsUrl) ?>">Productos</a>
            <a href="<?= $e($business['catalog_url']) ?>#contacto">Contacto</a>
        </nav>
        <button type="button" class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="nav-mobile" aria-label="Abrir menú">☰</button>
    </div>
    <div class="nav-mobile" id="nav-mobile" hidden>
        <div class="container" style="padding:0.5rem 0 0.75rem">
            <a href="<?= $e($business['catalog_url']) ?>" class="<?= $navActive === 'inicio' ? 'is-active' : '' ?>">Inicio</a>
            <a href="<?= $e($productsUrl) ?>">Productos</a>
            <a href="<?= $e($business['catalog_url']) ?>#contacto">Contacto</a>
        </div>
    </div>
</header>

<?php if ($isHome): ?>
    <header class="site-header">
        <div class="container">
            <p class="eyebrow">Pañalera Digital</p>
            <h1><?= $e($business['name']) ?></h1>
            <?php if (! empty($business['description'])): ?>
                <p class="lead"><?= $e($business['description']) ?></p>
            <?php endif; ?>
            <?php if ($productCount > 0): ?>
                <p class="lead"><?= $productCount ?> productos disponibles</p>
            <?php endif; ?>
            <a class="btn btn-primary" href="<?= $e($productsUrl) ?>">Ver productos</a>
            <?php if (! empty($business['whatsapp_url'])): ?>
                <a class="btn btn-whatsapp" href="<?= $e($business['whatsapp_url']) ?>" target="_blank" rel="noopener">
                    Consultar por WhatsApp
                </a>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
        <section class="cta-card">
            <strong>Catálogo con filtros</strong>
            <p>Buscá por nombre, categoría o rango de precios.</p>
            <a class="btn btn-primary" href="<?= $e($productsUrl) ?>">Ir a productos</a>
        </section>
    </main>
<?php else: ?>
    <main class="container product-detail">
        <div>
            <a class="back-link" href="<?= $e($productsUrl) ?>">← Volver a productos</a>
            <div class="product-detail__media">
                <?php if (! empty($product['image_url'])): ?>
                    <img src="<?= $e($product['image_url']) ?>" alt="<?= $e($product['name']) ?>">
                <?php else: ?>
                    <div class="product-card__placeholder" aria-hidden="true"></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-detail__info">
            <p class="eyebrow"><?= $e($business['name']) ?></p>
            <h1><?= $e($product['name']) ?></h1>
            <p class="price price--lg"><?= $e($product['price_formatted']) ?></p>

            <?php if (! empty($product['description'])): ?>
                <div class="product-detail__description">
                    <p><?= nl2br($e($product['description'])) ?></p>
                </div>
            <?php endif; ?>

            <?php if (! empty($product['attribute_values'])): ?>
                <dl class="attrs">
                    <?php foreach ($product['attribute_values'] as $label => $value): ?>
                        <?php if ($value === null || $value === ''): continue; endif; ?>
                        <div>
                            <dt><?= $e((string) $label) ?></dt>
                            <dd><?= $e((string) $value) ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            <?php endif; ?>

            <?php if (! empty($product['whatsapp_url'])): ?>
                <a class="btn btn-whatsapp btn-block" href="<?= $e($product['whatsapp_url']) ?>" target="_blank" rel="noopener">
                    Pedir por WhatsApp
                </a>
            <?php endif; ?>
        </div>
    </main>
<?php endif; ?>

<footer class="site-footer" id="contacto">
    <div class="container">
        <p><?= $e($business['name']) ?></p>
        <?php if (! empty($business['whatsapp_url'])): ?>
            <p><a href="<?= $e($business['whatsapp_url']) ?>" target="_blank" rel="noopener">WhatsApp</a></p>
        <?php endif; ?>
        <?php if (! empty($business['instagram_url'])): ?>
            <p><a href="<?= $e($business['instagram_url']) ?>" target="_blank" rel="noopener">Instagram</a></p>
        <?php endif; ?>
    </div>
</footer>

<script>
(function () {
    var toggle = document.getElementById('nav-toggle');
    var mobile = document.getElementById('nav-mobile');
    if (!toggle || !mobile) return;
    toggle.addEventListener('click', function () {
        var open = !mobile.classList.contains('is-open');
        mobile.classList.toggle('is-open', open);
        mobile.hidden = !open;
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
})();
</script>
</body>
</html>
