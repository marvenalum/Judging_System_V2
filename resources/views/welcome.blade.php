<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judging System - Fair & Efficient Competition Platform</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .footer-bottom-links {
                flex-direction: column;
                gap: 0.75rem;
            }
        }

        :root {
            --primary: #1b1b18;
            --primary-light: #706f6c;
            --accent: #f53003;
            --bg-light: #FDFDFC;
            --bg-dark: #0a0a0a;
            --card-light: #ffffff;
            --card-dark: #161615;
            --border-light: #e3e3e0;
            --border-dark: #3E3E3A;
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background: var(--bg-light);
            color: var(--primary);
            overflow-x: hidden;
        }

        /* Animated gradient background */
        .gradient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, #FDFDFC 0%, #f5f5f3 50%, #FDFDFC 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Floating shapes */
        .shape {
            position: fixed;
            border-radius: 50%;
            opacity: 0.1;
            z-index: -1;
            animation: float 20s infinite;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            background: var(--accent);
            top: 10%;
            left: -5%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            background: var(--primary);
            bottom: 10%;
            right: -3%;
            animation-delay: 5s;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            background: var(--accent);
            top: 60%;
            left: 80%;
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(20px, -20px) rotate(90deg); }
            50% { transform: translate(-10px, 30px) rotate(180deg); }
            75% { transform: translate(30px, 10px) rotate(270deg); }
        }

        /* Header */
        header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(253, 253, 252, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            animation: slideDown 0.8s ease-out;
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--bg-light);
            opacity: 0.95;
            z-index: -1;
        }

        header > * {
            position: relative;
            z-index: 1;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            max-width: 1200px;
            margin-left: 0%;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent), #ff6b4a);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }

        .main-nav {
            display: flex;
            gap: 2rem;
            align-items: center;
            padding:3em;
        }

        .nav-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 0;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--accent);
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--primary);
        }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            right: -100%;
            width: 280px;
            height: 100vh;
            background: white;
            box-shadow: -4px 0 24px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            transition: right 0.3s ease;
            z-index: 1000;
            flex-direction: column;
            gap: 1.5rem;
        }

        .mobile-menu.active {
            right: 0;
        }

        .mobile-nav-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 1.1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-light);
        }

        .mobile-auth {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: auto;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-ghost {
            color: var(--primary);
            border-color: var(--border-light);
        }

        .btn-ghost:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(27, 27, 24, 0.15);
        }

        .btn-primary:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(27, 27, 24, 0.25);
        }

        /* Hero Section */
        .hero {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 2rem;
            text-align: center;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero .subtitle {
            font-size: 1.25rem;
            color: var(--primary-light);
            max-width: 700px;
            margin: 0 auto 2.5rem;
            line-height: 1.8;
        }

        .cta-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-large {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            border-radius: 12px;
        }

        .btn-accent {
            background: linear-gradient(135deg, var(--accent), #ff6b4a);
            color: white;
            box-shadow: 0 8px 24px rgba(245, 48, 3, 0.3);
        }

        .btn-accent:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(245, 48, 3, 0.4);
        }

        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        /* Features Grid */
        .features {
            max-width: 1200px;
            margin: 6rem auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--card-light);
            padding: 2.5rem;
            border-radius: 16px;
            border: 1px solid var(--border-light);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            transition: all 0.4s ease;
            animation: fadeInUp 0.8s ease-out backwards;
            position: relative;
            overflow: hidden;
        }

        .feature-card:nth-child(1) { animation-delay: 0.1s; }
        .feature-card:nth-child(2) { animation-delay: 0.2s; }
        .feature-card:nth-child(3) { animation-delay: 0.3s; }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), #ff6b4a);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--accent), #ff6b4a);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 24px rgba(245, 48, 3, 0.2);
        }

        .feature-icon svg {
            width: 32px;
            height: 32px;
            color: white;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--primary-light);
            line-height: 1.7;
            font-size: 1rem;
        }

        /* Stats Section */
        .stats {
            max-width: 1200px;
            margin: 6rem auto;
            padding: 3rem 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            text-align: center;
        }

        .stat-item {
            animation: fadeInUp 0.8s ease-out backwards;
        }

        .stat-item:nth-child(1) { animation-delay: 0.1s; }
        .stat-item:nth-child(2) { animation-delay: 0.2s; }
        .stat-item:nth-child(3) { animation-delay: 0.3s; }
        .stat-item:nth-child(4) { animation-delay: 0.4s; }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--accent), #ff6b4a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--primary-light);
            font-size: 1rem;
        }

        /* Footer */
        footer {
            background: var(--primary);
            color: white;
            padding: 4rem 2rem 2rem;
            margin-top: 6rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .footer-section p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--accent);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--accent);
            transform: translateY(-3px);
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: rgba(255, 255, 255, 0.6);
        }

        .footer-bottom-links {
            display: flex;
            gap: 2rem;
        }

        .footer-bottom-links a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-bottom-links a:hover {
            color: white;
        }

        .newsletter-form {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .newsletter-form input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: white;
            font-size: 0.95rem;
        }

        .newsletter-form input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .newsletter-form input:focus {
            outline: none;
            border-color: var(--accent);
        }

        .newsletter-form button {
            padding: 0.75rem 1.5rem;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .newsletter-form button:hover {
            background: #ff6b4a;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero .subtitle {
                font-size: 1.1rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
            }

            .main-nav,
            .auth-buttons {
                display: none;

            }

            .mobile-menu-btn {
                display: block;
            }

            .mobile-menu {
                display: flex;
            }

            .about-content {
                flex-direction: column;
            }

            .about-image {
                margin-top: 2rem;
            }

            .features-grid-extended {
                grid-template-columns: 1fr;
            }

            .pricing-grid {
                grid-template-columns: 1fr;
            }

            .contact-content {
                flex-direction: column;
            }

            .contact-info {
                margin-bottom: 2rem;
            }
        }

        /* About Section */
        .about {
            max-width: 1200px;
            margin: 6rem auto;
            padding: 4rem 2rem;
            background: var(--card-light);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
        }

        .about-content {
            display: flex;
            gap: 4rem;
            align-items: center;
        }

        .about-text {
            flex: 1;
        }

        .about h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .about-text p {
            color: var(--primary-light);
            line-height: 1.8;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .about-features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 2rem;
        }

        .about-feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--primary);
        }

        .about-feature-item svg {
            color: var(--accent);
            flex-shrink: 0;
        }

        .about-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-placeholder {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, rgba(245, 48, 3, 0.1), rgba(255, 107, 74, 0.1));
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
        }

        /* Additional Features */
        .additional-features {
            max-width: 1200px;
            margin: 6rem auto;
            padding: 2rem;
            text-align: center;
        }

        .additional-features h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .features-grid-extended {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .extended-feature {
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .extended-feature:hover {
            transform: translateY(-5px);
        }

        .extended-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, rgba(245, 48, 3, 0.1), rgba(255, 107, 74, 0.1));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--accent);
        }

        .extended-icon svg {
            width: 28px;
            height: 28px;
        }

        .extended-feature h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .extended-feature p {
            color: var(--primary-light);
            line-height: 1.6;
        }

        /* Pricing Section */
        .pricing {
            max-width: 1200px;
            margin: 6rem auto;
            padding: 2rem;
            text-align: center;
        }

        .pricing h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .pricing-subtitle {
            color: var(--primary-light);
            font-size: 1.2rem;
            margin-bottom: 3rem;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .pricing-card {
            background: var(--card-light);
            padding: 3rem 2rem;
            border-radius: 16px;
            border: 2px solid var(--border-light);
            transition: all 0.4s ease;
            position: relative;
        }

        .pricing-card.featured {
            border-color: var(--accent);
            box-shadow: 0 12px 40px rgba(245, 48, 3, 0.15);
            transform: scale(1.05);
        }

        .pricing-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        .pricing-card.featured:hover {
            transform: translateY(-8px) scale(1.07);
        }

        .popular-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--accent), #ff6b4a);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .pricing-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .price {
            margin-bottom: 2rem;
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 0.25rem;
        }

        .currency {
            font-size: 1.5rem;
            color: var(--primary-light);
        }

        .amount {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .amount-text {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .period {
            color: var(--primary-light);
            font-size: 1rem;
        }

        .pricing-features {
            list-style: none;
            margin-bottom: 2rem;
            text-align: left;
        }

        .pricing-features li {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-light);
            color: var(--primary);
        }

        .pricing-features li:last-child {
            border-bottom: none;
        }

        /* Contact Section */
        .contact {
            max-width: 1200px;
            margin: 6rem auto;
            padding: 2rem;
            text-align: center;
        }

        .contact h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .contact-subtitle {
            color: var(--primary-light);
            font-size: 1.2rem;
            margin-bottom: 3rem;
        }

        .contact-content {
            display: flex;
            gap: 4rem;
            text-align: left;
        }

        .contact-info {
            flex: 1;
        }

        .contact-item {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
            align-items: flex-start;
        }

        .contact-item svg {
            color: var(--accent);
            flex-shrink: 0;
            margin-top: 0.25rem;
        }

        .contact-item h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .contact-item p {
            color: var(--primary-light);
            line-height: 1.6;
        }

        .contact-form {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-form input,
        .contact-form textarea {
            padding: 1rem;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            outline: none;
            border-color: var(--accent);
        }

        .contact-form textarea {
            resize: vertical;
        }
    </style>
</head>
<body>
    <div class="gradient-bg"></div>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <header>
        <div class="logo">
            <div class="logo-icon">J</div>
            <span>JudgingSystem</span>
        </div>
        <nav class="main-nav">
            <a href="#home" class="nav-link active">Home</a>
            <a href="#about" class="nav-link">About</a>
            <a href="#features" class="nav-link">Features</a>
            <a href="#pricing" class="nav-link">Pricing</a>
            <a href="#contact" class="nav-link">Contact</a>
        </nav>
        <div class="auth-buttons">
            <a href="/login" class="btn btn-primary">Log in</a>
        </div>
        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </header>

    <div class="mobile-menu" id="mobileMenu">
        <a href="#home" class="mobile-nav-link" onclick="toggleMobileMenu()">Home</a>
        <a href="#about" class="mobile-nav-link" onclick="toggleMobileMenu()">About</a>
        <a href="#features" class="mobile-nav-link" onclick="toggleMobileMenu()">Features</a>
        <a href="#pricing" class="mobile-nav-link" onclick="toggleMobileMenu()">Pricing</a>
        <a href="#contact" class="mobile-nav-link" onclick="toggleMobileMenu()">Contact</a>
        <div class="mobile-auth">
            <a href="/login" class="btn btn-ghost">Log in</a>
            <a href="/register" class="btn btn-primary">Get Started</a>
        </div>
    </div>

    <section class="hero" id="home">
        <h1>Fair & Efficient<br>Competition Judging</h1>
        <p class="subtitle">
            A comprehensive platform that transforms the way competitions are judged. 
            Manage participants, evaluate performances, and ensure transparent results with our advanced tools.
        </p>
        <div class="cta-buttons">
            <a href="/register" class="btn btn-accent btn-large">Start Free Trial</a>
            <a href="#features" class="btn btn-outline btn-large">Learn More</a>
        </div>
    </section>

    <section class="stats">
        <div class="stat-item">
            <div class="stat-number">10K+</div>
            <div class="stat-label">Competitions Hosted</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">50K+</div>
            <div class="stat-label">Active Judges</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">98%</div>
            <div class="stat-label">Satisfaction Rate</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Support Available</div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="feature-card">
            <div class="feature-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3>For Judges</h3>
            <p>
                Access intuitive scoring interfaces, real-time collaboration tools, and comprehensive evaluation criteria. 
                Ensure fair and consistent judging with our purpose-built platform designed by competition experts.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3>For Participants</h3>
            <p>
                Submit entries seamlessly, track your progress in real-time, and receive detailed feedback. 
                Stay informed with live updates and transparent scoring throughout every stage of the competition.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h3>For Administrators</h3>
            <p>
                Manage competitions effortlessly with powerful admin tools. Configure judging criteria, oversee judges, 
                and generate comprehensive reports with our intuitive dashboard designed for efficiency.
            </p>
        </div>
    </section>

    <section class="about" id="about">
        <div class="about-content">
            <div class="about-text">
                <h2>About Our Platform</h2>
                <p>
                    Founded by competition organizers and judges, JudgingSystem was born from the need for a fair, 
                    transparent, and efficient way to manage competitions of all sizes.
                </p>
                <p>
                    We've processed over 10,000 competitions and helped 50,000+ judges deliver accurate, unbiased 
                    results. Our platform combines cutting-edge technology with competition best practices to ensure 
                    every participant gets a fair evaluation.
                </p>
                <div class="about-features">
                    <div class="about-feature-item">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Real-time collaboration</span>
                    </div>
                    <div class="about-feature-item">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Advanced analytics</span>
                    </div>
                    <div class="about-feature-item">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Customizable workflows</span>
                    </div>
                    <div class="about-feature-item">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>24/7 support</span>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <div class="image-placeholder">
                    <svg width="120" height="120" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <section class="additional-features">
        <h2>Why Choose JudgingSystem?</h2>
        <div class="features-grid-extended">
            <div class="extended-feature">
                <div class="extended-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h4>Lightning Fast</h4>
                <p>Process scores instantly with our optimized infrastructure</p>
            </div>
            <div class="extended-feature">
                <div class="extended-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h4>Secure & Private</h4>
                <p>Bank-level encryption keeps all data safe and confidential</p>
            </div>
            <div class="extended-feature">
                <div class="extended-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <h4>Detailed Analytics</h4>
                <p>Comprehensive reports and insights for every competition</p>
            </div>
            <div class="extended-feature">
                <div class="extended-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4>Global Access</h4>
                <p>Host competitions anywhere with cloud-based platform</p>
            </div>
            <div class="extended-feature">
                <div class="extended-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h4>Unlimited Judges</h4>
                <p>Add as many judges as needed without extra costs</p>
            </div>
            <div class="extended-feature">
                <div class="extended-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h4>Export Reports</h4>
                <p>Download results in multiple formats (PDF, Excel, CSV)</p>
            </div>
        </div>
    </section>

    <section class="pricing" id="pricing">
        <h2>Simple, Transparent Pricing</h2>
        <p class="pricing-subtitle">Choose the plan that fits your needs</p>
        <div class="pricing-grid">
            <div class="pricing-card">
                <h3>Starter</h3>
                <div class="price">
                    <span class="currency">$</span>
                    <span class="amount">29</span>
                    <span class="period">/month</span>
                </div>
                <ul class="pricing-features">
                    <li>Up to 5 competitions</li>
                    <li>10 judges per competition</li>
                    <li>100 participants</li>
                    <li>Basic analytics</li>
                    <li>Email support</li>
                </ul>
                <a href="/register" class="btn btn-outline btn-large">Get Started</a>
            </div>
            <div class="pricing-card featured">
                <div class="popular-badge">Most Popular</div>
                <h3>Professional</h3>
                <div class="price">
                    <span class="currency">$</span>
                    <span class="amount">79</span>
                    <span class="period">/month</span>
                </div>
                <ul class="pricing-features">
                    <li>Unlimited competitions</li>
                    <li>Unlimited judges</li>
                    <li>500 participants</li>
                    <li>Advanced analytics</li>
                    <li>Priority support</li>
                    <li>Custom branding</li>
                </ul>
                <a href="/register" class="btn btn-accent btn-large">Get Started</a>
            </div>
            <div class="pricing-card">
                <h3>Enterprise</h3>
                <div class="price">
                    <span class="amount-text">Custom</span>
                </div>
                <ul class="pricing-features">
                    <li>Everything in Professional</li>
                    <li>Unlimited participants</li>
                    <li>White-label solution</li>
                    <li>Dedicated account manager</li>
                    <li>24/7 phone support</li>
                    <li>Custom integrations</li>
                </ul>
                <a href="#contact" class="btn btn-outline btn-large">Contact Sales</a>
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <h2>Get In Touch</h2>
        <p class="contact-subtitle">Have questions? We're here to help</p>
        <div class="contact-content">
            <div class="contact-info">
                <div class="contact-item">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <h4>Email</h4>
                        <p>support@judgingsystem.com</p>
                    </div>
                </div>
                <div class="contact-item">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <div>
                        <h4>Phone</h4>
                        <p>+1 (555) 123-4567</p>
                    </div>
                </div>
                <div class="contact-item">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div>
                        <h4>Office</h4>
                        <p>123 Competition Street<br>San Francisco, CA 94102</p>
                    </div>
                </div>
            </div>
            <form class="contact-form">
                <input type="text" placeholder="Your Name" required>
                <input type="email" placeholder="Your Email" required>
                <textarea placeholder="Your Message" rows="5" required></textarea>
                <button type="submit" class="btn btn-accent btn-large">Send Message</button>
            </form>
        </div>
    </section>