<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Judging Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Josefin+Sans:wght@200;300;400;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --black: #0A0A0A;
            --near-black: #111111;
            --deep: #1A1208;
            --gold: #C9A84C;
            --gold-light: #E8C97A;
            --gold-pale: #F5E6C0;
            --rose: #C9748A;
            --rose-light: #E8A0B0;
            --cream: #FAF7F2;
            --warm-white: #FDF9F4;
            --text-muted: rgba(250,247,242,0.5);
            --text-dim: rgba(250,247,242,0.3);
            --border: rgba(201,168,76,0.2);
            --border-light: rgba(201,168,76,0.1);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Josefin Sans', sans-serif;
            background: var(--black);
            color: var(--cream);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── NOISE TEXTURE ─────────────────────── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 9999;
            opacity: 0.6;
        }

        /* ── HEADER ────────────────────────────── */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            padding: 0 3rem;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(180deg, rgba(10,10,10,0.95) 0%, transparent 100%);
            backdrop-filter: blur(4px);
            transition: background 0.4s ease;
        }

        header.scrolled {
            background: rgba(10,10,10,0.97);
            border-bottom: 1px solid var(--border);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo-crown {
            font-size: 1.4rem;
            line-height: 1;
        }

        .logo-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 500;
            color: var(--gold-light);
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .main-nav {
            display: flex;
            gap: 3rem;
            align-items: center;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 400;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            transition: color 0.3s ease;
        }

        .nav-link:hover { color: var(--gold-light); }

        .nav-divider {
            width: 1px;
            height: 20px;
            background: var(--border);
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-family: 'Josefin Sans', sans-serif;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-ghost {
            color: var(--text-muted);
            background: transparent;
            padding: 0.6rem 1.2rem;
        }

        .btn-ghost:hover { color: var(--cream); }

        .btn-gold {
            background: var(--gold);
            color: var(--black);
            padding: 0.75rem 1.75rem;
            clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%);
        }

        .btn-gold:hover {
            background: var(--gold-light);
            transform: translateY(-1px);
        }

        .btn-outline-gold {
            background: transparent;
            color: var(--gold);
            padding: 0.75rem 1.75rem;
            border: 1px solid var(--gold);
        }

        .btn-outline-gold:hover {
            background: rgba(201,168,76,0.1);
        }

        .btn-large {
            font-size: 0.7rem;
            padding: 1rem 2.5rem;
        }

        /* ── MOBILE ────────────────────────────── */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--cream);
        }

        .mobile-menu {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10,10,10,0.98);
            z-index: 999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2.5rem;
        }

        .mobile-menu.active { display: flex; }

        .mobile-menu .nav-link {
            font-size: 0.9rem;
            color: var(--cream);
        }

        .mobile-close {
            position: absolute;
            top: 2rem;
            right: 2rem;
            background: none;
            border: none;
            color: var(--cream);
            cursor: pointer;
            font-size: 1.5rem;
        }

        /* ── HERO ──────────────────────────────── */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 8rem 3rem 6rem;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 80% at 50% 120%, rgba(201,168,76,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 40% 50% at 20% 50%, rgba(201,116,138,0.06) 0%, transparent 60%),
                radial-gradient(ellipse 40% 50% at 80% 50%, rgba(201,168,76,0.06) 0%, transparent 60%);
        }

        /* Decorative arcs */
        .hero-arc {
            position: absolute;
            border-radius: 50%;
            border: 1px solid;
            pointer-events: none;
        }

        .hero-arc-1 {
            width: 600px; height: 600px;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            border-color: rgba(201,168,76,0.08);
        }

        .hero-arc-2 {
            width: 900px; height: 900px;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            border-color: rgba(201,168,76,0.05);
        }

        .hero-arc-3 {
            width: 1200px; height: 1200px;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            border-color: rgba(201,168,76,0.03);
        }

        /* Vertical gold lines */
        .hero-line {
            position: absolute;
            width: 1px;
            height: 100%;
            top: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(201,168,76,0.15) 40%, rgba(201,168,76,0.15) 60%, transparent 100%);
        }

        .hero-line-left { left: 20%; }
        .hero-line-right { right: 20%; }

        .hero-content {
            max-width: 900px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-eyebrow {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .eyebrow-line {
            width: 60px;
            height: 1px;
            background: var(--gold);
            opacity: 0.5;
        }

        .eyebrow-text {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--gold);
        }

        .hero h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(3.5rem, 8vw, 7rem);
            font-weight: 300;
            line-height: 1.05;
            color: var(--cream);
            margin-bottom: 1rem;
            letter-spacing: -0.01em;
        }

        .hero h1 em {
            font-style: italic;
            color: var(--gold-light);
        }

        .hero-subtitle {
            font-size: 0.8rem;
            font-weight: 300;
            letter-spacing: 0.15em;
            color: var(--text-muted);
            line-height: 2;
            max-width: 560px;
            margin: 2rem auto 3.5rem;
            text-transform: uppercase;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 5rem;
        }

        .hero-ornament {
            position: absolute;
            bottom: -2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--text-dim);
            font-size: 0.6rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }

        .hero-ornament-line {
            width: 40px;
            height: 1px;
            background: currentColor;
        }

        /* Stats strip */
        .stats-strip {
            position: relative;
            display: flex;
            justify-content: center;
            gap: 0;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem 3.5rem;
            border-left: 1px solid var(--border);
        }

        .stat-item:last-child { border-right: 1px solid var(--border); }

        .stat-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            font-weight: 400;
            color: var(--gold-light);
            display: block;
        }

        .stat-label {
            font-size: 0.6rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        /* ── DIVIDER ───────────────────────────── */
        .section-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            padding: 2rem 0;
        }

        .divider-line {
            width: 80px;
            height: 1px;
            background: var(--border);
        }

        .divider-diamond {
            width: 6px;
            height: 6px;
            background: var(--gold);
            transform: rotate(45deg);
            opacity: 0.6;
        }

        /* ── FEATURES ──────────────────────────── */
        .features-section {
            padding: 8rem 3rem;
            background: var(--near-black);
            position: relative;
        }

        .features-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }

        .section-header {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 5rem;
        }

        .section-label {
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1.25rem;
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 300;
            color: var(--cream);
            line-height: 1.15;
            margin-bottom: 1.25rem;
        }

        .section-title em {
            font-style: italic;
            color: var(--gold-light);
        }

        .section-description {
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            line-height: 2;
            text-transform: uppercase;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0;
            max-width: 1200px;
            margin: 0 auto;
            border: 1px solid var(--border);
        }

        .feature-card {
            padding: 3rem;
            border-right: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            position: relative;
            overflow: hidden;
            transition: background 0.4s ease;
        }

        .feature-card:nth-child(3n) { border-right: none; }
        .feature-card:nth-child(n+4) { border-bottom: none; }

        .feature-card::before {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 100%; height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .feature-card:hover { background: rgba(201,168,76,0.03); }
        .feature-card:hover::before { transform: scaleX(1); }

        .feature-number {
            font-family: 'Cormorant Garamond', serif;
            font-size: 4rem;
            font-weight: 300;
            color: var(--border);
            line-height: 1;
            margin-bottom: 1.5rem;
            transition: color 0.3s ease;
        }

        .feature-card:hover .feature-number { color: rgba(201,168,76,0.2); }

        .feature-icon-wrap {
            width: 44px;
            height: 44px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: border-color 0.3s ease;
        }

        .feature-card:hover .feature-icon-wrap { border-color: var(--gold); }

        .feature-icon-wrap svg {
            width: 20px;
            height: 20px;
            color: var(--gold);
        }

        .feature-card h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.4rem;
            font-weight: 500;
            color: var(--cream);
            margin-bottom: 1rem;
            letter-spacing: 0.02em;
        }

        .feature-card p {
            font-size: 0.72rem;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            line-height: 2;
        }

        /* ── CATEGORIES / VALUE PROPS ──────────── */
        .value-section {
            padding: 8rem 3rem;
            background: var(--black);
            position: relative;
        }

        .value-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6rem;
            align-items: center;
        }

        .value-text h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 300;
            line-height: 1.2;
            color: var(--cream);
            margin-bottom: 2rem;
        }

        .value-text h2 em {
            font-style: italic;
            color: var(--gold-light);
        }

        .value-text p {
            font-size: 0.72rem;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            line-height: 2.2;
            text-transform: uppercase;
            margin-bottom: 2.5rem;
        }

        .value-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .value-list li {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            font-size: 0.72rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--cream);
        }

        .value-list-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            margin-top: 1px;
            color: var(--gold);
        }

        .value-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1px;
            background: var(--border);
        }

        .value-card {
            background: var(--near-black);
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .value-card-num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3rem;
            font-weight: 300;
            color: var(--gold-light);
            display: block;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .value-card-label {
            font-size: 0.6rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        /* ── CATEGORIES ──────────────────────────── */
        .categories-section {
            padding: 8rem 3rem;
            background: var(--near-black);
            position: relative;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            max-width: 1200px;
            margin: 4rem auto 0;
        }

        .category-card {
            padding: 2.5rem 1.75rem;
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.35s ease;
        }

        .category-card:hover {
            border-color: var(--gold);
            background: rgba(201,168,76,0.04);
            transform: translateY(-4px);
        }

        .category-icon {
            font-size: 2rem;
            margin-bottom: 1.25rem;
            display: block;
        }

        .category-card h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem;
            font-weight: 500;
            color: var(--cream);
            margin-bottom: 0.75rem;
        }

        .category-card p {
            font-size: 0.65rem;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            line-height: 1.9;
            text-transform: uppercase;
        }

        .category-arrow {
            position: absolute;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 32px;
            height: 32px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .category-card:hover .category-arrow {
            background: var(--gold);
            color: var(--black);
            border-color: var(--gold);
        }

        /* ── CTA ───────────────────────────────── */
        .cta-section {
            padding: 10rem 3rem;
            background: var(--black);
            position: relative;
            overflow: hidden;
        }

        .cta-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 80% 100% at 50% 100%, rgba(201,168,76,0.1) 0%, transparent 60%);
        }

        .cta-inner {
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .cta-crown {
            font-size: 3rem;
            display: block;
            margin-bottom: 2rem;
        }

        .cta-section h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.5rem, 6vw, 5rem);
            font-weight: 300;
            color: var(--cream);
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }

        .cta-section h2 em {
            font-style: italic;
            color: var(--gold-light);
        }

        .cta-section p {
            font-size: 0.75rem;
            letter-spacing: 0.15em;
            color: var(--text-muted);
            text-transform: uppercase;
            line-height: 2;
            max-width: 560px;
            margin: 0 auto 3.5rem;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        /* ── FOOTER ────────────────────────────── */
        footer {
            background: var(--near-black);
            padding: 5rem 3rem 2.5rem;
            border-top: 1px solid var(--border);
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-top {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 4rem;
            margin-bottom: 4rem;
            padding-bottom: 4rem;
            border-bottom: 1px solid var(--border);
        }

        .footer-brand p {
            font-size: 0.68rem;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            line-height: 2;
            text-transform: uppercase;
            margin-top: 1.25rem;
            max-width: 300px;
        }

        .social-links {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .social-link {
            width: 36px;
            height: 36px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.8rem;
        }

        .social-link:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .footer-col h4 {
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1.5rem;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.68rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            transition: color 0.2s ease;
        }

        .footer-links a:hover { color: var(--cream); }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-dim);
            font-size: 0.6rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .footer-bottom a {
            color: var(--text-dim);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-bottom a:hover { color: var(--text-muted); }

        .footer-bottom-right {
            display: flex;
            gap: 2rem;
        }

        /* ── RESPONSIVE ────────────────────────── */
        @media (max-width: 1024px) {
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .categories-grid { grid-template-columns: repeat(2, 1fr); }
            .value-inner { grid-template-columns: 1fr; gap: 4rem; }
            .footer-top { grid-template-columns: 1fr 1fr; gap: 3rem; }
        }

        @media (max-width: 768px) {
            header { padding: 0 1.5rem; }
            .main-nav, .auth-buttons { display: none; }
            .mobile-menu-btn { display: block; }
            .hero { padding: 7rem 1.5rem 5rem; }
            .stats-strip { flex-direction: column; gap: 0; }
            .stat-item { border: none; border-top: 1px solid var(--border); }
            .features-grid { grid-template-columns: 1fr; }
            .feature-card { border-right: none; }
            .feature-card:nth-child(n+4) { border-bottom: 1px solid var(--border); }
            .feature-card:last-child { border-bottom: none; }
            .categories-grid { grid-template-columns: 1fr 1fr; }
            .footer-top { grid-template-columns: 1fr; gap: 2.5rem; }
            .footer-bottom { flex-direction: column; gap: 1rem; text-align: center; }
            .footer-bottom-right { flex-direction: column; gap: 0.75rem; align-items: center; }
            .value-cards { grid-template-columns: 1fr 1fr; }
            .cta-buttons { flex-direction: column; align-items: center; }
        }

        /* ── ANIMATIONS ────────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }

        .fade-up {
            opacity: 0;
            animation: fadeUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.25s; }
        .delay-3 { animation-delay: 0.4s; }
        .delay-4 { animation-delay: 0.55s; }
        .delay-5 { animation-delay: 0.7s; }

        .gold-shimmer {
            background: linear-gradient(90deg, var(--gold-light) 0%, var(--gold-pale) 40%, var(--gold-light) 60%, var(--gold) 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 4s linear infinite;
        }
    </style>
</head>
<body>

    <!-- Mobile menu -->
    <div class="mobile-menu" id="mobileMenu">
        <button class="mobile-close" onclick="closeMobile()">✕</button>
        <a href="#home" class="nav-link" onclick="closeMobile()">Home</a>
        <a href="#features" class="nav-link" onclick="closeMobile()">Features</a>
        <a href="#categories" class="nav-link" onclick="closeMobile()">Categories</a>
        <a href="#about" class="nav-link" onclick="closeMobile()">About</a>
        <a href="#contact" class="nav-link" onclick="closeMobile()">Contact</a>
        <a href="/register" class="btn btn-gold btn-large" onclick="closeMobile()">Get Started</a>
    </div>

    <header id="header">
        <a href="#home" class="logo">
            <span class="logo-crown">♛</span>
            <span class="logo-text">Crown</span>
        </a>
        <nav class="main-nav">
            <a href="#home" class="nav-link">Home</a>
            <a href="#features" class="nav-link">Features</a>
            <a href="#categories" class="nav-link">Categories</a>
            <div class="nav-divider"></div>
            <a href="#contact" class="nav-link">Contact</a>
        </nav>
        <div class="auth-buttons">
            <a href="/login" class="btn btn-ghost">Sign In</a>
            <a href="/register" class="btn btn-gold">Get Started</a>
        </div>
        <button class="mobile-menu-btn" onclick="openMobile()">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8h16M4 16h16"/>
            </svg>
        </button>
    </header>

    <!-- ── HERO ── -->
    <section class="hero" id="home">
        <div class="hero-bg"></div>
        <div class="hero-arc hero-arc-1"></div>
        <div class="hero-arc hero-arc-2"></div>
        <div class="hero-arc hero-arc-3"></div>
        <div class="hero-line hero-line-left"></div>
        <div class="hero-line hero-line-right"></div>

        <div class="hero-content">
            <div class="hero-eyebrow fade-up delay-1">
                <div class="eyebrow-line"></div>
                <span class="eyebrow-text"> Judging System</span>
                <div class="eyebrow-line"></div>
            </div>

            <h1 class="fade-up delay-2">
                Where <em>Elegance</em><br>Meets Precision
            </h1>

            <p class="hero-subtitle fade-up delay-3">
                The definitive judging platform for beauty pageants, talent competitions,<br>
                and cultural events. Professional. Transparent. Dignified.
            </p>

            <div class="hero-cta fade-up delay-4">
                <a href="/register" class="btn btn-gold btn-large">Begin Your Event</a>
                <a href="#features" class="btn btn-outline-gold btn-large">Explore Platform</a>
            </div>

            <!-- Stats -->
            <div class="stats-strip fade-up delay-5">
                <div class="stat-item">
                    <span class="stat-value">2,400+</span>
                    <span class="stat-label">Pageants Held</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">18K+</span>
                    <span class="stat-label">Contestants Scored</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">140+</span>
                    <span class="stat-label">Countries</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">99.9%</span>
                    <span class="stat-label">Uptime</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ── FEATURES ── -->
    <section class="features-section" id="features">
        

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-number">01</div>
                <div class="feature-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                    </svg>
                </div>
                <h3>Scoring Criteria Builder</h3>
                <p>Design custom scoring rubrics for evening gown, swimwear, talent, interview, and Q&A segments. Weighted percentages calculated automatically.</p>
            </div>

            <div class="feature-card">
                <div class="feature-number">02</div>
                <div class="feature-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </div>
                <h3>Judge Panel Management</h3>
                <p>Onboard celebrity judges, industry professionals, and community leaders with role-based access and individual scoring dashboards.</p>
            </div>

            <div class="feature-card">
                <div class="feature-number">03</div>
                <div class="feature-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                    </svg>
                </div>
                <h3>Live Leaderboard</h3>
                <p>Real-time rankings updated per segment. Display results on stage screens with elegant, branded overlays and transition animations.</p>
            </div>

            <div class="feature-card">
                <div class="feature-number">04</div>
                <div class="feature-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                </div>
                <h3>Conflict of Interest Guard</h3>
                <p>Automatic detection when judges are related to contestants. Score masking and blind judging modes ensure integrity at every stage.</p>
            </div>

            <div class="feature-card">
                <div class="feature-number">05</div>
                <div class="feature-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <h3>Official Score Sheets</h3>
                <p>Generate notarized, watermarked PDF scorecards for every contestant. Accepted by national and international pageant federations.</p>
            </div>

            <div class="feature-card">
                <div class="feature-number">06</div>
                <div class="feature-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3h3m-6 3h.008v.008H6.75V15zm0-3h.008v.008H6.75V12zm0-3h.008v.008H6.75V9z"/>
                    </svg>
                </div>
                <h3>Contestant Profiles</h3>
                <p>Rich media profiles with photo galleries, platform statements, biographical data, and social media links — accessible to judges before scoring.</p>
            </div>
        </div>
    </section>

    <!-- ── VALUE / ABOUT ── -->
    <section class="value-section" id="about">
        <div class="value-inner">
          
          
        </div>
    </section>

    <!-- ── CATEGORIES ── -->
    <section class="categories-section" id="categories">
        <div class="section-header">
            <div class="section-label">Event Types</div>
            <h2 class="section-title">Every stage. <em>Every crown.</em></h2>
            <p class="section-description">Designed for all pageant formats — from local beauty titles to prestigious national competitions.</p>
        </div>

        <div class="categories-grid">
            <div class="category-card">
                <span class="category-icon">👑</span>
                <h3>Beauty Pageants</h3>
                <p>Miss, Mrs., Mister categories with full segment support including preliminary rounds.</p>
                <div class="category-arrow">→</div>
            </div>
            <div class="category-card">
                <span class="category-icon">🎭</span>
                <h3>Talent Shows</h3>
                <p>Scoring rubrics for singing, dancing, acting, and multi-discipline performances.</p>
                <div class="category-arrow">→</div>
            </div>
            <div class="category-card">
                <span class="category-icon">🌺</span>
                <h3>Cultural Festivals</h3>
                <p>Ethnic, folk, and heritage competitions with culturally sensitive criteria frameworks.</p>
                <div class="category-arrow">→</div>
            </div>
            <div class="category-card">
                <span class="category-icon">🎓</span>
                <h3>Academic Events</h3>
                <p>School-level pageants, recognition days, and youth leadership competitions.</p>
                <div class="category-arrow">→</div>
            </div>
        </div>
    </section>

    <!-- ── CTA ── -->
    <section class="cta-section" id="contact">
        <div class="cta-bg"></div>
        <div class="cta-inner">
            <span class="cta-crown">♛</span>
            <h2>The stage is set.<br>Let the <em>judging begin.</em></h2>
            <p>Join hundreds of pageant organizers who run their events with confidence, clarity, and elegance.</p>
            <div class="cta-buttons">
                <a href="/register" class="btn btn-gold btn-large">Start Free Trial</a>
                <a href="mailto:hello@crownplatform.com" class="btn btn-outline-gold btn-large">Contact Our Team</a>
            </div>
        </div>
    </section>

    <!-- ── FOOTER ── -->
    <footer>
        <div class="footer-inner">
            <div class="footer-top">
                <div class="footer-brand">
                    <div class="logo">
                        <span class="logo-crown" style="color:var(--gold)">♛</span>
                        <span class="logo-text">Crown</span>
                    </div>
                    <p>The professional judging platform built exclusively for beauty pageants, talent competitions, and cultural events worldwide.</p>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">f</a>
                        <a href="#" class="social-link" aria-label="Instagram">ig</a>
                        <a href="#" class="social-link" aria-label="Twitter">tw</a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Platform</h4>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#categories">Event Types</a></li>
                        <li><a href="#">Integrations</a></li>
                        <li><a href="#">Changelog</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Company</h4>
                    <ul class="footer-links">
                        <li><a href="#about">About</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Press Kit</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Support</h4>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#contact">Contact Us</a></li>
                        <li><a href="#">System Status</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div>© 2026 Crown Pageant Platform. All rights reserved.</div>
                <div class="footer-bottom-right">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const header = document.getElementById('header');
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 30);
        });

        function openMobile() {
            document.getElementById('mobileMenu').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobile() {
            document.getElementById('mobileMenu').classList.remove('active');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const href = a.getAttribute('href');
                if (href === '#') return;
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Intersection observer for feature cards
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.feature-card, .category-card').forEach((el, i) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = `opacity 0.7s ease ${i * 0.08}s, transform 0.7s ease ${i * 0.08}s`;
            observer.observe(el);
        });
    </script>
</body>
</html>