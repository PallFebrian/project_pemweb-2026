<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="ESA Runner adalah sistem layanan jasa titip dan jasa suruh mahasiswa Universitas Esa Unggul Citra Raya."
    >

    <title>ESA Runner - Jasa Titip dan Suruh Mahasiswa</title>

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
            --green: #16a36a;
            --white: #ffffff;
            --bg: #f4f8ff;
            --text: #101f3d;
            --muted: #667085;
            --line: #e4ecf7;

            --shadow:
                0 22px 60px rgba(6, 26, 58, 0.12);

            --shadow-soft:
                0 14px 36px rgba(16, 33, 63, 0.08);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            overflow-x: hidden;
            color: var(--text);
            background: var(--bg);
            font-family:
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        body.menu-open {
            overflow: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font: inherit;
        }

        img {
            display: block;
            max-width: 100%;
        }

        .container {
            width: min(1140px, calc(100% - 40px));
            margin: 0 auto;
        }

        /* =========================
           HERO
        ========================== */

        .hero {
            position: relative;
            min-height: 760px;
            overflow: hidden;
            padding-bottom: 150px;
            color: var(--white);
            background:
                radial-gradient(
                    circle at 70% 38%,
                    rgba(11, 99, 246, 0.40),
                    transparent 22%
                ),
                radial-gradient(
                    circle at 92% 48%,
                    rgba(11, 99, 246, 0.18),
                    transparent 30%
                ),
                linear-gradient(
                    135deg,
                    #031127 0%,
                    #061b42 48%,
                    #082b63 100%
                );
        }

        .hero::before {
            position: absolute;
            right: -120px;
            bottom: -180px;
            z-index: 1;
            width: 680px;
            height: 390px;
            border-radius: 55% 45% 0 0;
            background: rgba(255, 255, 255, 0.07);
            transform: rotate(-5deg);
            content: "";
        }

        .hero::after {
            position: absolute;
            right: -120px;
            bottom: -215px;
            left: -120px;
            z-index: 2;
            height: 330px;
            border-radius: 50% 50% 0 0;
            background: var(--bg);
            content: "";
        }

        /* =========================
           NAVBAR
        ========================== */

        .navbar {
            position: relative;
            z-index: 20;
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
            flex-shrink: 0;
            color: var(--white);
            font-weight: 900;
        }

        .brand-logo {
            width: 56px;
            height: 56px;
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid rgba(255, 255, 255, 0.20);
            border-radius: 16px;
            background: var(--white);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
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
            gap: 28px;
        }

        .nav-menu a {
            color: rgba(255, 255, 255, 0.84);
            font-size: 14px;
            font-weight: 800;
            white-space: nowrap;
            transition:
                color 0.2s ease,
                transform 0.2s ease;
        }

        .nav-menu a:hover {
            color: var(--white);
            transform: translateY(-1px);
        }

        .nav-button {
            padding: 13px 18px;
            border: 1px solid rgba(255, 255, 255, 0.44);
            border-radius: 13px;
            color: var(--white) !important;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(8px);
        }

        .menu-toggle {
            width: 44px;
            height: 44px;
            display: none;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, 0.30);
            border-radius: 12px;
            color: var(--white);
            background: rgba(255, 255, 255, 0.06);
            cursor: pointer;
        }

        .menu-toggle span,
        .menu-toggle::before,
        .menu-toggle::after {
            width: 20px;
            height: 2px;
            display: block;
            border-radius: 999px;
            background: currentColor;
            content: "";
        }

        .menu-toggle span {
            margin: 4px 0;
        }

        /* =========================
           HERO CONTENT
        ========================== */

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
            color: var(--white);
            font-size: clamp(52px, 6vw, 70px);
            line-height: 1.02;
            letter-spacing: -2px;
        }

        .hero-title span {
            color: var(--orange);
        }

        .hero-description {
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

        .button {
            min-height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 28px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 900;
            transition:
                transform 0.2s ease,
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .button:hover {
            transform: translateY(-2px);
        }

        .button-primary {
            color: var(--white);
            background: var(--orange);
            box-shadow: 0 16px 34px rgba(255, 122, 0, 0.28);
        }

        .button-primary:hover {
            background: var(--orange-dark);
        }

        .button-secondary {
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.42);
            background: rgba(255, 255, 255, 0.05);
        }

        .button-secondary:hover {
            border-color: rgba(255, 255, 255, 0.75);
        }

        .stats {
            position: relative;
            z-index: 6;
            max-width: 720px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
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
            display: grid;
            place-items: center;
            flex-shrink: 0;
            border-radius: 16px;
            color: var(--white);
            background: rgba(11, 99, 246, 0.28);
            font-size: 20px;
        }

        .stat-item strong {
            display: block;
            margin-bottom: 4px;
            color: var(--white);
            font-size: 14px;
            line-height: 1.2;
        }

        .stat-item span {
            display: block;
            color: rgba(255, 255, 255, 0.70);
            font-size: 12px;
            line-height: 1.4;
        }

        /* =========================
           HERO VISUAL DESKTOP
        ========================== */

        .hero-visual {
            position: relative;
            min-height: 440px;
            perspective: 1200px;
        }

        .visual-orbit {
            position: absolute;
            top: -28px;
            right: -24px;
            z-index: 1;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background:
                radial-gradient(
                    circle at 40% 38%,
                    rgba(11, 99, 246, 0.50),
                    transparent 42%
                ),
                radial-gradient(
                    circle at 70% 72%,
                    rgba(255, 122, 0, 0.18),
                    transparent 36%
                );
            filter: blur(2px);
            opacity: 0.95;
        }

        .visual-ring {
            position: absolute;
            top: 20px;
            right: 18px;
            z-index: 2;
            width: 365px;
            height: 365px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.025);
        }

        .visual-ring::before {
            position: absolute;
            inset: 42px;
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 50%;
            content: "";
        }

        .logo-card-3d {
            position: absolute;
            top: 34px;
            right: 46px;
            z-index: 5;
            width: 350px;
            height: 350px;
            transform-style: preserve-3d;
            transform:
                rotateX(9deg)
                rotateY(-14deg)
                rotateZ(-5deg);
            animation: float-card 5s ease-in-out infinite;
        }

        .logo-card-3d::before {
            position: absolute;
            inset: 18px -14px -22px 24px;
            z-index: 0;
            border-radius: 58px;
            background:
                linear-gradient(
                    135deg,
                    rgba(174, 191, 218, 0.95),
                    rgba(228, 235, 248, 0.92)
                );
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.28);
            transform: translateZ(-34px);
            content: "";
        }

        .logo-card-3d::after {
            position: absolute;
            right: 18px;
            bottom: -34px;
            left: 36px;
            z-index: -1;
            height: 36px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.30);
            filter: blur(18px);
            transform: rotateZ(5deg);
            content: "";
        }

        .logo-card-face {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            padding: 26px;
            border: 1px solid rgba(255, 255, 255, 0.82);
            border-radius: 62px;
            background:
                linear-gradient(
                    145deg,
                    #ffffff 0%,
                    #f4f8ff 46%,
                    #dfe8f7 100%
                );
            box-shadow:
                0 34px 80px rgba(0, 0, 0, 0.34),
                inset 0 2px 0 rgba(255, 255, 255, 0.96),
                inset -16px -18px 36px rgba(123, 145, 183, 0.22);
        }

        .logo-card-face::before {
            position: absolute;
            inset: 18px;
            z-index: 1;
            border-radius: 48px;
            background:
                linear-gradient(
                    145deg,
                    rgba(255, 255, 255, 0.82),
                    rgba(233, 240, 251, 0.72)
                );
            box-shadow:
                inset 12px 12px 24px rgba(255, 255, 255, 0.70),
                inset -12px -14px 28px rgba(96, 120, 164, 0.10);
            content: "";
        }

        .logo-card-shine {
            position: absolute;
            top: -90px;
            left: -90px;
            z-index: 4;
            width: 230px;
            height: 430px;
            background:
                linear-gradient(
                    110deg,
                    transparent 0%,
                    rgba(255, 255, 255, 0.02) 35%,
                    rgba(255, 255, 255, 0.58) 50%,
                    rgba(255, 255, 255, 0.02) 66%,
                    transparent 100%
                );
            opacity: 0.75;
            pointer-events: none;
            transform: rotate(18deg);
        }

        .logo-card-inner {
            position: relative;
            z-index: 3;
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            overflow: hidden;
            border-radius: 42px;
            background:
                radial-gradient(
                    circle at 25% 18%,
                    rgba(255, 255, 255, 0.95),
                    transparent 34%
                ),
                linear-gradient(
                    145deg,
                    #ffffff 0%,
                    #f9fbff 55%,
                    #edf3fb 100%
                );
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.95),
                0 18px 40px rgba(16, 33, 63, 0.10);
        }

        .logo-card-inner::after {
            position: absolute;
            bottom: 40px;
            left: 50%;
            width: 140px;
            height: 18px;
            border-radius: 50%;
            background: rgba(16, 33, 63, 0.12);
            filter: blur(4px);
            transform: translateX(-50%);
            content: "";
        }

        .logo-card-inner img {
            position: relative;
            z-index: 2;
            width: 76%;
            height: 76%;
            object-fit: contain;
            filter:
                drop-shadow(
                    0 12px 12px rgba(16, 33, 63, 0.08)
                );
        }

        @keyframes float-card {
            0%,
            100% {
                transform:
                    rotateX(9deg)
                    rotateY(-14deg)
                    rotateZ(-5deg)
                    translateY(0);
            }

            50% {
                transform:
                    rotateX(7deg)
                    rotateY(-11deg)
                    rotateZ(-4deg)
                    translateY(-12px);
            }
        }

        /* =========================
           CARA ORDER
        ========================== */

        .quick-order {
            position: relative;
            z-index: 20;
            margin-top: 26px;
        }

        .quick-order-card {
            padding: 38px 34px 40px;
            border: 1px solid var(--line);
            border-radius: 26px;
            background: var(--white);
            box-shadow: 0 18px 46px rgba(16, 33, 63, 0.10);
        }

        .quick-order-card h2 {
            margin: 0 0 34px;
            color: var(--text);
            text-align: center;
            font-size: 32px;
            letter-spacing: -0.6px;
        }

        .step-row {
            display: grid;
            grid-template-columns:
                1fr auto
                1fr auto
                1fr auto
                1fr;
            gap: 18px;
            align-items: start;
        }

        .step {
            min-width: 0;
            text-align: center;
        }

        .step-number {
            width: 58px;
            height: 58px;
            display: grid;
            place-items: center;
            margin: 0 auto 16px;
            border-radius: 50%;
            color: var(--white);
            background: var(--blue);
            box-shadow: 0 12px 24px rgba(11, 99, 246, 0.18);
            font-size: 22px;
            font-weight: 900;
        }

        .step:last-child .step-number {
            background: var(--orange);
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
            line-height: 1.55;
        }

        .arrow {
            padding-top: 16px;
            color: var(--blue);
            font-size: 30px;
            font-weight: 900;
        }

        /* =========================
           SECTION GENERAL
        ========================== */

        .content-section {
            padding: 84px 0 0;
        }

        .section-header {
            max-width: 760px;
            margin: 0 auto 38px;
            text-align: center;
        }

        .section-tag {
            display: inline-flex;
            padding: 8px 14px;
            margin-bottom: 14px;
            border-radius: 999px;
            color: var(--blue);
            background: var(--blue-soft);
            font-size: 12px;
            font-weight: 900;
        }

        .section-header h2 {
            margin: 0;
            color: var(--text);
            font-size: clamp(31px, 5vw, 40px);
            line-height: 1.18;
            letter-spacing: -0.8px;
        }

        .section-header h2 span {
            color: var(--orange);
        }

        .section-header p {
            max-width: 680px;
            margin: 13px auto 0;
            color: var(--muted);
            font-size: 16px;
            line-height: 1.75;
        }

        /* =========================
           KATEGORI DINAMIS
        ========================== */

        .category-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .category-card {
            padding: 25px;
            border: 1px solid var(--line);
            border-radius: 23px;
            background: var(--white);
            box-shadow: var(--shadow-soft);
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow);
        }

        .category-heading {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .category-icon {
            width: 52px;
            height: 52px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            border-radius: 17px;
            color: var(--blue);
            background: var(--blue-soft);
            font-size: 24px;
        }

        .category-card h3 {
            margin: 0;
            color: var(--text);
            font-size: 19px;
        }

        .category-card p {
            min-height: 70px;
            margin: 16px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .price-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 20px;
        }

        .price-box {
            padding: 13px;
            border: 1px solid #e8f0fc;
            border-radius: 15px;
            background: #f8fbff;
        }

        .price-box span {
            display: block;
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
        }

        .price-box strong {
            display: block;
            margin-top: 5px;
            color: var(--text);
            font-size: 15px;
        }

        .price-box:last-child strong {
            color: var(--orange);
        }

        /* =========================
           RUTE DAN BIAYA
        ========================== */

        .route-section {
            margin-top: 84px;
            padding: 84px 0;
            background: var(--white);
        }

        .route-panel {
            display: grid;
            grid-template-columns: 0.85fr 1.15fr;
            gap: 46px;
            align-items: center;
            padding: 42px;
            border: 1px solid var(--line);
            border-radius: 30px;
            background: var(--bg);
        }

        .route-copy h2 {
            margin: 0;
            color: var(--text);
            font-size: clamp(31px, 5vw, 42px);
            line-height: 1.16;
            letter-spacing: -1px;
        }

        .route-copy h2 span {
            color: var(--orange);
        }

        .route-copy p {
            margin: 16px 0 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.75;
        }

        .route-list {
            display: grid;
            gap: 13px;
            margin-top: 24px;
        }

        .route-list-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: var(--text);
            font-size: 13px;
            line-height: 1.6;
        }

        .check-icon {
            width: 22px;
            height: 22px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            border-radius: 50%;
            color: var(--white);
            background: var(--green);
            font-size: 11px;
            font-weight: 900;
        }

        .map-preview {
            position: relative;
            min-height: 360px;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 23px;
            background:
                linear-gradient(
                    rgba(11, 99, 246, 0.06) 1px,
                    transparent 1px
                ),
                linear-gradient(
                    90deg,
                    rgba(11, 99, 246, 0.06) 1px,
                    transparent 1px
                ),
                #edf3fb;
            background-size: 30px 30px;
            box-shadow: var(--shadow-soft);
        }

        .map-preview::before,
        .map-preview::after {
            position: absolute;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.80);
            content: "";
        }

        .map-preview::before {
            top: 70px;
            left: -40px;
            width: 420px;
            height: 18px;
            transform: rotate(24deg);
        }

        .map-preview::after {
            top: 168px;
            right: -60px;
            width: 390px;
            height: 16px;
            transform: rotate(-31deg);
        }

        .map-route {
            position: absolute;
            top: 87px;
            left: 88px;
            z-index: 2;
            width: 310px;
            height: 184px;
            border-bottom: 7px solid var(--blue);
            border-left: 7px solid var(--blue);
            border-radius: 0 0 0 55px;
            transform: rotate(-8deg);
        }

        .map-route::before {
            position: absolute;
            top: 43px;
            right: -4px;
            width: 150px;
            height: 7px;
            border-radius: 999px;
            background: var(--blue);
            transform: rotate(-47deg);
            transform-origin: right center;
            content: "";
        }

        .map-marker {
            position: absolute;
            z-index: 4;
            width: 37px;
            height: 37px;
            display: grid;
            place-items: center;
            border: 5px solid var(--white);
            border-radius: 50%;
            color: var(--white);
            background: var(--blue);
            box-shadow: 0 8px 20px rgba(16, 33, 63, 0.25);
            font-size: 11px;
            font-weight: 900;
        }

        .marker-one {
            top: 235px;
            left: 70px;
            background: var(--green);
        }

        .marker-two {
            top: 152px;
            left: 238px;
        }

        .marker-three {
            top: 74px;
            right: 66px;
            background: var(--orange);
        }

        .map-label {
            position: absolute;
            top: 18px;
            left: 18px;
            z-index: 5;
            padding: 9px 12px;
            border-radius: 999px;
            color: var(--blue);
            background: rgba(255, 255, 255, 0.94);
            box-shadow: var(--shadow-soft);
            font-size: 10px;
            font-weight: 900;
        }

        .map-summary {
            position: absolute;
            right: 17px;
            bottom: 17px;
            left: 17px;
            z-index: 5;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 9px;
            padding: 13px;
            border: 1px solid rgba(255, 255, 255, 0.80);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(10px);
        }

        .map-summary span {
            display: block;
            color: var(--muted);
            font-size: 9px;
        }

        .map-summary strong {
            display: block;
            margin-top: 4px;
            color: var(--text);
            font-size: 12px;
        }

        /* =========================
           TIPE LAYANAN
        ========================== */

        .type-section {
            padding: 84px 0;
        }

        .type-panel {
            position: relative;
            overflow: hidden;
            padding: 46px;
            border-radius: 30px;
            color: var(--white);
            background:
                radial-gradient(
                    circle at 90% 10%,
                    rgba(11, 99, 246, 0.42),
                    transparent 27%
                ),
                linear-gradient(
                    135deg,
                    var(--navy),
                    var(--navy-2)
                );
            box-shadow: var(--shadow);
        }

        .type-panel::after {
            position: absolute;
            right: -130px;
            bottom: -220px;
            width: 460px;
            height: 460px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            content: "";
        }

        .type-heading {
            position: relative;
            z-index: 2;
            max-width: 650px;
            margin-bottom: 30px;
        }

        .type-heading h2 {
            margin: 0;
            color: var(--white);
            font-size: clamp(31px, 5vw, 42px);
            line-height: 1.16;
            letter-spacing: -1px;
        }

        .type-heading h2 span {
            color: var(--orange);
        }

        .type-heading p {
            margin: 14px 0 0;
            color: rgba(255, 255, 255, 0.72);
            line-height: 1.7;
        }

        .type-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .type-card {
            padding: 28px;
            border: 1px solid rgba(255, 255, 255, 0.13);
            border-radius: 23px;
            background: rgba(255, 255, 255, 0.08);
        }

        .type-card.express {
            border-color: rgba(255, 122, 0, 0.65);
        }

        .type-card h3 {
            margin: 0;
            color: var(--white);
            font-size: 24px;
        }

        .type-card.express h3 {
            color: #ff9b3d;
        }

        .type-card p {
            margin: 10px 0 0;
            color: rgba(255, 255, 255, 0.72);
            font-size: 14px;
            line-height: 1.7;
        }

        .type-card ul {
            margin: 18px 0 0;
            padding: 0;
            list-style: none;
        }

        .type-card li {
            display: flex;
            gap: 9px;
            color: rgba(255, 255, 255, 0.86);
            font-size: 13px;
            line-height: 1.9;
        }

        .type-card li::before {
            color: #3bd39b;
            content: "✓";
            font-weight: 900;
        }

        /* =========================
           CTA
        ========================== */

        .cta {
            padding: 0 0 78px;
        }

        .cta-box {
            padding: 50px 30px;
            border: 1px solid var(--line);
            border-radius: 30px;
            text-align: center;
            background:
                radial-gradient(
                    circle at 15% 20%,
                    rgba(11, 99, 246, 0.10),
                    transparent 27%
                ),
                radial-gradient(
                    circle at 85% 80%,
                    rgba(255, 122, 0, 0.12),
                    transparent 26%
                ),
                var(--white);
            box-shadow: var(--shadow-soft);
        }

        .cta-box h2 {
            margin: 0;
            color: var(--text);
            font-size: clamp(31px, 5vw, 42px);
            line-height: 1.16;
            letter-spacing: -1px;
        }

        .cta-box h2 span {
            color: var(--orange);
        }

        .cta-box p {
            max-width: 650px;
            margin: 15px auto 26px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.75;
        }

        .cta-actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 14px;
        }

        .cta-actions .button-secondary {
            color: var(--blue);
            border-color: var(--line);
            background: var(--white);
            box-shadow: var(--shadow-soft);
        }

        /* =========================
           FOOTER
        ========================== */

        .footer {
            color: rgba(255, 255, 255, 0.66);
            background: var(--navy);
        }

        .footer-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            padding: 38px 0;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-brand .brand-logo {
            width: 48px;
            height: 48px;
            border-radius: 14px;
        }

        .footer-brand strong {
            display: block;
            color: var(--white);
            font-size: 17px;
        }

        .footer-brand span {
            display: block;
            margin-top: 4px;
            font-size: 11px;
        }

        .footer-links {
            display: flex;
            gap: 22px;
            flex-wrap: wrap;
        }

        .footer-links a {
            font-size: 12px;
            font-weight: 700;
        }

        .footer-bottom {
            padding: 19px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.10);
            font-size: 11px;
            text-align: center;
        }

        /* =========================
           RESPONSIVE
        ========================== */

        @media (max-width: 1100px) {
            .hero {
                min-height: auto;
                padding-bottom: 170px;
            }

            .hero-content {
                grid-template-columns: 1fr;
            }

            .hero-left {
                max-width: 100%;
            }

            .hero-description {
                max-width: 760px;
            }

            .hero-visual {
                display: none;
            }

            .stats {
                max-width: 850px;
            }
        }

        @media (max-width: 1050px) {
            .menu-toggle {
                display: grid;
            }

            .nav-menu {
                position: fixed;
                top: 78px;
                right: 16px;
                left: 16px;
                display: none;
                flex-direction: column;
                align-items: stretch;
                gap: 3px;
                padding: 16px;
                border: 1px solid rgba(255, 255, 255, 0.14);
                border-radius: 18px;
                background: rgba(4, 21, 47, 0.98);
                box-shadow: var(--shadow);
            }

            .nav-menu.is-open {
                display: flex;
            }

            .nav-menu a {
                padding: 12px;
                border-radius: 10px;
            }

            .nav-button {
                margin-top: 4px;
            }
        }

        @media (max-width: 980px) {
            .hero-content {
                padding-top: 34px;
            }

            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .route-panel {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 760px) {
            .container {
                width: min(100% - 28px, 1140px);
            }

            .hero {
                padding-bottom: 165px;
            }

            .navbar {
                height: 84px;
            }

            .brand-logo {
                width: 48px;
                height: 48px;
            }

            .brand-text strong {
                font-size: 18px;
            }

            .brand-text span {
                font-size: 11px;
            }

            .hero-content {
                gap: 0;
                padding-top: 34px;
            }

            .hero-title {
                font-size: 42px;
                letter-spacing: -1px;
            }

            .hero-description {
                font-size: 15px;
            }

            .hero-actions {
                flex-direction: column;
            }

            .button {
                width: 100%;
            }

            .stats {
                grid-template-columns: 1fr;
                gap: 16px;
                margin-top: 46px;
            }

            .quick-order {
                margin-top: -35px;
            }

            .quick-order-card {
                padding: 30px 20px;
            }

            .quick-order-card h2 {
                margin-bottom: 26px;
                font-size: 28px;
            }

            .step-row {
                grid-template-columns: 1fr;
                gap: 22px;
            }

            .arrow {
                display: none;
            }

            .content-section {
                padding-top: 64px;
            }

            .category-grid,
            .type-grid {
                grid-template-columns: 1fr;
            }

            .category-card p {
                min-height: auto;
            }

            .route-section {
                margin-top: 64px;
                padding: 64px 0;
            }

            .route-panel {
                gap: 32px;
                padding: 28px 20px;
            }

            .map-preview {
                min-height: 330px;
            }

            .map-route {
                top: 92px;
                left: 45px;
                width: 220px;
                height: 145px;
            }

            .marker-one {
                top: 218px;
                left: 28px;
            }

            .marker-two {
                top: 148px;
                left: 168px;
            }

            .marker-three {
                top: 76px;
                right: 28px;
            }

            .map-summary {
                grid-template-columns: 1fr;
            }

            .type-section {
                padding: 64px 0;
            }

            .type-panel {
                padding: 32px 20px;
            }

            .cta {
                padding-bottom: 54px;
            }

            .cta-actions {
                flex-direction: column;
            }

            .footer-main {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: min(100% - 22px, 1140px);
            }

            .hero {
                padding-bottom: 150px;
            }

            .navbar {
                height: 78px;
            }

            .brand-logo {
                width: 44px;
                height: 44px;
            }

            .brand-text strong {
                font-size: 16px;
            }

            .brand-text span {
                display: none;
            }

            .hero-content {
                padding-top: 24px;
            }

            .hero-title {
                font-size: 36px;
            }

            .quick-order {
                margin-top: -28px;
            }

            .map-preview {
                min-height: 360px;
            }

            .map-route {
                left: 28px;
                width: 190px;
            }

            .marker-two {
                left: 142px;
            }

            .footer-links {
                gap: 14px;
            }
        }
    </style>
</head>

<body>
    <section
        class="hero"
        id="beranda"
    >
        <div class="container">
            <header class="navbar">
                <a
                    href="{{ route('landing') }}"
                    class="brand"
                >
                    <div class="brand-logo">
                        <img
                            src="{{ asset('images/logo-esgul-suruh.png') }}"
                            alt="Logo ESA Runner"
                        >
                    </div>

                    <div class="brand-text">
                        <strong>ESA RUNNER</strong>
                        <span>Jasa Titip & Suruh</span>
                    </div>
                </a>

                <button
                    type="button"
                    class="menu-toggle"
                    id="menuToggle"
                    aria-label="Buka menu navigasi"
                    aria-controls="navMenu"
                    aria-expanded="false"
                >
                    <span></span>
                </button>

                <nav
                    class="nav-menu"
                    id="navMenu"
                >
                    <a href="#beranda">Beranda</a>
                    <a href="#layanan">Layanan</a>
                    <a href="#cara-order">Cara Order</a>
                    <a href="#rute">Rute & Biaya</a>
                    <a href="#tipe">Tipe Layanan</a>

                    <a
                        href="{{ route('cek-status') }}"
                        class="nav-button"
                    >
                        Cek Status
                    </a>
                </nav>
            </header>

            <div class="hero-content">
                <div class="hero-left">
                    <h1 class="hero-title">
                        Praktis, Cepat,<br>
                        <span>Amanah.</span>
                    </h1>

                    <p class="hero-description">
                        ESA Runner membantu mahasiswa dan pelanggan
                        menyelesaikan kebutuhan titip beli, mengambil
                        barang, mengantar pesanan, serta jasa suruh
                        lainnya dengan proses yang praktis dan terpantau.
                    </p>

                    <div class="hero-actions">
                        <a
                            href="{{ route('buat-permintaan') }}"
                            class="button button-primary"
                        >
                            Buat Request Sekarang
                        </a>

                        <a
                            href="{{ route('cek-status') }}"
                            class="button button-secondary"
                        >
                            Cek Status Request
                        </a>
                    </div>

                    <div class="stats">
                        <div class="stat-item">
                            <div class="stat-icon">⚡</div>

                            <div>
                                <strong>Cepat & Praktis</strong>
                                <span>
                                    Request dibuat tanpa proses rumit
                                </span>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">📍</div>

                            <div>
                                <strong>Rute Otomatis</strong>
                                <span>
                                    Jarak perjalanan dihitung sistem
                                </span>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">🔎</div>

                            <div>
                                <strong>Status Terpantau</strong>
                                <span>
                                    Proses pesanan dapat dicek
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="visual-orbit"></div>
                    <div class="visual-ring"></div>

                    <div class="logo-card-3d">
                        <div class="logo-card-face">
                            <div class="logo-card-shine"></div>

                            <div class="logo-card-inner">
                                <img
                                    src="{{ asset('images/logo-esgul-suruh.png') }}"
                                    alt="Logo ESA Runner"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div
        class="quick-order"
        id="cara-order"
    >
        <div class="container">
            <div class="quick-order-card">
                <h2>Cara Order</h2>

                <div class="step-row">
                    <div class="step">
                        <div class="step-number">1</div>

                        <h3>Isi Request</h3>

                        <p>
                            Pilih layanan dan masukkan
                            detail kebutuhanmu.
                        </p>
                    </div>

                    <div class="arrow">›</div>

                    <div class="step">
                        <div class="step-number">2</div>

                        <h3>Admin Verifikasi</h3>

                        <p>
                            Admin memeriksa alamat,
                            jadwal, dan detail pesanan.
                        </p>
                    </div>

                    <div class="arrow">›</div>

                    <div class="step">
                        <div class="step-number">3</div>

                        <h3>Hitung Rute & Biaya</h3>

                        <p>
                            Sistem menghitung jarak
                            dan biaya layanan.
                        </p>
                    </div>

                    <div class="arrow">›</div>

                    <div class="step">
                        <div class="step-number">4</div>

                        <h3>Kurir Ditugaskan</h3>

                        <p>
                            Kurir menjalankan pesanan
                            sampai selesai.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main>
        <section
            class="content-section"
            id="layanan"
        >
            <div class="container">
                <div class="section-header">
                    <div class="section-tag">
                        Layanan Tersedia
                    </div>

                    <h2>
                        Pilih layanan yang
                        <span>kamu butuhkan</span>
                    </h2>

                    <p>
                        Jenis layanan dan harga ditampilkan
                        langsung dari data yang dikelola
                        melalui sistem administrator.
                    </p>
                </div>

                <div class="category-grid">
                    @forelse ($kategoriLayanan as $kategori)
                        @php
                            $namaKategori = mb_strtolower($kategori->nama);

                            $iconKategori = match (true) {
                                str_contains($namaKategori, 'makanan'),
                                str_contains($namaKategori, 'minuman') => '🍔',

                                str_contains($namaKategori, 'dokumen') => '📄',

                                str_contains($namaKategori, 'ambil'),
                                str_contains($namaKategori, 'paket') => '📦',

                                str_contains($namaKategori, 'antar'),
                                str_contains($namaKategori, 'kirim') => '🛵',

                                str_contains($namaKategori, 'beli'),
                                str_contains($namaKategori, 'belanja') => '🛍️',

                                str_contains($namaKategori, 'survei'),
                                str_contains($namaKategori, 'lokasi') => '📍',

                                str_contains($namaKategori, 'bersih') => '🧹',

                                default => '🤝',
                            };
                        @endphp

                        <article class="category-card">
                            <div class="category-heading">
                                <div class="category-icon">
                                    {{ $iconKategori }}
                                </div>

                                <h3>
                                    {{ $kategori->nama }}
                                </h3>
                            </div>

                            <p>
                                {{
                                    $kategori->deskripsi
                                    ?? 'Layanan tersedia untuk membantu kebutuhan pelanggan.'
                                }}
                            </p>

                            <div class="price-grid">
                                <div class="price-box">
                                    <span>Normal</span>

                                    <strong>
                                        Rp{{
                                            number_format(
                                                (float) $kategori->biaya_normal,
                                                0,
                                                ',',
                                                '.'
                                            )
                                        }}
                                    </strong>
                                </div>

                                <div class="price-box">
                                    <span>Express</span>

                                    <strong>
                                        Rp{{
                                            number_format(
                                                (float) $kategori->biaya_express,
                                                0,
                                                ',',
                                                '.'
                                            )
                                        }}
                                    </strong>
                                </div>
                            </div>
                        </article>
                    @empty
                        <article class="category-card">
                            <div class="category-heading">
                                <div class="category-icon">
                                    📌
                                </div>

                                <h3>
                                    Belum Ada Layanan
                                </h3>
                            </div>

                            <p>
                                Data kategori layanan belum
                                ditambahkan melalui panel admin.
                            </p>
                        </article>
                    @endforelse
                </div>
            </div>
        </section>

        <section
            class="route-section"
            id="rute"
        >
            <div class="container">
                <div class="route-panel">
                    <div class="route-copy">
                        <div class="section-tag">
                            Rute & Biaya
                        </div>

                        <h2>
                            Jarak dihitung
                            <span>secara otomatis</span>
                        </h2>

                        <p>
                            Pelanggan cukup memasukkan alamat
                            eksekusi dan alamat tujuan. Setelah
                            diverifikasi admin, sistem menghitung
                            seluruh rute perjalanan.
                        </p>

                        <div class="route-list">
                            <div class="route-list-item">
                                <div class="check-icon">✓</div>

                                <div>
                                    Rute Basecamp menuju Lokasi
                                    Eksekusi lalu Lokasi Tujuan.
                                </div>
                            </div>

                            <div class="route-list-item">
                                <div class="check-icon">✓</div>

                                <div>
                                    Perhitungan menggunakan
                                    OpenStreetMap, Leaflet, dan OSRM.
                                </div>
                            </div>

                            <div class="route-list-item">
                                <div class="check-icon">✓</div>

                                <div>
                                    Hasil jarak dan biaya diperiksa
                                    kembali oleh admin.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="map-preview">
                        <div class="map-label">
                            Contoh preview rute
                        </div>

                        <div class="map-route"></div>

                        <div class="map-marker marker-one">
                            1
                        </div>

                        <div class="map-marker marker-two">
                            2
                        </div>

                        <div class="map-marker marker-three">
                            3
                        </div>

                        <div class="map-summary">
                            <div>
                                <span>Total Jarak</span>
                                <strong>9,32 KM</strong>
                            </div>

                            <div>
                                <span>Tipe Layanan</span>
                                <strong>Normal</strong>
                            </div>

                            <div>
                                <span>Status</span>
                                <strong>Terverifikasi</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section
            class="type-section"
            id="tipe"
        >
            <div class="container">
                <div class="type-panel">
                    <div class="type-heading">
                        <h2>
                            Pilih tipe layanan
                            <span>sesuai kebutuhan</span>
                        </h2>

                        <p>
                            Gunakan layanan Normal untuk kebutuhan
                            rutin atau Express untuk kebutuhan
                            yang perlu diprioritaskan.
                        </p>
                    </div>

                    <div class="type-grid">
                        <article class="type-card">
                            <h3>Normal</h3>

                            <p>
                                Layanan dengan biaya lebih hemat
                                dan waktu pengerjaan fleksibel.
                            </p>

                            <ul>
                                <li>Biaya lebih terjangkau</li>
                                <li>Cocok untuk kebutuhan rutin</li>
                                <li>Waktu proses lebih fleksibel</li>
                            </ul>
                        </article>

                        <article class="type-card express">
                            <h3>Express</h3>

                            <p>
                                Layanan prioritas untuk kebutuhan
                                yang perlu diproses lebih cepat.
                            </p>

                            <ul>
                                <li>Prioritas pengerjaan</li>
                                <li>Lebih cepat diproses</li>
                                <li>Cocok untuk kebutuhan mendesak</li>
                            </ul>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="container">
                <div class="cta-box">
                    <h2>
                        Ada urusan?
                        <span>Biar ESA Runner bantu.</span>
                    </h2>

                    <p>
                        Buat request sekarang dan pantau
                        perkembangan pesanan menggunakan
                        kode request yang diberikan sistem.
                    </p>

                    <div class="cta-actions">
                        <a
                            href="{{ route('buat-permintaan') }}"
                            class="button button-primary"
                        >
                            Buat Request Sekarang
                        </a>

                        <a
                            href="{{ route('cek-status') }}"
                            class="button button-secondary"
                        >
                            Cek Status Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-main">
                <div class="footer-brand">
                    <div class="brand-logo">
                        <img
                            src="{{ asset('images/logo-esgul-suruh.png') }}"
                            alt="Logo ESA Runner"
                        >
                    </div>

                    <div>
                        <strong>ESA RUNNER</strong>

                        <span>
                            Jasa Titip & Suruh Mahasiswa
                        </span>
                    </div>
                </div>

                <div class="footer-links">
                    <a href="#beranda">Beranda</a>
                    <a href="#cara-order">Cara Order</a>
                    <a href="#layanan">Layanan</a>
                    <a href="#rute">Rute & Biaya</a>

                    <a href="{{ route('cek-status') }}">
                        Cek Status
                    </a>
                </div>
            </div>

            <div class="footer-bottom">
                © {{ date('Y') }} ESA Runner —
                Sistem Informasi Jasa Titip dan Suruh Mahasiswa.
            </div>
        </div>
    </footer>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        function closeMenu() {
            navMenu?.classList.remove('is-open');
            document.body.classList.remove('menu-open');
            menuToggle?.setAttribute('aria-expanded', 'false');
        }

        menuToggle?.addEventListener('click', () => {
            const isOpen = navMenu.classList.toggle('is-open');

            document.body.classList.toggle('menu-open', isOpen);
            menuToggle.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );
        });

        navMenu?.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', closeMenu);
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 1050) {
                closeMenu();
            }
        });
    </script>
</body>
</html>