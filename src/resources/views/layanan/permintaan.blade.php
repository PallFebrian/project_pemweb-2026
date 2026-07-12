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
        content="Buat permintaan layanan jasa titip dan jasa suruh melalui ESA Runner."
    >

    <title>Buat Permintaan Layanan - ESA Runner</title>

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
            --green: #16a36a;
            --white: #ffffff;
            --bg: #f4f8ff;
            --text: #101f3d;
            --muted: #667085;
            --line: #e4ecf7;
            --danger: #dc2626;

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

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        img {
            display: block;
            max-width: 100%;
        }

        [hidden] {
            display: none !important;
        }

        .container {
            width: min(1140px, calc(100% - 40px));
            margin: 0 auto;
        }

        .page-wrapper {
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* =========================
           HERO
        ========================== */

        .form-hero {
            position: relative;
            min-height: 530px;
            overflow: hidden;
            padding-bottom: 160px;
            color: var(--white);
            background:
                radial-gradient(
                    circle at 78% 30%,
                    rgba(11, 99, 246, 0.42),
                    transparent 24%
                ),
                radial-gradient(
                    circle at 92% 60%,
                    rgba(255, 122, 0, 0.13),
                    transparent 24%
                ),
                linear-gradient(
                    135deg,
                    #031127 0%,
                    #061b42 48%,
                    #082b63 100%
                );
        }

        .form-hero::before {
            position: absolute;
            right: -150px;
            bottom: -180px;
            z-index: 1;
            width: 620px;
            height: 350px;
            border-radius: 55% 45% 0 0;
            background: rgba(255, 255, 255, 0.07);
            transform: rotate(-6deg);
            content: "";
        }

        .form-hero::after {
            position: absolute;
            right: -120px;
            bottom: -210px;
            left: -120px;
            z-index: 2;
            height: 300px;
            border-radius: 50% 50% 0 0;
            background: var(--bg);
            content: "";
        }

        /* =========================
           NAVBAR
        ========================== */

        .navbar {
            position: relative;
            z-index: 10;
            min-height: 96px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
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
            border: 1px solid rgba(255, 255, 255, 0.22);
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

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-link {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 19px;
            border: 1px solid rgba(255, 255, 255, 0.40);
            border-radius: 14px;
            color: var(--white);
            background: rgba(255, 255, 255, 0.05);
            font-size: 14px;
            font-weight: 900;
            backdrop-filter: blur(10px);
            transition:
                transform 0.2s ease,
                border-color 0.2s ease,
                background 0.2s ease;
        }

        .nav-link:hover {
            border-color: rgba(255, 255, 255, 0.78);
            background: rgba(255, 255, 255, 0.09);
            transform: translateY(-1px);
        }

        .nav-link.status-link {
            border-color: rgba(255, 122, 0, 0.75);
            background: rgba(255, 122, 0, 0.14);
        }

        /* =========================
           HERO CONTENT
        ========================== */

        .hero-content {
            position: relative;
            z-index: 5;
            max-width: 840px;
            padding-top: 56px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            margin-bottom: 19px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 999px;
            color: var(--white);
            background: rgba(255, 255, 255, 0.10);
            font-size: 13px;
            font-weight: 900;
        }

        .hero-content h1 {
            max-width: 820px;
            margin: 0;
            color: var(--white);
            font-size: clamp(42px, 5vw, 56px);
            line-height: 1.08;
            letter-spacing: -1.5px;
        }

        .hero-content h1 span {
            color: var(--orange);
        }

        .hero-content > p {
            max-width: 720px;
            margin: 20px 0 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 17px;
            line-height: 1.75;
        }

        .hero-features {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 30px;
        }

        .hero-feature {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.06);
        }

        .hero-feature-icon {
            width: 30px;
            height: 30px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            border-radius: 10px;
            background: rgba(11, 99, 246, 0.30);
            font-size: 14px;
        }

        .hero-feature span {
            color: rgba(255, 255, 255, 0.88);
            font-size: 12px;
            font-weight: 800;
        }

        /* =========================
           FORM AREA
        ========================== */

        .form-area {
            position: relative;
            z-index: 10;
            margin-top: -102px;
            padding-bottom: 70px;
        }

        /*
         * Class di bawah dipakai oleh komponen:
         * form-permintaan-layanan
         */

        .form-shell {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 350px;
            gap: 22px;
            align-items: start;
        }

        .form-card,
        .side-card {
            border: 1px solid var(--line);
            border-radius: 28px;
            background: var(--white);
            box-shadow: var(--shadow);
        }

        .form-card {
            min-width: 0;
            padding: 32px;
        }

        .form-header {
            margin-bottom: 28px;
        }

        .form-header h2 {
            margin: 0 0 10px;
            color: var(--text);
            font-size: 30px;
            line-height: 1.2;
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
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            color: #344054;
            font-size: 14px;
            font-weight: 900;
        }

        .required-mark {
            color: var(--danger);
        }

        input,
        select,
        textarea {
            width: 100%;
            min-height: 50px;
            padding: 14px 15px;
            border: 1px solid #d7e1ef;
            border-radius: 15px;
            outline: none;
            color: var(--text);
            background: #fbfdff;
            font-size: 14px;
            transition:
                border-color 0.18s ease,
                background 0.18s ease,
                box-shadow 0.18s ease;
        }

        input::placeholder,
        textarea::placeholder {
            color: #98a2b3;
        }

        select {
            cursor: pointer;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
            line-height: 1.6;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--blue);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(11, 99, 246, 0.12);
        }

        input:disabled,
        select:disabled,
        textarea:disabled {
            cursor: not-allowed;
            opacity: 0.72;
            background: #f2f4f7;
        }

        .field-help,
        .form-help,
        small {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .error-message,
        .text-danger,
        small.text-danger {
            color: var(--danger);
            font-size: 12px;
            font-weight: 700;
        }

        .is-invalid,
        input[aria-invalid="true"],
        select[aria-invalid="true"],
        textarea[aria-invalid="true"] {
            border-color: var(--danger);
        }

        /* =========================
           TIPE LAYANAN
        ========================== */

        .type-options {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .type-option {
            position: relative;
            min-width: 0;
        }

        .type-option input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .type-box {
            min-height: 116px;
            display: flex;
            gap: 14px;
            padding: 18px;
            border: 1px solid #d7e1ef;
            border-radius: 20px;
            background: #fbfdff;
            cursor: pointer;
            transition:
                transform 0.18s ease,
                border-color 0.18s ease,
                background 0.18s ease,
                box-shadow 0.18s ease;
        }

        .type-box:hover {
            border-color: #abc8f7;
            transform: translateY(-2px);
        }

        .type-icon {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            border-radius: 15px;
            color: var(--blue);
            background: var(--blue-soft);
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

        .type-option input:focus-visible + .type-box {
            outline: 3px solid rgba(11, 99, 246, 0.24);
            outline-offset: 2px;
        }

        .type-option input:checked + .type-box {
            border-color: var(--blue);
            background:
                linear-gradient(
                    135deg,
                    rgba(11, 99, 246, 0.08),
                    rgba(255, 122, 0, 0.06)
                );
            box-shadow: 0 12px 28px rgba(11, 99, 246, 0.10);
        }

        .type-option input:checked + .type-box .type-icon {
            color: var(--white);
            background: var(--blue);
        }

        /* =========================
           INPUT TAMBAHAN
        ========================== */

        .input-with-icon {
            position: relative;
        }

        .input-with-icon input {
            padding-left: 45px;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            z-index: 2;
            color: var(--muted);
            transform: translateY(-50%);
            pointer-events: none;
        }

        .section-divider {
            grid-column: 1 / -1;
            height: 1px;
            margin: 5px 0;
            border: none;
            background: var(--line);
        }

        .form-section-title {
            grid-column: 1 / -1;
            margin: 4px 0 0;
        }

        .form-section-title h3 {
            margin: 0;
            color: var(--text);
            font-size: 18px;
        }

        .form-section-title p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        /* =========================
           SUBMIT BUTTON
        ========================== */

        .submit-btn {
            grid-column: 1 / -1;
            min-height: 58px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 24px;
            border: none;
            border-radius: 16px;
            color: var(--white);
            background: var(--orange);
            box-shadow: 0 16px 34px rgba(255, 122, 0, 0.26);
            font-size: 15px;
            font-weight: 900;
            cursor: pointer;
            transition:
                transform 0.18s ease,
                background 0.18s ease,
                box-shadow 0.18s ease;
        }

        .submit-btn:hover:not(:disabled) {
            background: var(--orange-dark);
            box-shadow: 0 18px 38px rgba(255, 122, 0, 0.30);
            transform: translateY(-2px);
        }

        .submit-btn:focus-visible {
            outline: 4px solid rgba(255, 122, 0, 0.22);
            outline-offset: 2px;
        }

        .submit-btn:disabled {
            cursor: not-allowed;
            opacity: 0.68;
            transform: none;
        }

        /* =========================
           SIDE CARD
        ========================== */

        .side-card {
            position: sticky;
            top: 22px;
            overflow: hidden;
        }

        .side-top {
            padding: 26px;
            color: var(--white);
            background:
                radial-gradient(
                    circle at 80% 20%,
                    rgba(11, 99, 246, 0.50),
                    transparent 32%
                ),
                linear-gradient(
                    135deg,
                    #031127,
                    #082b63
                );
        }

        .side-logo {
            width: 72px;
            height: 72px;
            display: grid;
            place-items: center;
            overflow: hidden;
            margin-bottom: 16px;
            border-radius: 22px;
            background: var(--white);
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.18);
        }

        .side-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .side-top h3 {
            margin: 0;
            color: var(--white);
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
            border: 1px solid #e8f0fc;
            border-radius: 18px;
            background: #f8fbff;
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
            overflow-wrap: anywhere;
            color: var(--text);
            font-size: 15px;
            line-height: 1.4;
        }

        .summary-box em {
            color: var(--muted);
            font-size: 13px;
            font-style: normal;
        }

        .price-summary {
            padding: 18px;
            border: 1px dashed rgba(11, 99, 246, 0.34);
            border-radius: 20px;
            background:
                linear-gradient(
                    135deg,
                    rgba(11, 99, 246, 0.08),
                    rgba(255, 122, 0, 0.10)
                );
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
            line-height: 1.2;
            letter-spacing: -0.8px;
        }

        .price-summary small {
            display: block;
            margin-top: 8px;
            color: var(--muted);
            font-weight: 500;
            line-height: 1.5;
        }

        .helper-list {
            display: grid;
            gap: 11px;
            margin: 18px 0 0;
            padding: 0;
            list-style: none;
        }

        .helper-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .helper-list b {
            color: var(--blue);
        }

        /* =========================
           NOTIFICATION
        ========================== */

        .alert {
            padding: 15px 17px;
            margin-bottom: 20px;
            border-radius: 15px;
            font-size: 14px;
            line-height: 1.6;
        }

        .alert-success {
            border: 1px solid rgba(22, 163, 106, 0.24);
            color: #087a4c;
            background: rgba(22, 163, 106, 0.09);
        }

        .alert-danger,
        .alert-error {
            border: 1px solid rgba(220, 38, 38, 0.22);
            color: #b42318;
            background: rgba(220, 38, 38, 0.07);
        }

        /* =========================
           LIVEWIRE LOADING
        ========================== */

        [wire\:loading] {
            display: none;
        }

        [wire\:loading].loading-inline {
            display: none;
        }

        .loading-spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: var(--white);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* =========================
           FOOTER INFO
        ========================== */

        .page-note {
            padding: 0 0 52px;
        }

        .page-note-card {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            padding: 24px;
            border: 1px solid var(--line);
            border-radius: 24px;
            background: var(--white);
            box-shadow: var(--shadow-soft);
        }

        .note-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .note-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            border-radius: 14px;
            color: var(--blue);
            background: var(--blue-soft);
            font-size: 18px;
        }

        .note-item strong {
            display: block;
            margin-bottom: 4px;
            color: var(--text);
            font-size: 14px;
        }

        .note-item span {
            display: block;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        /* =========================
           RESPONSIVE
        ========================== */

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
                align-items: center;
                gap: 18px;
            }

            .side-logo {
                flex-shrink: 0;
                margin-bottom: 0;
            }
        }

        @media (max-width: 760px) {
            .container {
                width: min(100% - 28px, 1140px);
            }

            .form-hero {
                min-height: auto;
                padding-bottom: 145px;
            }

            .navbar {
                min-height: 84px;
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

            .status-link {
                display: none;
            }

            .nav-link {
                min-height: 44px;
                padding: 0 15px;
                font-size: 13px;
            }

            .hero-content {
                padding-top: 38px;
            }

            .hero-content h1 {
                font-size: 40px;
            }

            .hero-content > p {
                font-size: 15px;
            }

            .hero-features {
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .form-area {
                margin-top: -82px;
            }

            .form-card {
                padding: 24px;
                border-radius: 24px;
            }

            .form-header h2 {
                font-size: 26px;
            }

            .form-grid,
            .type-options {
                grid-template-columns: 1fr;
            }

            .form-group.full,
            .submit-btn,
            .section-divider,
            .form-section-title {
                grid-column: 1;
            }

            .side-top {
                display: block;
            }

            .side-logo {
                margin-bottom: 16px;
            }

            .page-note-card {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 520px) {
            .container {
                width: min(100% - 22px, 1140px);
            }

            .navbar {
                min-height: 76px;
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

            .nav-link {
                width: 44px;
                min-height: 44px;
                padding: 0;
                font-size: 0;
            }

            .nav-link::before {
                font-size: 18px;
                content: "←";
            }

            .hero-content {
                padding-top: 28px;
            }

            .hero-badge {
                font-size: 12px;
            }

            .hero-content h1 {
                font-size: 34px;
                letter-spacing: -1px;
            }

            .hero-content > p {
                line-height: 1.65;
            }

            .form-area {
                margin-top: -70px;
            }

            .form-card,
            .side-card {
                border-radius: 22px;
            }

            .form-card {
                padding: 20px;
            }

            .form-header {
                margin-bottom: 22px;
            }

            .form-header h2 {
                font-size: 23px;
            }

            .type-box {
                min-height: auto;
                padding: 15px;
            }

            .side-body,
            .side-top {
                padding: 20px;
            }

            .price-summary strong {
                font-size: 24px;
            }

            .page-note {
                padding-bottom: 36px;
            }

            .page-note-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <main class="page-wrapper">
        <section class="form-hero">
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

                    <div class="navbar-actions">
                        <a
                            href="{{ route('cek-status') }}"
                            class="nav-link status-link"
                        >
                            🔎 Cek Status
                        </a>

                        <a
                            href="{{ route('landing') }}"
                            class="nav-link"
                        >
                            ← Kembali ke Beranda
                        </a>
                    </div>
                </header>

                <div class="hero-content">
                    <div class="hero-badge">
                        📝 Form Permintaan Layanan
                    </div>

                    <h1>
                        Buat permintaan dengan
                        <span>mudah dan terpantau.</span>
                    </h1>

                    <p>
                        Isi data permintaan secara lengkap dan benar.
                        Setelah permintaan dikirim, admin akan melakukan
                        verifikasi, menghitung rute serta biaya layanan,
                        kemudian menugaskan kurir yang tersedia.
                    </p>

                    <div class="hero-features">
                        <div class="hero-feature">
                            <div class="hero-feature-icon">
                                ✓
                            </div>

                            <span>
                                Diverifikasi Admin
                            </span>
                        </div>

                        <div class="hero-feature">
                            <div class="hero-feature-icon">
                                📍
                            </div>

                            <span>
                                Rute Dihitung Otomatis
                            </span>
                        </div>

                        <div class="hero-feature">
                            <div class="hero-feature-icon">
                                🔎
                            </div>

                            <span>
                                Status Bisa Dipantau
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="form-area">
            <div class="container">
                @livewire('form-permintaan-layanan')
            </div>
        </section>

        <section class="page-note">
            <div class="container">
                <div class="page-note-card">
                    <div class="note-item">
                        <div class="note-icon">
                            📝
                        </div>

                        <div>
                            <strong>
                                Isi Data Lengkap
                            </strong>

                            <span>
                                Pastikan nomor telepon, alamat,
                                dan detail permintaan sudah benar.
                            </span>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon">
                            🧭
                        </div>

                        <div>
                            <strong>
                                Verifikasi dan Perhitungan
                            </strong>

                            <span>
                                Admin memeriksa permintaan sebelum
                                rute dan biaya layanan ditentukan.
                            </span>
                        </div>
                    </div>

                    <div class="note-item">
                        <div class="note-icon">
                            🔐
                        </div>

                        <div>
                            <strong>
                                Simpan Kode Request
                            </strong>

                            <span>
                                Kode request digunakan untuk melihat
                                perkembangan status pesanan.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @livewireScripts
</body>
</html>