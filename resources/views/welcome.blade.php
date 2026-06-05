<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edutech — Sistem Pengolahan Nilai Siswa berbasis web. Kelola data siswa, input nilai, dan lihat laporan hasil belajar dengan mudah.">
    <title>Edutech — Sistem Pengolahan Nilai Siswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* ============================================
           DESIGN SYSTEM — Edutech Landing Page
           ============================================ */
        :root {
            --primary-50: #eef2ff;
            --primary-100: #e0e7ff;
            --primary-200: #c7d2fe;
            --primary-300: #a5b4fc;
            --primary-400: #818cf8;
            --primary-500: #6366f1;
            --primary-600: #4f46e5;
            --primary-700: #4338ca;
            --primary-800: #3730a3;
            --primary-900: #312e81;

            --emerald-400: #34d399;
            --emerald-500: #10b981;
            --emerald-600: #059669;

            --amber-400: #fbbf24;
            --amber-500: #f59e0b;

            --rose-400: #fb7185;
            --rose-500: #f43f5e;

            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --slate-950: #020617;

            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --transition-base: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ---- Reset ---- */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: var(--font-family);
            color: var(--slate-800);
            background: var(--slate-50);
            line-height: 1.6;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; display: block; }

        /* ---- Layout ---- */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ============================================
           NAVBAR
           ============================================ */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 16px 0;
            transition: var(--transition-base);
            background: transparent;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(99, 102, 241, 0.08);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            padding: 12px 0;
        }

        .navbar__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar__brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--primary-600);
            letter-spacing: -0.02em;
        }

        .navbar__logo {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-500), var(--primary-700));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 900;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
        }

        .navbar__links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .navbar__links a {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--slate-600);
            transition: var(--transition-base);
            position: relative;
        }

        .navbar__links a:hover {
            color: var(--primary-600);
        }

        .navbar__links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-500);
            border-radius: 1px;
            transition: var(--transition-base);
        }

        .navbar__links a:hover::after {
            width: 100%;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            font-family: var(--font-family);
            border: none;
            cursor: pointer;
            transition: var(--transition-base);
            text-align: center;
            justify-content: center;
        }

        .btn--primary {
            background: linear-gradient(135deg, var(--primary-500), var(--primary-700));
            color: white;
            box-shadow: 0 2px 12px rgba(99, 102, 241, 0.35);
        }

        .btn--primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(99, 102, 241, 0.45);
        }

        .btn--outline {
            background: transparent;
            color: var(--primary-600);
            border: 1.5px solid var(--primary-200);
        }

        .btn--outline:hover {
            background: var(--primary-50);
            border-color: var(--primary-400);
        }

        .btn--lg {
            padding: 14px 32px;
            font-size: 1rem;
            border-radius: 12px;
        }

        .btn--white {
            background: white;
            color: var(--primary-700);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .btn--white:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        /* ============================================
           HERO SECTION
           ============================================ */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 0 80px;
            overflow: hidden;
        }

        .hero__bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% 0%, rgba(99, 102, 241, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 20%, rgba(139, 92, 246, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse 50% 40% at 20% 80%, rgba(52, 211, 153, 0.06) 0%, transparent 50%),
                linear-gradient(180deg, var(--slate-50) 0%, white 100%);
            z-index: 0;
        }

        /* Floating decorative shapes */
        .hero__shapes {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }

        .hero__shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.15;
            animation: float 6s ease-in-out infinite;
        }

        .hero__shape--1 {
            width: 300px; height: 300px;
            background: linear-gradient(135deg, var(--primary-400), var(--primary-200));
            top: 10%; right: -5%;
            animation-delay: 0s;
        }

        .hero__shape--2 {
            width: 200px; height: 200px;
            background: linear-gradient(135deg, var(--emerald-400), var(--primary-300));
            bottom: 20%; left: -3%;
            animation-delay: 2s;
        }

        .hero__shape--3 {
            width: 150px; height: 150px;
            background: linear-gradient(135deg, var(--amber-400), var(--rose-400));
            top: 30%; left: 15%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(3deg); }
        }

        .hero__content {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .hero__text { max-width: 560px; }

        .hero__badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: var(--primary-50);
            border: 1px solid var(--primary-100);
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary-700);
            margin-bottom: 24px;
        }

        .hero__badge-dot {
            width: 6px; height: 6px;
            background: var(--emerald-500);
            border-radius: 50%;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.5); }
        }

        .hero__title {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: var(--slate-900);
            margin-bottom: 20px;
        }

        .hero__title span {
            background: linear-gradient(135deg, var(--primary-500), var(--primary-700));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero__subtitle {
            font-size: 1.15rem;
            color: var(--slate-500);
            line-height: 1.7;
            margin-bottom: 36px;
            max-width: 480px;
        }

        .hero__actions {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .hero__stats {
            display: flex;
            gap: 40px;
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid var(--slate-200);
        }

        .hero__stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.02em;
        }

        .hero__stat-label {
            font-size: 0.8rem;
            color: var(--slate-400);
            font-weight: 500;
            margin-top: 2px;
        }

        /* Hero illustration/card mockup */
        .hero__visual {
            position: relative;
            display: flex;
            justify-content: center;
        }

        .hero__card {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow:
                0 4px 6px rgba(0, 0, 0, 0.02),
                0 12px 40px rgba(0, 0, 0, 0.06),
                0 0 0 1px rgba(0, 0, 0, 0.03);
            max-width: 460px;
            width: 100%;
            transform: perspective(1000px) rotateY(-5deg) rotateX(2deg);
            transition: var(--transition-base);
            animation: cardFloat 4s ease-in-out infinite;
        }

        @keyframes cardFloat {
            0%, 100% { transform: perspective(1000px) rotateY(-5deg) rotateX(2deg) translateY(0); }
            50% { transform: perspective(1000px) rotateY(-5deg) rotateX(2deg) translateY(-10px); }
        }

        .hero__card:hover {
            transform: perspective(1000px) rotateY(0deg) rotateX(0deg);
        }

        .hero__card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .hero__card-title {
            font-weight: 700;
            font-size: 1rem;
            color: var(--slate-800);
        }

        .hero__card-badge {
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge--lulus { background: #dcfce7; color: #15803d; }
        .badge--tidak { background: #fee2e2; color: #b91c1c; }

        .hero__card-table {
            width: 100%;
            border-collapse: collapse;
        }

        .hero__card-table th {
            text-align: left;
            padding: 8px 12px;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--slate-100);
        }

        .hero__card-table td {
            padding: 10px 12px;
            font-size: 0.85rem;
            color: var(--slate-700);
            border-bottom: 1px solid var(--slate-50);
        }

        .hero__card-table tr:last-child td {
            border-bottom: none;
        }

        .hero__card-table .nilai-cell {
            font-weight: 700;
            font-variant-numeric: tabular-nums;
        }

        .hero__card-table .lulus { color: var(--emerald-600); }
        .hero__card-table .tidak { color: var(--rose-500); }

        .hero__card-footer {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--slate-100);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hero__card-avg {
            font-size: 0.8rem;
            color: var(--slate-500);
        }

        .hero__card-avg strong {
            color: var(--primary-600);
            font-weight: 700;
        }

        /* ============================================
           FEATURES SECTION
           ============================================ */
        .features {
            padding: 100px 0;
            background: white;
        }

        .section__header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 64px;
        }

        .section__label {
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--primary-600);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 12px;
        }

        .section__title {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.02em;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .section__desc {
            font-size: 1.05rem;
            color: var(--slate-500);
            line-height: 1.7;
        }

        .features__grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        .feature-card {
            padding: 32px;
            border-radius: 16px;
            border: 1px solid var(--slate-100);
            background: var(--slate-50);
            transition: var(--transition-base);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-400), var(--primary-600));
            opacity: 0;
            transition: var(--transition-base);
        }

        .feature-card:hover {
            border-color: var(--primary-200);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.08);
            transform: translateY(-4px);
        }

        .feature-card:hover::before { opacity: 1; }

        .feature-card__icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .feature-card__icon--indigo { background: var(--primary-100); color: var(--primary-600); }
        .feature-card__icon--emerald { background: #d1fae5; color: var(--emerald-600); }
        .feature-card__icon--amber { background: #fef3c7; color: #d97706; }
        .feature-card__icon--rose { background: #ffe4e6; color: #e11d48; }
        .feature-card__icon--violet { background: #ede9fe; color: #7c3aed; }
        .feature-card__icon--cyan { background: #cffafe; color: #0891b2; }

        .feature-card__title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--slate-800);
            margin-bottom: 8px;
        }

        .feature-card__desc {
            font-size: 0.88rem;
            color: var(--slate-500);
            line-height: 1.65;
        }

        /* ============================================
           ROLES SECTION
           ============================================ */
        .roles {
            padding: 100px 0;
            background: var(--slate-50);
        }

        .roles__grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        .role-card {
            background: white;
            border-radius: 20px;
            padding: 40px 32px;
            text-align: center;
            border: 1px solid var(--slate-100);
            transition: var(--transition-base);
            position: relative;
        }

        .role-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        }

        .role-card__avatar {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
        }

        .role-card__avatar--admin {
            background: linear-gradient(135deg, var(--primary-100), var(--primary-200));
            color: var(--primary-600);
        }

        .role-card__avatar--guru {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: var(--emerald-600);
        }

        .role-card__avatar--siswa {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
        }

        .role-card__title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--slate-800);
            margin-bottom: 12px;
        }

        .role-card__desc {
            font-size: 0.88rem;
            color: var(--slate-500);
            line-height: 1.65;
            margin-bottom: 20px;
        }

        .role-card__list {
            list-style: none;
            text-align: left;
        }

        .role-card__list li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
            font-size: 0.85rem;
            color: var(--slate-600);
        }

        .role-card__list li::before {
            content: '✓';
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #dcfce7;
            color: #15803d;
            font-size: 0.7rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        /* ============================================
           CTA SECTION
           ============================================ */
        .cta {
            padding: 100px 0;
            background: white;
        }

        .cta__card {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-800) 50%, var(--slate-900) 100%);
            border-radius: 24px;
            padding: 64px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta__card::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 30%, rgba(255, 255, 255, 0.05) 0%, transparent 40%);
        }

        .cta__content {
            position: relative;
            z-index: 1;
        }

        .cta__title {
            font-size: 2.2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .cta__desc {
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.7);
            max-width: 500px;
            margin: 0 auto 32px;
            line-height: 1.7;
        }

        .cta__actions {
            display: flex;
            gap: 16px;
            justify-content: center;
        }

        /* ============================================
           FOOTER
           ============================================ */
        .footer {
            padding: 40px 0;
            background: var(--slate-50);
            border-top: 1px solid var(--slate-100);
        }

        .footer__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer__copy {
            font-size: 0.82rem;
            color: var(--slate-400);
        }

        .footer__tech {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .footer__tech-badge {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 600;
            background: var(--slate-100);
            color: var(--slate-500);
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 1024px) {
            .hero__content {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }
            .hero__text { max-width: 100%; }
            .hero__subtitle { max-width: 100%; }
            .hero__actions { justify-content: center; }
            .hero__stats { justify-content: center; }
            .hero__card { transform: none; animation: none; }
            .features__grid,
            .roles__grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .navbar__links { display: none; }
            .hero__title { font-size: 2.4rem; }
            .hero__stats { gap: 24px; flex-wrap: wrap; }
            .features__grid,
            .roles__grid { grid-template-columns: 1fr; }
            .cta__card { padding: 40px 24px; }
            .cta__title { font-size: 1.6rem; }
            .cta__actions { flex-direction: column; align-items: center; }
            .footer__inner { flex-direction: column; gap: 16px; text-align: center; }
            .section__title { font-size: 1.7rem; }
        }

        @media (max-width: 480px) {
            .hero { padding: 100px 0 60px; }
            .hero__title { font-size: 2rem; }
            .hero__card { padding: 20px; }
        }
    </style>
</head>
<body>
    <!-- ======== NAVBAR ======== -->
    <nav class="navbar" id="navbar">
        <div class="container navbar__inner">
            <a href="/" class="navbar__brand">
                <div class="navbar__logo">E</div>
                Edutech
            </a>
            <ul class="navbar__links">
                <li><a href="#features">Fitur</a></li>
                <li><a href="#roles">Hak Akses</a></li>
                <li>
                    <a href="{{ url('/admin') }}" class="btn btn--primary" style="color: white;">
                        Masuk Panel
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- ======== HERO ======== -->
    <section class="hero" id="hero">
        <div class="hero__bg"></div>
        <div class="hero__shapes">
            <div class="hero__shape hero__shape--1"></div>
            <div class="hero__shape hero__shape--2"></div>
            <div class="hero__shape hero__shape--3"></div>
        </div>
        <div class="container hero__content">
            <div class="hero__text">
                <div class="hero__badge">
                    <span class="hero__badge-dot"></span>
                    Sistem Informasi Akademik
                </div>
                <h1 class="hero__title">
                    Kelola Nilai Siswa<br>dengan <span>Edutech</span>
                </h1>
                <p class="hero__subtitle">
                    Platform pengolahan nilai siswa yang cepat, rapi, dan terstruktur.
                    Mulai dari pengelolaan data siswa, input nilai, perhitungan otomatis,
                    hingga laporan hasil belajar.
                </p>
                <div class="hero__actions">
                    <a href="{{ url('/admin') }}" class="btn btn--primary btn--lg">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Masuk ke Panel
                    </a>
                    <a href="#features" class="btn btn--outline btn--lg">Pelajari Fitur</a>
                </div>
                <div class="hero__stats">
                    <div>
                        <div class="hero__stat-value">3</div>
                        <div class="hero__stat-label">Role Pengguna</div>
                    </div>
                    <div>
                        <div class="hero__stat-value">100%</div>
                        <div class="hero__stat-label">Otomatis</div>
                    </div>
                    <div>
                        <div class="hero__stat-value">30:30:40</div>
                        <div class="hero__stat-label">Bobot Nilai</div>
                    </div>
                </div>
            </div>

            <div class="hero__visual">
                <div class="hero__card">
                    <div class="hero__card-header">
                        <div class="hero__card-title">📊 Rekap Nilai Siswa</div>
                        <span class="hero__card-badge badge--lulus">Semester 1</span>
                    </div>
                    <table class="hero__card-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tugas</th>
                                <th>UTS</th>
                                <th>UAS</th>
                                <th>Akhir</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Andi S.</td>
                                <td class="nilai-cell">85</td>
                                <td class="nilai-cell">78</td>
                                <td class="nilai-cell">82</td>
                                <td class="nilai-cell">81.7</td>
                                <td><span class="hero__card-badge badge--lulus" style="font-size:0.7rem">Lulus</span></td>
                            </tr>
                            <tr>
                                <td>Budi R.</td>
                                <td class="nilai-cell">70</td>
                                <td class="nilai-cell">65</td>
                                <td class="nilai-cell">72</td>
                                <td class="nilai-cell">69.3</td>
                                <td><span class="hero__card-badge badge--tidak" style="font-size:0.7rem">Tidak Lulus</span></td>
                            </tr>
                            <tr>
                                <td>Citra P.</td>
                                <td class="nilai-cell">92</td>
                                <td class="nilai-cell">88</td>
                                <td class="nilai-cell">95</td>
                                <td class="nilai-cell">92.0</td>
                                <td><span class="hero__card-badge badge--lulus" style="font-size:0.7rem">Lulus</span></td>
                            </tr>
                            <tr>
                                <td>Dewi A.</td>
                                <td class="nilai-cell">75</td>
                                <td class="nilai-cell">80</td>
                                <td class="nilai-cell">78</td>
                                <td class="nilai-cell">77.7</td>
                                <td><span class="hero__card-badge badge--lulus" style="font-size:0.7rem">Lulus</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="hero__card-footer">
                        <span class="hero__card-avg">Rata-rata: <strong>80.2</strong></span>
                        <span class="hero__card-avg">Kelulusan: <strong>75%</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======== FEATURES ======== -->
    <section class="features" id="features">
        <div class="container">
            <div class="section__header">
                <span class="section__label">Fitur Utama</span>
                <h2 class="section__title">Semua yang Dibutuhkan untuk<br>Pengelolaan Nilai</h2>
                <p class="section__desc">
                    Dirancang khusus untuk kebutuhan institusi pendidikan
                    dalam mengelola proses penilaian secara menyeluruh.
                </p>
            </div>
            <div class="features__grid">
                <div class="feature-card">
                    <div class="feature-card__icon feature-card__icon--indigo">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3 class="feature-card__title">Manajemen Data Siswa</h3>
                    <p class="feature-card__desc">Kelola data siswa lengkap meliputi NIS, nama, dan kelas. Data terhubung langsung ke akun pengguna sistem.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon feature-card__icon--emerald">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 1.1 2.7 3 6 3s6-1.9 6-3v-5"/></svg>
                    </div>
                    <h3 class="feature-card__title">Data Guru & Mapel</h3>
                    <p class="feature-card__desc">Setiap guru terdaftar dengan ID unik dan mata pelajaran yang diampu. Nilai terhubung otomatis ke guru yang menginput.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon feature-card__icon--amber">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <h3 class="feature-card__title">Input & Validasi Nilai</h3>
                    <p class="feature-card__desc">Input nilai tugas, UTS, dan UAS dengan validasi otomatis rentang 0–100. Proses cepat dan terhindar dari kesalahan input.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon feature-card__icon--rose">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <h3 class="feature-card__title">Hitung Otomatis</h3>
                    <p class="feature-card__desc">Nilai akhir dihitung otomatis dengan bobot 30% Tugas + 30% UTS + 40% UAS. Status kelulusan ditentukan otomatis (≥ 70 = Lulus).</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon feature-card__icon--violet">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    </div>
                    <h3 class="feature-card__title">Laporan Nilai</h3>
                    <p class="feature-card__desc">Tampilkan rekap nilai siswa dengan statistik lengkap. Admin dan guru lihat semua, siswa lihat nilai pribadi.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon feature-card__icon--cyan">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3 class="feature-card__title">Kontrol Akses Role</h3>
                    <p class="feature-card__desc">Sistem role-based access control menggunakan Filament Shield. Setiap role memiliki hak akses yang berbeda sesuai kebutuhannya.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ======== ROLES ======== -->
    <section class="roles" id="roles">
        <div class="container">
            <div class="section__header">
                <span class="section__label">Hak Akses Pengguna</span>
                <h2 class="section__title">Tiga Role dengan<br>Akses yang Tepat</h2>
                <p class="section__desc">
                    Setiap pengguna mendapat tampilan dan fitur yang sesuai
                    dengan perannya dalam sistem.
                </p>
            </div>
            <div class="roles__grid">
                <div class="role-card">
                    <div class="role-card__avatar role-card__avatar--admin">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3 class="role-card__title">Admin</h3>
                    <p class="role-card__desc">Hak akses penuh untuk mengelola seluruh sistem.</p>
                    <ul class="role-card__list">
                        <li>Manajemen data pengguna</li>
                        <li>CRUD siswa & guru</li>
                        <li>CRUD nilai siswa</li>
                        <li>Kelola laporan</li>
                        <li>Manajemen role & permission</li>
                    </ul>
                </div>
                <div class="role-card">
                    <div class="role-card__avatar role-card__avatar--guru">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 1.1 2.7 3 6 3s6-1.9 6-3v-5"/></svg>
                    </div>
                    <h3 class="role-card__title">Guru</h3>
                    <p class="role-card__desc">Mengelola nilai siswa sesuai mata pelajaran.</p>
                    <ul class="role-card__list">
                        <li>Input nilai siswa</li>
                        <li>Edit & hapus nilai</li>
                        <li>Lihat rekap nilai</li>
                        <li>Validasi nilai siswa</li>
                    </ul>
                </div>
                <div class="role-card">
                    <div class="role-card__avatar role-card__avatar--siswa">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <h3 class="role-card__title">Siswa</h3>
                    <p class="role-card__desc">Melihat informasi nilai dan status kelulusan.</p>
                    <ul class="role-card__list">
                        <li>Lihat nilai pribadi</li>
                        <li>Lihat status kelulusan</li>
                        <li>Lihat laporan hasil belajar</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ======== CTA ======== -->
    <section class="cta">
        <div class="container">
            <div class="cta__card">
                <div class="cta__content">
                    <h2 class="cta__title">Siap Menggunakan Edutech?</h2>
                    <p class="cta__desc">
                        Masuk ke panel administrasi untuk mulai mengelola data siswa,
                        input nilai, dan melihat laporan hasil belajar.
                    </p>
                    <div class="cta__actions">
                        <a href="{{ url('/admin') }}" class="btn btn--white btn--lg">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                            Masuk ke Panel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======== FOOTER ======== -->
    <footer class="footer">
        <div class="container footer__inner">
            <span class="footer__copy">&copy; {{ date('Y') }} Edutech — Sistem Pengolahan Nilai Siswa</span>
            <div class="footer__tech">
                <span class="footer__tech-badge">Laravel</span>
                <span class="footer__tech-badge">Filament</span>
                <span class="footer__tech-badge">Shield</span>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });

        // Smooth reveal animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        document.querySelectorAll('.feature-card, .role-card').forEach((el, i) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = `opacity 0.6s ease ${i * 0.1}s, transform 0.6s ease ${i * 0.1}s`;
            observer.observe(el);
        });
    </script>
</body>
</html>
