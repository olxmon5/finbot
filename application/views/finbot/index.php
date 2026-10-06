<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'FinBot — Dashboard Hasil Isian' ?></title>

    <!-- Meta Tags SEO -->
    <meta name="title" content="FinBot — Dashboard Hasil Isian Keuangan">
    <meta name="description" content="Dashboard pembukuan dan analisis finansial otomatis dari FinBot AI Assistant.">
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

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">

    <style>
        :root {
            --finbot-blue: #2563eb;
            --finbot-blue-dark: #1d4ed8;
            --finbot-blue-light: #eff6ff;
            --finbot-navy: #0f172a;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --income-green: #10b981;
            --income-bg: #ecfdf5;
            --expense-red: #ef4444;
            --expense-bg: #fef2f2;
            --card-shadow: 0 10px 30px -5px rgba(37, 99, 235, 0.08);
            --card-shadow-hover: 0 20px 40px -8px rgba(37, 99, 235, 0.16);
            --card-border: #f1f5f9;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: var(--text-dark);
            overflow-x: hidden;
            min-height: 100vh;
            padding-bottom: 70px;
        }

        @media (min-width: 992px) {
            body {
                padding-bottom: 30px;
            }
        }

        /* Container Limit (Matching showcase.php) */
        .showcase-container {
            width: 100%;
            max-width: 1330px;
            margin: 0 auto;
            padding: 0 16px;
        }

        @media (min-width: 768px) {
            .showcase-container {
                padding: 0 28px;
            }
        }

        /* Top Navbar */
        .navbar-dash {
            padding: 14px 0;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.45rem;
            letter-spacing: -0.5px;
        }

        .brand-logo .logo-bubble {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.15rem;
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

        /* Buttons matching showcase.php */
        .btn-finbot-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 9px 20px;
            border-radius: 9999px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.28);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-finbot-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.38);
        }

        .btn-finbot-secondary {
            background: #ffffff;
            color: var(--finbot-blue);
            font-weight: 700;
            font-size: 0.9rem;
            padding: 8px 18px;
            border-radius: 9999px;
            border: 1.5px solid #bfdbfe;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-finbot-secondary:hover {
            background: #eff6ff;
            color: var(--finbot-blue-dark);
            border-color: var(--finbot-blue);
            transform: translateY(-2px);
        }

        /* Token Pill Badge & Quota Widget Styles */
        .token-badge-container {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1.5px solid #dbeafe;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.82rem;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.06);
            transition: all 0.2s ease;
            max-width: 100%;
        }

        .token-badge-container:hover {
            border-color: var(--finbot-blue);
        }

        .token-badge-container .token-code {
            font-weight: 800;
            color: var(--finbot-blue);
            font-family: monospace;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            word-break: break-all;
        }

        .token-code {
            font-family: monospace;
            letter-spacing: 0.5px;
            color: var(--finbot-blue);
        }

        .border-lg-end {
            border-right: none;
        }

        .border-top-mobile {
            border-top: 1px solid #f1f5f9;
        }

        @media (min-width: 992px) {
            .border-lg-end {
                border-right: 1px solid #f1f5f9 !important;
            }
            .border-top-mobile {
                border-top: none !important;
            }
        }

        /* Nav Tabs Custom */
        .nav-tabs-showcase {
            display: flex;
            gap: 8px;
            border-bottom: none;
        }

        .nav-tabs-showcase .nav-link {
            border-radius: 9999px;
            font-weight: 700;
            font-size: 0.92rem;
            color: var(--text-muted);
            padding: 10px 22px;
            border: 1px solid transparent;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .nav-tabs-showcase .nav-link:hover {
            color: var(--finbot-blue);
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        .nav-tabs-showcase .nav-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.3);
            border-color: transparent;
        }

        @media (max-width: 575.98px) {
            .nav-tabs-showcase {
                width: 100%;
                background: #e2e8f0;
                padding: 4px;
                border-radius: 9999px;
                gap: 4px;
            }

            .nav-tabs-showcase .nav-item {
                flex: 1;
            }

            .nav-tabs-showcase .nav-link {
                width: 100%;
                padding: 8px 12px;
                font-size: 0.86rem;
                background: transparent;
                box-shadow: none;
            }

            .nav-tabs-showcase .nav-link.active {
                background: #ffffff;
                color: var(--finbot-blue);
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            }
        }

        /* Cards Standard */
        .card-showcase {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card-showcase:hover {
            box-shadow: var(--card-shadow-hover);
        }

        /* Metric Summary Cards */
        .metric-card-pro {
            padding: 22px 20px;
            border-radius: 20px;
            background: #ffffff;
            border: 1px solid #f1f5f9;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .metric-card-pro:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-shadow-hover);
        }

        .metric-icon-circle {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 12px;
            flex-shrink: 0;
        }

        .metric-icon-circle.blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .metric-icon-circle.green {
            background: #dcfce7;
            color: #10b981;
        }

        .metric-icon-circle.red {
            background: #fee2e2;
            color: #ef4444;
        }

        .metric-icon-circle.purple {
            background: #f3e8ff;
            color: #8b5cf6;
        }

        .metric-title-small {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .metric-value-huge {
            font-size: clamp(1.15rem, 2.2vw, 1.6rem);
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
            line-height: 1.2;
            word-break: break-word;
        }

        .metric-sub-badge {
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        @media (max-width: 575.98px) {
            .metric-card-pro {
                padding: 14px 12px;
                border-radius: 16px;
            }

            .metric-icon-circle {
                width: 34px;
                height: 34px;
                font-size: 0.95rem;
                border-radius: 10px;
                margin-bottom: 8px;
            }

            .metric-title-small {
                font-size: 0.7rem;
                margin-bottom: 2px;
            }

            .metric-value-huge {
                font-size: clamp(0.98rem, 4.4vw, 1.25rem);
            }

            .metric-sub-badge {
                font-size: 0.68rem;
            }
        }

        /* Banner Hero & Sample Notice */
        .dash-hero-banner {
            background: radial-gradient(circle at 90% 20%, rgba(219, 234, 254, 0.9) 0%, rgba(238, 246, 255, 0.6) 45%, #ffffff 80%);
            border: 1px solid #bfdbfe;
            border-radius: 22px;
            padding: 24px 26px;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px -5px rgba(37, 99, 235, 0.08);
        }

        .sample-alert-banner {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1.5px solid #fde68a;
            border-radius: 18px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* Transaction Item & Badges */
        .tx-badge-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .tx-badge-circle.income {
            background: #dcfce7;
            color: #10b981;
        }

        .tx-badge-circle.expense {
            background: #fee2e2;
            color: #ef4444;
        }

        .table-custom-pro {
            margin-bottom: 0;
        }

        .table-custom-pro th {
            background: #f8fafc;
            color: #475569;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .table-custom-pro td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Mobile Transaction Card List */
        .tx-mobile-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            padding: 14px;
            margin-bottom: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .tx-mobile-card:hover {
            border-color: #dbeafe;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.06);
        }

        /* Floating Action Button (FAB) Mobile */
        .finbot-fab-btn {
            position: fixed;
            bottom: 22px;
            right: 20px;
            z-index: 1040;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            border: none;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.45);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-decoration: none;
            cursor: pointer;
        }

        .finbot-fab-btn:hover,
        .finbot-fab-btn:active {
            transform: scale(1.08);
            color: #ffffff;
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.55);
        }

        /* Modal Customization */
        .modal-content-pro {
            border-radius: 24px;
            border: none;
            box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.3);
            overflow: hidden;
        }

        .modal-header-pro {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            padding: 20px 24px;
        }

        .btn-check-toggle-label {
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .navbar-dash {
                padding: 12px 0;
            }

            .dash-hero-banner {
                padding: 18px 18px;
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

    <!-- Top Navbar (Matching showcase.php) -->
    <nav class="navbar-dash">
        <div class="showcase-container d-flex justify-content-between align-items-center">
            <!-- Brand Logo -->
            <a href="<?= base_url('finbot') ?>" class="brand-logo">
                <div class="logo-bubble">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div>
                    <span class="text-fin">Fin</span><span class="text-bot">Bot</span>
                </div>
            </a>

            <!-- Right Navbar Actions (Desktop & Tablet) -->
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-finbot-primary btn-sm d-none d-md-inline-flex"
                    onclick="openTransactionModal('income')">
                    <i class="fa-solid fa-plus"></i> Catat Transaksi
                </button>
                <a href="https://t.me/erka_finbot" target="_blank" class="btn btn-finbot-secondary btn-sm"
                    title="Buka Telegram Bot @erka_finbot">
                    <i class="fa-brands fa-telegram text-primary"></i> <span class="d-none d-sm-inline">Telegram
                        Bot</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="py-3 py-md-4">
        <div class="showcase-container">

            <!-- Hero Banner: Info Bisnis & Periode Filter -->
            <div class="dash-hero-banner mb-3">
                <div class="row align-items-center g-3">
                    <div class="col-lg-7">
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h2 class="fw-bold mb-0 text-dark"
                                style="letter-spacing: -0.5px; font-size: clamp(1.25rem, 3vw, 1.75rem);">
                                <?= !empty($business->name) ? htmlspecialchars($business->name) : 'Warung Berkah Nusantara' ?>
                            </h2>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 0.92rem;">
                            Mutasi keuangan via Telegram <b>@erka_finbot</b> dan web tersinkronisasi otomatis secara
                            realtime.
                        </p>
                    </div>
                    <div class="col-lg-5 text-lg-end">
                        <div class="dropdown d-inline-block w-100 text-lg-end">
                            <button
                                class="btn btn-white bg-white border dropdown-toggle fw-bold rounded-pill px-3 px-md-4 py-2 shadow-sm w-100 w-lg-auto"
                                type="button" id="periodFilterBtn" data-bs-toggle="dropdown">
                                <i class="fa-regular fa-calendar-days me-1 text-primary"></i>
                                <span id="currentPeriodLabel">Bulan Ini (<?= date('F Y') ?>)</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-4 border-0 w-100 w-lg-auto">
                                <li><a class="dropdown-item fw-semibold py-2" href="javascript:void(0)"
                                        onclick="changePeriod('today', 'Hari Ini')"><i
                                            class="fa-solid fa-clock me-2 text-muted"></i>Hari Ini</a></li>
                                <li><a class="dropdown-item fw-semibold py-2" href="javascript:void(0)"
                                        onclick="changePeriod('this_week', 'Minggu Ini')"><i
                                            class="fa-solid fa-calendar-week me-2 text-muted"></i>Minggu Ini</a></li>
                                <li><a class="dropdown-item fw-semibold py-2 active" href="javascript:void(0)"
                                        onclick="changePeriod('this_month', 'Bulan Ini')"><i
                                            class="fa-solid fa-calendar-days me-2 text-muted"></i>Bulan Ini</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banner Notifikasi Mode Sampel (Jika tanpa token) -->
            <?php if ($is_sample): ?>
                <div class="sample-alert-banner">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning text-dark p-2 flex-shrink-0"
                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                            <i class="fa-solid fa-lightbulb"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Anda sedang melihat Tinjauan Data Sampel (Demo)</h6>
                            <small class="text-secondary">Untuk melihat data pembukuan riil tokomu, masukkan token akses
                                atau ketik <code>/token</code> di bot Telegram @erka_finbot.</small>
                        </div>
                    </div>
                    <div class="w-100 w-sm-auto text-end">
                        <button class="btn btn-warning btn-sm rounded-pill fw-bold px-3 shadow-sm w-100 w-sm-auto"
                            onclick="openTokenModal()">
                            <i class="fa-solid fa-key me-1"></i> Masukkan Token Anda
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Navigation Tabs (Only 2 Main Focused Tabs: Dashboard & Riwayat Transaksi) -->
            <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-2">
                <ul class="nav nav-tabs-showcase" id="dashTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-overview-btn" data-bs-toggle="tab"
                            data-bs-target="#tab-overview" type="button">
                            <i class="fa-solid fa-chart-pie"></i> Ringkasan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-transactions-btn" data-bs-toggle="tab"
                            data-bs-target="#tab-transactions" type="button" onclick="loadTransactionsTable()">
                            <i class="fa-solid fa-receipt"></i> Transaksi
                        </button>
                    </li>
                </ul>

                <!-- Desktop Shortcut to record transaction inside tab header -->
                <div class="d-none d-md-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-finbot-primary" onclick="openTransactionModal('income')">
                        <i class="fa-solid fa-plus me-1"></i> Catat Transaksi
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="dashTabsContent">

                <!-- 1. TAB RINGKASAN & GRAFIK (DASHBOARD HASIL ISIAN) -->
                <div class="tab-pane fade show active" id="tab-overview" role="tabpanel">

                    <!-- Unified Token & Kuota Container (Responsive Web & Mobile) -->
                    <div class="card card-showcase p-3 p-md-3 mb-3 mb-md-4 border-0">
                        <div class="row g-3 align-items-center">
                            <!-- Left: Status Token / Mode Akses -->
                            <div class="col-12 col-lg-5 pe-lg-4 border-lg-end">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <div class="rounded-3 p-2 bg-primary-subtle text-primary flex-shrink-0 d-flex align-items-center justify-content-center"
                                            style="width:36px; height:36px;">
                                            <i class="fa-solid fa-key"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-muted fw-bold text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Akses Token</div>
                                            <?php if (!empty($token) && !$is_sample): ?>
                                                <span class="token-code text-truncate d-block fw-bold" id="displayActiveToken" style="font-size:0.88rem; max-width:180px;">
                                                    <?= htmlspecialchars($token) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark fw-bold" style="font-size:0.72rem;">
                                                    <i class="fa-solid fa-flask me-1"></i> Mode Simulasi Demo
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Aksi Token -->
                                    <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                        <?php if (!empty($token) && !$is_sample): ?>
                                            <button class="btn btn-sm btn-light border text-muted px-2 py-1 rounded-pill" title="Salin Token"
                                                onclick="copyTokenToClipboard('<?= htmlspecialchars($token) ?>')">
                                                <i class="fa-regular fa-copy"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary px-2.5 py-1 rounded-pill fw-bold"
                                                style="font-size:0.75rem;" onclick="openTokenModal()">
                                                Ganti
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold"
                                                style="font-size:0.78rem;" onclick="openTokenModal()">
                                                <i class="fa-solid fa-key me-1"></i> Masukkan Token
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Kuota & Upgrade Paket -->
                            <div class="col-12 col-lg-7 ps-lg-4 pt-2 pt-lg-0 border-top-mobile">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-1">
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <div class="rounded-3 p-2 bg-success-subtle text-success flex-shrink-0 d-flex align-items-center justify-content-center"
                                            style="width:36px; height:36px;">
                                            <i class="fa-solid fa-bolt"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="fw-bold mb-0 text-dark" style="font-size:0.9rem;"
                                                    id="quotaPlanName">FinBot Free</h6>
                                                <span class="badge bg-primary rounded-pill px-2"
                                                    style="font-size:0.68rem;" id="quotaPlanBadge">FREE</span>
                                            </div>
                                            <small class="text-muted text-truncate d-block" style="font-size:0.76rem;"
                                                id="quotaDetailText">Memuat kuota transaksi...</small>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 ms-auto ms-sm-0">
                                        <button class="btn btn-sm btn-finbot-primary rounded-pill px-3 py-1 fw-bold"
                                            style="font-size:0.78rem;" onclick="openUpgradeModal()">
                                            <i class="fa-solid fa-sparkles me-1"></i> Upgrade
                                        </button>
                                    </div>
                                </div>
                                <div class="progress mt-2" style="height: 6px; border-radius: 9999px; background-color: #f1f5f9;">
                                    <div class="progress-bar bg-primary" id="quotaProgressBar" role="progressbar"
                                        style="width: 0%; transition: width 0.4s ease;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4 Metric Cards (Matching Value Props Style) -->
                    <div class="row g-2 g-md-3 mb-3 mb-md-4">
                        <!-- Pemasukan -->
                        <div class="col-6 col-lg-3">
                            <div class="metric-card-pro">
                                <div>
                                    <div class="metric-icon-circle green">
                                        <i class="fa-solid fa-arrow-trend-up"></i>
                                    </div>
                                    <div class="metric-title-small">Total Pemasukan</div>
                                    <div class="metric-value-huge text-success" id="valTotalIncome">Rp 0</div>
                                </div>
                            </div>
                        </div>

                        <!-- Pengeluaran -->
                        <div class="col-6 col-lg-3">
                            <div class="metric-card-pro">
                                <div>
                                    <div class="metric-icon-circle red">
                                        <i class="fa-solid fa-arrow-trend-down"></i>
                                    </div>
                                    <div class="metric-title-small">Total Pengeluaran</div>
                                    <div class="metric-value-huge text-danger" id="valTotalExpense">Rp 0</div>
                                </div>
                            </div>
                        </div>

                        <!-- Laba Bersih -->
                        <div class="col-6 col-lg-3">
                            <div class="metric-card-pro">
                                <div>
                                    <div class="metric-icon-circle purple">
                                        <i class="fa-solid fa-chart-line"></i>
                                    </div>
                                    <div class="metric-title-small">Laba Bersih (Net)</div>
                                    <div class="metric-value-huge text-primary" id="valNetCashflow">Rp 0</div>
                                </div>
                            </div>
                        </div>

                        <!-- Saldo Kas -->
                        <div class="col-6 col-lg-3">
                            <div class="metric-card-pro">
                                <div>
                                    <div class="metric-icon-circle blue">
                                        <i class="fa-solid fa-vault"></i>
                                    </div>
                                    <div class="metric-title-small">Total Saldo Kas</div>
                                    <div class="metric-value-huge text-dark" id="valTotalBalance">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row: Donut Kategori & Trend Arus Kas -->
                    <div class="row g-3 g-md-4 mb-3 mb-md-4">
                        <!-- Donut Chart: Kategori Pengeluaran Otomatis AI -->
                        <div class="col-12 col-lg-5">
                            <div class="card card-showcase p-3 p-md-4 h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">Distribusi Pengeluaran</h6>
                                        <small class="text-muted">Klasifikasi otomatis AI</small>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1 small">AI
                                        Parser</span>
                                </div>
                                <div style="position: relative; height: 230px;">
                                    <canvas id="categoryDonutChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Line Chart: Trend Arus Kas Harian -->
                        <div class="col-12 col-lg-7">
                            <div class="card card-showcase p-3 p-md-4 h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">Tren Arus Kas</h6>
                                        <small class="text-muted">Pemasukan vs Pengeluaran harian</small>
                                    </div>
                                    <span class="badge bg-light text-muted border rounded-pill">Harian</span>
                                </div>
                                <div style="position: relative; height: 230px;">
                                    <canvas id="cashflowTrendChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaksi Terbaru Card -->
                    <div class="card card-showcase p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.1rem;">Hasil Isian Transaksi
                                    Terbaru</h5>
                                <small class="text-muted">Mutasi terkini yang tercatat ke sistem</small>
                            </div>
                            <button class="btn btn-sm btn-link text-primary fw-bold text-decoration-none p-0"
                                onclick="$('#tab-transactions-btn').tab('show'); loadTransactionsTable();">
                                Lihat Semua Transaksi <i class="fa-solid fa-arrow-right ms-1"></i>
                            </button>
                        </div>

                        <div class="list-group list-group-flush" id="recentTransactionsList">
                            <div class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                <span class="ms-2">Memuat data hasil isian...</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- 2. TAB RIWAYAT TRANSAKSI LENGKAP -->
                <div class="tab-pane fade" id="tab-transactions" role="tabpanel">
                    <div class="card card-showcase p-3 p-md-4">
                        <!-- Top Header inside card -->
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">Daftar Riwayat Transaksi</h5>
                                <small class="text-muted">Filter, cari, dan kelola seluruh pembukuan usahamu</small>
                            </div>
                        </div>

                        <!-- Filter Controls -->
                        <div class="row g-2 g-md-3 align-items-center mb-3 pb-3 border-bottom">
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold text-muted mb-1">Pencarian</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                    <input type="text" id="filterSearch" class="form-control border-start-0"
                                        placeholder="Ketik kata kunci / chat..." onkeyup="delaySearchTx()">
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">Jenis</label>
                                <select id="filterType" class="form-select form-select-sm"
                                    onchange="loadTransactionsTable()">
                                    <option value="">Semua</option>
                                    <option value="income">Pemasukan</option>
                                    <option value="expense">Pengeluaran</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">Kategori</label>
                                <select id="filterCategory" class="form-select form-select-sm"
                                    onchange="loadTransactionsTable()">
                                    <option value="">Semua</option>
                                    <option value="Penjualan">Penjualan</option>
                                    <option value="Pendapatan Lain">Pendapatan Lain</option>
                                    <option value="Stok Barang">Stok Barang</option>
                                    <option value="Operasional">Operasional</option>
                                    <option value="Makan & Minum">Makan & Minum</option>
                                    <option value="Transportasi">Transportasi</option>
                                    <option value="Listrik & Air">Listrik & Air</option>
                                    <option value="Gaji">Gaji</option>
                                    <option value="Sewa">Sewa</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">Mulai</label>
                                <input type="date" id="filterStartDate" class="form-control form-control-sm"
                                    onchange="loadTransactionsTable()">
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">Sampai</label>
                                <input type="date" id="filterEndDate" class="form-control form-control-sm"
                                    onchange="loadTransactionsTable()">
                            </div>
                        </div>

                        <!-- Desktop / Tablet Table View (>= 768px) -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover align-middle table-custom-pro">
                                <thead>
                                    <tr>
                                        <th style="width: 45px;">#</th>
                                        <th>Tanggal</th>
                                        <th>Jenis</th>
                                        <th>Kategori</th>
                                        <th>Keterangan / Chat Asli</th>
                                        <th>Sumber</th>
                                        <th class="text-end">Nominal</th>
                                        <th class="text-center" style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="transactionsTableBody">
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">Memuat data transaksi...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card List View (< 768px) -->
                        <div class="d-md-none" id="transactionsMobileCards">
                            <div class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                <span class="ms-2">Memuat data transaksi...</span>
                            </div>
                        </div>

                        <!-- Footer Info & Export -->
                        <div
                            class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top flex-wrap gap-2">
                            <span class="small text-muted" id="txTableInfoText">Menampilkan data transaksi</span>
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold"
                                onclick="exportCSV()">
                                <i class="fa-solid fa-file-csv me-1"></i> Unduh CSV
                            </button>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- Floating Action Button (FAB) Mobile -->
    <button class="finbot-fab-btn d-md-none" title="Catat Transaksi Cepat" onclick="openTransactionModal('expense')">
        <i class="fa-solid fa-plus"></i>
    </button>

    <!-- ================= MODAL MASUKKAN / GANTI TOKEN ================= -->
    <div class="modal fade" id="modalToken" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-pro">
                <div class="modal-header modal-header-pro">
                    <div>
                        <h5 class="modal-title fw-bold mb-0"><i class="fa-solid fa-key me-2"></i>Akses Token Dashboard
                        </h5>
                        <small class="opacity-75">Buka data pembukuan usahamu dengan kode token</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Masukkan Kode Token Anda</label>
                        <input type="text" id="inputUserToken" class="form-control form-control-lg fw-bold"
                            placeholder="Contoh: FB-WARUNG-01" value="<?= htmlspecialchars($token) ?>">
                        <small class="text-muted d-block mt-2">
                            <i class="fa-solid fa-circle-info text-primary me-1"></i> Dapatkan kode token dengan
                            mengetik perintah <code>/token</code> pada bot Telegram <b>@erka_finbot</b>.
                        </small>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <?php if (!empty($token)): ?>
                            <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill"
                                onclick="clearTokenAccess()">
                                Mode Sampel (Demo)
                            </button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-primary rounded-pill flex-fill fw-bold"
                            onclick="applyUserToken()">
                            Terapkan Token
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= MODAL TRANSAKSI (CATAT / EDIT) ================= -->
    <div class="modal fade" id="modalTransaction" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-pro">
                <div class="modal-header modal-header-pro">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalTxTitle">Catat Transaksi</h5>
                        <small class="opacity-75">Form input pembukuan keuangan</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formTransaction" onsubmit="submitTransaction(event)">
                    <input type="hidden" id="txFormId" name="id">
                    <div class="modal-body p-3 p-md-4">
                        <!-- Jenis Transaksi -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Jenis Transaksi</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="type" id="txTypeIncome" value="income">
                                <label
                                    class="btn btn-outline-success flex-fill fw-bold rounded-pill btn-check-toggle-label"
                                    for="txTypeIncome">
                                    <i class="fa-solid fa-arrow-up me-1"></i> Pemasukan
                                </label>

                                <input type="radio" class="btn-check" name="type" id="txTypeExpense" value="expense"
                                    checked>
                                <label
                                    class="btn btn-outline-danger flex-fill fw-bold rounded-pill btn-check-toggle-label"
                                    for="txTypeExpense">
                                    <i class="fa-solid fa-arrow-down me-1"></i> Pengeluaran
                                </label>
                            </div>
                        </div>

                        <!-- Nominal -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Nominal (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                                <input type="number" inputmode="numeric" step="any" min="100" id="txFormAmount"
                                    name="amount" class="form-control form-control-lg fw-bold" placeholder="0" required>
                            </div>
                        </div>

                        <!-- Kategori Standar -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Kategori</label>
                            <select id="txFormCategory" name="category_name" class="form-select" required>
                                <option value="Penjualan">Penjualan (Pemasukan)</option>
                                <option value="Pendapatan Lain">Pendapatan Lain (Pemasukan)</option>
                                <option value="Stok Barang" selected>Stok Barang & Kulakan (Pengeluaran)</option>
                                <option value="Operasional">Operasional Toko (Pengeluaran)</option>
                                <option value="Makan & Minum">Makan & Minum (Pengeluaran)</option>
                                <option value="Transportasi">Transportasi & Bensin (Pengeluaran)</option>
                                <option value="Listrik & Air">Listrik & Air (Pengeluaran)</option>
                                <option value="Gaji">Gaji & Upah (Pengeluaran)</option>
                                <option value="Sewa">Sewa Tempat (Pengeluaran)</option>
                                <option value="Lainnya">Lainnya (Pengeluaran)</option>
                            </select>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Keterangan / Catatan</label>
                            <input type="text" id="txFormDescription" name="description" class="form-control"
                                placeholder="Contoh: Kulakan beras 2 karung, Bensin motor..." required>
                        </div>

                        <!-- Tanggal & Akun -->
                        <div class="row g-2">
                            <div class="col-6 mb-2">
                                <label class="form-label fw-bold small text-muted">Tanggal</label>
                                <input type="date" id="txFormDate" name="transaction_date" class="form-control" required
                                    value="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label fw-bold small text-muted">Akun / Kas</label>
                                <select id="txFormAccount" name="account_id" class="form-select">
                                    <?php if (!empty($accounts)):
                                        foreach ($accounts as $acc): ?>
                                            <option value="<?= $acc->id ?>"><?= htmlspecialchars($acc->name) ?></option>
                                        <?php endforeach; else: ?>
                                        <option value="1">Kas Tunai (Cash)</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 p-md-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan
                            Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL UPGRADE PAKET ================= -->
    <div class="modal fade" id="modalUpgradePlan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="--bs-modal-width: 50%;">
            <div class="modal-content modal-content-pro">
                <div class="modal-header modal-header-pro">
                    <div>
                        <h5 class="modal-title fw-bold mb-1"><i class="fa-solid fa-gem me-2"></i>Pilihan Paket Langganan FinBot</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3 p-md-4 bg-light">
                    <!-- Billing Cycle Switcher in Modal -->
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center gap-2 p-1 bg-white rounded-pill border shadow-sm position-relative">
                            <button type="button" class="btn rounded-pill px-3 py-1 fw-bold btn-sm active" id="modalCycleMonthly" onclick="setModalBillingCycle('monthly')" style="background:#2563eb; color:#fff;">
                                Bulanan
                            </button>
                            <button type="button" class="btn rounded-pill px-3 py-1 fw-bold btn-sm" id="modalCycleYearly" onclick="setModalBillingCycle('yearly')">
                                Tahunan <span class="badge bg-success text-white rounded-pill ms-1" style="font-size:0.7rem;" id="badgehemat">Hemat ~17%</span>
                            </button>
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- Free Plan -->
                        <div class="col-md-4">
                            <div
                                class="card h-100 border-0 rounded-4 p-3 bg-white shadow-sm d-flex flex-column justify-content-between">
                                <div>
                                    <span
                                        class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 fw-bold">Free</span>
                                    <div>
                                        <span class="display-6 fw-bold mt-2 mb-0">Rp 0</span>
                                        <small class="text-muted">/ selamanya</small>
                                    </div>
                                    <hr class="my-3 opacity-25">
                                    <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-3">
                                        <li><i class="fa-solid fa-check text-success me-1"></i> 50 transaksi / bulan</li>
                                        <li><i class="fa-solid fa-check text-success me-1"></i> Telegram Bot (@erka_finbot)</li>
                                        <li><i class="fa-solid fa-check text-success me-1"></i> Dashboard Web Realtime</li>
                                    </ul>
                                </div>
                                <button class="btn btn-outline-secondary rounded-pill w-100 btn-sm fw-bold disabled">
                                    Paket Dasar Saat Ini
                                </button>
                            </div>
                        </div>

                        <!-- Pro Plan (Warung) -->
                        <div class="col-md-4">
                            <div
                                class="card h-100 border-2 rounded-4 p-3 bg-white shadow d-flex flex-column justify-content-between border-primary position-relative">
                                <div
                                    class="position-absolute top-0 start-50 translate-middle badge bg-primary px-3 py-1 rounded-pill small">
                                    POPULER
                                </div>
                                <div>
                                    <span
                                        class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold mt-2">
                                        Pro (Warung)</span>
                                    <div>
                                        <span class="display-6 fw-bold mt-2 mb-0 text-primary" id="modalPricePro">Rp 9.900</span>
                                        <small class="text-muted" id="modalPeriodPro">/ bulan</small>
                                    </div>
                                    <hr class="my-2 opacity-25">
                                    <ul class="list-unstyled small text-dark d-flex flex-column gap-2 mb-3">
                                        <li><i class="fa-solid fa-circle-check text-primary me-1"></i> <b>500 transaksi / bulan</b></li>
                                        <li><i class="fa-solid fa-circle-check text-primary me-1"></i> Multi-Transaksi Sekaligus</li>
                                        <li><i class="fa-solid fa-circle-check text-primary me-1"></i> Grafik Analisis Finansial</li>
                                        <li><i class="fa-solid fa-circle-check text-primary me-1"></i> Export Data CSV / Excel</li>
                                    </ul>
                                </div>
                                <button class="btn btn-primary rounded-pill w-100 btn-sm fw-bold shadow-sm d-flex align-items-center justify-content-center gap-1"
                                    id="btnUpgradeProModal" onclick="processUpgrade('pro', activeModalCycle)">
                                    Upgrade
                                </button>
                            </div>
                        </div>

                        <!-- Pro+ Plan (Usaha) -->
                        <div class="col-md-4">
                            <div
                                class="card h-100 border-0 rounded-4 p-3 bg-white shadow-sm d-flex flex-column justify-content-between">
                                <div>
                                    <span class="badge rounded-pill px-3 py-1 fw-bold"
                                        style="background:#f3e8ff; color:#7e22ce;"> Pro+ (Usaha)</span>
                                    <div>
                                        <span class="display-6 fw-bold mt-2 mb-0 text-dark" id="modalPriceProPlus">Rp 19.900</span>
                                        <small class="text-muted" id="modalPeriodProPlus">/ bulan</small>
                                    </div>
                                    <hr class="my-2 opacity-25">
                                    <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-3">
                                        <li><i class="fa-solid fa-check text-success me-1"></i> <b>2.000 transaksi / bulan</b></li>
                                        <li><i class="fa-solid fa-check text-success me-1"></i> Semua Fitur Pro</li>
                                        <li><i class="fa-solid fa-check text-success me-1"></i> Rekap Laporan Mingguan</li>
                                        <li><i class="fa-solid fa-check text-success me-1"></i> Analisis Bisnis Lanjutan</li>
                                    </ul>
                                </div>
                                <button class="btn btn-outline-dark rounded-pill w-100 btn-sm fw-bold d-flex align-items-center justify-content-center gap-1"
                                    id="btnUpgradeProPlusModal" onclick="processUpgrade('pro_plus', activeModalCycle)">
                                    Upgrade
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        // State
        const ACTIVE_TOKEN = '<?= htmlspecialchars($token) ?>';
        const IS_SAMPLE = <?= $is_sample ? 'true' : 'false' ?>;
        const BASE_URL = '<?= site_url("finbot/") ?>';

        let currentPeriod = 'this_month';
        let donutChartInstance = null;
        let cashflowChartInstance = null;
        let searchTimeout = null;

        $(document).ready(function () {
            loadDashboard();
            loadQuotaStatus();

            // Handle resize to adjust chart legend responsiveness dynamically
            let resizeTimer;
            $(window).on('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () {
                    if (donutChartInstance) {
                        donutChartInstance.options.plugins.legend.position = window.innerWidth < 768 ? 'bottom' : 'right';
                        donutChartInstance.update();
                    }
                }, 250);
            });
        });

        function changePeriod(period, label) {
            currentPeriod = period;
            $('#currentPeriodLabel').text(label);
            loadDashboard();
        }

        // ==========================================
        // 1. LOAD DASHBOARD DATA
        // ==========================================
        function loadDashboard() {
            $.ajax({
                url: BASE_URL + 'get_summary',
                type: 'GET',
                data: {
                    token: ACTIVE_TOKEN,
                    period: currentPeriod
                },
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        renderSummaryMetrics(res.summary);
                        renderDonutChart(res.expense_categories);
                        renderCashflowChart(res.cashflow_trend);
                        renderRecentTransactions(res.recent_transactions);
                    }
                }
            });
        }

        function renderSummaryMetrics(sum) {
            $('#valTotalIncome').text('Rp ' + Number(sum.total_income).toLocaleString('id-ID'));
            $('#valTotalExpense').text('Rp ' + Number(sum.total_expense).toLocaleString('id-ID'));

            let net = Number(sum.net_cashflow);
            let prefix = net >= 0 ? '+Rp ' : '-Rp ';
            $('#valNetCashflow').text(prefix + Math.abs(net).toLocaleString('id-ID'));

            if (net >= 0) {
                $('#valNetCashflow').removeClass('text-danger').addClass('text-primary');
            } else {
                $('#valNetCashflow').removeClass('text-primary').addClass('text-danger');
            }

            $('#valTotalBalance').text('Rp ' + Number(sum.total_balance).toLocaleString('id-ID'));
        }

        function renderDonutChart(categories) {
            let labels = [];
            let data = [];
            let colors = ['#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#06b6d4', '#ef4444', '#64748b'];

            if (categories && categories.length > 0) {
                categories.forEach(item => {
                    labels.push(item.category_name);
                    data.push(item.total);
                });
            } else {
                labels = ['Belum Ada Pengeluaran'];
                data = [1];
                colors = ['#e2e8f0'];
            }

            const ctx = document.getElementById('categoryDonutChart').getContext('2d');
            if (donutChartInstance) donutChartInstance.destroy();

            const isMobile = window.innerWidth < 768;

            donutChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: isMobile ? 'bottom' : 'right',
                            labels: {
                                boxWidth: 10,
                                font: { family: 'Plus Jakarta Sans', size: 11, weight: 'bold' },
                                padding: isMobile ? 8 : 12
                            }
                        }
                    },
                    cutout: '68%'
                }
            });
        }

        function renderCashflowChart(cashflow) {
            const ctx = document.getElementById('cashflowTrendChart').getContext('2d');
            if (cashflowChartInstance) cashflowChartInstance.destroy();

            cashflowChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: cashflow.labels || [],
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: cashflow.income || [],
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 3
                        },
                        {
                            label: 'Pengeluaran',
                            data: cashflow.expense || [],
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { boxWidth: 10, font: { family: 'Plus Jakarta Sans', size: 11, weight: 'bold' } }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function (v) {
                                    return (v >= 1000000) ? (v / 1000000) + 'jt' : (v / 1000) + 'k';
                                },
                                font: { size: 10 }
                            },
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            ticks: { font: { size: 10 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        function renderRecentTransactions(transactions) {
            let html = '';
            if (!transactions || transactions.length === 0) {
                html = '<div class="text-center py-4 text-muted small"><i class="fa-regular fa-folder-open fs-4 d-block mb-2 text-secondary opacity-50"></i>Belum ada transaksi pada periode ini.</div>';
            } else {
                transactions.forEach(t => {
                    let isIncome = t.type === 'income';
                    let sign = isIncome ? '+' : '-';
                    let colorClass = isIncome ? 'text-success' : 'text-danger';
                    let iconClass = isIncome ? 'fa-arrow-up' : 'fa-arrow-down';
                    let badgeClass = isIncome ? 'income' : 'expense';

                    html += `
                    <div class="list-group-item px-0 py-2 py-md-3 border-bottom d-flex align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
                            <div class="tx-badge-circle ${badgeClass}">
                                <i class="fa-solid ${iconClass}"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="fw-bold text-dark text-truncate" style="font-size:0.92rem;">${escapeHtml(t.category_name)}</div>
                                <div class="small text-muted" style="font-size:0.8rem;">${escapeHtml(t.description)} • <span>${t.transaction_date}</span></div>
                            </div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <div class="fw-bold ${colorClass}" style="font-size:0.95rem;">${sign} ${Number(t.amount).toLocaleString('id-ID')}</div>
                            <span class="badge bg-light text-muted border" style="font-size:0.68rem;">${t.source}</span>
                        </div>
                    </div>
                    `;
                });
            }
            $('#recentTransactionsList').html(html);
        }

        // ==========================================
        // 2. TRANSACTIONS TAB TABLE & MOBILE CARDS
        // ==========================================
        function delaySearchTx() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(loadTransactionsTable, 300);
        }

        function loadTransactionsTable() {
            let params = {
                token: ACTIVE_TOKEN,
                search: $('#filterSearch').val(),
                type: $('#filterType').val(),
                category: $('#filterCategory').val(),
                start_date: $('#filterStartDate').val(),
                end_date: $('#filterEndDate').val()
            };

            $.ajax({
                url: BASE_URL + 'get_transactions',
                type: 'GET',
                data: params,
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        renderTransactionsTable(res.data);
                        $('#txTableInfoText').text(`Menampilkan ${res.data.length} dari total ${res.total} transaksi`);
                    }
                }
            });
        }

        function renderTransactionsTable(list) {
            let tableHtml = '';
            let cardsHtml = '';

            if (!list || list.length === 0) {
                tableHtml = '<tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data transaksi yang sesuai filter.</td></tr>';
                cardsHtml = '<div class="text-center py-4 text-muted small"><i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-secondary opacity-50"></i>Tidak ada data transaksi yang sesuai filter.</div>';
            } else {
                list.forEach((item, idx) => {
                    let isInc = item.type === 'income';
                    let sign = isInc ? '+' : '-';
                    let colorClass = isInc ? 'text-success' : 'text-danger';
                    let badgeType = isInc ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle';
                    let labelType = isInc ? 'Pemasukan' : 'Pengeluaran';

                    // Desktop row
                    tableHtml += `
                    <tr>
                        <td>${idx + 1}</td>
                        <td><strong>${item.transaction_date}</strong></td>
                        <td>
                            <span class="badge ${badgeType} px-2 py-1 rounded-pill">
                                ${labelType}
                            </span>
                        </td>
                        <td><strong>${escapeHtml(item.category_name)}</strong></td>
                        <td>
                            <div class="text-dark fw-semibold">${escapeHtml(item.description)}</div>
                            ${item.raw_message ? `<small class="text-muted"><i class="fa-regular fa-comment-dots me-1"></i>"${escapeHtml(item.raw_message)}"</small>` : ''}
                        </td>
                        <td><span class="badge bg-light text-muted border">${item.source}</span></td>
                        <td class="text-end fw-bold ${colorClass}">
                            ${sign} ${Number(item.amount).toLocaleString('id-ID')}
                        </td>
                        <td class="text-center" nowrap>
                            <button class="btn btn-sm btn-outline-primary rounded-circle me-1" style="width:32px;height:32px;padding:0;" title="Edit" onclick="openEditTransaction(${item.id})">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger rounded-circle" style="width:32px;height:32px;padding:0;" title="Hapus" onclick="deleteTransaction(${item.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `;

                    // Mobile card
                    cardsHtml += `
                    <div class="tx-mobile-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge ${badgeType} px-2 py-1 rounded-pill me-1" style="font-size:0.72rem;">${labelType}</span>
                                <span class="text-muted small" style="font-size:0.78rem;">${item.transaction_date}</span>
                            </div>
                            <div class="fw-bold ${colorClass}" style="font-size:1rem;">
                                ${sign}Rp ${Number(item.amount).toLocaleString('id-ID')}
                            </div>
                        </div>
                        <div class="fw-bold text-dark mb-1" style="font-size:0.92rem;">
                            ${escapeHtml(item.category_name)}: <span class="fw-normal text-muted">${escapeHtml(item.description)}</span>
                        </div>
                        ${item.raw_message ? `<div class="small text-muted mb-2 fst-italic" style="font-size:0.75rem;"><i class="fa-regular fa-comment-dots me-1"></i>"${escapeHtml(item.raw_message)}"</div>` : ''}
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                            <span class="badge bg-light text-muted border" style="font-size:0.7rem;">Sumber: ${item.source}</span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1" style="font-size:0.78rem;" onclick="openEditTransaction(${item.id})">
                                    <i class="fa-solid fa-pencil me-1"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" style="font-size:0.78rem;" onclick="deleteTransaction(${item.id})">
                                    <i class="fa-solid fa-trash me-1"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                    `;
                });
            }

            $('#transactionsTableBody').html(tableHtml);
            $('#transactionsMobileCards').html(cardsHtml);
        }

        // ==========================================
        // 3. CRUD TRANSACTIONS
        // ==========================================
        function openTransactionModal(defaultType) {
            $('#formTransaction')[0].reset();
            $('#txFormId').val('');
            $('#modalTxTitle').text('Catat Transaksi Manual');
            if (defaultType === 'income') $('#txTypeIncome').prop('checked', true);
            else $('#txTypeExpense').prop('checked', true);
            $('#txFormDate').val(new Date().toISOString().slice(0, 10));
            $('#modalTransaction').modal('show');
        }

        function openEditTransaction(id) {
            $.ajax({
                url: BASE_URL + 'get_transaction_by_id/' + id,
                type: 'GET',
                data: { token: ACTIVE_TOKEN },
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        let d = res.data;
                        $('#txFormId').val(d.id);
                        $('#modalTxTitle').text('Edit Transaksi #' + d.id);
                        if (d.type === 'income') $('#txTypeIncome').prop('checked', true);
                        else $('#txTypeExpense').prop('checked', true);

                        $('#txFormAmount').val(d.amount);
                        $('#txFormCategory').val(d.category_name);
                        $('#txFormDescription').val(d.description);
                        $('#txFormDate').val(d.transaction_date);
                        $('#txFormAccount').val(d.account_id || 1);

                        $('#modalTransaction').modal('show');
                    }
                }
            });
        }

        function submitTransaction(e) {
            e.preventDefault();
            let id = $('#txFormId').val();
            let url = id ? (BASE_URL + 'update_transaction') : (BASE_URL + 'save_transactions');

            let formData = {
                id: id,
                token: ACTIVE_TOKEN,
                type: $('input[name="type"]:checked').val(),
                amount: $('#txFormAmount').val(),
                category_name: $('#txFormCategory').val(),
                description: $('#txFormDescription').val(),
                transaction_date: $('#txFormDate').val(),
                account_id: $('#txFormAccount').val(),
                source: 'web'
            };

            let postData = id ? formData : JSON.stringify({ transactions: [formData], token: ACTIVE_TOKEN, source: 'web' });

            $.ajax({
                url: url,
                type: 'POST',
                contentType: id ? 'application/x-www-form-urlencoded' : 'application/json',
                data: postData,
                success: function (res) {
                    $('#modalTransaction').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message || 'Transaksi berhasil disimpan',
                        timer: 1800,
                        showConfirmButton: false
                    });
                    loadDashboard();
                    loadTransactionsTable();
                }
            });
        }

        function deleteTransaction(id) {
            Swal.fire({
                title: 'Hapus Transaksi?',
                text: 'Data transaksi akan dihapus (Soft delete).',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: BASE_URL + 'delete_transaction',
                        type: 'POST',
                        data: { id: id, token: ACTIVE_TOKEN },
                        dataType: 'json',
                        success: function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            loadDashboard();
                            loadTransactionsTable();
                        }
                    });
                }
            });
        }

        // ==========================================
        // 4. TOKEN MODAL & MANAGEMENT
        // ==========================================
        function openTokenModal() {
            $('#modalToken').modal('show');
        }

        function applyUserToken() {
            let token = $('#inputUserToken').val().trim();
            if (!token) {
                Swal.fire('Info', 'Silakan masukkan kode token terlebih dahulu', 'info');
                return;
            }
            window.location.href = '<?= site_url("finbot/dashboard?token=") ?>' + encodeURIComponent(token);
        }

        function clearTokenAccess() {
            window.location.href = '<?= site_url("finbot/dashboard?token=demo") ?>';
        }

        function copyTokenToClipboard(text) {
            navigator.clipboard.writeText(text).then(function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Token Disalin!',
                    text: text,
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }

        // ==========================================
        // 5. PLAN & UPGRADE
        // ==========================================
        function loadQuotaStatus() {
            $.ajax({
                url: BASE_URL + 'get_plan_info?token=' + encodeURIComponent(ACTIVE_TOKEN),
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success' && res.data) {
                        const d = res.data;
                        $('#quotaPlanName').text(d.plan_name);
                        $('#quotaPlanBadge').text(d.plan.toUpperCase());
                        $('#quotaDetailText').html(`<b>${d.used} / ${d.limit}</b> transaksi bulan ini (${d.remaining} transaksi tersisa)`);
                        $('#quotaProgressBar').css('width', d.percentage + '%');
                    }
                }
            });
        }

        function openUpgradeModal() {
            $('#modalUpgradePlan').modal('show');
        }

        let activeModalCycle = 'monthly';

        function setModalBillingCycle(cycle) {
            activeModalCycle = cycle;
            const btnM = $('#modalCycleMonthly');
            const btnY = $('#modalCycleYearly');
            const pricePro = $('#modalPricePro');
            const periodPro = $('#modalPeriodPro');
            const subPro = $('#modalSubPro');
            const priceProPlus = $('#modalPriceProPlus');
            const periodProPlus = $('#modalPeriodProPlus');
            const subProPlus = $('#modalSubProPlus');

            if (cycle === 'yearly') {
                btnY.addClass('active').css({ background: '#2563eb', color: '#fff' });
                btnM.removeClass('active').css({ background: 'transparent', color: '#0f172a' });

                pricePro.text('Rp 99.000');
                periodPro.text('/ tahun');
                subPro.html('Setara <b>Rp8.250/bln</b> (Hemat Rp19.800/thn)');

                priceProPlus.text('Rp 199.000');
                periodProPlus.text('/ tahun');
                subProPlus.html('Setara <b>Rp16.580/bln</b> (Hemat Rp39.800/thn)');
            } else {
                btnM.addClass('active').css({ background: '#2563eb', color: '#fff' });
                btnY.removeClass('active').css({ background: 'transparent', color: '#0f172a' });

                pricePro.text('Rp 9.900');
                periodPro.text('/ bulan');
                subPro.text('Ditagih bulanan (Rp9.900/bln)');

                priceProPlus.text('Rp 19.900');
                periodProPlus.text('/ bulan');
                subProPlus.text('Ditagih bulanan (Rp19.900/bln)');
            }
        }

        function processUpgrade(planCode, cycle) {
            const selectedCycle = cycle || activeModalCycle || 'monthly';
            const adminPhone = '6289520305077';
            const planNames = {
                'pro': 'FinBot Pro (Warung)',
                'pro_plus': 'FinBot Pro+ (Usaha)'
            };
            const planPrices = {
                'pro': { 'monthly': 'Rp 9.900', 'yearly': 'Rp 99.000' },
                'pro_plus': { 'monthly': 'Rp 19.900', 'yearly': 'Rp 199.000' }
            };

            const planName = planNames[planCode] || 'FinBot Pro';
            const cycleName = selectedCycle === 'yearly' ? 'Tahunan' : 'Bulanan';
            const priceText = (planPrices[planCode] && planPrices[planCode][selectedCycle]) ? planPrices[planCode][selectedCycle] : 'Rp 9.900';
            const tokenStr = ACTIVE_TOKEN ? ACTIVE_TOKEN : 'Demo / Akun Baru';

            const waMessage = `Halo Admin FinBot, saya ingin request upgrade akun FinBot:\n`
                + `• Token Akun: ${tokenStr}\n`
                + `• Paket Pilihan: ${planName}\n`
                + `• Periode: ${cycleName} (${priceText})\n\n`
                + `Mohon dibantu proses aktivasi dan petunjuk pembayarannya. Terima kasih!`;

            const waUrl = `https://wa.me/${adminPhone}?text=${encodeURIComponent(waMessage)}`;

            // Swal.fire({
            //     title: 'Hubungkan ke WhatsApp Admin',
            //     html: `Anda akan diarahkan ke WhatsApp Admin (<b>6289520305077</b>) untuk konfirmasi upgrade paket <b>${planName}</b> (${cycleName}).`,
            //     icon: 'info',
            //     showCancelButton: true,
            //     confirmButtonColor: '#2563eb',
            //     cancelButtonColor: '#64748b',
            //     confirmButtonText: '<i class="fa-brands fa-whatsapp me-1"></i> Buka Chat WhatsApp',
            //     cancelButtonText: 'Batal'
            // }).then((result) => {
            //     if (result.isConfirmed) {
                    $('#modalUpgradePlan').modal('hide');
                    window.open(waUrl, '_blank');
            //     }
            // });
        }

        function exportCSV() {
            window.open(BASE_URL + 'get_transactions?token=' + encodeURIComponent(ACTIVE_TOKEN) + '&limit=1000', '_blank');
        }

        function escapeHtml(string) {
            return String(string).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
    </script>
</body>

</html>