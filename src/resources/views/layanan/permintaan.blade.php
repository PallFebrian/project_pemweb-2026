<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Permintaan Layanan - Esgul Suruh</title>

    @livewireStyles

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
            --danger: #dc2626;
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

        .page-wrapper {
            min-height: 100vh;
            overflow-x: hidden;
        }

        .form-hero {
            position: relative;
            overflow: hidden;
            min-height: 470px;
            padding-bottom: 135px;
            color: white;
            background:
                radial-gradient(circle at 78% 30%, rgba(11, 99, 246, 0.42), transparent 24%),
                radial-gradient(circle at 92% 60%, rgba(255, 122, 0, 0.12), transparent 24%),
                linear-gradient(135deg, #031127 0%, #061b42 48%, #082b63 100%);
        }

        .form-hero::before {
            content: "";
            position: absolute;
            right: -150px;
            bottom: -180px;
            width: 620px;
            height: 350px;
            border-radius: 55% 45% 0 0;
            background: rgba(255, 255, 255, 0.07);
            transform: rotate(-6deg);
            z-index: 1;
        }

        .form-hero::after {
            content: "";
            position: absolute;
            left: -120px;
            right: -120px;
            bottom: -210px;
            height: 300px;
            background: var(--bg);
            border-radius: 50% 50% 0 0;
            z-index: 2;
        }

        .navbar {
            position: relative;
            z-index: 5;
            height: 92px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 900;
            color: white;
        }

        .brand-logo {
            width: 54px;
            height: 54px;
            flex-shrink: 0;
            overflow: hidden;
            border-radius: 16px;
            background: white;
            border: 1px solid rgba(255, 255, 255, 0.24);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
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

        .back-link {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            border-radius: 15px;
            color: white;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.42);
            font-size: 14px;
            font-weight: 900;
            backdrop-filter: blur(10px);
        }

        .back-link:hover {
            border-color: rgba(255, 255, 255, 0.78);
        }

        .hero-content {
            position: relative;
            z-index: 5;
            max-width: 740px;
            padding-top: 48px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            margin-bottom: 18px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.10);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.14);
            font-size: 13px;
            font-weight: 900;
        }

        .hero-content h1 {
            margin: 0;
            color: white;
            font-size: 52px;
            line-height: 1.08;
            letter-spacing: -1.4px;
        }

        .hero-content h1 span {
            color: var(--orange);
        }

        .hero-content p {
            max-width: 650px;
            margin: 18px 0 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 17px;
            line-height: 1.75;
        }

        .form-area {
            position: relative;
            z-index: 10;
            margin-top: -92px;
            padding-bottom: 60px;
        }

        .form-shell {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 350px;
            gap: 22px;
            align-items: start;
        }

        .form-card,
        .side-card {
            background: white;
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: var(--shadow);
        }

        .form-card {
            padding: 32px;
        }

        .form-header {
            margin-bottom: 26px;
        }

        .form-header h2 {
            margin: 0 0 10px;
            color: var(--text);
            font-size: 30px;
            letter-spacing: -0.7px;
        }

        .form-header p {
            margin: 0;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.7;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full {
            grid-column: span 2;
        }

        label {
            color: #344054;
            font-size: 14px;
            font-weight: 900;
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid #d7e1ef;
            border-radius: 15px;
            padding: 14px 15px;
            color: var(--text);
            background: #fbfdff;
            font-size: 14px;
            outline: none;
            transition: 0.18s ease;
        }

        textarea {
            min-height: 116px;
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            background: white;
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(11, 99, 246, 0.12);
        }

        small {
            color: var(--danger);
            font-size: 12px;
            font-weight: 700;
        }

        .type-options {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .type-option {
            position: relative;
        }

        .type-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .type-box {
            min-height: 112px;
            display: flex;
            gap: 14px;
            padding: 18px;
            border-radius: 20px;
            background: #fbfdff;
            border: 1px solid #d7e1ef;
            cursor: pointer;
            transition: 0.18s ease;
        }

        .type-icon {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            border-radius: 15px;
            background: var(--blue-soft);
            color: var(--blue);
            font-size: 20px;
        }

        .type-box strong {
            display: block;
            margin-bottom: 5px;
            color: var(--text);
            font-size: 16px;
        }

        .type-box span {
            display: block;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .type-option input:checked + .type-box {
            border-color: var(--blue);
            background: linear-gradient(135deg, rgba(11, 99, 246, 0.08), rgba(255, 122, 0, 0.06));
            box-shadow: 0 12px 28px rgba(11, 99, 246, 0.10);
        }

        .type-option input:checked + .type-box .type-icon {
            background: var(--blue);
            color: white;
        }

        .submit-btn {
            grid-column: span 2;
            min-height: 58px;
            border: none;
            border-radius: 16px;
            background: var(--orange);
            color: white;
            font-size: 15px;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 0 16px 34px rgba(255, 122, 0, 0.26);
            transition: 0.18s ease;
        }

        .submit-btn:hover {
            background: var(--orange-dark);
            transform: translateY(-2px);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .side-card {
            overflow: hidden;
            position: sticky;
            top: 22px;
        }

        .side-top {
            padding: 26px;
            color: white;
            background:
                radial-gradient(circle at 80% 20%, rgba(11, 99, 246, 0.50), transparent 32%),
                linear-gradient(135deg, #031127, #082b63);
        }

        .side-logo {
            width: 72px;
            height: 72px;
            display: grid;
            place-items: center;
            margin-bottom: 16px;
            border-radius: 22px;
            background: white;
            overflow: hidden;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.18);
        }

        .side-top h3 {
            margin: 0;
            font-size: 24px;
            line-height: 1.2;
            letter-spacing: -0.6px;
        }

        .side-top h3 span {
            color: var(--orange);
        }

        .side-top p {
            margin: 10px 0 0;
            color: rgba(255, 255, 255, 0.74);
            font-size: 14px;
            line-height: 1.6;
        }

        .side-body {
            padding: 24px;
        }

        .summary-box {
            padding: 16px;
            margin-bottom: 14px;
            border-radius: 18px;
            background: #f8fbff;
            border: 1px solid #e8f0fc;
        }

        .summary-box span {
            display: block;
            margin-bottom: 7px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 900;
        }

        .summary-box strong {
            display: block;
            color: var(--text);
            font-size: 15px;
            line-height: 1.4;
        }

        .price-summary {
            padding: 18px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(11, 99, 246, 0.08), rgba(255, 122, 0, 0.10));
            border: 1px dashed rgba(11, 99, 246, 0.34);
        }

        .price-summary span {
            display: block;
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 900;
        }

        .price-summary strong {
            display: block;
            color: var(--orange);
            font-size: 28px;
            letter-spacing: -0.8px;
        }

        .helper-list {
            margin: 18px 0 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 11px;
        }

        .helper-list li {
            display: flex;
            gap: 10px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .helper-list b {
            color: var(--blue);
        }

        @media (max-width: 980px) {
            .form-shell {
                grid-template-columns: 1fr;
            }

            .side-card {
                position: static;
                order: -1;
            }

            .side-top {
                display: flex;
                gap: 18px;
                align-items: center;
            }

            .side-logo {
                margin-bottom: 0;
                flex-shrink: 0;
            }
        }

        @media (max-width: 720px) {
            .container {
                width: min(100% - 24px, 1140px);
            }

            .navbar {
                height: auto;
                padding: 20px 0;
                align-items: flex-start;
                flex-direction: column;
            }

            .back-link {
                width: 100%;
            }

            .form-hero {
                padding-bottom: 120px;
            }

            .hero-content {
                padding-top: 28px;
            }

            .hero-content h1 {
                font-size: 36px;
            }

            .hero-content p {
                font-size: 15px;
            }

            .form-area {
                margin-top: -70px;
            }

            .form-card {
                padding: 22px;
                border-radius: 24px;
            }

            .form-header h2 {
                font-size: 25px;
            }

            .form-grid,
            .type-options {
                grid-template-columns: 1fr;
            }

            .form-group.full,
            .submit-btn {
                grid-column: span 1;
            }

            .side-top {
                display: block;
            }

            .side-logo {
                margin-bottom: 16px;
            }

            .price-summary strong {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .brand-text span {
                display: none;
            }

            .brand-logo {
                width: 46px;
                height: 46px;
            }

            .brand-text strong {
                font-size: 18px;
            }

            .hero-content h1 {
                font-size: 32px;
            }

            .form-card,
            .side-card {
                border-radius: 22px;
            }
        }
    </style>
</head>
<body>
    <main class="page-wrapper">
        <section class="form-hero">
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

                    <a href="/" class="back-link">
                        ← Kembali ke Beranda
                    </a>
                </header>

                <div class="hero-content">
                    <div class="hero-badge">
                        📝 Form Request Layanan
                    </div>

                    <h1>
                        Buat request jasa titip & suruh dengan <span>lebih mudah.</span>
                    </h1>

                    <p>
                        Isi data permintaan secara lengkap. Setelah request tersimpan,
                        kamu akan langsung diarahkan ke WhatsApp admin untuk konfirmasi.
                    </p>
                </div>
            </div>
        </section>

        <section class="form-area">
            <div class="container">
                @livewire('form-permintaan-layanan')
            </div>
        </section>
    </main>

    @livewireScripts
</body>
</html>