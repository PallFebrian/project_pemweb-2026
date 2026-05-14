<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esgul Suruh - Jasa Titip dan Suruh Mahasiswa</title>

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --navy: #04152f;
            --navy-2: #082b63;
            --blue: #0b63f6;
            --blue-soft: #eef5ff;
            --orange: #ff7a00;
            --orange-dark: #e86b00;
            --white: #ffffff;
            --bg: #f4f8ff;
            --text: #101f3d;
            --muted: #667085;
            --line: #e4ecf7;
            --shadow: 0 22px 60px rgba(6, 26, 58, 0.12);
            --shadow-soft: 0 14px 36px rgba(16, 33, 63, 0.08);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img {
            display: block;
            max-width: 100%;
        }

        .container {
            width: min(1140px, calc(100% - 40px));
            margin: 0 auto;
        }

        .hero {
            position: relative;
            overflow: hidden;
            min-height: 760px;
            padding-bottom: 150px;
            color: white;
            background:
                radial-gradient(circle at 70% 38%, rgba(11, 99, 246, 0.40), transparent 22%),
                radial-gradient(circle at 92% 48%, rgba(11, 99, 246, 0.18), transparent 30%),
                linear-gradient(135deg, #031127 0%, #061b42 48%, #082b63 100%);
        }

        .hero::before {
            content: "";
            position: absolute;
            right: -120px;
            bottom: -180px;
            width: 680px;
            height: 390px;
            border-radius: 55% 45% 0 0;
            background: rgba(255, 255, 255, 0.07);
            transform: rotate(-5deg);
            z-index: 1;
        }

        .hero::after {
            content: "";
            position: absolute;
            left: -120px;
            right: -120px;
            bottom: -215px;
            height: 330px;
            background: var(--bg);
            border-radius: 50% 50% 0 0;
            z-index: 2;
        }

        .navbar {
            position: relative;
            z-index: 10;
            height: 96px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-weight: 900;
            flex-shrink: 0;
        }

        .brand-logo {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            overflow: hidden;
            background: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
            flex-shrink: 0;
        }

        .brand-text strong {
            display: block;
            font-size: 21px;
            line-height: 1;
            letter-spacing: -0.5px;
        }

        .brand-text span {
            display: block;
            margin-top: 5px;
            color: rgba(255, 255, 255, 0.72);
            font-size: 13px;
            font-weight: 800;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-menu a {
            color: rgba(255, 255, 255, 0.84);
            font-size: 14px;
            font-weight: 800;
            white-space: nowrap;
        }

        .nav-menu a:hover {
            color: white;
        }

        .nav-button {
            padding: 14px 22px;
            border-radius: 14px;
            color: white !important;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(8px);
        }

        .hero-content {
            position: relative;
            z-index: 5;
            display: grid;
            grid-template-columns: 1fr 0.95fr;
            gap: 48px;
            align-items: center;
            padding-top: 64px;
        }

        .hero-left {
            max-width: 610px;
        }

        .hero-title {
            margin: 0;
            font-size: 70px;
            line-height: 1.02;
            letter-spacing: -2px;
            color: white;
        }

        .hero-title span {
            color: var(--orange);
        }

        .hero-desc {
            max-width: 590px;
            margin: 26px 0 0;
            color: rgba(255, 255, 255, 0.82);
            font-size: 18px;
            line-height: 1.75;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 36px;
        }

        .btn-primary,
        .btn-secondary {
            min-height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 28px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 900;
            transition: 0.2s ease;
        }

        .btn-primary {
            color: white;
            background: var(--orange);
            box-shadow: 0 16px 34px rgba(255, 122, 0, 0.28);
        }

        .btn-primary:hover {
            background: var(--orange-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            color: white;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.42);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.75);
        }

        .stats {
            position: relative;
            z-index: 6;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
            max-width: 720px;
            margin-top: 62px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: rgba(11, 99, 246, 0.28);
            color: white;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-item strong {
            display: block;
            margin-bottom: 4px;
            color: white;
            font-size: 14px;
            line-height: 1.2;
        }

        .stat-item span {
            display: block;
            color: rgba(255, 255, 255, 0.70);
            font-size: 12px;
            line-height: 1.4;
        }

        .hero-visual {
            position: relative;
            min-height: 440px;
            perspective: 1200px;
        }

        .visual-orbit {
            position: absolute;
            top: -28px;
            right: -24px;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background:
                radial-gradient(circle at 40% 38%, rgba(11, 99, 246, 0.50), transparent 42%),
                radial-gradient(circle at 70% 72%, rgba(255, 122, 0, 0.18), transparent 36%);
            filter: blur(2px);
            opacity: 0.95;
            z-index: 1;
        }

        .visual-ring {
            position: absolute;
            top: 20px;
            right: 18px;
            width: 365px;
            height: 365px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.025);
            z-index: 2;
        }

        .visual-ring::before {
            content: "";
            position: absolute;
            inset: 42px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.10);
        }

        .logo-card-3d {
            position: absolute;
            top: 34px;
            right: 46px;
            z-index: 5;
            width: 350px;
            height: 350px;
            transform-style: preserve-3d;
            transform: rotateX(9deg) rotateY(-14deg) rotateZ(-5deg);
            animation: floatCard 5s ease-in-out infinite;
        }

        .logo-card-3d::before {
            content: "";
            position: absolute;
            inset: 18px -14px -22px 24px;
            border-radius: 58px;
            background: linear-gradient(135deg, rgba(174, 191, 218, 0.95), rgba(228, 235, 248, 0.92));
            transform: translateZ(-34px);
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.28);
            z-index: 0;
        }

        .logo-card-3d::after {
            content: "";
            position: absolute;
            left: 36px;
            right: 18px;
            bottom: -34px;
            height: 36px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.30);
            filter: blur(18px);
            transform: rotateZ(5deg);
            z-index: -1;
        }

        .logo-card-face {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 26px;
            border-radius: 62px;
            background:
                linear-gradient(145deg, #ffffff 0%, #f4f8ff 46%, #dfe8f7 100%);
            box-shadow:
                0 34px 80px rgba(0, 0, 0, 0.34),
                inset 0 2px 0 rgba(255,255,255,0.96),
                inset -16px -18px 36px rgba(123, 145, 183, 0.22);
            border: 1px solid rgba(255,255,255,0.82);
            overflow: hidden;
        }

        .logo-card-face::before {
            content: "";
            position: absolute;
            inset: 18px;
            border-radius: 48px;
            background:
                linear-gradient(145deg, rgba(255,255,255,0.82), rgba(233,240,251,0.72));
            box-shadow:
                inset 12px 12px 24px rgba(255,255,255,0.70),
                inset -12px -14px 28px rgba(96,120,164,0.10);
            z-index: 1;
        }

        .logo-card-shine {
            position: absolute;
            top: -90px;
            left: -90px;
            width: 230px;
            height: 430px;
            background: linear-gradient(
                110deg,
                transparent 0%,
                rgba(255,255,255,0.02) 35%,
                rgba(255,255,255,0.58) 50%,
                rgba(255,255,255,0.02) 66%,
                transparent 100%
            );
            transform: rotate(18deg);
            opacity: 0.75;
            z-index: 4;
            pointer-events: none;
        }

        .logo-card-inner {
            position: relative;
            z-index: 3;
            width: 100%;
            height: 100%;
            border-radius: 42px;
            background:
                radial-gradient(circle at 25% 18%, rgba(255,255,255,0.95), transparent 34%),
                linear-gradient(145deg, #ffffff 0%, #f9fbff 55%, #edf3fb 100%);
            display: grid;
            place-items: center;
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.95),
                0 18px 40px rgba(16, 33, 63, 0.10);
            overflow: hidden;
        }

        .logo-card-inner::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 40px;
            width: 140px;
            height: 18px;
            border-radius: 50%;
            background: rgba(16, 33, 63, 0.12);
            filter: blur(4px);
            transform: translateX(-50%);
        }

        .logo-card-inner img {
            position: relative;
            z-index: 2;
            width: 76%;
            height: 76%;
            object-fit: contain;
            filter: drop-shadow(0 12px 12px rgba(16, 33, 63, 0.08));
        }

        .mini-badge {
            position: absolute;
            z-index: 8;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 13px;
            border-radius: 999px;
            background: rgba(255,255,255,0.92);
            color: var(--text);
            font-size: 12px;
            font-weight: 900;
            box-shadow: 0 14px 34px rgba(0,0,0,0.16);
            border: 1px solid rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
        }

        .mini-badge span {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: var(--blue);
            color: white;
            font-size: 12px;
        }

        .badge-one {
            top: 56px;
            right: 344px;
        }

        .badge-two {
            right: 2px;
            bottom: 76px;
        }

        @keyframes floatCard {
            0%, 100% {
                transform: rotateX(9deg) rotateY(-14deg) rotateZ(-5deg) translateY(0);
            }
            50% {
                transform: rotateX(7deg) rotateY(-11deg) rotateZ(-4deg) translateY(-12px);
            }
        }

        .quick-order {
            position: relative;
            z-index: 20;
            margin-top: -74px;
        }

        .quick-order-card {
            padding: 38px 34px 40px;
            background: white;
            border: 1px solid var(--line);
            border-radius: 26px;
            box-shadow: 0 18px 46px rgba(16, 33, 63, 0.10);
        }

        .quick-order-card h2 {
            margin: 0 0 34px;
            text-align: center;
            color: var(--text);
            font-size: 32px;
            letter-spacing: -0.6px;
        }

        .step-row {
            display: grid;
            grid-template-columns: 1fr auto 1fr auto 1fr auto 1fr;
            gap: 18px;
            align-items: start;
        }

        .step {
            text-align: center;
            min-width: 0;
        }

        .step-number {
            width: 58px;
            height: 58px;
            display: grid;
            place-items: center;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: var(--blue);
            color: white;
            font-size: 22px;
            font-weight: 900;
            box-shadow: 0 12px 24px rgba(11, 99, 246, 0.18);
        }

        .step h3 {
            margin: 0 0 8px;
            color: var(--text);
            font-size: 16px;
        }

        .step p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .arrow {
            padding-top: 16px;
            color: var(--blue);
            font-size: 30px;
            font-weight: 900;
        }

        section.content-section {
            padding: 78px 0 0;
        }

        .section-header {
            max-width: 760px;
            margin: 0 auto 34px;
            text-align: center;
        }

        .section-tag {
            display: inline-flex;
            padding: 8px 14px;
            margin-bottom: 14px;
            border-radius: 999px;
            background: var(--blue-soft);
            color: var(--blue);
            font-size: 12px;
            font-weight: 900;
        }

        .section-header h2 {
            margin: 0;
            color: var(--text);
            font-size: 38px;
            line-height: 1.18;
            letter-spacing: -0.8px;
        }

        .section-header h2 span {
            color: var(--orange);
        }

        .section-header p {
            margin: 12px 0 0;
            color: var(--muted);
            font-size: 16px;
            line-height: 1.75;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .info-card {
            padding: 24px;
            background: white;
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: var(--shadow-soft);
        }

        .info-icon {
            width: 56px;
            height: 56px;
            display: grid;
            place-items: center;
            margin-bottom: 16px;
            border-radius: 18px;
            background: var(--blue-soft);
            color: var(--blue);
            font-size: 26px;
        }

        .info-card h3 {
            margin: 0 0 8px;
            color: var(--text);
            font-size: 20px;
        }

        .info-card p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .price-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 18px;
        }

        .price-box {
            padding: 12px;
            border-radius: 16px;
            background: #f8fbff;
            border: 1px solid #e8f0fc;
        }

        .price-box span {
            display: block;
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 800;
        }

        .price-box strong {
            color: var(--orange);
            font-size: 15px;
        }

        .type-panel {
            padding: 34px;
            border-radius: 30px;
            background: var(--navy);
            color: white;
            box-shadow: var(--shadow);
        }

        .type-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .type-card {
            padding: 28px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .type-card h3 {
            margin: 0 0 10px;
            color: white;
            font-size: 24px;
        }

        .type-card p {
            margin: 0;
            color: rgba(255, 255, 255, 0.78);
            line-height: 1.7;
        }

        .type-card ul {
            margin: 16px 0 0;
            padding-left: 18px;
            color: rgba(255, 255, 255, 0.88);
            font-size: 14px;
            line-height: 1.9;
        }

        .cta {
            padding: 78px 0 58px;
        }

        .cta-box {
            padding: 44px 26px;
            text-align: center;
            background:
                linear-gradient(135deg, rgba(11, 99, 246, 0.08), rgba(255, 122, 0, 0.10)),
                white;
            border: 1px solid var(--line);
            border-radius: 30px;
            box-shadow: var(--shadow-soft);
        }

        .cta-logo {
            width: 84px;
            height: 84px;
            margin: 0 auto 18px;
            border-radius: 24px;
            background: white;
            border: 1px solid var(--line);
            overflow: hidden;
        }

        .cta-box h2 {
            margin: 0;
            color: var(--text);
            font-size: 38px;
            letter-spacing: -0.8px;
        }

        .cta-box h2 span {
            color: var(--orange);
        }

        .cta-box p {
            max-width: 720px;
            margin: 14px auto 26px;
            color: var(--muted);
            line-height: 1.75;
        }

        .footer {
            padding: 0 0 34px;
            color: var(--muted);
            font-size: 14px;
        }

        .footer-inner {
            padding-top: 22px;
            border-top: 1px solid var(--line);
            display: flex;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .footer strong {
            color: var(--blue);
        }

        @media (max-width: 1120px) {
            .nav-menu {
                gap: 22px;
            }

            .hero-title {
                font-size: 58px;
            }

            .logo-card-3d {
                right: 20px;
                width: 320px;
                height: 320px;
            }

            .visual-orbit {
                right: -34px;
            }

            .badge-one,
            .badge-two {
                display: none;
            }
        }

        @media (max-width: 980px) {
            .hero {
                min-height: auto;
                padding-bottom: 160px;
            }

            .navbar {
                height: 84px;
            }

            .nav-menu {
                display: none;
            }

            .hero-content {
                grid-template-columns: 1fr;
                gap: 34px;
                padding-top: 34px;
            }

            .hero-left {
                max-width: 100%;
            }

            .hero-title {
                font-size: 52px;
            }

            .hero-desc {
                max-width: 720px;
            }

            .stats {
                grid-template-columns: repeat(3, 1fr);
                max-width: 100%;
                margin-top: 44px;
            }

            .hero-visual {
                min-height: 360px;
            }

            .logo-card-3d {
                left: 50%;
                right: auto;
                top: 20px;
                transform: translateX(-50%) rotateX(9deg) rotateY(-14deg) rotateZ(-5deg);
            }

            .visual-orbit,
            .visual-ring {
                left: 50%;
                right: auto;
                transform: translateX(-50%);
            }

            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            @keyframes floatCard {
                0%, 100% {
                    transform: translateX(-50%) rotateX(9deg) rotateY(-14deg) rotateZ(-5deg) translateY(0);
                }
                50% {
                    transform: translateX(-50%) rotateX(7deg) rotateY(-11deg) rotateZ(-4deg) translateY(-12px);
                }
            }
        }

        @media (max-width: 760px) {
            .container {
                width: min(100% - 28px, 1140px);
            }

            .hero {
                padding-bottom: 125px;
            }

            .brand-text strong {
                font-size: 18px;
            }

            .brand-text span {
                font-size: 12px;
            }

            .brand-logo {
                width: 48px;
                height: 48px;
            }

            .hero-title {
                font-size: 42px;
                letter-spacing: -1px;
            }

            .hero-desc {
                font-size: 15px;
            }

            .hero-actions {
                flex-direction: column;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
            }

            .stats {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .hero-visual {
                min-height: 290px;
            }

            .logo-card-3d {
                width: 240px;
                height: 240px;
            }

            .logo-card-face {
                border-radius: 46px;
                padding: 18px;
            }

            .logo-card-face::before {
                inset: 12px;
                border-radius: 36px;
            }

            .logo-card-inner {
                border-radius: 30px;
            }

            .logo-card-3d::before {
                border-radius: 42px;
                inset: 14px -10px -16px 18px;
            }

            .visual-orbit {
                width: 310px;
                height: 310px;
            }

            .visual-ring {
                width: 255px;
                height: 255px;
            }

            .quick-order {
                margin-top: -58px;
            }

            .quick-order-card {
                padding: 30px 20px;
            }

            .quick-order-card h2 {
                font-size: 28px;
                margin-bottom: 26px;
            }

            .step-row {
                grid-template-columns: 1fr;
                gap: 22px;
            }

            .arrow {
                display: none;
            }

            .service-grid,
            .category-grid,
            .type-grid {
                grid-template-columns: 1fr;
            }

            section.content-section {
                padding-top: 62px;
            }

            .section-header h2,
            .cta-box h2 {
                font-size: 30px;
            }

            .section-header p {
                font-size: 15px;
            }

            .type-panel {
                padding: 26px 20px;
            }

            .price-wrap {
                grid-template-columns: 1fr;
            }

            .cta {
                padding-top: 62px;
                padding-bottom: 38px;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: min(100% - 22px, 1140px);
            }

            .hero {
                padding-bottom: 110px;
            }

            .navbar {
                height: 78px;
            }

            .brand-text span {
                display: none;
            }

            .brand-logo {
                width: 44px;
                height: 44px;
            }

            .hero-content {
                padding-top: 24px;
            }

            .hero-title {
                font-size: 36px;
            }

            .hero-visual {
                min-height: 250px;
            }

            .logo-card-3d {
                width: 215px;
                height: 215px;
            }

            .visual-orbit {
                width: 270px;
                height: 270px;
            }

            .visual-ring {
                width: 220px;
                height: 220px;
            }

            .quick-order {
                margin-top: -46px;
            }
        }
    </style>
</head>
<body>
    <section class="hero">
        <div class="container">
            <header class="navbar">
                <a href="/" class="brand">
                    <div class="brand-logo">
                        <img src="{{ asset('images/logo-esgul-suruh.png') }}" alt="Logo Esgul Suruh">
                    </div>

                    <div class="brand-text">
                        <strong>ESGUL SURUH</strong>
                        <span>Jasa Titip & Suruh</span>
                    </div>
                </a>

                <nav class="nav-menu">
                    <a href="/">Beranda</a>
                    <a href="#layanan">Layanan</a>
                    <a href="#kategori">Kategori</a>
                    <a href="#cara-order">Cara Order</a>
                    <a href="#tipe">Tipe Layanan</a>
                    <a href="/admin">Admin</a>
                    <a href="{{ route('buat-permintaan') }}" class="nav-button">Buat Request</a>
                </nav>
            </header>

            <div class="hero-content">
                <div class="hero-left">
                    <h1 class="hero-title">
                        Praktis, Cepat,<br>
                        <span>Amanah.</span>
                    </h1>

                    <p class="hero-desc">
                        Esgul Suruh hadir untuk memudahkan mahasiswa dalam menyelesaikan berbagai
                        keperluan harian dengan bantuan yang terpercaya.
                    </p>

                    <div class="hero-actions">
                        <a href="{{ route('buat-permintaan') }}" class="btn-primary">
                            Buat Request Sekarang
                        </a>

                        <a href="#kategori" class="btn-secondary">
                            Lihat Kategori
                        </a>
                    </div>

                    <div class="stats">
                        <div class="stat-item">
                            <div class="stat-icon">⚡</div>
                            <div>
                                <strong>Cepat & Praktis</strong>
                                <span>Proses kilat tanpa ribet</span>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">🤝</div>
                            <div>
                                <strong>Amanah & Terpercaya</strong>
                                <span>Dijamin amanah & teliti</span>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">🕘</div>
                            <div>
                                <strong>Layanan 24/7</strong>
                                <span>Siap bantu kapan aja</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="visual-orbit"></div>
                    <div class="visual-ring"></div>

                    <div class="mini-badge badge-one">
                        <span>✓</span>
                        Request Cepat
                    </div>

                    <div class="mini-badge badge-two">
                        <span>💬</span>
                        Via WhatsApp
                    </div>

                    <div class="logo-card-3d">
                        <div class="logo-card-face">
                            <div class="logo-card-shine"></div>

                            <div class="logo-card-inner">
                                <img src="{{ asset('images/logo-esgul-suruh.png') }}" alt="Logo Esgul Suruh">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="cara-order" class="quick-order">
        <div class="container">
            <div class="quick-order-card">
                <h2>Cara Order</h2>

                <div class="step-row">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h3>Pilih Kategori</h3>
                        <p>Pilih layanan yang kamu butuhkan</p>
                    </div>

                    <div class="arrow">›</div>

                    <div class="step">
                        <div class="step-number">2</div>
                        <h3>Isi Detail</h3>
                        <p>Lengkapi form request dengan jelas</p>
                    </div>

                    <div class="arrow">›</div>

                    <div class="step">
                        <div class="step-number">3</div>
                        <h3>Pilih Tipe Layanan</h3>
                        <p>Normal atau Express sesuai kebutuhanmu</p>
                    </div>

                    <div class="arrow">›</div>

                    <div class="step">
                        <div class="step-number">4</div>
                        <h3>Chat via WhatsApp</h3>
                        <p>Langsung terhubung dengan admin kami</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="layanan" class="content-section">
        <div class="container">
            <div class="section-header">
                <div class="section-tag">Layanan Kami</div>
                <h2>Bantu kebutuhan mahasiswa dengan <span>lebih mudah</span></h2>
                <p>
                    Layanan dibuat untuk membantu aktivitas harian mahasiswa agar lebih praktis,
                    cepat, dan tetap terkelola melalui sistem.
                </p>
            </div>

            <div class="service-grid">
                <div class="info-card">
                    <div class="info-icon">🍔</div>
                    <h3>Titip Beli</h3>
                    <p>Bantu beliin makanan, minuman, atau kebutuhan harian di sekitar kampus.</p>
                </div>

                <div class="info-card">
                    <div class="info-icon">📦</div>
                    <h3>Ambil Paket</h3>
                    <p>Bantu ambil paket atau barang saat kamu sedang sibuk kuliah atau kegiatan lain.</p>
                </div>

                <div class="info-card">
                    <div class="info-icon">🧹</div>
                    <h3>Jasa Suruh</h3>
                    <p>Bantu urusan tertentu seperti bersih kost, survei tempat, antar jemput, dan lainnya.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="kategori" class="content-section">
        <div class="container">
            <div class="section-header">
                <div class="section-tag">Kategori & Harga</div>
                <h2>Daftar layanan yang <span>tersedia</span></h2>
                <p>
                    Harga layanan normal dan express diambil langsung dari data kategori yang dikelola melalui admin panel.
                </p>
            </div>

            <div class="category-grid">
                @forelse ($kategoriLayanan as $kategori)
                    <div class="info-card">
                        <div class="info-icon">🛵</div>
                        <h3>{{ $kategori->nama }}</h3>
                        <p>{{ $kategori->deskripsi ?? 'Layanan tersedia untuk membantu kebutuhan mahasiswa.' }}</p>

                        <div class="price-wrap">
                            <div class="price-box">
                                <span>Normal</span>
                                <strong>Rp{{ number_format((float) $kategori->biaya_normal, 0, ',', '.') }}</strong>
                            </div>

                            <div class="price-box">
                                <span>Express</span>
                                <strong>Rp{{ number_format((float) $kategori->biaya_express, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="info-card">
                        <div class="info-icon">📌</div>
                        <h3>Belum Ada Kategori</h3>
                        <p>Tambahkan kategori layanan terlebih dahulu melalui Filament Admin Panel.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="tipe" class="content-section">
        <div class="container">
            <div class="type-panel">
                <div class="section-header" style="margin-bottom: 28px;">
                    <div class="section-tag" style="background: rgba(255,255,255,0.12); color: white;">
                        Tipe Layanan
                    </div>

                    <h2 style="color: white;">
                        Pilih layanan sesuai <span>kebutuhanmu</span>
                    </h2>

                    <p style="color: rgba(255,255,255,0.78);">
                        Sistem menyediakan pilihan normal dan express agar pengguna bisa menyesuaikan antara biaya dan kecepatan proses.
                    </p>
                </div>

                <div class="type-grid">
                    <div class="type-card">
                        <h3>Normal</h3>
                        <p>Layanan dengan biaya lebih hemat dan estimasi pengerjaan yang lebih santai.</p>
                        <ul>
                            <li>Biaya lebih murah</li>
                            <li>Cocok untuk kebutuhan non-mendesak</li>
                            <li>Estimasi waktu lebih fleksibel</li>
                        </ul>
                    </div>

                    <div class="type-card">
                        <h3>Express</h3>
                        <p>Layanan prioritas untuk kebutuhan yang perlu diproses lebih cepat.</p>
                        <ul>
                            <li>Proses lebih cepat</li>
                            <li>Prioritas pengerjaan lebih tinggi</li>
                            <li>Cocok untuk kebutuhan urgent</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <div class="cta-box">
                <div class="cta-logo">
                    <img src="{{ asset('images/logo-esgul-suruh.png') }}" alt="Logo Esgul Suruh">
                </div>

                <h2>Mau nyuruh? <span>Gampang banget.</span></h2>

                <p>
                    Isi request layanan sekarang, pilih kategori dan tipe layanan,
                    lalu lanjutkan komunikasi langsung melalui WhatsApp admin.
                </p>

                <a href="{{ route('buat-permintaan') }}" class="btn-primary">
                    Buat Request Sekarang
                </a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-inner">
                <div>
                    <strong>Esgul Suruh</strong> — Sistem Informasi Jasa Titip dan Suruh Mahasiswa
                </div>
                <div>
                    Laravel • Filament v3 • Livewire • MariaDB • Docker
                </div>
            </div>
        </div>
    </footer>
</body>
</html>