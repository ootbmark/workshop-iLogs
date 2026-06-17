<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Survey</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts (Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --bs-body-bg: #f3f5f9;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --accent-primary: #1e293b;
            /* Deep Indigo Navy */
            --accent-blue: #3f51b5;
            /* Royal SPREAD Blue */
            --accent-hover: #2c387e;
            --accent-success: #198754;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 0;
            margin: 0;
        }

        /* Portal Breadcrumb/Subheader navigation */
        .portal-breadcrumb {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 2rem;
            font-size: 0.85rem;
        }

        .portal-breadcrumb a {
            color: var(--text-muted);
            text-decoration: none;
        }

        .portal-breadcrumb a:hover {
            color: var(--accent-blue);
        }

        /* Survey Content Wrapper */
        .survey-container {
            max-width: 1200px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .survey-header-section {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .survey-title {
            font-weight: 800;
            color: #3f51b5;
            font-size: 2.25rem;
            letter-spacing: -0.025em;
        }

        .survey-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            max-width: 900px;
            margin: 0.75rem auto 0 auto;
            line-height: 1.6;
        }

        .survey-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            overflow: hidden;
            padding: 2.5rem;
        }

        /* Step Progress bar styling - matching Screenshot 3 */
        .progress-bar-container {
            background-color: #eaeaf5;
            border-radius: 50px;
            height: 34px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .progress-bar-fill {
            height: 100%;
            background: #5c6bc0;
            position: absolute;
            left: 0;
            top: 0;
            transition: width 0.4s ease-in-out;
            z-index: 1;
        }

        .progress-bar-text {
            position: relative;
            z-index: 2;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.9rem;
        }

        /* Horizontal Assessment Bar: Value, Effort, Priority */
        .assessment-bar {
            background-color: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 2rem;
        }

        .assessment-col {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .assessment-title {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 0.95rem;
            min-width: 60px;
        }

        /* Custom Radio Styling matching Screenshot 3 */
        .custom-radio-group {
            display: flex;
            gap: 1rem;
        }

        .custom-radio-label {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.9rem;
            cursor: pointer;
            user-select: none;
        }

        .custom-radio-circle {
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            transition: all 0.2s;
        }

        input[type="radio"]:checked+.custom-radio-circle {
            border-color: #5c6bc0;
        }

        input[type="radio"]:checked+.custom-radio-circle::after {
            content: '';
            width: 10px;
            height: 10px;
            background-color: #e07a5f;
            /* Accent Orange color from Screenshot 3 */
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Radio option color variations */
        .radio-high {
            color: #dc3545;
        }

        .radio-medium {
            color: #f59e0b;
        }

        .radio-low {
            color: #10b981;
        }

        /* General Forms elements */
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
        }

        .required-asterisk {
            color: #dc3545;
            margin-left: 0.2rem;
        }

        .form-control,
        .form-select {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: var(--text-dark);
            border-radius: 6px;
            padding: 12px 16px;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #ffffff;
            border-color: #5c6bc0;
            color: var(--text-dark);
            box-shadow: 0 0 0 3px rgba(92, 107, 192, 0.15);
        }

        .code-input {
            font-family: 'Space Mono', monospace;
            font-size: 2rem;
            letter-spacing: 0.5rem;
            text-align: center;
            text-transform: uppercase;
            max-width: 320px;
            margin: 0 auto;
        }

        /* Primary action button from SPREAD platform */
        .btn-spread-submit {
            background-color: #101a36;
            /* Dark Indigo-Blue from Screenshot 4 */
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 12px 42px;
            border-radius: 4px;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-spread-submit:hover {
            background-color: #1e2e5c;
            color: #ffffff;
        }

        .btn-spread-secondary {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 12px 24px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
        }

        .btn-spread-secondary:hover {
            background-color: #e2e8f0;
            color: #334155;
        }

        /* Dashboard Table Styling */
        .table-container {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            background-color: #ffffff;
        }

        .table-spread {
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .table-spread th {
            background-color: #ffffff;
            color: #475569;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
            padding: 14px 16px;
            text-transform: capitalize;
        }

        .table-spread td {
            padding: 14px 16px;
            vertical-align: middle;
            color: #334155;
            border-bottom: 1px solid var(--border-color);
        }

        .table-spread tbody tr:last-child td {
            border-bottom: none;
        }

        /* Action Icons */
        .action-btn {
            background: none;
            border: none;
            padding: 4px 8px;
            font-size: 1.1rem;
            transition: color 0.2s;
        }

        .action-btn.view-btn {
            color: #6366f1;
        }

        .action-btn.edit-btn {
            color: #f59e0b;
        }

        .action-btn.delete-btn {
            color: #ef4444;
        }

        .action-btn:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }

        /* Survey Question Card Wrapper (Screenshot 3 style) */
        .survey-question-box {
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 2.25rem;
            margin-bottom: 1.5rem;
        }

        .question-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
        }

        /* Custom SPREAD styled selection option */
        .custom-survey-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            cursor: pointer;
        }

        .custom-survey-radio {
            width: 22px;
            height: 22px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        input[type="radio"]:checked+.custom-survey-radio {
            border-color: #101a36;
            border-width: 6px;
        }

        /* Custom Notification Alerts */
        .alert-spread {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            color: #ef4444;
            border-radius: 6px;
            padding: 12px 16px;
            display: none;
            margin-bottom: 1.5rem;
        }

        /* Transition delays */
        .fade-step {
            display: none;
        }

        .fade-step.active {
            display: block;
            animation: fadeIn 0.4s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Main Viewport Container */
        .qr-wrapper {
            max-width: 600px;
            width: 100%;
            margin: auto;
            padding: 2rem 1.5rem;
        }

        /* QR Frame Design */
        .qr-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 3rem 2rem;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .qr-title {
            font-weight: 800;
            color: var(--accent-blue);
            font-size: 1.75rem;
            letter-spacing: -0.025em;
            line-height: 1.3;
        }

        .qr-subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 0.5rem;
            margin-bottom: 2rem;
        }

        /* QR Code Holder with target styling */
        .qr-box-outer {
            background-color: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 2rem;
            display: inline-block;
            margin-bottom: 2rem;
        }

        #qr-canvas {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
        }

        /* Space Mono Access Code Badge */
        .access-code-badge {
            font-family: 'Space Mono', monospace;
            background-color: rgba(63, 81, 181, 0.08);
            border: 1px solid rgba(63, 81, 181, 0.2);
            color: var(--accent-blue);
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
        }

        /* Action Buttons */
        .btn-spread-primary {
            background-color: #101a36;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 12px 24px;
            border-radius: 4px;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-spread-primary:hover {
            background-color: #1e2e5c;
            color: #ffffff;
        }

        .btn-spread-secondary {
            background-color: #ffffff;
            color: #475569;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 11px 24px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            transition: all 0.2s ease;
        }

        .btn-spread-secondary:hover {
            background-color: #f8fafc;
            color: #1e293b;
        }

        /* Alert Toast notification positioning */
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
        }

        /* Print Styles - perfectly formats the card onto sheet without buttons */
        @media print {
            body {
                background-color: #ffffff !important;
                min-height: auto;
            }

            .portal-breadcrumb,
            .actions-panel,
            .toast-container {
                display: none !important;
            }

            .qr-wrapper {
                max-width: 100%;
                margin: 0;
                padding: 0;
            }

            .qr-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 2cm auto !important;
            }

            .qr-box-outer {
                background-color: transparent !important;
                border: none !important;
                padding: 0 !important;
            }
        }
    </style>
</head>

<body>
    <div class="portal-breadcrumb">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <span class="text-dark fw-semibold">I-LOGS</span>
            </div>
        </div>
    </div>
    <div class="survey-container">
        @yield('content')
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}'
        });
    </script>
@endif
@if (session('error'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: '{{ session('error') }}'
        });
    </script>
@endif
@yield('scripts')

</html>
