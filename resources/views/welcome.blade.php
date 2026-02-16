<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JudgingSystem - Enterprise Competition Management Platform</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|space-grotesk:500,600,700" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --navy-900: #0A1628;
            --navy-800: #142236;
            --navy-700: #1E2D44;
            --navy-600: #2A3F5F;
            --slate-500: #64748B;
            --slate-400: #94A3B8;
            --slate-300: #CBD5E1;
            --slate-200: #E2E8F0;
            --slate-100: #F1F5F9;
            --slate-50: #F8FAFC;
            --white: #FFFFFF;
            
            --blue-600: #2563EB;
            --blue-500: #3B82F6;
            --blue-400: #60A5FA;
            
            --gold-600: #D97706;
            --gold-500: #F59E0B;
            
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;
            --spacing-3xl: 4rem;
            
            --radius-sm: 0.25rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--white);
            color: var(--navy-900);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        /* Header */
        header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px) saturate(180%);
            -webkit-backdrop-filter: blur(12px) saturate(180%);
            border-bottom: 1px solid var(--slate-200);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        header.scrolled {
            box-shadow: var(--shadow-md);
        }

        .header-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 var(--spacing-xl);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 72px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            text-decoration: none;
            color: var(--navy-900);
            font-weight: 700;
            font-size: 1.25rem;
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: -0.01em;
        }

        .logo-mark {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--blue-600) 0%, var(--blue-400) 100%);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            box-shadow: var(--shadow-md);
        }

        .main-nav {
            display: flex;
            gap: var(--spacing-2xl);
            align-items: center;
        }

        .nav-link {
            color: var(--slate-500);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9375rem;
            padding: var(--spacing-sm) 0;
            position: relative;
            transition: color 0.2s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--navy-900);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--blue-600);
            border-radius: 2px;
        }

        .auth-buttons {
            display: flex;
            gap: var(--spacing-md);
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9375rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-ghost {
            color: var(--navy-900);
            background: transparent;
        }

        .btn-ghost:hover {
            background: var(--slate-100);
        }

        .btn-primary {
            background: var(--navy-900);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            background: var(--navy-800);
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        .btn-blue {
            background: var(--blue-600);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-blue:hover {
            background: var(--blue-500);
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        .btn-large {
            padding: 0.875rem 1.75rem;
            font-size: 1rem;
        }

        .btn-outline {
            border: 1px solid var(--slate-300);
            color: var(--navy-900);
            background: white;
        }

        .btn-outline:hover {
            border-color: var(--navy-900);
            background: var(--slate-50);
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: var(--spacing-sm);
            color: var(--navy-900);
        }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            right: -100%;
            width: 320px;
            height: 100vh;
            background: white;
            box-shadow: -4px 0 24px rgba(0, 0, 0, 0.15);
            padding: var(--spacing-2xl);
            transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 2000;
            flex-direction: column;
            gap: var(--spacing-lg);
        }

        .mobile-menu.active {
            right: 0;
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(10, 22, 40, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .mobile-overlay.active {
            opacity: 1;
        }

        .mobile-nav-link {
            color: var(--navy-900);
            text-decoration: none;
            font-weight: 500;
            font-size: 1.0625rem;
            padding: var(--spacing-md) 0;
            border-bottom: 1px solid var(--slate-200);
        }

        .mobile-auth {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
            margin-top: auto;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(180deg, var(--slate-50) 0%, var(--white) 100%);
            padding: 6rem var(--spacing-xl) 8rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 1440px;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 20%, rgba(37, 99, 235, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(37, 99, 235, 0.06) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-container {
            max-width: 1280px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero-content {
            max-width: 720px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) var(--spacing-lg);
            background: white;
            border: 1px solid var(--slate-200);
            border-radius: 100px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--navy-900);
            margin-bottom: var(--spacing-xl);
            box-shadow: var(--shadow-sm);
        }

        .hero-badge-dot {
            width: 8px;
            height: 8px;
            background: var(--blue-600);
            border-radius: 50%;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .hero h1 {
            font-size: 3.75rem;
            font-weight: 700;
            margin-bottom: var(--spacing-xl);
            color: var(--navy-900);
            letter-spacing: -0.03em;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--slate-500);
            max-width: 640px;
            margin: 0 auto var(--spacing-2xl);
            line-height: 1.7;
        }

        .hero-cta {
            display: flex;
            gap: var(--spacing-md);
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: var(--spacing-3xl);
        }

        .hero-stats {
            display: flex;
            gap: var(--spacing-2xl);
            justify-content: center;
            padding-top: var(--spacing-2xl);
            border-top: 1px solid var(--slate-200);
        }

        .hero-stat {
            text-align: center;
        }

        .hero-stat-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--navy-900);
            font-family: 'Space Grotesk', sans-serif;
        }

        .hero-stat-label {
            font-size: 0.875rem;
            color: var(--slate-500);
            margin-top: var(--spacing-xs);
        }

        /* Trust Section */
        .trust-section {
            background: white;
            padding: var(--spacing-3xl) var(--spacing-xl);
            border-top: 1px solid var(--slate-200);
            border-bottom: 1px solid var(--slate-200);
        }

        .trust-container {
            max-width: 1280px;
            margin: 0 auto;
            text-align: center;
        }

        .trust-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: var(--spacing-xl);
        }

        .trust-logos {
            display: flex;
            gap: var(--spacing-3xl);
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .trust-logo {
            height: 32px;
            opacity: 0.4;
            filter: grayscale(1);
            transition: all 0.3s ease;
        }

        .trust-logo:hover {
            opacity: 0.7;
            filter: grayscale(0);
        }

        /* Features Section */
        .features-section {
            padding: 6rem var(--spacing-xl);
            background: var(--white);
        }

        .features-container {
            max-width: 1280px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            max-width: 720px;
            margin: 0 auto var(--spacing-3xl);
        }

        .section-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--blue-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: var(--spacing-md);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--navy-900);
            margin-bottom: var(--spacing-md);
            letter-spacing: -0.02em;
        }

        .section-description {
            font-size: 1.125rem;
            color: var(--slate-500);
            line-height: 1.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-2xl);
        }

        .feature-card {
            background: white;
            padding: var(--spacing-2xl);
            border-radius: var(--radius-xl);
            border: 1px solid var(--slate-200);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            border-color: var(--blue-600);
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--blue-600) 0%, var(--blue-400) 100%);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: var(--spacing-lg);
        }

        .feature-icon svg {
            width: 24px;
            height: 24px;
            color: white;
        }

        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--navy-900);
            margin-bottom: var(--spacing-md);
        }

        .feature-card p {
            color: var(--slate-500);
            line-height: 1.7;
        }

        /* Value Props Section */
        .value-props {
            background: var(--slate-50);
            padding: 6rem var(--spacing-xl);
        }

        .value-props-container {
            max-width: 1280px;
            margin: 0 auto;
        }

        .value-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--spacing-2xl);
            margin-top: var(--spacing-3xl);
        }

        .value-card {
            background: white;
            padding: var(--spacing-2xl);
            border-radius: var(--radius-xl);
            border: 1px solid var(--slate-200);
        }

        .value-card-header {
            display: flex;
            align-items: center;
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
        }

        .value-number {
            width: 40px;
            height: 40px;
            background: var(--navy-900);
            color: white;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
        }

        .value-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--navy-900);
        }

        .value-card p {
            color: var(--slate-500);
            line-height: 1.7;
        }

        /* Pricing Section */
        .pricing-section {
            padding: 6rem var(--spacing-xl);
            background: white;
        }

        .pricing-container {
            max-width: 1280px;
            margin: 0 auto;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-xl);
            margin-top: var(--spacing-3xl);
        }

        .pricing-card {
            background: white;
            border: 2px solid var(--slate-200);
            border-radius: var(--radius-xl);
            padding: var(--spacing-2xl);
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .pricing-card:hover {
            border-color: var(--blue-600);
            box-shadow: var(--shadow-xl);
            transform: translateY(-8px);
        }

        .pricing-card.featured {
            border-color: var(--blue-600);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(180deg, var(--white) 0%, var(--slate-50) 100%);
        }

        .pricing-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--blue-600);
            color: white;
            padding: var(--spacing-xs) var(--spacing-lg);
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .pricing-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--navy-900);
            margin-bottom: var(--spacing-sm);
        }

        .pricing-card .plan-description {
            font-size: 0.875rem;
            color: var(--slate-500);
            margin-bottom: var(--spacing-xl);
        }

        .pricing-price {
            margin-bottom: var(--spacing-xl);
        }

        .price-amount {
            font-size: 3rem;
            font-weight: 700;
            color: var(--navy-900);
            font-family: 'Space Grotesk', sans-serif;
        }

        .price-period {
            color: var(--slate-500);
            font-size: 1rem;
        }

        .pricing-features {
            list-style: none;
            margin-bottom: var(--spacing-xl);
        }

        .pricing-features li {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-md);
            padding: var(--spacing-md) 0;
            color: var(--slate-500);
        }

        .pricing-features svg {
            flex-shrink: 0;
            color: var(--blue-600);
            margin-top: 2px;
        }

        /* CTA Section */
        .cta-section {
            background: var(--navy-900);
            color: white;
            padding: 6rem var(--spacing-xl);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 1440px;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(59, 130, 246, 0.08) 0%, transparent 50%);
            pointer-events: none;
        }

        .cta-container {
            max-width: 1280px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: var(--spacing-lg);
            color: white;
        }

        .cta-section p {
            font-size: 1.25rem;
            color: var(--slate-300);
            max-width: 640px;
            margin: 0 auto var(--spacing-2xl);
        }

        .cta-buttons {
            display: flex;
            gap: var(--spacing-md);
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-white {
            background: white;
            color: var(--navy-900);
            box-shadow: var(--shadow-md);
        }

        .btn-white:hover {
            background: var(--slate-100);
            transform: translateY(-1px);
        }

        .btn-outline-white {
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            background: transparent;
        }

        .btn-outline-white:hover {
            border-color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Footer */
        footer {
            background: var(--navy-900);
            color: white;
            padding: 4rem var(--spacing-xl) 2rem;
        }

        .footer-container {
            max-width: 1280px;
            margin: 0 auto;
        }

        .footer-top {
            display: grid;
            grid-template-columns: 2fr repeat(3, 1fr);
            gap: var(--spacing-3xl);
            margin-bottom: var(--spacing-3xl);
            padding-bottom: var(--spacing-3xl);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-brand p {
            color: var(--slate-300);
            line-height: 1.7;
            margin-top: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
        }

        .footer-section h4 {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: var(--spacing-lg);
            color: white;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: var(--spacing-md);
        }

        .footer-links a {
            color: var(--slate-300);
            text-decoration: none;
            font-size: 0.9375rem;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        .social-links {
            display: flex;
            gap: var(--spacing-md);
            margin-top: var(--spacing-lg);
        }

        .social-link {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .social-link:hover {
            background: var(--blue-600);
            transform: translateY(-2px);
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--slate-400);
            font-size: 0.875rem;
        }

        .footer-bottom-links {
            display: flex;
            gap: var(--spacing-xl);
        }

        .footer-bottom-links a {
            color: var(--slate-400);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-bottom-links a:hover {
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .pricing-grid {
                grid-template-columns: 1fr;
            }

            .footer-top {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .header-container {
                padding: 0 var(--spacing-lg);
            }

            .main-nav,
            .auth-buttons {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .mobile-menu,
            .mobile-overlay {
                display: flex;
            }

            .hero {
                padding: 4rem var(--spacing-lg) 5rem;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.125rem;
            }

            .hero-stats {
                flex-wrap: wrap;
                gap: var(--spacing-xl);
            }

            .section-title {
                font-size: 2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .value-grid {
                grid-template-columns: 1fr;
            }

            .footer-top {
                grid-template-columns: 1fr;
            }

            .footer-bottom {
                flex-direction: column;
                gap: var(--spacing-lg);
                text-align: center;
            }

            .footer-bottom-links {
                flex-direction: column;
                gap: var(--spacing-md);
            }
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>

    <header id="header">
        <div class="header-container">
            <a href="#home" class="logo">
                <div class="logo-mark">J</div>
                <span>JudgingSystem</span>
            </a>
            <nav class="main-nav">
                <a href="#home" class="nav-link active">Home</a>
                <a href="#features" class="nav-link">Features</a>
                <a href="#pricing" class="nav-link">Pricing</a>
                <a href="#about" class="nav-link">About</a>
                <a href="#contact" class="nav-link">Contact</a>
            </nav>
            <div class="auth-buttons">
                <a href="/login" class="btn btn-ghost">Sign In</a>
                <a href="/register" class="btn btn-primary">Get Started</a>
            </div>
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </header>

    <div class="mobile-menu" id="mobileMenu">
        <a href="#home" class="mobile-nav-link" onclick="toggleMobileMenu()">Home</a>
        <a href="#features" class="mobile-nav-link" onclick="toggleMobileMenu()">Features</a>
        <a href="#pricing" class="mobile-nav-link" onclick="toggleMobileMenu()">Pricing</a>
        <a href="#about" class="mobile-nav-link" onclick="toggleMobileMenu()">About</a>
        <a href="#contact" class="mobile-nav-link" onclick="toggleMobileMenu()">Contact</a>
        <div class="mobile-auth">
            <a href="/login" class="btn btn-ghost">Sign In</a>
            <a href="/register" class="btn btn-primary">Get Started</a>
        </div>
    </div>

    <section class="hero" id="home">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge animate-fade-in-up">
                    <div class="hero-badge-dot"></div>
                    <span>Trusted by 10,000+ Organizations</span>
                </div>
                <h1 class="animate-fade-in-up delay-100">Enterprise Competition Management Platform</h1>
                <p class="hero-subtitle animate-fade-in-up delay-200">
                    Streamline your competition workflow with professional-grade judging tools, 
                    real-time collaboration, and comprehensive analytics. Built for organizations 
                    that demand excellence.
                </p>
                <div class="hero-cta animate-fade-in-up delay-300">
                    <a href="/register" class="btn btn-blue btn-large">Start Free Trial</a>
                    <a href="#features" class="btn btn-outline btn-large">Watch Demo</a>
                </div>
                <div class="hero-stats animate-fade-in-up delay-400">
                    <div class="hero-stat">
                        <div class="hero-stat-value">10K+</div>
                        <div class="hero-stat-label">Competitions</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">50K+</div>
                        <div class="hero-stat-label">Active Judges</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">98%</div>
                        <div class="hero-stat-label">Satisfaction</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">24/7</div>
                        <div class="hero-stat-label">Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="trust-section">
        <div class="trust-container">
            <div class="trust-label">Trusted by industry leaders</div>
            <div class="trust-logos">
                <svg class="trust-logo" width="120" height="32" viewBox="0 0 120 32" fill="currentColor">
                    <text x="0" y="24" font-family="Space Grotesk, sans-serif" font-size="20" font-weight="600">Company</text>
                </svg>
                <svg class="trust-logo" width="120" height="32" viewBox="0 0 120 32" fill="currentColor">
                    <text x="0" y="24" font-family="Space Grotesk, sans-serif" font-size="20" font-weight="600">Enterprise</text>
                </svg>
                <svg class="trust-logo" width="120" height="32" viewBox="0 0 120 32" fill="currentColor">
                    <text x="0" y="24" font-family="Space Grotesk, sans-serif" font-size="20" font-weight="600">Global Inc</text>
                </svg>
                <svg class="trust-logo" width="120" height="32" viewBox="0 0 120 32" fill="currentColor">
                    <text x="0" y="24" font-family="Space Grotesk, sans-serif" font-size="20" font-weight="600">Partners</text>
                </svg>
            </div>
        </div>
    </section>

    <section class="features-section" id="features">
        <div class="features-container">
            <div class="section-header">
                <div class="section-label">Features</div>
                <h2 class="section-title">Everything you need to run competitions</h2>
                <p class="section-description">
                    Comprehensive tools designed for judges, participants, and administrators to 
                    deliver fair, transparent, and efficient competition management.
                </p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3>Intuitive Judging Interface</h3>
                    <p>
                        Purpose-built scoring tools with customizable criteria, real-time collaboration, 
                        and automated calculations ensure consistent, fair evaluations across all judges.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3>Advanced Analytics</h3>
                    <p>
                        Comprehensive reporting and data visualization tools provide deep insights into 
                        scoring patterns, judge performance, and competition outcomes.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3>Enterprise Security</h3>
                    <p>
                        Bank-level encryption, SOC 2 compliance, role-based access control, and 
                        comprehensive audit trails keep your data secure and confidential.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3>Real-Time Updates</h3>
                    <p>
                        Live scoring updates, instant notifications, and synchronized dashboards ensure 
                        all stakeholders stay informed throughout the competition process.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3>Custom Workflows</h3>
                    <p>
                        Flexible configuration options adapt to your unique judging criteria, scoring 
                        methods, and competition formats with zero compromise.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3>Unlimited Scalability</h3>
                    <p>
                        Whether you're managing 10 or 10,000 participants, our cloud infrastructure 
                        scales seamlessly to meet your needs without performance degradation.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="value-props" id="about">
        <div class="value-props-container">
            <div class="section-header">
                <div class="section-label">Why Choose Us</div>
                <h2 class="section-title">Built for professional organizations</h2>
                <p class="section-description">
                    Founded by competition organizers and judges, we understand the challenges of 
                    managing large-scale events and have built solutions that address them.
                </p>
            </div>
            <div class="value-grid">
                <div class="value-card">
                    <div class="value-card-header">
                        <div class="value-number">1</div>
                        <h3>Proven at Scale</h3>
                    </div>
                    <p>
                        Over 10,000 competitions hosted and 50,000+ judges trained on our platform. 
                        Our track record speaks for itself with a 98% satisfaction rate and industry-leading 
                        retention metrics.
                    </p>
                </div>
                <div class="value-card">
                    <div class="value-card-header">
                        <div class="value-number">2</div>
                        <h3>Expert Support</h3>
                    </div>
                    <p>
                        Dedicated account managers, 24/7 technical support, and comprehensive onboarding 
                        ensure your team is successful from day one. Average response time under 2 hours.
                    </p>
                </div>
                <div class="value-card">
                    <div class="value-card-header">
                        <div class="value-number">3</div>
                        <h3>Continuous Innovation</h3>
                    </div>
                    <p>
                        Regular feature updates driven by customer feedback. Our product roadmap is shaped 
                        by the real needs of competition organizers worldwide.
                    </p>
                </div>
                <div class="value-card">
                    <div class="value-card-header">
                        <div class="value-number">4</div>
                        <h3>Transparent Pricing</h3>
                    </div>
                    <p>
                        No hidden fees, no per-user charges, no surprises. Pay for what you need with 
                        flexible plans that grow with your organization.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="pricing-section" id="pricing">
        <div class="pricing-container">
            <div class="section-header">
                <div class="section-label">Pricing</div>
                <h2 class="section-title">Plans designed for every organization</h2>
                <p class="section-description">
                    Start free and scale as you grow. All plans include core features with no hidden fees.
                </p>
            </div>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <h3>Starter</h3>
                    <p class="plan-description">Perfect for small competitions and pilot programs</p>
                    <div class="pricing-price">
                        <span class="price-amount">$29</span>
                        <span class="price-period">/month</span>
                    </div>
                    <ul class="pricing-features">
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Up to 5 competitions</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>10 judges per competition</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>100 participants</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Basic analytics & reporting</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Email support</span>
                        </li>
                    </ul>
                    <a href="/register" class="btn btn-outline btn-large" style="width: 100%;">Get Started</a>
                </div>
                <div class="pricing-card featured">
                    <div class="pricing-badge">Most Popular</div>
                    <h3>Professional</h3>
                    <p class="plan-description">Best for growing organizations and regular events</p>
                    <div class="pricing-price">
                        <span class="price-amount">$79</span>
                        <span class="price-period">/month</span>
                    </div>
                    <ul class="pricing-features">
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Unlimited competitions</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Unlimited judges</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Up to 500 participants</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Advanced analytics & insights</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Priority support</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Custom branding</span>
                        </li>
                    </ul>
                    <a href="/register" class="btn btn-blue btn-large" style="width: 100%;">Get Started</a>
                </div>
                <div class="pricing-card">
                    <h3>Enterprise</h3>
                    <p class="plan-description">For large organizations with complex requirements</p>
                    <div class="pricing-price">
                        <span class="price-amount" style="font-size: 2rem;">Custom</span>
                    </div>
                    <ul class="pricing-features">
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Everything in Professional</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Unlimited participants</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>White-label solution</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Dedicated account manager</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>24/7 phone support</span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Custom integrations & API</span>
                        </li>
                    </ul>
                    <a href="#contact" class="btn btn-outline btn-large" style="width: 100%;">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section" id="contact">
        <div class="cta-container">
            <h2>Ready to transform your competitions?</h2>
            <p>
                Join thousands of organizations worldwide who trust JudgingSystem for fair, 
                efficient, and transparent competition management.
            </p>
            <div class="cta-buttons">
                <a href="/register" class="btn btn-white btn-large">Start Free Trial</a>
                <a href="mailto:sales@judgingsystem.com" class="btn btn-outline-white btn-large">Contact Sales</a>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-container">
            <div class="footer-top">
                <div class="footer-brand">
                    <div class="logo">
                        <div class="logo-mark">J</div>
                        <span>JudgingSystem</span>
                    </div>
                    <p>
                        Professional competition management platform trusted by organizations worldwide.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="LinkedIn">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link" aria-label="GitHub">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Product</h4>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="#">Integrations</a></li>
                        <li><a href="#">Changelog</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Company</h4>
                    <ul class="footer-links">
                        <li><a href="#about">About</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Press</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Support</h4>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="#">Status</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div>&copy; 2026 JudgingSystem. All rights reserved.</div>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Header scroll effect
        const header = document.getElementById('header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileOverlay = document.getElementById('mobileOverlay');
            mobileMenu.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });

        // Update active nav link on scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (scrollY >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>