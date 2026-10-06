<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinBot — Kelola Keuangan Lebih Mudah dengan AI Assistant</title>

    <!-- Meta Tags SEO & Search Engine -->
    <meta name="title" content="FinBot — Kelola Keuangan Lebih Mudah dengan AI Assistant">
    <meta name="description"
        content="FinBot adalah asisten keuangan pintar berbasis AI di Telegram untuk mencatat pengeluaran, pemasukan, dan laporan arus kas bisnis maupun pribadi secara instan.">
    <meta name="keywords"
        content="finbot, asisten keuangan, bot telegram keuangan, catat pengeluaran otomatis, manajemen keuangan umkm, finance tracker, bot pembukuan telegram">
    <meta name="author" content="FinBot">

    <!-- Open Graph / Facebook / WhatsApp Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= base_url('finbot') ?>">
    <meta property="og:title" content="FinBot — Asisten Keuangan Pintar Berbasis AI">
    <meta property="og:description"
        content="Catat keuangan semudah chatting di Telegram. Cukup ketik santai, FinBot langsung merapikan pemasukan, pengeluaran, dan laporan kas usahamu!">
    <meta property="og:image" content="<?= base_url('assets_front/img/produk.png') ?>">
    <meta property="og:image:secure_url" content="<?= base_url('assets_front/img/produk.png') ?>">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="FinBot AI Finance Assistant">
    <meta property="og:site_name" content="FinBot">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= base_url('finbot') ?>">
    <meta name="twitter:title" content="FinBot — Asisten Keuangan Pintar Berbasis AI">
    <meta name="twitter:description"
        content="Catat keuangan semudah chatting di Telegram. Cukup ketik santai, FinBot langsung merapikan pemasukan, pengeluaran, dan laporan kas usahamu!">
    <meta name="twitter:image" content="<?= base_url('assets_front/img/produk.png') ?>">

    <!-- Theme & Favicon -->
    <meta name="theme-color" content="#2563eb">
    <link rel="icon" type="image/jpeg" href="<?= base_url('assets_front/img/finbot-icon.png') ?>">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --finbot-blue: #2563eb;
            --finbot-blue-dark: #1d4ed8;
            --finbot-blue-light: #eff6ff;
            --finbot-navy: #0f172a;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --green-badge: #10b981;
            --purple-badge: #8b5cf6;
            --orange-badge: #f97316;
            --teal-badge: #06b6d4;
            --card-shadow: 0 10px 30px -5px rgba(37, 99, 235, 0.08);
            --card-shadow-hover: 0 20px 40px -8px rgba(37, 99, 235, 0.16);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            color: var(--text-dark);
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* Container Limit */
        .showcase-container {
            width: 100%;
            max-width: 1330px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Top Navbar */
        .navbar-showcase {
            padding: 24px 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar-showcase.scrolled {
            padding: 14px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.7rem;
            letter-spacing: -0.5px;
        }

        .brand-logo .logo-bubble {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.35rem;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .brand-logo:hover .logo-bubble {
            transform: scale(1.08) rotate(8deg);
        }

        .brand-logo .text-fin {
            color: #0f172a;
        }

        .brand-logo .text-bot {
            color: var(--finbot-blue);
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 36px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-menu .nav-item a {
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s ease;
            position: relative;
            padding: 6px 2px;
        }

        .nav-menu .nav-item a:hover {
            color: var(--finbot-blue);
        }

        .nav-menu .nav-item a.active {
            color: var(--finbot-blue);
            font-weight: 700;
        }

        .nav-menu .nav-item a.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--finbot-blue);
            border-radius: 4px;
        }

        /* Buttons */
        .btn-finbot-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 12px 28px;
            border-radius: 9999px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
        }

        .btn-finbot-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.45);
        }

        .btn-finbot-secondary {
            background: #ffffff;
            color: var(--finbot-blue);
            font-weight: 700;
            font-size: 0.95rem;
            padding: 12px 26px;
            border-radius: 9999px;
            border: 1.5px solid #93c5fd;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-finbot-secondary:hover {
            background: #eff6ff;
            color: var(--finbot-blue-dark);
            border-color: var(--finbot-blue);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            padding: 40px 0 60px;
            background: radial-gradient(circle at 82% 38%, rgba(219, 234, 254, 0.85) 0%, rgba(238, 246, 255, 0.5) 45%, rgba(255, 255, 255, 1) 75%);
            overflow: hidden;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #dbeafe;
            color: var(--finbot-blue);
            font-weight: 700;
            font-size: 0.85rem;
            padding: 7px 18px;
            border-radius: 9999px;
            margin-bottom: 24px;
            letter-spacing: 0.2px;
        }

        .hero-heading {
            font-size: 3.5rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.15;
            letter-spacing: -1.2px;
            margin-bottom: 22px;
        }

        .hero-heading .text-blue {
            color: var(--finbot-blue);
        }

        .hero-description {
            font-size: 1.12rem;
            color: #64748b;
            line-height: 1.7;
            max-width: 520px;
            margin-bottom: 34px;
            font-weight: 450;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* Hero Mascot Area */
        .hero-mascot-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 500px;
        }

        .mascot-glow-bg {
            position: absolute;
            width: 440px;
            height: 440px;
            background: radial-gradient(circle, rgba(147, 197, 253, 0.6) 0%, rgba(191, 219, 254, 0.3) 50%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            z-index: 1;
            filter: blur(24px);
        }

        .mascot-image {
            width: 100%;
            max-width: 440px;
            height: auto;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 20px 30px rgba(37, 99, 235, 0.18));
            animation: floatRobot 4s ease-in-out infinite alternate;
            border-radius: 36px;
        }

        @keyframes floatRobot {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            100% {
                transform: translateY(-16px) rotate(1.5deg);
            }
        }

        /* Floating Cards */
        .floating-bubble-speech {
            position: absolute;
            top: 15px;
            right: 20px;
            background: #2563eb;
            color: #ffffff;
            padding: 12px 18px;
            border-radius: 20px 20px 20px 4px;
            font-size: 0.88rem;
            font-weight: 700;
            line-height: 1.35;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.35);
            z-index: 4;
            animation: floatSlow 3.5s ease-in-out infinite alternate;
        }

        .floating-card {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 18px;
            padding: 10px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            z-index: 4;
            transition: transform 0.3s ease;
        }

        .floating-card:hover {
            transform: scale(1.05);
        }

        .floating-card-1 {
            top: 55px;
            left: -15px;
            animation: floatUp 4s ease-in-out infinite alternate;
        }

        .floating-card-2 {
            bottom: 65px;
            left: -25px;
            animation: floatDown 4.5s ease-in-out infinite alternate;
        }

        .floating-card-3 {
            bottom: 75px;
            right: -10px;
            animation: floatUp 3.8s ease-in-out infinite alternate;
        }

        @keyframes floatUp {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-10px);
            }
        }

        @keyframes floatDown {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(10px);
            }
        }

        @keyframes floatSlow {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-8px);
            }
        }

        .floating-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .floating-icon.green {
            background: #d1fae5;
            color: #10b981;
        }

        .floating-icon.purple {
            background: #ede9fe;
            color: #8b5cf6;
        }

        .floating-icon.orange {
            background: #ffedd5;
            color: #f97316;
        }

        .floating-card-text {
            display: flex;
            flex-direction: column;
        }

        .floating-card-title {
            font-size: 0.85rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        .floating-card-sub {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
        }

        /* Sparkles Accents */
        .mascot-sparkle {
            position: absolute;
            color: #60a5fa;
            font-size: 1.2rem;
            z-index: 3;
            animation: pulseSparkle 2s infinite ease-in-out;
        }

        .sparkle-1 {
            top: 35px;
            right: 95px;
            font-size: 1.4rem;
        }

        .sparkle-2 {
            bottom: 120px;
            right: 15px;
            font-size: 1rem;
            color: #93c5fd;
        }

        .sparkle-3 {
            top: 180px;
            left: 15px;
            font-size: 1.1rem;
            color: #60a5fa;
        }

        @keyframes pulseSparkle {

            0%,
            100% {
                opacity: 0.4;
                transform: scale(0.8);
            }

            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        /* Value Props 4-Card Bar */
        .value-props-section {
            margin-top: -20px;
            position: relative;
            z-index: 10;
            margin-bottom: 70px;
        }

        .props-card-container {
            background: #ffffff;
            border-radius: 24px;
            padding: 34px 28px;
            box-shadow: 0 16px 40px -10px rgba(15, 23, 42, 0.07);
            border: 1px solid #f1f5f9;
        }

        .prop-item {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 10px 16px;
            position: relative;
        }

        .prop-icon-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 18px;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .prop-item:hover .prop-icon-circle {
            transform: scale(1.12) rotate(6deg);
        }

        .prop-icon-circle.blue {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        }

        .prop-icon-circle.green {
            background: #10b981;
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }

        .prop-icon-circle.purple {
            background: #8b5cf6;
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(139, 92, 246, 0.3);
        }

        .prop-icon-circle.orange {
            background: #f97316;
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(249, 115, 22, 0.3);
        }

        .prop-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }

        .prop-description {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.55;
            margin: 0;
        }

        /* Features Section */
        .features-section {
            padding: 30px 0 70px;
        }

        .section-badge {
            display: inline-block;
            background-color: #e0f2fe;
            color: var(--finbot-blue);
            font-weight: 700;
            font-size: 0.85rem;
            padding: 6px 16px;
            border-radius: 9999px;
            margin-bottom: 14px;
        }

        .section-heading {
            font-size: 2.3rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
            letter-spacing: -0.8px;
        }

        .section-subheading {
            font-size: 1.05rem;
            color: #64748b;
            line-height: 1.65;
        }

        .feature-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            padding: 30px 26px;
            height: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            border-color: #bfdbfe;
            transform: translateY(-6px);
            box-shadow: var(--card-shadow-hover);
        }

        .feature-icon-wrapper {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon-wrapper {
            transform: scale(1.1);
        }

        .feature-card-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
            letter-spacing: -0.3px;
        }

        .feature-card-desc {
            font-size: 0.92rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* Interactive Demo Section */
        .demo-section {
            background: linear-gradient(180deg, #f8fafc 0%, #eff6ff 100%);
            padding: 70px 0;
            border-radius: 36px;
            margin: 30px 0 70px;
        }

        .demo-chat-box {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.12);
            padding: 24px;
            border: 1px solid #e2e8f0;
        }

        .demo-chat-stream {
            background: #f1f5f9;
            border-radius: 18px;
            padding: 20px;
            height: 320px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .demo-bubble {
            max-width: 80%;
            padding: 12px 18px;
            border-radius: 18px;
            font-size: 0.92rem;
            line-height: 1.45;
        }

        .demo-bubble.user {
            align-self: flex-end;
            background: #2563eb;
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }

        .demo-bubble.bot {
            align-self: flex-start;
            background: #ffffff;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .quick-tag-btn {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quick-tag-btn:hover {
            background: #dbeafe;
            color: #2563eb;
            border-color: #93c5fd;
        }

        /* Testimonials Section */
        .testi-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
            height: 100%;
            transition: transform 0.3s ease;
        }

        .testi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 35px rgba(37, 99, 235, 0.08);
        }

        .testi-stars {
            color: #fbbf24;
            margin-bottom: 14px;
            font-size: 0.9rem;
        }

        .testi-text {
            font-size: 0.94rem;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .testi-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .testi-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #3b82f6;
        }

        /* FAQ Accordion */
        .faq-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            margin-bottom: 12px;
            overflow: hidden;
        }

        .faq-button {
            width: 100%;
            padding: 18px 24px;
            background: none;
            border: none;
            text-align: left;
            font-weight: 700;
            font-size: 1.05rem;
            color: #0f172a;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .faq-content {
            padding: 0 24px 20px;
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
            display: none;
        }

        .faq-item.active .faq-content {
            display: block;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        /* CTA Section */
        .cta-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            border-radius: 32px;
            padding: 60px 40px;
            color: #ffffff;
            text-align: center;
            margin-bottom: 70px;
            position: relative;
            overflow: hidden;
        }

        .cta-banner::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
        }

        /* Footer */
        .footer-showcase {
            border-top: 1px solid #f1f5f9;
            padding: 40px 0;
            color: #64748b;
            font-size: 0.9rem;
        }

        @media (max-width: 991.98px) {
            .nav-menu {
                display: none;
            }

            .hero-heading {
                font-size: 2.6rem;
            }

            .hero-mascot-wrapper {
                margin-top: 40px;
            }

            .floating-card-1 {
                left: 0;
            }

            .floating-card-2 {
                left: 0;
            }

            .floating-card-3 {
                right: 0;
            }
        }

        @media (max-width: 767.98px) {
            .hero-heading {
                font-size: 2.1rem;
            }

            .hero-actions {
                flex-direction: column;
                width: 100%;
            }

            .hero-actions .btn-finbot-primary,
            .hero-actions .btn-finbot-secondary {
                width: 100%;
                justify-content: center;
            }

            .props-card-container {
                padding: 20px 14px;
            }

            .prop-item {
                margin-bottom: 20px;
            }
        }

        #badgehemat{
            position: absolute;
            top: -10px;
            right: -30px;
        }
    </style>
