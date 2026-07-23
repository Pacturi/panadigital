<!DOCTYPE html>
<html lang="es-AR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ config('app.name', 'Paña Digital') }} — SaaS para pañaleras</title>
<meta name="description" content="Gestioná stock de pañales por talle y marca, catálogo online y pedidos por WhatsApp. Pensado solo para pañaleras.">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">

<style>
  /* ============================================================
     PAÑA DIGITAL — Opción A: Índigo y durazno
     Un color de marca (índigo) + un acento cálido (durazno).
     El verde queda reservado solo para "en stock" (uso semántico,
     nunca de marca), así se corta la asociación con WhatsApp.
     ============================================================ */
  :root{
    --cream: #FBF7F0;
    --alt: #F5F1E9;
    --white: #FFFFFF;

    --indigo: #5B54D6;
    --indigo-hover: #4A44C0;
    --indigo-soft: #EDEBFC;
    --indigo-ink: #2E2A6B;

    --apricot: #FFA85C;
    --apricot-hover: #F5943E;
    --apricot-soft: #FFF0DE;
    --apricot-ink: #8A4B12;

    --stock-green: #2F9E63;
    --stock-green-soft: #E7F5EC;
    --stock-green-ink: #1B5C2E;

    --text: #232134;
    --text-muted: #6E6B82;
    --border: rgba(35, 33, 52, 0.10);

    /* Compat aliases usados en el markup */
    --paper: var(--cream);
    --paper-2: var(--alt);
    --ink: var(--text);
    --ink-muted: var(--text-muted);
    --ink-line: var(--border);
    --chalk-bg: var(--text);
    --chalk-bg-2: #2E2B45;
    --chalk-bg-3: #3A3656;
    --chalk-white: #FFFFFF;
    --chalk-muted: rgba(255,255,255,.65);
    --chalk-line: rgba(255,255,255,.14);

    --font-display: 'Baloo 2', system-ui, sans-serif;
    --font-body: 'Inter', system-ui, sans-serif;
    --font-mono: 'JetBrains Mono', ui-monospace, monospace;

    --radius-lg: 26px;
    --radius-md: 16px;
    --radius-sm: 10px;
    --shadow-card: 0 18px 48px -20px rgba(35, 33, 52, 0.14);
    --shadow-tag: 0 14px 32px -12px rgba(35, 33, 52, 0.16);
    --shadow-btn: 0 12px 28px -10px rgba(91, 84, 214, 0.40);
    --container: 1180px;
  }

  *,*::before,*::after{ box-sizing: border-box; }
  html{ scroll-behavior: smooth; }
  body{
    margin:0;
    font-family: var(--font-body);
    background: var(--cream);
    color: var(--text);
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
  }
  img{ max-width:100%; display:block; }
  a{ color: inherit; text-decoration:none; }
  ul{ margin:0; padding:0; list-style:none; }
  h1,h2,h3{ font-family: var(--font-display); margin:0; line-height:1.06; }
  p{ margin:0; }
  section{ position:relative; }

  :focus-visible{ outline: 3px solid var(--indigo); outline-offset: 3px; border-radius: 6px; }

  .wrap{ max-width: var(--container); margin-inline:auto; padding-inline: 24px; }
  .eyebrow{
    display:inline-flex; align-items:center; gap:8px;
    font-family: var(--font-mono); font-size: 12.5px; font-weight:600;
    letter-spacing: .12em; text-transform: uppercase;
  }
  .eyebrow::before{ content:""; width:16px; height:2px; background:currentColor; display:inline-block; border-radius:2px; }
  .eyebrow--mint{ color: var(--indigo-hover); }
  .eyebrow--yellow{ color: var(--apricot-ink); }
  .eyebrow--blue{ color: var(--indigo); }

  .swatch--brand{ background: var(--indigo); }
  .swatch--sand{ background: var(--apricot); }

  .btn{
    display:inline-flex; align-items:center; justify-content:center; gap:8px;
    font-family: var(--font-body); font-weight:700; font-size: 15.5px;
    padding: 14px 26px; border-radius: 999px; border: 2px solid transparent;
    cursor:pointer; transition: transform .18s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease;
    white-space: nowrap;
  }
  .btn:hover{ transform: translateY(-2px); }
  .btn-primary{ background: var(--indigo); color: #fff; box-shadow: var(--shadow-btn); }
  .btn-primary:hover{ background: var(--indigo-hover); box-shadow: 0 14px 32px -10px rgba(74, 68, 192, 0.55); }
  .btn-secondary{
    background: var(--white); border-color: var(--indigo); color: var(--indigo-ink);
  }
  .btn-secondary:hover{ background: var(--indigo-soft); }
  .btn-ghost-dark{ background: transparent; border-color: rgba(255,255,255,.35); color: #fff; }
  .btn-ghost-dark:hover{ border-color: #fff; color: #fff; }
  .btn-ghost-light{ background: transparent; border-color: var(--border); color: var(--text); }
  .btn-ghost-light:hover{ border-color: var(--indigo); color: var(--indigo-ink); }
  .btn-on-gradient{
    background: var(--white); color: var(--text); border-color: transparent;
    box-shadow: 0 10px 28px -10px rgba(35,33,52,.2);
  }
  .btn-on-gradient:hover{ background: #fff; transform: translateY(-2px); }

  /* Blobs — solo 2, no un arcoíris: índigo (marca) + durazno (acento) */
  .blob{
    position:absolute; border-radius:50%; filter: blur(64px); pointer-events:none; z-index:0;
  }
  .blob--indigo{ background: rgba(91, 84, 214, 0.30); }
  .blob--apricot{ background: rgba(255, 168, 92, 0.30); }

  .barcode{
    height: 22px;
    --bar: currentColor;
    background-image: repeating-linear-gradient(
      to right,
      var(--bar) 0px, var(--bar) 2px,
      transparent 2px, transparent 5px,
      var(--bar) 5px, var(--bar) 6px,
      transparent 6px, transparent 9px,
      var(--bar) 9px, var(--bar) 12px,
      transparent 12px, transparent 15px,
      var(--bar) 15px, var(--bar) 16px,
      transparent 16px, transparent 21px,
      var(--bar) 21px, var(--bar) 24px,
      transparent 24px, transparent 28px
    );
    opacity:.9;
  }
  .barcode--on-dark{ color: rgba(255,255,255,.28); }
  .barcode--on-light{ color: rgba(35, 33, 52, 0.12); }

  .reveal{ opacity:0; transform: translateY(26px); transition: opacity .7s cubic-bezier(.2,.7,.2,1), transform .7s cubic-bezier(.2,.7,.2,1); }
  .reveal.is-visible{ opacity:1; transform:none; }
  .reveal-delay-1{ transition-delay: .08s; }
  .reveal-delay-2{ transition-delay: .16s; }
  .reveal-delay-3{ transition-delay: .24s; }

  @media (prefers-reduced-motion: reduce){
    .reveal{ opacity:1 !important; transform:none !important; transition:none !important; }
    .swing, .marquee-track, .float-slow{ animation: none !important; }
    .blob{ filter: blur(40px); }
  }

  /* ============================== NAV ============================== */
  header.nav{
    position: sticky; top:0; z-index: 50;
    background: rgba(251, 247, 240, 0.88);
    backdrop-filter: blur(14px);
    border-bottom: 1px solid var(--border);
  }
  .nav-inner{ display:flex; align-items:center; justify-content:space-between; padding-block: 14px; }
  .brand{ display:flex; align-items:center; gap:10px; color: var(--text); }
  .brand-mark{
    width:34px; height:34px; border-radius: 9px;
    background: var(--indigo);
    display:grid; place-items:center;
    box-shadow: 0 6px 16px -6px rgba(91, 84, 214, 0.55);
  }
  .brand-mark svg{ width:19px; height:19px; }
  .brand-name{ font-family: var(--font-display); font-weight:700; font-size: 19px; letter-spacing: -.01em; }
  .brand-name b{ color: var(--indigo-hover); font-weight:800; }

  .nav-links{ display:none; align-items:center; gap:30px; }
  .nav-links a{
    color: var(--text-muted); font-size: 14.5px; font-weight:600;
    transition: color .15s ease;
  }
  .nav-links a:hover{ color: var(--text); }
  .nav-cta{ display:none; align-items:center; gap:12px; }
  .nav-cta .btn{ padding: 10px 20px; font-size:14.5px; }

  .nav-burger{
    display:grid; place-items:center; width:38px; height:38px;
    background:transparent; border: 1px solid var(--border); border-radius: 8px; color: var(--text);
    cursor:pointer;
  }
  .nav-mobile{
    display:none; flex-direction:column; gap:4px; padding: 8px 24px 20px;
    background: var(--cream); border-bottom: 1px solid var(--border);
  }
  .nav-mobile.is-open{ display:flex; }
  .nav-mobile a{ color: var(--text-muted); font-weight:600; padding: 10px 0; border-bottom: 1px solid var(--border); }
  .nav-mobile a:last-child{ border-bottom:none; color: var(--text); }

  @media (min-width: 900px){
    .nav-links{ display:flex; }
    .nav-cta{ display:flex; }
    .nav-burger{ display:none; }
  }

  /* ============================== HERO ============================== */
  .hero{
    background: var(--cream);
    color: var(--text);
    padding-block: 68px 92px;
    overflow:hidden;
  }
  .hero .blob--indigo{ width:420px; height:420px; top:-120px; right:8%; }
  .hero .blob--apricot{ width:360px; height:360px; bottom:-80px; left:5%; }
  .hero-grid{ display:grid; gap:52px; align-items:center; position:relative; z-index:1; }
  .hero-copy .eyebrow{ margin-bottom:18px; }
  .hero h1{
    font-size: clamp(34px, 5.6vw, 58px);
    font-weight:700; letter-spacing:-.01em; color: var(--text);
  }
  .hero h1 em{ font-style:normal; color: var(--indigo-hover); }
  .hero-sub{
    margin-top:18px; font-size: 17px; line-height:1.6; color: var(--text-muted);
    max-width: 46ch;
  }
  .hero-ctas{ display:flex; flex-wrap:wrap; gap:14px; margin-top:30px; }
  .hero-note{
    margin-top:18px; display:flex; align-items:center; gap:10px;
    font-family: var(--font-mono); font-size:12.5px; color: var(--text-muted);
  }
  .hero-note svg{ width:15px; height:15px; color: var(--stock-green); flex-shrink:0; }

  /* --- hero visual composition --- */
  .hero-visual{ position:relative; min-height: 460px; }
  .hero-tag-string{
    position: absolute;
    left: 22%;
    top: 0;
    width:2px; height:44px;
    margin: 0;
    transform: translateX(-50%);
    background: linear-gradient(rgba(91, 84, 214, 0.05), rgba(91, 84, 214, 0.55));
    z-index: 2;
  }
  .hero-tag-wrap{
    position: absolute;
    left: 22%;
    top: 44px;
    width:140px;
    margin: 0;
    transform: translateX(-50%);
    transform-origin: top center;
    animation: swing 4.6s ease-in-out infinite;
    z-index: 2;
  }
  @keyframes swing{
    0%,100%{ transform: translateX(-50%) rotate(-4deg); }
    50%{ transform: translateX(-50%) rotate(4deg); }
  }
  .hero-tag{
    background: var(--indigo-soft);
    border: 1px solid rgba(91, 84, 214, 0.25);
    border-radius: 14px;
    padding: 14px 12px 12px;
    box-shadow: var(--shadow-tag);
    position:relative;
  }
  .hero-tag::before{
    content:""; position:absolute; top:-7px; left:50%; transform:translateX(-50%);
    width:12px; height:12px; border-radius:50%;
    background: var(--indigo); border:3px solid var(--cream);
  }
  .qr-grid{
    width:100%; aspect-ratio:1/1; display:grid;
    grid-template-columns: repeat(7,1fr); grid-template-rows: repeat(7,1fr);
    gap:2px; margin-bottom:10px;
  }
  .qr-grid i{ background: transparent; border-radius:1px; }
  .qr-grid i.on{ background: var(--text); }
  .qr-finder{ background: var(--text) !important; border-radius:2px; position:relative; }
  .qr-finder::after{ content:""; position:absolute; inset:3px; background:var(--indigo-soft); border-radius:1px; }
  .qr-finder::before{ content:""; position:absolute; inset:6px; background:var(--text); border-radius:1px; z-index:1; }
  .hero-tag-label{
    font-family: var(--font-mono); font-size:10.5px; font-weight:700;
    letter-spacing:.04em; color: var(--text); text-align:center; line-height:1.4;
  }
  .hero-tag-label span{ display:block; color: var(--text-muted); font-weight:600; font-size:9.5px; margin-top:2px; }

  .hero-card-photo{
    position:absolute; left:20px; bottom:-100px; width: min(400px, 70%);
    z-index:2;
    background: transparent;
    border: none;
    box-shadow: none;
    border-radius: 0;
    overflow: visible;
  }
  .hero-card-photo img{
    width: 100%; height: auto;
    display: block;
    background: transparent;
  }
  .catalog-head{ display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
  .catalog-head b{ font-family:var(--font-display); font-size:14px; color:var(--text); }
  .catalog-chip{
    font-family: var(--font-mono); font-size:9.5px; font-weight:700;
    background: var(--apricot-soft); color: var(--apricot-ink);
    padding:3px 8px; border-radius:999px;
  }
  .catalog-cats{ display:flex; gap:6px; margin-bottom:12px; }
  .catalog-cats span{
    font-size:10.5px; font-weight:600; color: var(--text-muted);
    background: var(--alt); padding:5px 9px; border-radius:999px;
  }
  .catalog-row{ display:flex; align-items:center; gap:10px; padding:7px 0; border-top:1px solid var(--border); }
  .catalog-row:first-of-type{ border-top:none; }
  .catalog-row i.swatch{ width:26px; height:26px; border-radius:7px; flex-shrink:0; }
  .catalog-row .info{ flex:1; min-width:0; }
  .catalog-row .info b{ display:block; font-size:12px; color:var(--text); }
  .catalog-row .info small{ font-size:10.5px; color: var(--text-muted); }
  .catalog-row .tag-low{
    font-family:var(--font-mono); font-size:8.5px; font-weight:700;
    color: var(--apricot-ink); background: var(--apricot-soft); padding:2px 6px; border-radius:5px;
  }
  .catalog-row .tag-ok{
    font-family:var(--font-mono); font-size:8.5px; font-weight:700;
    color: var(--stock-green-ink); background: var(--stock-green-soft); padding:2px 6px; border-radius:5px;
  }

  .hero-card-chat{
    position:absolute; right:0; top: 8px; width: min(248px, 50%);
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-md); padding:14px; z-index:2;
    box-shadow: var(--shadow-card);
  }
  .chat-msg{
    display:flex; flex-direction:column;
    max-width:92%; margin-bottom:10px;
  }
  .chat-msg:last-child{ margin-bottom:0; }
  .chat-msg.in{ align-items:flex-start; }
  .chat-msg.out{ align-items:flex-end; margin-left:auto; }
  .chat-bubble{
    font-size:12px; line-height:1.45; padding:9px 11px; border-radius:11px;
    width:fit-content; max-width:100%;
  }
  .chat-msg.in .chat-bubble{
    background: var(--alt); color: var(--text);
    border-bottom-left-radius:3px;
  }
  .chat-msg.out .chat-bubble{
    background: var(--indigo); color: #fff;
    border-bottom-right-radius:3px; font-weight:600;
  }
  .chat-time{
    display:block; margin-top:4px;
    font-family: var(--font-mono); font-size:10px; font-weight:500;
    letter-spacing: .02em; opacity: .45; color: var(--text-muted);
    line-height: 1;
  }

  @media (min-width: 860px){
    .hero-grid{ grid-template-columns: 1.05fr .95fr; gap:40px; }
    .hero-visual{ min-height:500px; }
  }

  @media (max-width: 859px){
    .hero-visual{ min-height: 420px; }
    .hero-tag-string, .hero-tag-wrap{ left: 28%; }
    .hero-card-chat{ width: min(220px, 48%); }
    .hero-card-photo{ width: min(280px, 70%); }
  }

  /* ============================== RUBROS BAR ============================== */
  .rubros{ background: var(--white); border-block:1px solid var(--border); padding-block:26px; }
  .rubros-inner{ display:flex; flex-direction:column; gap:16px; align-items:center; text-align:center; }
  .rubros p{ font-family:var(--font-mono); font-size:11.5px; letter-spacing:.1em; text-transform:uppercase; color: var(--text-muted); }
  .rubros-chips{ display:flex; flex-wrap:wrap; gap:10px; justify-content:center; }
  .rubros-chips span{
    font-size:13.5px; font-weight:600; color: var(--text);
    border:1px solid var(--border); background: var(--cream);
    padding:8px 16px; border-radius:999px;
    display:flex; align-items:center; gap:7px;
  }
  .rubros-chips span:nth-child(odd) svg{ color: var(--indigo); }
  .rubros-chips span:nth-child(even) svg{ color: var(--apricot-hover); }
  .rubros-chips span svg{ width:14px; height:14px; }
  @media (min-width:760px){ .rubros-inner{ flex-direction:row; justify-content:space-between; text-align:left; } }

  /* ============================== STATS ============================== */
  .stats{ background: var(--white); padding-block:96px; }
  .stats-grid{ display:grid; gap:48px; align-items:center; }
  .stats-copy .eyebrow{ margin-bottom:16px; }
  .stats h2{ font-size: clamp(28px, 4vw, 40px); color:var(--text); }
  .stats-copy p.lead{ margin-top:16px; font-size:16px; line-height:1.7; color: var(--text-muted); max-width:52ch; }
  .stats .btn{ margin-top:26px; }

  .stat-bubbles{ position:relative; min-height:340px; }
  .bubble{
    position:absolute; border-radius:50%; display:flex; flex-direction:column;
    align-items:center; justify-content:center; text-align:center;
    box-shadow: 0 20px 44px -20px rgba(35, 33, 52, 0.16);
  }
  .bubble b{ font-family:var(--font-display); font-weight:800; line-height:1; }
  .bubble span{ font-size:11.5px; font-weight:600; margin-top:6px; line-height:1.3; padding-inline:10px; }
  .bubble--1{ width:150px; height:150px; background: var(--apricot-soft); color: var(--apricot-ink); top:0; right:10%; }
  .bubble--1 b{ font-size:26px; letter-spacing:-.02em; }
  .bubble--2{ width:118px; height:118px; background: var(--indigo-soft); color: var(--indigo-ink); top:150px; right:38%; }
  .bubble--2 b{ font-size:28px; }
  .bubble--3{ width:190px; height:190px; background: var(--indigo); color: #fff; bottom:0; left:6%; }
  .bubble--3 b{ font-size:34px; }
  @media (min-width:900px){ .stats-grid{ grid-template-columns: 1fr 1fr; } }

  /* ============================== FEATURE DEEP DIVE ============================== */
  .feature{ background: var(--cream); color: var(--text); padding-block:96px; }
  .feature-head{ text-align:center; max-width:640px; margin: 0 auto 52px; }
  .feature-head .eyebrow{ justify-content:center; margin-bottom:16px; }
  .feature-head h2{ font-size: clamp(28px, 4vw, 40px); color: var(--text); }
  .feature-grid{ display:grid; gap:44px; align-items:center; }

  .feature-mock{
    background: var(--white); border:1px solid var(--border); border-radius: var(--radius-lg);
    padding:22px; position:relative; box-shadow: var(--shadow-card);
  }
  .feature-mock .catalog-mini{ background:var(--alt); border-radius:var(--radius-md); padding:16px; margin-bottom:14px; }
  .feature-mock .qr-mini{ display:flex; align-items:center; gap:12px; background:var(--indigo-soft); border-radius:var(--radius-md); padding:14px; }
  .qr-mini .qr-grid{ width:56px; flex-shrink:0; margin-bottom:0; }
  .qr-mini .qr-finder::after{ background: var(--indigo-soft); }
  .qr-mini .qr-text b{ display:block; font-family:var(--font-display); font-size:14px; color: var(--text); }
  .qr-mini .qr-text small{ color: var(--text-muted); font-size:12px; }
  .link-pill{
    margin-top:12px; font-family:var(--font-mono); font-size:12px; color:var(--indigo-ink);
    background: var(--indigo-soft); border:1px solid rgba(91, 84, 214, 0.3);
    padding:8px 12px; border-radius:9px; display:inline-block;
  }

  .feature-list{ display:flex; flex-direction:column; }
  .feature-item{ padding-block:20px; border-top:1px solid var(--border); }
  .feature-item:first-child{ border-top:none; padding-top:0; }
  .feature-item summary{
    display:flex; align-items:center; justify-content:space-between; gap:16px;
    cursor:pointer; list-style:none; font-family:var(--font-display); font-size:18px; font-weight:600; color: var(--text);
  }
  .feature-item summary::-webkit-details-marker{ display:none; }
  .feature-item summary .chev{ width:20px; height:20px; flex-shrink:0; transition: transform .2s ease; color:var(--indigo); }
  .feature-item[open] summary .chev{ transform: rotate(180deg); }
  .feature-item p{ margin-top:12px; color: var(--text-muted); font-size:15px; line-height:1.65; max-width:52ch; }

  @media (min-width:900px){ .feature-grid{ grid-template-columns: 1fr 1fr; } }

  /* ============================== CÓMO FUNCIONA ============================== */
  .steps{ background: var(--indigo-soft); padding-block:88px; }
  .steps-head{ text-align:center; max-width:600px; margin:0 auto 50px; }
  .steps-head .eyebrow{ justify-content:center; margin-bottom:16px; }
  .steps-head h2{ font-size: clamp(26px, 3.6vw, 36px); color:var(--text); }
  .steps-grid{ display:grid; gap:28px; }
  .step-card{
    border:1px solid var(--border); border-radius: var(--radius-md);
    padding:26px; position:relative;
  }
  /* Las tres tarjetas comparten fondo: el número ya marca la secuencia, no hace falta un color distinto por paso */
  .step-card--green, .step-card--white, .step-card--blue{ background: var(--white); }
  .step-num{
    font-family:var(--font-mono); font-size:13px; font-weight:700; color: var(--text-muted);
    letter-spacing:.05em;
  }
  .step-card h3{ font-size:20px; margin-top:14px; color:var(--text); }
  .step-card p{ margin-top:10px; font-size:14.5px; line-height:1.6; color: var(--text-muted); }
  @media (min-width:860px){ .steps-grid{ grid-template-columns: repeat(3,1fr); } }

  /* ============================== WHY / BENEFIT CARDS ============================== */
  .why{ background: var(--white); padding-block: 0 96px; }
  .why-head{ margin-bottom:40px; max-width:600px; padding-top: 8px; }
  .why-head .eyebrow{ margin-bottom:16px; }
  .why-head h2{ font-size: clamp(26px, 3.6vw, 38px); color:var(--text); }
  .why-grid{ display:grid; gap:22px; }
  .why-card{ border-radius: var(--radius-lg); padding:28px; min-height:300px; display:flex; flex-direction:column; border: 1px solid var(--border); color: var(--text); }
  .why-card--green{ background: var(--indigo-soft); }
  .why-card--white{ background: var(--white); }
  .why-card--yellow{ background: var(--apricot-soft); }
  .why-icon{
    width:44px; height:44px; border-radius:12px; display:grid; place-items:center; margin-bottom:18px;
  }
  .why-icon--stock{ background: var(--indigo); color: #fff; }
  .why-icon--reports{ background: var(--apricot); color: #fff; }
  .why-icon--alert{ background: var(--apricot-ink); color: #fff; }
  .why-card h3{ font-size:21px; margin-bottom:10px; }
  .why-card p{ font-size:14.5px; line-height:1.6; color: var(--text-muted); }
  .why-mini-chart{ margin-top:auto; padding-top:18px; display:flex; align-items:end; gap:6px; height:64px; }
  .why-mini-chart i{ flex:1; border-radius:4px 4px 0 0; background: var(--apricot); opacity: 0.9; }
  .why-mini-list{ margin-top:auto; padding-top:16px; display:flex; flex-direction:column; gap:8px; }
  .why-mini-list div{
    font-size:12px; font-weight:600; background: rgba(255,255,255,.7); border-radius:8px; padding:9px 11px;
    display:flex; justify-content:space-between; gap:8px;
  }
  .why-mini-list div b{ color: var(--apricot-ink); font-family:var(--font-mono); font-weight:700; }
  @media (min-width:900px){ .why-grid{ grid-template-columns: repeat(3,1fr); } }

  /* ============================== MARQUEE ============================== */
  .marquee-section{ background: var(--alt); color: var(--text); padding-block:88px; overflow:hidden; }
  .marquee-head{ text-align:center; max-width:620px; margin:0 auto 44px; }
  .marquee-head .eyebrow{ justify-content:center; margin-bottom:16px; }
  .marquee-head h2{ font-size: clamp(26px, 3.6vw, 38px); color: var(--text); }
  .marquee-head p{ margin-top:14px; color: var(--text-muted); font-size:15.5px; }
  .marquee-head .btn{ margin-top:24px; }
  .marquee-mask{ mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent); }
  .marquee-track{
    display:flex; gap:16px; width:max-content;
    animation: scroll-left 32s linear infinite;
  }
  .marquee-track:hover{ animation-play-state: paused; }
  @keyframes scroll-left{ from{ transform: translateX(0); } to{ transform: translateX(-50%); } }
  .marquee-pill{
    font-family: var(--font-mono); font-size:13.5px; white-space:nowrap;
    border:1px solid var(--border); border-radius:999px; padding:12px 20px;
    background: var(--white); color: var(--text-muted);
  }
  .marquee-pill b{ color: var(--indigo-ink); font-weight:600; }

  /* ============================== TESTIMONIALS ============================== */
  .testimonials{ background: var(--cream); padding-block:96px; }
  .testi-head{ text-align:center; max-width:600px; margin:0 auto 48px; }
  .testi-head .eyebrow{ justify-content:center; margin-bottom:16px; }
  .testi-head h2{ font-size: clamp(26px, 3.6vw, 38px); color:var(--text); }
  .testi-grid{ display:grid; gap:22px; }
  .testi-card{
    border:1px solid var(--border); border-radius: var(--radius-md);
    padding:26px; display:flex; flex-direction:column; gap:16px;
  }
  /* Fondo neutro para las tres: lo que distingue cada caso es el avatar, no un color de tarjeta distinto */
  .testi-card:nth-child(1),
  .testi-card:nth-child(2),
  .testi-card:nth-child(3){ background: var(--white); }
  .testi-quote{ font-size:15px; line-height:1.65; color: var(--text); }
  .testi-person{ display:flex; align-items:center; gap:12px; margin-top:auto; }
  .testi-avatar{
    width:40px; height:40px; border-radius:50%; flex-shrink:0;
    display:grid; place-items:center; font-family:var(--font-display); font-weight:700; color: #fff; font-size:14px;
  }
  .testi-person b{ display:block; font-size:13.5px; }
  .testi-person small{ color: var(--text-muted); font-size:12px; }
  @media (min-width:900px){ .testi-grid{ grid-template-columns: repeat(3,1fr); } }

  /* ============================== FINAL CTA ============================== */
  .cta-section{ background: var(--cream); padding-block: 0 96px; }
  .final-cta{
    background: linear-gradient(135deg, #5B54D6 0%, #FFA85C 100%);
    color: #fff; border-radius: var(--radius-lg);
    margin-inline:24px; padding: 56px 32px; text-align:center;
    position:relative; overflow:hidden;
    box-shadow: 0 24px 60px -24px rgba(91, 84, 214, 0.5);
  }
  .final-cta::before{
    content:""; position:absolute; inset:0;
    background-image: radial-gradient(circle, rgba(255,255,255,.18) 1.5px, transparent 1.5px);
    background-size: 22px 22px; opacity:.45; pointer-events:none;
  }
  .final-cta-inner{ position:relative; max-width:560px; margin:0 auto; }
  .final-cta h2{ font-size: clamp(26px, 4vw, 38px); color: #fff; }
  .final-cta p{ margin-top:14px; color: rgba(255,255,255,.88); font-size:16px; }
  .final-cta .ctas{ margin-top:28px; display:flex; flex-wrap:wrap; gap:14px; justify-content:center; }

  /* ============================== FOOTER ============================== */
  footer{ background: var(--text); color: #fff; padding-top:80px; }
  .footer-grid{ display:grid; gap:40px; padding-bottom:48px; border-bottom:1px solid rgba(255,255,255,.12); }
  .footer-brand p{ margin-top:14px; color: rgba(255,255,255,.62); font-size:14.5px; max-width:34ch; line-height:1.6; }
  .footer-social{ display:flex; gap:10px; margin-top:20px; }
  .footer-social a{
    width:36px; height:36px; border:1px solid rgba(255,255,255,.14); border-radius:50%;
    display:grid; place-items:center; transition: border-color .15s ease, color .15s ease; color: #fff;
  }
  .footer-social a:hover{ border-color: var(--apricot); color: var(--apricot); }
  .footer-social svg{ width:16px; height:16px; }
  .footer-col h4{ font-family:var(--font-mono); font-size:12px; letter-spacing:.1em; text-transform:uppercase; color: rgba(255,255,255,.45); margin-bottom:16px; }
  .footer-col ul{ display:flex; flex-direction:column; gap:11px; }
  .footer-col a{ font-size:14.5px; color: rgba(255,255,255,.82); }
  .footer-col a:hover{ color: var(--apricot); }
  @media (min-width:860px){ .footer-grid{ grid-template-columns: 1.4fr repeat(3, 1fr); } }

  .footer-wordmark{ padding-block: 30px 18px; overflow:hidden; }
  .footer-wordmark span{
    display:block; font-family: var(--font-display); font-weight:800;
    font-size: clamp(58px, 15vw, 168px); letter-spacing:-.03em; line-height:.85;
    color: transparent; -webkit-text-stroke: 1.5px rgba(255,255,255,.18);
    text-align:center; white-space:nowrap;
  }
  .footer-bottom{
    border-top:1px solid rgba(255,255,255,.12); padding-block:20px;
    display:flex; flex-direction:column; gap:12px; align-items:center; text-align:center;
    font-size:13px; color: rgba(255,255,255,.45);
  }
  @media (min-width:700px){ .footer-bottom{ flex-direction:row; justify-content:space-between; text-align:left; } }
</style>
</head>
<body>

{{-- ============================== NAV ============================== --}}
<header class="nav">
  <div class="wrap nav-inner">
    <a href="/" class="brand" aria-label="{{ config('app.name', 'Paña Digital') }} — inicio">
      <span class="brand-mark" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none"><path d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm11 0h1v1h-1v-1Zm3 0h1v1h-1v-1Zm-3 3h1v1h-1v-1Zm3 0h1v3h-2v-2h1v-1Zm-3 3h1v1h-1v-1Z" stroke="#fff" stroke-width="1.4" stroke-linejoin="round"/></svg>
      </span>
      <span class="brand-name">Paña<b>Digital</b></span>
    </a>

    <nav class="nav-links" aria-label="Navegación principal">
      <a href="#funciones">Funciones</a>
      <a href="#como-funciona">Cómo funciona</a>
      <a href="#casos">Casos reales</a>
      <a href="#precios">Precios</a>
    </nav>

    <div class="nav-cta">
      @auth
      <a href="{{ route('dashboard') }}" class="btn btn-primary">Ir al panel</a>
      @else
      <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
      @endauth
    </div>

    <button class="nav-burger" id="navBurger" aria-expanded="false" aria-controls="navMobile" aria-label="Abrir menú">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="none"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    </button>
  </div>
  <nav class="nav-mobile" id="navMobile" aria-label="Navegación móvil">
    <a href="#funciones">Funciones</a>
    <a href="#como-funciona">Cómo funciona</a>
    <a href="#casos">Casos reales</a>
    <a href="#precios">Precios</a>
    @auth
    <a href="{{ route('dashboard') }}">Ir al panel</a>
    @else
    <a href="{{ route('login') }}">Iniciar sesión</a>
    @endauth
  </nav>
</header>

{{-- ============================== HERO ============================== --}}
<section class="hero">
  <div class="blob blob--indigo" aria-hidden="true"></div>
  <div class="blob blob--apricot" aria-hidden="true"></div>
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <span class="eyebrow eyebrow--mint">Solo para pañaleras</span>
      <h1>El <em>sistema</em><br>de tu pañalera</h1>
      <p class="hero-sub">
        Stock por talle y marca, catálogo online y pedidos por WhatsApp.
        Pensado para pañales, mamaderas, higiene y ropa de bebé — no para cualquier rubro.
      </p>
      <div class="hero-ctas">
        @auth
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Ir al panel</a>
        @else
        <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
        @endauth
        <a href="#como-funciona" class="btn btn-secondary">Ver cómo funciona</a>
      </div>
      <div class="hero-note">
        <svg viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Panel web · catálogo público · sin app para descargar
      </div>
    </div>

    <div class="hero-visual">
      <div class="hero-tag-string" aria-hidden="true"></div>
      <div class="hero-tag-wrap swing" aria-hidden="true">
        <div class="hero-tag">
          <div class="qr-grid">
            <i class="qr-finder" style="grid-column:1/3;grid-row:1/3;"></i><i></i><i class="on"></i><i></i><i class="qr-finder" style="grid-column:5/7;grid-row:1/3;"></i><i></i>
            <i></i><i></i><i class="on"></i><i class="on"></i><i></i><i></i><i class="on"></i>
            <i class="on"></i><i></i><i class="on"></i><i></i><i class="on"></i><i></i><i class="on"></i>
            <i></i><i class="on"></i><i></i><i class="on"></i><i></i><i class="on"></i><i></i>
            <i class="on"></i><i></i><i class="on"></i><i class="on"></i><i></i><i class="on"></i><i></i>
            <i></i><i class="on"></i><i></i><i></i><i class="on"></i><i></i><i class="on"></i>
            <i class="qr-finder" style="grid-column:1/3;grid-row:7/9;"></i><i></i><i class="on"></i><i></i><i class="on"></i><i class="on"></i><i></i>
          </div>
          <p class="hero-tag-label">ESCANEÁ EL QR<span>catálogo al instante</span></p>
        </div>
      </div>

      <div class="hero-card-photo">
        <img
          src="{{ asset('images/hero/woman.jpg') }}"
          alt="Dueña de pañalera gestionando su negocio"
          width="600"
          height="750"
          loading="eager"
          decoding="async"
        >
      </div>

      <div class="hero-card-chat" aria-hidden="true">
        <div class="chat-msg in">
          <div class="chat-bubble">Hola! ¿Tenés Pampers talle G?</div>
          <time class="chat-time" datetime="23:37">23:37</time>
        </div>
        <div class="chat-msg out">
          <div class="chat-bubble">Sí 🙌 Mirá stock y precios: mipanadigital.com/tu-pañalera</div>
          <time class="chat-time" datetime="23:37">23:37</time>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ============================== Items vendibles ============================== --}}
<section class="rubros">
  <div class="wrap rubros-inner">
    <p>Categorías de tu pañalera</p>
    <div class="rubros-chips">
      @foreach ([
        'Pañales',
        'Pañales de natación',
        'Toallitas húmedas',
        'Mamaderas',
        'Chupetes',
        'Bodies y remeras',
        'Accesorios de lactancia',
        'Higiene del bebé',
      ] as $rubro)
        <span>
          <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="3.4" stroke="currentColor" stroke-width="1.6"/></svg>
          {{ $rubro }}
        </span>
      @endforeach
    </div>
  </div>
</section>

{{-- ============================== STATS ============================== --}}
<section class="stats">
  <div class="wrap stats-grid">
    <div class="stats-copy reveal">
      <span class="eyebrow eyebrow--mint">Hecho para pañaleras</span>
      <h2>Todo lo que tu local de bebé necesita, en un solo panel</h2>
      <p class="lead">
        Cargá pañales por marca, talle y cantidad. Compartí el catálogo con un link o QR
        en el mostrador. Tus clientas ven stock real y vos sabés qué reponer antes de que falte.
      </p>
      @auth
      <a href="{{ route('dashboard') }}" class="btn btn-secondary">Ir al panel</a>
      @else
      <a href="{{ route('login') }}" class="btn btn-secondary">Iniciar sesión</a>
      @endauth
    </div>
    <div class="stat-bubbles">
      <div class="bubble bubble--1 reveal reveal-delay-1"><b>QR</b><span>catálogo al instante</span></div>
      <div class="bubble bubble--2 reveal reveal-delay-2"><b>100%</b><span>panel web, sin app</span></div>
      <div class="bubble bubble--3 reveal reveal-delay-3"><b>24/7</b><span>catálogo abierto para tus clientas</span></div>
    </div>
  </div>
</section>

<div class="barcode barcode--on-light wrap" aria-hidden="true"></div>

{{-- ============================== FEATURE DEEP DIVE ============================== --}}
<section class="feature" id="funciones">
  <div class="wrap">
    <div class="feature-head reveal">
      <span class="eyebrow eyebrow--yellow">Catálogo + WhatsApp</span>
      <h2>Un catálogo que las mamás encuentran solas</h2>
    </div>
    <div class="feature-grid">
      <div class="feature-mock reveal">
        <div class="catalog-mini">
          <div class="catalog-head"><b>Pañalera La Cigüeña</b><span class="catalog-chip">QR activo</span></div>
          <div class="catalog-row"><i class="swatch swatch--sand"></i><div class="info"><b>Babysec Premium G x30</b><small>Marca · Talle · Cantidad</small></div><span class="tag-ok">DISPONIBLE</span></div>
          <div class="catalog-row"><i class="swatch swatch--brand"></i><div class="info"><b>Body algodón 3-6 m</b><small>Talle · Color · Género</small></div></div>
        </div>
        <div class="qr-mini">
          <div class="qr-grid" style="width:56px;">
            <i class="qr-finder" style="grid-column:1/3;grid-row:1/3;"></i><i></i><i class="on"></i><i></i><i class="qr-finder" style="grid-column:5/7;grid-row:1/3;"></i><i></i>
            <i></i><i></i><i class="on"></i><i class="on"></i><i></i><i></i><i class="on"></i>
            <i class="on"></i><i></i><i class="on"></i><i></i><i class="on"></i><i></i><i class="on"></i>
            <i></i><i class="on"></i><i></i><i class="on"></i><i></i><i class="on"></i><i></i>
            <i class="on"></i><i></i><i class="on"></i><i class="on"></i><i></i><i class="on"></i><i></i>
            <i></i><i class="on"></i><i></i><i></i><i class="on"></i><i></i><i class="on"></i>
            <i class="qr-finder" style="grid-column:1/3;grid-row:7/9;"></i><i></i><i class="on"></i><i></i><i class="on"></i><i class="on"></i><i></i>
          </div>
          <div class="qr-text">
            <b>QR para el mostrador</b>
            <small>Pegalo en la caja: ven stock por talle al instante</small>
          </div>
        </div>
        <span class="link-pill">mipanadigital.com/tu-pañalera</span>
      </div>

      <div class="feature-list reveal">
        <details class="feature-item" open>
          <summary>Stock por talle y marca
            <svg class="chev" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </summary>
          <p>Sabé cuántos RN, M o XG te quedan de cada marca. Cada categoría pide los atributos que corresponden: pañales, mamaderas, bodies y más.</p>
        </details>
        <details class="feature-item">
          <summary>QR y link para WhatsApp
            <svg class="chev" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </summary>
          <p>Cuando te preguntan “¿tenés Huggies G?”, mandás el link. La clienta ve precio y stock sin que armes listas a mano.</p>
        </details>
        <details class="feature-item">
          <summary>Catálogo público de tu pañalera
            <svg class="chev" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </summary>
          <p>URL propia para tu negocio. Ideal para Instagram, estados de WhatsApp o un cartel en la vidriera.</p>
        </details>
        <details class="feature-item">
          <summary>Panel web, sin app
            <svg class="chev" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </summary>
          <p>Entrás desde el celular o la PC del local. Actualizás precios y stock en el momento, sin descargar nada.</p>
        </details>
      </div>
    </div>
  </div>
</section>

{{-- ============================== CÓMO FUNCIONA ============================== --}}
<section class="steps" id="como-funciona">
  <div class="wrap">
    <div class="steps-head reveal">
      <span class="eyebrow eyebrow--mint">Tres pasos, nada más</span>
      <h2>Así funciona en tu pañalera</h2>
    </div>
    <div class="steps-grid">
      <div class="step-card step-card--green reveal reveal-delay-1">
        <span class="step-num">01 / CARGÁ</span>
        <h3>Armá tu catálogo de bebé</h3>
        <p>Pañales con marca, talle y cantidad. Mamaderas con capacidad. Bodies con color y género. Cada categoría ya sabe qué pedir.</p>
      </div>
      <div class="step-card step-card--white reveal reveal-delay-2">
        <span class="step-num">02 / COMPARTÍ</span>
        <h3>QR en caja y link por WhatsApp</h3>
        <p>Imprimí el QR para el mostrador y mandá tu link cuando te preguntan precios o talles.</p>
      </div>
      <div class="step-card step-card--blue reveal reveal-delay-3">
        <span class="step-num">03 / CONTROLÁ</span>
        <h3>Stock al día, ventas sin fricción</h3>
        <p>Actualizás packs y talles desde el panel. Tus clientas ven lo que hay; vos sabés qué reponer.</p>
      </div>
    </div>
  </div>
</section>

{{-- ============================== WHY / BENEFIT CARDS ============================== --}}
<section class="why">
  <div class="wrap">
    <div class="why-head reveal">
      <span class="eyebrow eyebrow--mint">Cómo te ayuda en el día a día</span>
      <h2>Menos ida y vuelta por WhatsApp, más ventas cerradas</h2>
    </div>
    <div class="why-grid">
      <div class="why-card why-card--green reveal reveal-delay-1">
        <span class="why-icon why-icon--stock" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none"><path d="M4 6h3v12H4V6Zm5 0h1v12H9V6Zm3 0h2v12h-2V6Zm4 0h1v12h-1V6Zm3 0h2v12h-2V6Z" stroke="currentColor" stroke-width="1.3"/></svg>
        </span>
        <h3>Talles siempre claros</h3>
        <p>Dejá de adivinar si queda Pampers M o Huggies XG. El stock por talle y marca está a la vista para vos y para la clienta.</p>
      </div>
      <div class="why-card why-card--white reveal reveal-delay-2">
        <span class="why-icon why-icon--reports" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none"><path d="M4 20V4M4 20h16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
        </span>
        <h3>Qué se pide más</h3>
        <p>Mirá qué packs y talles se consultan más para reponer primero lo que mueve tu pañalera.</p>
        <div class="why-mini-chart" aria-hidden="true">
          <i style="height:38%"></i><i style="height:62%"></i><i style="height:44%"></i><i style="height:80%"></i><i style="height:56%"></i><i style="height:70%"></i>
        </div>
      </div>
      <div class="why-card why-card--yellow reveal reveal-delay-3">
        <span class="why-icon why-icon--alert" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none"><path d="M12 3v10m0 0-3.5-3.5M12 13l3.5-3.5M5 17h14v4H5v-4Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <h3>Alertas antes de que falte</h3>
        <p>Te avisamos cuando un talle o pack está por acabarse, para no perder el fin de semana sin el producto estrella.</p>
        <div class="why-mini-list">
          <div>Pampers M x36 <b>3 packs</b></div>
          <div>Huggies XG x30 <b>2 packs</b></div>
          <div>Toallitas Huggies <b>5 uds.</b></div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ============================== MARQUEE ============================== --}}
<section class="marquee-section" id="casos">
  <div class="wrap">
    <div class="marquee-head reveal">
      <span class="eyebrow eyebrow--mint">Pedidos reales de pañaleras</span>
      <h2>Hecho a medida de tu local de bebé</h2>
      <p>Cada función nació de lo que pedís todos los días detrás del mostrador.</p>
      @auth
      <a href="{{ route('dashboard') }}" class="btn btn-primary">Ir al panel</a>
      @else
      <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
      @endauth
    </div>
  </div>
  <div class="marquee-mask">
    <div class="marquee-track">
      @php
        $pedidos = [
          'necesito saber cuántos Pampers M me quedan',
          'quiero mandar el catálogo por WhatsApp cuando preguntan talles',
          'necesito un QR en la caja para que vean stock solas',
          'quiero cargar bodies con talle, color y género',
          'necesito alertas cuando se acaba un pack',
          'quiero un link propio para Instagram de la pañalera',
        ];
      @endphp
      @for ($i = 0; $i < 2; $i++)
        @foreach ($pedidos as $pedido)
          <span class="marquee-pill">“<b>{{ $pedido }}</b>”</span>
        @endforeach
      @endfor
    </div>
  </div>
</section>

<div class="barcode barcode--on-light wrap" aria-hidden="true"></div>

{{-- ============================== TESTIMONIALS ============================== --}}
<section class="testimonials">
  <div class="wrap">
    <div class="testi-head reveal">
      <span class="eyebrow eyebrow--mint">Casos reales</span>
      <h2>Pañaleras que ya atienden distinto</h2>
    </div>
    @php
      $testimonios = [
        [
          'quote' => 'Antes contestaba “dejame fijarme” por cada talle. Ahora mando el link y la clienta ve si hay Pampers G o Huggies XG.',
          'name' => 'Marisa G.',
          'role' => 'Pañalera · Córdoba',
          'color' => 'var(--indigo)',
        ],
        [
          'quote' => 'El QR en la caja cambió el mostrador. Mientras esperan, ya saben qué packs hay y a qué precio.',
          'name' => 'Valentina P.',
          'role' => 'Pañalera · Rosario',
          'color' => 'var(--apricot)',
        ],
        [
          'quote' => 'La alerta de stock bajo me salvó el finde: se me acababa el talle M, el que más piden.',
          'name' => 'Noelia R.',
          'role' => 'Pañalera · CABA',
          'color' => 'var(--text)',
        ],
      ];
    @endphp
    <div class="testi-grid">
      @foreach ($testimonios as $i => $t)
        <div class="testi-card reveal reveal-delay-{{ $i + 1 }}">
          <p class="testi-quote">"{{ $t['quote'] }}"</p>
          <div class="testi-person">
            <span class="testi-avatar" style="background: {{ $t['color'] }};">{{ strtoupper(substr($t['name'],0,1)) }}</span>
            <div>
              <b>{{ $t['name'] }}</b>
              <small>{{ $t['role'] }}</small>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ============================== FINAL CTA ============================== --}}
<section id="precios" class="cta-section">
  <div class="wrap">
    <div class="final-cta reveal">
      <div class="final-cta-inner">
        <h2>Tu pañalera, ordenada desde hoy</h2>
        <p>Iniciá sesión, cargá tus packs y compartí el catálogo. Stock por talle, link y QR listos para el mostrador.</p>
        <div class="ctas">
          @auth
          <a href="{{ route('dashboard') }}" class="btn btn-on-gradient">Ir al panel</a>
          @else
          <a href="{{ route('login') }}" class="btn btn-on-gradient">Iniciar sesión</a>
          @endauth
          <a href="https://wa.me/5490000000000" class="btn btn-ghost-dark" target="_blank" rel="noopener">Hablar con un asesor</a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ============================== FOOTER ============================== --}}
<footer>
  <div class="wrap footer-grid">
    <div class="footer-brand">
      <a href="/" class="brand" style="color:#fff;">
        <span class="brand-mark" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none"><path d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Z" stroke="#fff" stroke-width="1.4" stroke-linejoin="round"/></svg>
        </span>
        <span class="brand-name">Paña<b style="color:var(--apricot)">Digital</b></span>
      </a>
      <p>SaaS de stock, catálogo y WhatsApp pensado solo para pañaleras.</p>
      <div class="footer-social">
        <a href="#" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3.6" stroke="currentColor" stroke-width="1.5"/><circle cx="17" cy="7" r="1" fill="currentColor"/></svg></a>
        <a href="#" aria-label="WhatsApp"><svg viewBox="0 0 24 24" fill="none"><path d="M12 3a9 9 0 0 0-7.6 13.8L3 21l4.4-1.3A9 9 0 1 0 12 3Z" stroke="currentColor" stroke-width="1.5"/><path d="M8.5 9.5c.5 3 2.5 5 5.5 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></a>
        <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="none"><path d="M14 21v-7h2.4l.4-3H14V9c0-.9.3-1.5 1.7-1.5H17V5c-.3 0-1.2-.1-2.3-.1-2.3 0-3.7 1.4-3.7 3.9V11H8.5v3H11v7h3Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg></a>
      </div>
    </div>

    <div class="footer-col">
      <h4>Plataforma</h4>
      <ul>
        <li><a href="#funciones">Catálogo de pañalera</a></li>
        <li><a href="#funciones">Stock por talle</a></li>
        <li><a href="#funciones">Código QR</a></li>
        <li><a href="#funciones">WhatsApp Business</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Funciones</h4>
      <ul>
        <li><a href="#funciones">Atributos por categoría</a></li>
        <li><a href="#funciones">Alertas de stock bajo</a></li>
        <li><a href="#casos">Casos de pañaleras</a></li>
        <li><a href="{{ route('login') }}">Iniciar sesión</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Ayuda</h4>
      <ul>
        <li><a href="https://wa.me/5490000000000" target="_blank" rel="noopener">Hablar por WhatsApp</a></li>
        <li><a href="#precios">Empezar</a></li>
        <li><a href="#como-funciona">Cómo funciona</a></li>
      </ul>
    </div>
  </div>

  <div class="wrap footer-wordmark" aria-hidden="true">
    <span>PAÑADIGITAL</span>
  </div>

  <div class="wrap footer-bottom">
    <span>&copy; {{ date('Y') }} Paña Digital. Todos los derechos reservados.</span>
    <span>Hecho para pañaleras · 100% panel web</span>
  </div>
</footer>

<script>
  // Menú móvil
  const burger = document.getElementById('navBurger');
  const mobileNav = document.getElementById('navMobile');
  if (burger && mobileNav) {
    burger.addEventListener('click', () => {
      const isOpen = mobileNav.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', String(isOpen));
    });
    mobileNav.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
      mobileNav.classList.remove('is-open');
      burger.setAttribute('aria-expanded', 'false');
    }));
  }

  // Reveal on scroll
  const revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && revealEls.length) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    revealEls.forEach(el => io.observe(el));
  } else {
    revealEls.forEach(el => el.classList.add('is-visible'));
  }
</script>
</body>
</html>