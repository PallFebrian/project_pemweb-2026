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
        content="Cek status request layanan ESA Runner menggunakan kode request dan nomor WhatsApp."
    >

    <title>Cek Status Request - ESA Runner</title>

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
            --warning: #d97706;

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
        input {
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

        .page-wrapper {
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* =========================
           HERO
        ========================== */

        .status-hero {
            position: relative;
            min-height: 470px;
            overflow: hidden;
            padding-bottom: 140px;
            color: var(--white);
            background:
                radial-gradient(
                    circle at 78% 28%,
                    rgba(11, 99, 246, 0.42),
                    transparent 24%
                ),
                radial-gradient(
                    circle at 92% 62%,
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

        .status-hero::before {
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

        .status-hero::after {
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

        .nav-link.primary {
            border-color: rgba(255, 122, 0, 0.80);
            background: var(--orange);
            box-shadow: 0 14px 30px rgba(255, 122, 0, 0.24);
        }

        /* =========================
           HERO CONTENT
        ========================== */

        .hero-content {
            position: relative;
            z-index: 5;
            max-width: 820px;
            padding-top: 48px;
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
            max-width: 800px;
            margin: 0;
            color: var(--white);
            font-size: clamp(42px, 5vw, 56px);
            line-height: 1.08;
            letter-spacing: -1.5px;
        }

        .hero-content h1 span {
            color: var(--orange);
        }

        .hero-content p {
            max-width: 690px;
            margin: 20px 0 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 17px;
            line-height: 1.75;
        }

        /* =========================
           CONTENT AREA
        ========================== */

        .status-area {
            position: relative;
            z-index: 10;
            margin-top: -92px;
            padding-bottom: 70px;
        }

        .status-card {
            padding: 34px;
            border: 1px solid var(--line);
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow);
        }

        .status-header {
            margin-bottom: 26px;
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

        .status-header h2 {
            margin: 0;
            color: var(--text);
            font-size: clamp(30px, 5vw, 42px);
            line-height: 1.15;
            letter-spacing: -1px;
        }

        .status-header h2 span {
            color: var(--orange);
        }

        .status-header p {
            max-width: 680px;
            margin: 13px auto 0;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.7;
        }

        /* =========================
           SEARCH FORM
        ========================== */

        .status-form {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 16px;
            align-items: end;
            padding: 18px;
            border: 1px solid var(--line);
            border-radius: 22px;
            background: #f8fbff;
        }

        .form-group {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        label {
            color: #344054;
            font-size: 14px;
            font-weight: 900;
        }

        input {
            width: 100%;
            min-height: 52px;
            padding: 14px 15px;
            border: 1px solid #d7e1ef;
            border-radius: 15px;
            outline: none;
            color: var(--text);
            background: var(--white);
            font-size: 14px;
            transition:
                border-color 0.18s ease,
                box-shadow 0.18s ease;
        }

        input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(11, 99, 246, 0.12);
        }

        small {
            color: var(--danger);
            font-size: 12px;
            font-weight: 700;
        }

        .submit-btn,
        .secondary-btn,
        .map-link,
        .refresh-btn,
        .reset-btn {
            min-height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 20px;
            border: none;
            border-radius: 15px;
            color: var(--white);
            background: var(--orange);
            box-shadow: 0 14px 30px rgba(255, 122, 0, 0.24);
            font-size: 14px;
            font-weight: 900;
            cursor: pointer;
            transition:
                transform 0.18s ease,
                background 0.18s ease,
                border-color 0.18s ease;
            white-space: nowrap;
        }

        .submit-btn:hover,
        .secondary-btn:hover,
        .map-link:hover,
        .refresh-btn:hover,
        .reset-btn:hover {
            transform: translateY(-2px);
        }

        button:disabled {
            cursor: not-allowed;
            opacity: 0.65;
            transform: none !important;
        }

        .reset-btn {
            color: var(--blue);
            border: 1px solid var(--line);
            background: var(--white);
            box-shadow: none;
        }

        .refresh-btn,
        .secondary-btn {
            background: var(--navy-2);
            box-shadow: none;
        }

        /* =========================
           RESULT
        ========================== */

        .empty-result {
            margin-top: 22px;
            padding: 28px;
            border: 1px solid #fed7aa;
            border-radius: 22px;
            text-align: center;
            background: #fff7ed;
        }

        .empty-icon {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            margin: 0 auto 12px;
            border-radius: 50%;
            color: var(--white);
            background: var(--orange);
            font-weight: 900;
        }

        .empty-result h3 {
            margin: 0 0 8px;
            color: #9a3412;
            font-size: 22px;
        }

        .empty-result p {
            max-width: 540px;
            margin: 0 auto;
            color: #9a3412;
            line-height: 1.6;
        }

        .result-card {
            margin-top: 24px;
            padding: 24px;
            border: 1px solid var(--line);
            border-radius: 26px;
            background: var(--white);
        }

        .result-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            padding-bottom: 18px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--line);
        }

        .result-label {
            display: block;
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 900;
        }

        .result-top h3 {
            margin: 0;
            color: var(--text);
            font-size: 28px;
            line-height: 1.2;
            letter-spacing: -0.7px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 0 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
        }

        .status-blue {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .status-yellow {
            color: #b45309;
            background: #fef3c7;
        }

        .status-green {
            color: #15803d;
            background: #dcfce7;
        }

        .status-red {
            color: #b91c1c;
            background: #fee2e2;
        }

        .status-gray {
            color: #475569;
            background: #f1f5f9;
        }

        .info-grid,
        .location-grid,
        .map-grid {
            display: grid;
            gap: 14px;
        }

        .info-grid {
            grid-template-columns: repeat(3, 1fr);
            margin-bottom: 14px;
        }

        .location-grid {
            grid-template-columns: repeat(2, 1fr);
            margin-top: 14px;
        }

        .map-grid {
            grid-template-columns: repeat(3, 1fr);
            margin-top: 14px;
        }

        .info-item,
        .detail-box,
        .admin-note,
        .timeline-card {
            padding: 16px;
            border: 1px solid #e8f0fc;
            border-radius: 18px;
            background: #f8fbff;
        }

        .info-item span,
        .detail-box span,
        .admin-note span,
        .timeline-card > span {
            display: block;
            margin-bottom: 7px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 900;
        }

        .info-item strong,
        .detail-box strong {
            display: block;
            overflow-wrap: anywhere;
            color: var(--text);
            font-size: 15px;
            line-height: 1.45;
        }

        .detail-box,
        .admin-note,
        .timeline-card {
            margin-top: 14px;
        }

        .detail-box p,
        .admin-note p {
            margin: 0;
            overflow-wrap: anywhere;
            color: #344054;
            font-size: 14px;
            line-height: 1.7;
        }

        .admin-note {
            border-color: #bfdbfe;
            background: #eff6ff;
        }

        .map-link {
            min-height: 48px;
            padding: 0 14px;
            color: var(--blue);
            border: 1px solid #cfe0ff;
            background: var(--blue-soft);
            box-shadow: none;
            font-size: 13px;
        }

        .result-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        .refresh-message {
            margin-top: 14px;
            padding: 12px 14px;
            border: 1px solid rgba(22, 163, 106, 0.22);
            border-radius: 15px;
            color: #087a4c;
            background: rgba(22, 163, 106, 0.08);
            font-size: 13px;
            font-weight: 800;
        }

        /* =========================
           TIMELINE
        ========================== */

        .timeline-list {
            display: grid;
            gap: 12px;
            margin-top: 10px;
        }

        .timeline-item {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 12px;
        }

        .timeline-dot {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            color: var(--white);
            background: var(--blue);
            font-size: 12px;
            font-weight: 900;
        }

        .timeline-content {
            min-width: 0;
            padding-bottom: 12px;
            border-bottom: 1px solid #e8f0fc;
        }

        .timeline-item:last-child .timeline-content {
            padding-bottom: 0;
            border-bottom: none;
        }

        .timeline-content strong {
            display: block;
            color: var(--text);
            font-size: 14px;
        }

        .timeline-content p {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        .timeline-content time {
            display: block;
            margin-top: 5px;
            color: #98a2b3;
            font-size: 11px;
            font-weight: 800;
        }

        .payment-card {
            margin-top: 16px;
            padding: 20px;
            border: 1px solid #fed7aa;
            border-radius: 24px;
            background:
                linear-gradient(
                    135deg,
                    rgba(255, 122, 0, 0.08),
                    rgba(11, 99, 246, 0.035)
                );
        }

        .payment-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 16px;
        }

        .payment-top span {
            display: block;
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 900;
        }

        .payment-top h4 {
            margin: 0;
            color: var(--text);
            font-size: 22px;
            line-height: 1.25;
        }

        .payment-badge {
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            line-height: 1;
            white-space: nowrap;
        }

        .payment-badge.waiting {
            color: #b45309;
            background: #fef3c7;
        }

        .payment-badge.paid {
            color: #15803d;
            background: #dcfce7;
        }

        .payment-badge.pending {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 12px;
        }

        .payment-item {
            padding: 16px;
            border: 1px solid rgba(228, 236, 247, 0.95);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.76);
        }

        .payment-item span {
            display: block;
            margin-bottom: 7px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 900;
        }

        .payment-item strong {
            display: block;
            color: var(--text);
            font-size: 16px;
            line-height: 1.45;
            overflow-wrap: anywhere;
        }

        .payment-method-title {
            display: block;
            margin: 18px 0 10px;
            color: var(--text);
            font-size: 14px;
            font-weight: 900;
        }

        .payment-method-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .payment-method-option {
            position: relative;
            display: block;
            cursor: pointer;
        }

        .payment-method-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .payment-method-box {
            min-height: 124px;
            display: flex;
            flex-direction: column;
            gap: 7px;
            padding: 15px;
            border: 1px solid #e4ecf7;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.82);
            transition:
                border-color 0.18s ease,
                box-shadow 0.18s ease,
                transform 0.18s ease,
                background 0.18s ease;
        }

        .payment-method-box strong {
            color: var(--text);
            font-size: 16px;
            font-weight: 900;
        }

        .payment-method-box span {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.45;
        }

        .payment-method-box small {
            margin-top: auto;
            color: #0b63f6;
            font-size: 11px;
            font-weight: 900;
        }

        .payment-method-option input:checked + .payment-method-box {
            border-color: #0b63f6;
            background: #eef5ff;
            box-shadow: 0 12px 26px rgba(11, 99, 246, 0.12);
            transform: translateY(-1px);
        }

        .payment-note {
            margin: 15px 0 0;
            color: #7c2d12;
            font-size: 13px;
            line-height: 1.65;
        }

        .payment-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 17px;
        }

        .payment-whatsapp-btn {
            min-height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border-radius: 16px;
            color: white;
            background: #ff7a00;
            font-size: 14px;
            font-weight: 900;
            text-decoration: none;
            box-shadow: 0 14px 30px rgba(255, 122, 0, 0.18);
        }

        @media (max-width: 920px) {
            .payment-grid,
            .payment-method-grid {
                grid-template-columns: 1fr;
            }

            .payment-top {
                align-items: flex-start;
                flex-direction: column;
            }

            .payment-whatsapp-btn {
                width: 100%;
            }
        }

        @media (min-width: 921px) and (max-width: 1100px) {
            .payment-method-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* =========================
           RESPONSIVE
        ========================== */

        @media (max-width: 920px) {
            .status-form,
            .info-grid,
            .location-grid,
            .map-grid {
                grid-template-columns: 1fr;
            }

            .submit-btn,
            .secondary-btn,
            .map-link,
            .refresh-btn,
            .reset-btn {
                width: 100%;
            }
        }

        @media (max-width: 760px) {
            .container {
                width: min(100% - 28px, 1140px);
            }

            .status-hero {
                min-height: auto;
                padding-bottom: 135px;
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

            .navbar-actions {
                gap: 8px;
            }

            .nav-link {
                min-height: 44px;
                padding: 0 14px;
                font-size: 13px;
            }

            .hero-content {
                padding-top: 36px;
            }

            .hero-content h1 {
                font-size: 40px;
            }

            .hero-content p {
                font-size: 15px;
            }

            .status-area {
                margin-top: -82px;
                padding-bottom: 52px;
            }

            .status-card {
                padding: 24px;
                border-radius: 24px;
            }

            .result-card {
                padding: 20px;
                border-radius: 22px;
            }

            .result-top {
                flex-direction: column;
            }

            .result-top h3 {
                font-size: 24px;
            }
        }

        @media (max-width: 520px) {
            .container {
                width: min(100% - 22px, 1140px);
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
                padding: 0 12px;
                font-size: 12px;
            }

            .hero-content h1 {
                font-size: 34px;
                letter-spacing: -1px;
            }

            .status-card {
                padding: 20px;
            }

            .status-form {
                padding: 14px;
            }
        }
        .complaint-card {
            margin-top: 16px;
            padding: 20px;
            border: 1px solid #fecaca;
            border-radius: 24px;
            background:
                linear-gradient(
                    135deg,
                    rgba(239, 68, 68, 0.075),
                    rgba(11, 99, 246, 0.035)
                );
        }

        .complaint-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 14px;
        }

        .complaint-top span {
            display: block;
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 900;
        }

        .complaint-top h4 {
            margin: 0;
            color: var(--text);
            font-size: 22px;
            line-height: 1.25;
        }

        .complaint-badge {
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            border-radius: 999px;
            color: #b91c1c;
            background: #fee2e2;
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
        }

        .complaint-note {
            margin: 0 0 15px;
            color: #7f1d1d;
            font-size: 13px;
            line-height: 1.65;
        }

        .complaint-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .complaint-option {
            min-height: 112px;
            display: flex;
            flex-direction: column;
            gap: 7px;
            padding: 16px;
            border: 1px solid #f3d4d4;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.82);
            text-decoration: none;
            transition:
                border-color 0.18s ease,
                box-shadow 0.18s ease,
                transform 0.18s ease,
                background 0.18s ease;
        }

        .complaint-option:hover {
            border-color: #ef4444;
            background: #fff7f7;
            box-shadow: 0 12px 26px rgba(239, 68, 68, 0.10);
            transform: translateY(-1px);
        }

        .complaint-option strong {
            color: var(--text);
            font-size: 15px;
            font-weight: 900;
        }

        .complaint-option span {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.55;
        }

        .complaint-option small {
            margin-top: auto;
            color: #dc2626;
            font-size: 11px;
            font-weight: 900;
        }

        @media (max-width: 920px) {
            .complaint-grid {
                grid-template-columns: 1fr;
            }

            .complaint-top {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <main class="page-wrapper">
        <section class="status-hero">
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
                            href="{{ route('landing') }}"
                            class="nav-link"
                        >
                            Beranda
                        </a>

                        <a
                            href="{{ route('buat-permintaan') }}"
                            class="nav-link primary"
                        >
                            Buat Request
                        </a>
                    </div>
                </header>

                <div class="hero-content">
                    <div class="hero-badge">
                        🔎 Cek Status Request
                    </div>

                    <h1>
                        Pantau request kamu
                        <span>secara mudah.</span>
                    </h1>

                    <p>
                        Masukkan kode request dan nomor WhatsApp yang digunakan
                        saat membuat permintaan untuk melihat status terbaru,
                        detail layanan, biaya, lokasi, dan riwayat prosesnya.
                    </p>
                </div>
            </div>
        </section>

        <section class="status-area">
            <div class="container">
                @livewire('cek-status-permintaan')
            </div>
        </section>
    </main>

    @livewireScripts
</body>
</html>