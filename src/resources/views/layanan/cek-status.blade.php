<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Request - Esgul Suruh</title>

    @livewireStyles

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .18), transparent 34%),
                linear-gradient(135deg, #020617 0%, #08245c 55%, #0f172a 100%);
            color: #0f172a;
            min-height: 100vh;
        }

        .page-wrapper {
            width: 100%;
            min-height: 100vh;
            padding: 28px 18px 60px;
        }

        .navbar {
            width: min(1120px, 100%);
            margin: 0 auto 34px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
        }

        .brand img {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: white;
            padding: 6px;
        }

        .brand strong {
            display: block;
            font-size: 20px;
            line-height: 1;
        }

        .brand span {
            font-size: 13px;
            color: #cbd5e1;
        }

        .nav-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .nav-actions a {
            text-decoration: none;
            color: white;
            font-size: 14px;
            font-weight: 700;
            padding: 12px 16px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.22);
            background: rgba(255,255,255,.08);
        }

        .nav-actions a.primary {
            background: #ff7a00;
            border-color: #ff7a00;
        }

        .content {
            width: min(980px, 100%);
            margin: 0 auto;
        }

        .status-card {
            background: rgba(255,255,255,.96);
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 30px;
            padding: 34px;
            box-shadow: 0 30px 80px rgba(0,0,0,.24);
        }

        .status-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .eyebrow {
            display: inline-flex;
            padding: 8px 14px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 14px;
        }

        h1 {
            margin: 0;
            font-size: clamp(30px, 5vw, 48px);
            color: #061637;
            letter-spacing: -1.2px;
        }

        .status-header p {
            max-width: 620px;
            margin: 14px auto 0;
            color: #64748b;
            line-height: 1.7;
        }

        .status-form {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 16px;
            align-items: end;
            padding: 18px;
            border-radius: 22px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        label {
            font-size: 14px;
            font-weight: 800;
            color: #334155;
        }

        input {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 15px;
            padding: 14px 15px;
            font-size: 14px;
            outline: none;
            background: white;
        }

        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
        }

        small {
            color: #dc2626;
            font-size: 12px;
            font-weight: 700;
        }

        button {
            border: none;
            cursor: pointer;
            border-radius: 15px;
            padding: 15px 22px;
            background: #ff7a00;
            color: white;
            font-size: 15px;
            font-weight: 900;
            box-shadow: 0 12px 30px rgba(255, 122, 0, .24);
            white-space: nowrap;
        }

        button:disabled {
            opacity: .65;
            cursor: not-allowed;
        }

        .empty-result {
            margin-top: 22px;
            text-align: center;
            padding: 28px;
            border-radius: 22px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
        }

        .empty-icon {
            width: 42px;
            height: 42px;
            margin: 0 auto 12px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: #fb923c;
            color: white;
            font-weight: 900;
        }

        .empty-result h2 {
            margin: 0 0 8px;
            color: #9a3412;
        }

        .empty-result p {
            margin: 0;
            color: #9a3412;
            line-height: 1.6;
        }

        .result-card {
            margin-top: 24px;
            padding: 24px;
            border-radius: 24px;
            background: white;
            border: 1px solid #e2e8f0;
        }

        .result-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 20px;
            padding-bottom: 18px;
            border-bottom: 1px solid #e2e8f0;
        }

        .result-label {
            display: block;
            color: #64748b;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .result-top h2 {
            margin: 0;
            color: #061637;
            font-size: 26px;
        }

        .status-badge {
            display: inline-flex;
            padding: 9px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 900;
        }

        .status-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-yellow {
            background: #fef3c7;
            color: #b45309;
        }

        .status-green {
            background: #dcfce7;
            color: #15803d;
        }

        .status-red {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status-gray {
            background: #f1f5f9;
            color: #475569;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 16px;
        }

        .info-item,
        .detail-box,
        .admin-note {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 16px;
        }

        .info-item span,
        .detail-box span,
        .admin-note span {
            display: block;
            color: #64748b;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .info-item strong {
            color: #0f172a;
            font-size: 15px;
        }

        .detail-box,
        .admin-note {
            margin-top: 14px;
        }

        .detail-box p,
        .admin-note p {
            margin: 0;
            color: #334155;
            line-height: 1.7;
        }

        .location-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .admin-note {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .secondary-btn {
            margin-top: 18px;
            background: #0b2a66;
            box-shadow: none;
        }

        @media (max-width: 860px) {
            .status-form,
            .info-grid,
            .location-grid {
                grid-template-columns: 1fr;
            }

            button {
                width: 100%;
            }

            .navbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .nav-actions {
                justify-content: flex-start;
            }

            .status-card {
                padding: 22px;
                border-radius: 24px;
            }

            .result-top {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <main class="page-wrapper">
        <nav class="navbar">
            <a href="{{ route('landing') }}" class="brand">
                <img src="{{ asset('images/logo-esgul-suruh.png') }}" alt="Logo Esgul Suruh">
                <div>
                    <strong>ESGUL SURUH</strong>
                    <span>Jasa Titip & Suruh</span>
                </div>
            </a>

            <div class="nav-actions">
                <a href="{{ route('landing') }}">Beranda</a>
                <a href="{{ route('buat-permintaan') }}" class="primary">Buat Request</a>
            </div>
        </nav>

        <section class="content">
            @livewire('cek-status-permintaan')
        </section>
    </main>

    @livewireScripts
</body>
</html>