</head>

<body>

    <!-- Header Navigation Bar -->
    <nav class="navbar-showcase" id="navbar">
        <div class="showcase-container d-flex justify-content-between align-items-center">
            <!-- Brand Logo -->
            <a href="#beranda" class="brand-logo">
                <div class="logo-bubble">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div>
                    <span class="text-fin">Fin</span><span class="text-bot">Bot</span>
                </div>
            </a>

            <!-- Center Menu Links -->
            <ul class="nav-menu d-none d-lg-flex">
                <li class="nav-item"><a href="#fitur">Fitur</a></li>
                <li class="nav-item"><a href="#keunggulan">Kenapa</a></li>
                <li class="nav-item"><a href="#faq">FAQ</a></li>
                <li class="nav-item"><a href="#harga">Harga</a></li>
            </ul>

            <!-- Right CTA Action -->
            <div class="d-flex align-items-center gap-3">
                <a href="https://t.me/erka_finbot" target="_blank" class="btn-finbot-primary">
                    Mulai Sekarang
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="beranda">
        <div class="showcase-container">
            <div class="row align-items-center">
                <!-- Left Content -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="hero-badge">
                        <i class="fa-solid fa-sparkles text-primary"></i> AI Finance Assistant • @erka_finbot
                    </div>

                    <h1 class="hero-heading">
                        Kelola Keuangan<br>
                        Lebih Mudah dengan<br>
                        <span class="text-blue">FinBot</span>
                    </h1>

                    <p class="hero-description">
                        <b>“Nggak perlu format. Chat aja.”</b> Cukup kirim chat lewat Telegram, AI FinBot otomatis
                        menggolongkan pemasukan, pengeluaran stok, dan arus kas usahamu dalam hitungan detik.
                    </p>

                    <div class="hero-actions">
                        <a href="https://t.me/erka_finbot" target="_blank" class="btn-finbot-primary">
                            <i class="fa-brands fa-telegram me-1"></i> Chat dengan Finbot
                        </a>
                        <a href="<?= base_url('finbot/dashboard') ?>" class="btn-finbot-secondary">
                            <i class="fa-solid fa-chart-pie me-1"></i> Buka Dashboard
                        </a>
                    </div>
                </div>

                <!-- Right Mascot & Floating Visuals -->
                <div class="col-lg-6">
                    <div class="hero-mascot-wrapper">
                        <!-- Background Lighting Glow -->
                        <div class="mascot-glow-bg"></div>

                        <!-- 3D Mascot Image -->
                        <img src="<?= base_url('assets_front/img/robot.png') ?>" alt="FinBot Mascot AI Assistant"
                            class="mascot-image">

                        <!-- Sparkles Accents -->
                        <i class="fa-solid fa-sparkle mascot-sparkle sparkle-1"></i>
                        <i class="fa-solid fa-sparkle mascot-sparkle sparkle-2"></i>
                        <i class="fa-solid fa-sparkle mascot-sparkle sparkle-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Props 4-Card Bar -->
    <section class="value-props-section">
        <div class="showcase-container">
            <div class="props-card-container">
                <div class="row g-4">
                    <!-- Prop 1: Mudah Digunakan -->
                    <div class="col-md-6 col-lg-3">
                        <div class="prop-item">
                            <div class="prop-icon-circle blue">
                                <i class="fa-solid fa-bolt"></i>
                            </div>
                            <h4 class="prop-title">Mudah Digunakan</h4>
                            <p class="prop-description">
                                Interface yang sederhana dan intuitif untuk semua orang.
                            </p>
                        </div>
                    </div>

                    <!-- Prop 2: Aman & Terpercaya -->
                    <div class="col-md-6 col-lg-3">
                        <div class="prop-item">
                            <div class="prop-icon-circle green">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <h4 class="prop-title">Aman & Terpercaya</h4>
                            <p class="prop-description">
                                Data keuangan Anda terlindungi dengan teknologi keamanan modern.
                            </p>
                        </div>
                    </div>

                    <!-- Prop 3: Berbasis AI -->
                    <div class="col-md-6 col-lg-3">
                        <div class="prop-item">
                            <div class="prop-icon-circle purple">
                                <i class="fa-solid fa-brain"></i>
                            </div>
                            <h4 class="prop-title">Berbasis AI</h4>
                            <p class="prop-description">
                                Rekomendasi cerdas untuk keputusan finansial yang lebih baik.
                            </p>
                        </div>
                    </div>

                    <!-- Prop 4: Multi Platform -->
                    <div class="col-md-6 col-lg-3">
                        <div class="prop-item">
                            <div class="prop-icon-circle orange">
                                <i class="fa-solid fa-mobile-screen-button"></i>
                            </div>
                            <h4 class="prop-title">Multi Platform</h4>
                            <p class="prop-description">
                                Bisa diakses kapan saja, di mana saja, melalui web atau mobile.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Unggulan Section -->
    <section class="features-section" id="fitur">
        <div class="showcase-container">
            <!-- Section Header -->
            <div class="row align-items-end mb-5">
                <div class="col-lg-6">
                    <div class="section-badge">Fitur Unggulan</div>
                    <h2 class="section-heading">
                        Semua yang Anda Butuhkan untuk Mengelola Keuangan
                    </h2>
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <p class="section-subheading mb-0">
                        FinBot hadir dengan berbagai fitur lengkap yang dirancang untuk memudahkan Anda mengatur
                        keuangan pribadi maupun bisnis.
                    </p>
                </div>
                  <!-- Feature Cards Grid -->
            <div class="row g-4">
                <!-- Feature 1: Pencatatan Transaksi -->
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper" style="background:#d1fae5; color:#10b981;">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <h3 class="feature-card-title">Pencatatan Transaksi</h3>
                        <p class="feature-card-desc">
                            Catat pengeluaran dan pemasukan cukup dengan mengetik santai di chat Telegram tanpa harus
                            hafal kode atau format kaku.
                        </p>
                    </div>
                </div>

                <!-- Feature 2: Ringkasan Keuangan -->
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper" style="background:#ede9fe; color:#8b5cf6;">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <h3 class="feature-card-title">Ringkasan Keuangan</h3>
                        <p class="feature-card-desc">
                            Dapatkan gambaran arus kas, saldo harian/bulanan, dan laba-rugi usaha secara instan dan
                            selalu ter-update secara otomatis.
                        </p>
                    </div>
                </div>

                <!-- Feature 3: Laporan & Analisis -->
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper" style="background:#dbeafe; color:#2563eb;">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h3 class="feature-card-title">Laporan & Analisis</h3>
                        <p class="feature-card-desc">
                            Visualisasi grafik tren belanja, breakdown kategori terbanyak, dan insight AI untuk memotong
                            pengeluaran yang tidak perlu.
                        </p>
                    </div>
                </div>

                <!-- Feature 4: Multi Akun Kas & Bank -->
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper" style="background:#ffedd5; color:#f97316;">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <h3 class="feature-card-title">Multi Akun Kas & Bank</h3>
                        <p class="feature-card-desc">
                            Kelola Kas Tunai toko, rekening BCA, Mandiri, hingga e-Wallet secara terpisah. Saldo otomatis
                            ter-update setiap ada transaksi baru.
                        </p>
                    </div>
                </div>

                <!-- Feature 5: AI Natural Language -->
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper" style="background:#cffafe; color:#0891b2;">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </div>
                        <h3 class="feature-card-title">Normalisasi Nominal AI</h3>
                        <p class="feature-card-desc">
                            AI secara cerdas memahami nominal sehari-hari seperti <code>120k</code>, <code>23rb</code>,
                            <code>1.5jt</code> hingga multi-transaksi dalam satu chat.
                        </p>
                    </div>
                </div>

                <!-- Feature 6: Sinkronisasi Multi-Device -->
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper" style="background:#e0e7ff; color:#4f46e5;">
                            <i class="fa-solid fa-arrows-rotate"></i>
                        </div>
                        <h3 class="feature-card-title">Sinkronisasi Realtime</h3>
                        <p class="feature-card-desc">
                            Semua data yang dicatat di Telegram langsung muncul di Web Dashboard secara real-time, dapat
                            diakses dari laptop, tablet, dan smartphone.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Simulator Section -->
    <section class="showcase-container" id="live-demo">
        <div class="demo-section px-4 px-md-5">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="section-badge mb-2">Coba Langsung</div>
                    <h2 class="section-heading mb-3">
                        Ketik Apapun, AI Langsung Paham.
                    </h2>
                    <p class="text-muted mb-3">
                        Cobalah mengetik kalimat pengeluaran atau pemasukan sehari-hari di kolom demo simulasi berikut
                        untuk melihat bagaimana FinBot merapikan transaksimu.
                    </p>

                    <!-- Dual Target Tabs: Pemilik Usaha & Pribadi -->
                    <div class="mb-4">
                        <div class="tab-content" id="promptTabContent">
                            <!-- Pemilik Usaha Prompts -->
                            <div class="tab-pane fade show active" id="prompts-usaha" role="tabpanel">
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="quick-tag-btn" onclick="testPrompt('jual 450rb catering siang')">jual 450rb catering siang</button>
                                    <button class="quick-tag-btn" onclick="testPrompt('kulakan minyak 2 kardus 320k')">kulakan minyak 2 kardus 320k</button>
                                    <button class="quick-tag-btn" onclick="testPrompt('gaji kasir mingguan 600rb')">gaji kasir mingguan 600rb</button>
                                    <button class="quick-tag-btn" onclick="testPrompt('makan siang geprek 25k')">makan siang geprek 25k</button>
                                    <button class="quick-tag-btn" onclick="testPrompt('gajian masuk 6.5jt')">gajian masuk 6.5jt</button>
                                    <button class="quick-tag-btn" onclick="testPrompt('nongkrong 45rb dan parkir 2k')">nongkrong 45rb & parkir 2k</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="<?= base_url('finbot/dashboard') ?>" class="btn-finbot-primary">
                        <i class="fa-solid fa-table-columns me-1"></i> Buka Full Dashboard
                    </a>
                </div>

                <div class="col-lg-7">
                    <div class="demo-chat-box">
                        <div class="d-flex align-items-center justify-content-between pb-3 border-bottom mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:10px; height:10px; background:#10b981; border-radius:50%;"></div>
                                <span class="fw-bold" style="font-size:0.9rem;">FinBot AI Telegram Preview</span>
                            </div>
                            <span
                                class="badge bg-primary-subtle text-primary border border-primary-subtle">Online</span>
                        </div>

                        <div class="demo-chat-stream" id="demoStream">
                            <div class="demo-bubble bot">
                                👋 Halo! Saya FinBot. Silakan ketik transaksi harianmu, contoh: <b>kulakan minyak 320k</b>, <b>kopi susu 22k</b>, atau <b>terima transfer 1.8jt</b>.
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <input type="text" id="demoInput" class="form-control rounded-pill px-3"
                                placeholder="Ketik transaksi (contoh: 25k makan siang)..."
                                onkeypress="if(event.key==='Enter') sendDemoChat()">
                            <button class="btn btn-primary rounded-pill px-4" onclick="sendDemoChat()">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section class="features-section" id="keunggulan">
        <div class="showcase-container">
            <div class="text-center max-w-700 mx-auto mb-5">
                <div class="section-badge">Kenapa FinBot?</div>
                <h2 class="section-heading">Hemat Waktu 10x Lipat Dibanding Cara Lama</h2>
                <p class="section-subheading">Tinggalkan buku catatan manual dan rumus spreadsheet yang memusingkan.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="p-4 rounded-4 bg-light border h-100">
                        <h4 class="fw-bold text-danger mb-3 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-xmark"></i> Cara Lama (Buku Kas & Excel)
                        </h4>
                        <ul class="list-unstyled d-flex flex-column gap-3 text-muted">
                            <li class="d-flex align-items-start gap-2">
                                <i class="fa-solid fa-xmark text-danger mt-1"></i>
                                <span>Sering lupa mencatat struk belanja karena ribet harus buka laptop.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fa-solid fa-xmark text-danger mt-1"></i>
                                <span>Harus input manual ke banyak kolom Excel & rawan rumus error.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fa-solid fa-xmark text-danger mt-1"></i>
                                <span>Tidak ada insight otomatis jika pengeluaran usaha sedang membengkak.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="p-4 rounded-4 border h-100"
                        style="background:#eff6ff; border-color:#bfdbfe !important;">
                        <h4 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-check"></i> Menggunakan FinBot AI
                        </h4>
                        <ul class="list-unstyled d-flex flex-column gap-3 text-dark">
                            <li class="d-flex align-items-start gap-2">
                                <i class="fa-solid fa-check text-primary mt-1 fw-bold"></i>
                                <span><b>Chat 3 Detik:</b> Cukup kirim chat singkat lewat Telegram saat bayar di
                                    kasir.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fa-solid fa-check text-primary mt-1 fw-bold"></i>
                                <span><b>Otomatis & Terstruktur:</b> AI menggolongkan kategori, tanggal, dan nominal
                                    seketika.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fa-solid fa-check text-primary mt-1 fw-bold"></i>
                                <span><b>Laporan Cerdas:</b> Dashboard visual siap pakai untuk pantau arus kas bisnis
                                    kapan saja.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing / Paket Section -->
    <section class="features-section py-5" id="harga"
        style="background: linear-gradient(180deg, #ffffff 0%, #f0f7ff 100%);">
        <div class="showcase-container">
            <div class="text-center mb-5">
                <div class="section-badge" style="background:#dbeafe; color:#2563eb;">Model Monetisasi Terjangkau</div>
                <h2 class="section-heading">Pilih Paket yang Pas untuk Kebutuhanmu</h2>
                <p class="text-muted max-w-600 mx-auto" style="max-width:620px; font-size:1.05rem;">
                    Bukan software akuntansi yang rumit, melainkan <b>kemudahan mencatat keuangan</b>. Mulai gratis tanpa ribet.
                </p>

                <!-- Billing Cycle Toggle (Bulanan / Tahunan) -->
                <div class="d-inline-flex align-items-center gap-2 p-1 bg-white rounded-pill border shadow-sm mt-3 position-relative">
                    <button class="btn rounded-pill px-4 py-2 fw-bold btn-sm active" id="btnCycleMonthly" onclick="setBillingCycle('monthly')" style="background:#2563eb; color:#fff;">
                        Bulanan
                    </button>
                    <button class="btn rounded-pill px-4 py-2 fw-bold btn-sm" id="btnCycleYearly" onclick="setBillingCycle('yearly')">
                        Tahunan <span class="badge bg-success text-white rounded-pill ms-1" id="badgehemat" style="font-size:0.72rem;">Hemat 17%</span>
                    </button>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <!-- 1. Paket Gratis -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 rounded-4 p-4 shadow-sm position-relative d-flex flex-column justify-content-between"
                        style="background:#ffffff; border:1px solid #e2e8f0 !important;">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-light text-dark px-3 py-2 rounded-pill fw-semibold">FinBot Free</span>
                                <span class="text-muted small">Coba / Personal</span>
                            </div>
                            <div class="my-3">
                                <span class="display-6 fw-bold text-dark">Rp 0</span>
                                <span class="text-muted">/ selamanya</span>
                            </div>
                            <div class="small text-muted mb-3">Cocok untuk mulai membiasakan diri mencatat keuangan harian.</div>

                            <hr class="opacity-25 my-3">

                            <ul class="list-unstyled d-flex flex-column gap-2 mb-4 text-secondary small">
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-check text-success"></i> <b>50 transaksi / bulan</b>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-check text-success"></i> Telegram Bot AI (@erka_finbot)
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-check text-success"></i> Natural Language Parser
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-check text-success"></i> Web Dashboard Ringkas
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-check text-success"></i> Laporan Arus Kas Dasar
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-check text-success"></i> Kategori Dasar Finansial
                                </li>
                            </ul>
                        </div>

                        <a href="https://t.me/erka_finbot" target="_blank"
                            class="btn btn-outline-primary rounded-pill py-2 fw-bold w-100">
                            Mulai Gratis Sekarang
                        </a>
                    </div>
                </div>

                <!-- 2. Paket Pro (Warung) - POPULER -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-2 rounded-4 p-4 shadow-md position-relative d-flex flex-column justify-content-between"
                        style="background:#ffffff; border-color:#2563eb !important;">
                        <div
                            class="position-absolute top-0 start-50 translate-middle badge bg-primary px-3 py-2 rounded-pill shadow-sm">
                            PALING DIREKOMENDASIKAN
                        </div>
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3 mt-2">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold">FinBot Pro (Warung)</span>
                                <span class="text-primary small fw-semibold">UMKM Mikro & Aktif</span>
                            </div>
                            <div class="my-3">
                                <span id="pricePro" class="display-6 fw-bold text-primary">Rp 9.900</span>
                                <span id="periodPro" class="text-muted">/ bulan</span>
                            </div>
                            <div id="subPro" class="small text-muted mb-3">Ditagih bulanan (Rp9.900/bln)</div>

                            <hr class="opacity-25 my-3">

                            <ul class="list-unstyled d-flex flex-column gap-2 mb-4 text-dark small">
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-primary"></i> <b>500 transaksi / bulan</b>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-primary"></i> <b>Unlimited Chat & Multi-Transaksi</b>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-primary"></i> Laporan Bulanan & Grafik Cashflow
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-primary"></i> Custom Kategori & Multi Akun
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-primary"></i> Export Laporan ke Excel / CSV
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-primary"></i> Riwayat & Edit Transaksi Lengkap
                                </li>
                            </ul>
                        </div>

                        <a href="https://t.me/erka_finbot" target="_blank"
                            class="btn btn-outline-dark rounded-pill py-2 fw-bold w-100">
                            Langganan
                        </a>
                    </div>
                </div>

                <!-- 3. Paket Pro+ (Usaha) -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 rounded-4 p-4 shadow-sm position-relative d-flex flex-column justify-content-between"
                        style="background:#ffffff; border:1px solid #e2e8f0 !important;">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-purple-subtle text-purple px-3 py-2 rounded-pill fw-bold"
                                    style="background:#f3e8ff; color:#7e22ce;">FinBot Pro+ (Usaha)</span>
                                <span class="text-muted small">Toko Ramai & Bisnis</span>
                            </div>
                            <div class="my-3">
                                <span id="priceProPlus" class="display-6 fw-bold text-dark">Rp 19.900</span>
                                <span id="periodProPlus" class="text-muted">/ bulan</span>
                            </div>
                            <div id="subProPlus" class="small text-muted mb-3">Ditagih bulanan (Rp19.900/bln)</div>

                            <hr class="opacity-25 my-3">

                            <ul class="list-unstyled d-flex flex-column gap-2 mb-4 text-secondary small">
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-check text-success"></i> <b>2.000 transaksi / bulan</b>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-check text-success"></i> <b>Semua Fitur Pro Lengkap</b>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-check text-success"></i> Unlimited Kategori Transaksi
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-check text-success"></i> Laporan Mingguan & Bulanan Otomatis
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-check text-success"></i> Analisis Pengeluaran Terbesar
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-check text-success"></i> Prioritas Bantuan & Integrasi
                                </li>
                            </ul>
                        </div>

                        <a href="https://t.me/erka_finbot" target="_blank"
                            class="btn btn-outline-dark rounded-pill py-2 fw-bold w-100">
                            Langganan
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i class="fa-brands fa-telegram text-primary me-1"></i> <b>Semua pencatatan transaksi menggunakan bot Telegram @erka_finbot tanpa perlu install aplikasi baru.</b>
                </p>
            </div>
        </div>
    </section>

    <!-- Testimoni Section -->
    <section class="features-section bg-light py-5" id="testimoni">
        <div class="showcase-container">
            <div class="text-center mb-5">
                <div class="section-badge">Pengalaman Pengguna</div>
                <h2 class="section-heading">Kata Mereka yang Menggunakan FinBot</h2>
                <p class="section-subheading">Cerita jujur dari pemilik usaha dan pengguna aktif sehari-hari.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testi-card">
                        <div class="testi-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                        </div>
                        <p class="testi-text">
                            "Dulu paling males nyatet belanjaan harian warung kopi karena harus buka excel malam-malam. Sekarang pas belanja biji kopi atau beli galon, langsung chat di Telegram sambil jalan. Pas buka dashboard sorenya, arus kas udah langsung rapi."
                        </p>
                        <div class="testi-user">
                            <div class="testi-avatar">R</div>
                            <div>
                                <div class="fw-bold text-dark">Rian Santoso</div>
                                <div class="small text-muted">Owner Kedai Kopi Seduh</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="testi-card">
                        <div class="testi-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                        </div>
                        <p class="testi-text">
                            "Ngebantu banget buat jualan online. Tiap ada transferan masuk dari customer, tinggal ketik 'masuk transfer bca 1.8jt orderan hijab'. Gak perlu mikir kode-kodean akun atau rumus akuntansi rumit, bot langsung catat ke penjualan."
                        </p>
                        <div class="testi-user">
                            <div class="testi-avatar">D</div>
                            <div>
                                <div class="fw-bold text-dark">Dewi Anggraeni</div>
                                <div class="small text-muted">Owner Hijab & Boutique Online</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="testi-card">
                        <div class="testi-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                        </div>
                        <p class="testi-text">
                            "Fitur multi-transaksinya mantap pol. Pas habis makan siang terus bayar parkir, tinggal kirim 'makan geprek 25k dan parkir 3rb' langsung otomatis kepisah jadi 2 pengeluaran. Pengeluaran bulanan pribadi jadi ke-track jelas tanpa ada yang bocor."
                        </p>
                        <div class="testi-user">
                            <div class="testi-avatar">D</div>
                            <div>
                                <div class="fw-bold text-dark">Dimas Pratama</div>
                                <div class="small text-muted">Freelancer & Personal Expense</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="features-section" id="faq">
        <div class="showcase-container">
            <div class="text-center mb-5">
                <div class="section-badge">FAQ</div>
                <h2 class="section-heading">Pertanyaan yang Sering Diajukan</h2>
            </div>

            <div class="max-w-800 mx-auto" style="max-width: 800px;">
                <div class="faq-item active">
                    <button class="faq-button" onclick="toggleFaq(this)">
                        <span>Bagaimana cara menghubungkan FinBot dengan akun Telegram?</span>
                        <i class="fa-solid fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-content">
                        Cukup klik tombol 'Mulai Sekarang', buka link bot Telegram FinBot (<b>@erka_finbot</b>), dan tekan <b>/start</b>. Bot akan langsung terhubung ke dashboard akun bisnis Anda.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button" onclick="toggleFaq(this)">
                        <span>Apakah format chat harus baku dan sesuai aturan tertentu?</span>
                        <i class="fa-solid fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-content">
                        Tidak sama sekali! Tagline kami adalah <b>"Nggak perlu format. Chat aja."</b> Anda bisa mengetik
                        '120k bensin', 'beli beras 50rb', atau 'masuk 2jt'. AI FinBot otomatis mengerti nominal dan kategorinya.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button" onclick="toggleFaq(this)">
                        <span>Apakah data keuangan bisnis saya aman?</span>
                        <i class="fa-solid fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-content">
                        Sangat aman. Seluruh data keuangan dienkripsi dan disimpan dalam database terisolasi untuk tiap bisnis dengan token akses khusus.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-button" onclick="toggleFaq(this)">
                        <span>Bisakah mencatat beberapa pengeluaran sekaligus dalam 1 pesan?</span>
                        <i class="fa-solid fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-content">
                        Bisa! AI FinBot memiliki kemampuan Multi-Transaction Parser. Contoh: "Beli bensin 50rb dan makan siang 35rb" akan otomatis dipecah menjadi 2 transaksi terpisah.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Banner Section -->
    <section class="showcase-container">
        <div class="cta-banner">
            <h2 class="fw-bold fs-1 mb-3">Siap Mengelola Keuangan dengan Cerdas?</h2>
            <p class="fs-5 text-white max-w-600 mx-auto mb-4" style="max-width: 600px;">
                Tingkatkan produktivitas bisnis dan pantau arus kas Anda tanpa repot. Cukup chat, AI yang merapikan.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="https://t.me/erka_finbot" target="_blank"
                    class="btn btn-light rounded-pill px-4 py-3 fw-bold text-primary shadow">
                    <i class="fa-solid fa-paper-plane me-2"></i> Coba FinBot Sekarang — Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-showcase">
        <div class="showcase-container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <div class="logo-bubble"
                    style="width:32px; height:32px; font-size:0.9rem; background:#2563eb; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <span class="fw-bold text-dark">FinBot</span> &copy; <?= date('Y') ?>. All rights reserved.
            </div>
            <div class="d-flex gap-4">
                <a href="#fitur" class="text-decoration-none text-muted">Fitur</a>
                <a href="#keunggulan" class="text-decoration-none text-muted">Kenapa</a>
                <a href="#faq" class="text-decoration-none text-muted">FAQ</a>
                <a href="#harga" class="text-decoration-none text-muted">Harga</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 40) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // FAQ Toggle
        function toggleFaq(btn) {
            const item = btn.parentElement;
            item.classList.toggle('active');
        }

        // Billing Cycle Toggle (Bulanan / Tahunan)
        let activeCycle = 'monthly';

        function setBillingCycle(cycle) {
            activeCycle = cycle;
            const btnM = document.getElementById('btnCycleMonthly');
            const btnY = document.getElementById('btnCycleYearly');
            const pricePro = document.getElementById('pricePro');
            const periodPro = document.getElementById('periodPro');
            const subPro = document.getElementById('subPro');
            const priceProPlus = document.getElementById('priceProPlus');
            const periodProPlus = document.getElementById('periodProPlus');
            const subProPlus = document.getElementById('subProPlus');

            if (cycle === 'yearly') {
                btnY.classList.add('active');
                btnY.style.background = '#2563eb';
                btnY.style.color = '#fff';
                btnM.classList.remove('active');
                btnM.style.background = 'transparent';
                btnM.style.color = '#0f172a';

                if (pricePro) pricePro.textContent = 'Rp 99.000';
                if (periodPro) periodPro.textContent = '/ tahun';
                if (subPro) subPro.innerHTML = 'Setara <b>Rp8.250/bln</b> (Hemat Rp19.800/thn)';

                if (priceProPlus) priceProPlus.textContent = 'Rp 199.000';
                if (periodProPlus) periodProPlus.textContent = '/ tahun';
                if (subProPlus) subProPlus.innerHTML = 'Setara <b>Rp16.580/bln</b> (Hemat Rp39.800/thn)';
            } else {
                btnM.classList.add('active');
                btnM.style.background = '#2563eb';
                btnM.style.color = '#fff';
                btnY.classList.remove('active');
                btnY.style.background = 'transparent';
                btnY.style.color = '#0f172a';

                if (pricePro) pricePro.textContent = 'Rp 9.900';
                if (periodPro) periodPro.textContent = '/ bulan';
                if (subPro) subPro.textContent = 'Ditagih bulanan (Rp9.900/bln)';

                if (priceProPlus) priceProPlus.textContent = 'Rp 19.900';
                if (periodProPlus) periodProPlus.textContent = '/ bulan';
                if (subProPlus) subProPlus.textContent = 'Ditagih bulanan (Rp19.900/bln)';
            }
        }

        // WhatsApp Upgrade Request
        function requestUpgradeWA(planName) {
            const adminPhone = '6289520305077';
            const cycleText = activeCycle === 'yearly' ? 'Tahunan' : 'Bulanan';
            const msg = `Halo Admin FinBot, saya ingin upgrade akun ke paket:\n`
                + `• Paket: ${planName}\n`
                + `• Periode: ${cycleText}\n\n`
                + `Mohon dibantu langkah pembayarannya. Terima kasih!`;
            const waUrl = `https://wa.me/${adminPhone}?text=${encodeURIComponent(msg)}`;
            window.open(waUrl, '_blank');
        }

        // Demo Interactive Simulator
        function testPrompt(text) {
            document.getElementById('demoInput').value = text;
            sendDemoChat();
        }

        function sendDemoChat() {
            const input = document.getElementById('demoInput');
            const text = input.value.trim();
            if (!text) return;

            const stream = document.getElementById('demoStream');

            // Add user bubble
            const userBubble = document.createElement('div');
            userBubble.className = 'demo-bubble user';
            userBubble.textContent = text;
            stream.appendChild(userBubble);
            input.value = '';
            stream.scrollTop = stream.scrollHeight;

            // Typing indicator
            const typingBubble = document.createElement('div');
            typingBubble.className = 'demo-bubble bot';
            typingBubble.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-primary me-2"></i> AI menganalisis transaksi...';
            stream.appendChild(typingBubble);
            stream.scrollTop = stream.scrollHeight;

            // Simulate intelligent NLP parsing response
            setTimeout(() => {
                let responseHTML = '';
                const lower = text.toLowerCase();

                // Multi-transaction recognition
                if (lower.includes(' dan ') || lower.includes(' & ')) {
                    if (lower.includes('kantong kresek') || lower.includes('nota')) {
                        responseHTML = `Saya memahami <b>2 transaksi</b>:<br><br>
                        🔴 Kantong kresek — <b>Rp35.000</b> (Operasional)<br>
                        🔴 Nota toko — <b>Rp15.000</b> (Operasional)<br><br>
                        🔴 Total Pengeluaran: <b>Rp50.000</b><br>
                        <small class="text-success">✅ Berhasil disimpan ke Dashboard.</small>`;
                    } else if (lower.includes('nongkrong') || lower.includes('parkir')) {
                        responseHTML = `Saya memahami <b>2 transaksi</b>:<br><br>
                        🔴 Nongkrong — <b>Rp45.000</b> (Hiburan & Makan)<br>
                        🔴 Parkir — <b>Rp2.000</b> (Transportasi)<br><br>
                        🔴 Total Pengeluaran: <b>Rp47.000</b><br>
                        <small class="text-success">✅ Berhasil disimpan ke Dashboard.</small>`;
                    } else {
                        responseHTML = `Saya memahami <b>2 transaksi</b>:<br><br>
                        🔴 Pengeluaran 1 tercatat otomatis<br>
                        🔴 Pengeluaran 2 tercatat otomatis<br><br>
                        <small class="text-success">✅ Multi-transaksi tersimpan ke sistem.</small>`;
                    }
                } else if (lower.includes('rekap') || lower.includes('laporan') || lower.includes('saldo')) {
                    responseHTML = `📊 <b>Ringkasan Finansial Bulan Ini</b><br>
                    • Total Pemasukan: <span class="text-success fw-bold">Rp12.450.000</span><br>
                    • Total Pengeluaran: <span class="text-danger fw-bold">Rp4.230.000</span><br>
                    • Laba Bersih: <span class="text-primary fw-bold">Rp8.220.000</span><br>
                    • Saldo Kas: <span class="text-dark fw-bold">Rp15.700.000</span><br>
                    <small class="text-muted">✅ Data tersinkronisasi dengan dashboard.</small>`;
                } else if (lower.includes('jual') || lower.includes('transfer') || lower.includes('gajian') || lower.includes('masuk') || lower.includes('omzet')) {
                    let amount = 'Rp1.800.000';
                    let category = 'Penjualan Produk';
                    let desc = text;

                    if (lower.includes('450rb') || lower.includes('450k')) { amount = 'Rp450.000'; category = 'Penjualan / Catering'; }
                    else if (lower.includes('1.8jt') || lower.includes('1800k')) { amount = 'Rp1.800.000'; category = 'Penjualan Orderan Kue'; }
                    else if (lower.includes('6.5jt') || lower.includes('6500k')) { amount = 'Rp6.500.000'; category = 'Gaji Bulanan'; }
                    else if (lower.includes('2jt')) { amount = 'Rp2.000.000'; category = 'Penjualan'; }

                    responseHTML = `🟢 <b>Pemasukan Berhasil Dicatat</b><br>
                    💰 Nominal: <b>${amount}</b><br>
                    🏷 Kategori: <b>${category}</b><br>
                    📝 Keterangan: <i>${desc}</i><br>
                    <small class="text-success">✅ Saldo kas otomatis bertambah.</small>`;
                } else if (lower.includes('kulakan') || lower.includes('minyak') || lower.includes('listrik') || lower.includes('gaji') || lower.includes('kopi') || lower.includes('bensin') || lower.includes('geprek') || lower.includes('paket data') || lower.includes('beli') || lower.includes('keluar')) {
                    let amount = 'Rp50.000';
                    let category = 'Operasional';
                    let desc = text;

                    if (lower.includes('320k') || lower.includes('320rb')) { amount = 'Rp320.000'; category = 'Stok Bahan Baku (Minyak)'; }
                    else if (lower.includes('250rb') || lower.includes('250k')) { amount = 'Rp250.000'; category = 'Utilitas & Listrik Ruko'; }
                    else if (lower.includes('600rb') || lower.includes('600k')) { amount = 'Rp600.000'; category = 'Gaji Karyawan Kasir'; }
                    else if (lower.includes('22k') || lower.includes('22rb')) { amount = 'Rp22.000'; category = 'Makanan & Minuman'; }
                    else if (lower.includes('30rb') || lower.includes('30k')) { amount = 'Rp30.000'; category = 'Bahan Bakar & Transportasi'; }
                    else if (lower.includes('25k') || lower.includes('25rb')) { amount = 'Rp25.000'; category = 'Makan Siang'; }
                    else if (lower.includes('100rb') || lower.includes('100k')) { amount = 'Rp100.000'; category = 'Komunikasi & Internet'; }

                    responseHTML = `🔴 <b>Pengeluaran Berhasil Dicatat</b><br>
                    💰 Nominal: <b>${amount}</b><br>
                    🏷 Kategori: <b>${category}</b><br>
                    📝 Keterangan: <i>${desc}</i><br>
                    <small class="text-success">✅ Tersimpan ke Database & Dashboard.</small>`;
                } else {
                    responseHTML = `🤖 <b>FinBot NLP Parser</b><br>
                    Pesan Anda: "<i>${text}</i>"<br>
                    ✅ Transaksi berhasil diidentifikasi dan disimpan ke sistem.`;
                }

                typingBubble.innerHTML = responseHTML;
                stream.scrollTop = stream.scrollHeight;
            }, 600);
        }
    </script>
</body>

</html>