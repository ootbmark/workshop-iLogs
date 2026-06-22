<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>iLOG | Workshop Evaluation System</title>
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@500;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --bs-navy-50: #f4f6fa;
            --bs-navy-100: #e8ecf4;
            --bs-navy-800: #1e2e5c;
            --bs-navy-900: #101a36;
            --bs-spreadBlue-500: #3f51b5;
            --bs-spreadBlue-600: #2c387e;
            --bs-spreadOrange-500: #e07a5f;
            --bs-spreadOrange-600: #c96146;
            --bs-font-sans-serif: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #334155;
            font-family: var(--bs-font-sans-serif);
            min-height: 100vh;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        /* Premium Brand Overrides */
        .bg-navy-900 {
            background-color: var(--bs-navy-900) !important;
        }

        .bg-navy-100 {
            background-color: var(--bs-navy-100) !important;
        }

        .bg-navy-50 {
            background-color: var(--bs-navy-50) !important;
        }

        .text-navy-900 {
            color: var(--bs-navy-900) !important;
        }

        .btn-navy-900 {
            background-color: var(--bs-navy-900);
            color: #ffffff;
            font-weight: 700;
            border: none;
            transition: background-color 0.2s ease;
        }

        .btn-navy-900:hover {
            background-color: var(--bs-navy-800);
            color: #ffffff;
        }

        .btn-spreadBlue {
            background-color: var(--bs-spreadBlue-500);
            color: #ffffff;
            font-weight: 700;
            border: none;
        }

        .btn-spreadBlue:hover {
            background-color: var(--bs-spreadBlue-600);
            color: #ffffff;
        }

        .btn-spreadOrange {
            background-color: var(--bs-spreadOrange-500);
            color: #ffffff;
            font-weight: 700;
            border: none;
        }

        .btn-spreadOrange:hover {
            background-color: var(--bs-spreadOrange-600);
            color: #ffffff;
        }

        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }

        .header-gradient {
            background: linear-gradient(135deg, var(--bs-navy-900) 0%, var(--bs-spreadBlue-600) 50%, var(--bs-spreadOrange-500) 100%);
        }

        /* Floating custom alert layout */
        .modal-custom-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-custom-card {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
            transform: scale(0.95);
            opacity: 0;
            transition: all 0.2s ease-in-out;
        }

        .modal-custom-card.show {
            transform: scale(1);
            opacity: 1;
        }
    </style>
</head>

<body class="d-flex flex-column">
    @if (Auth::user())
        @include('version2.components.header')
        {{-- @yield('side-navigation') --}}
        @yield('navigation')
        <main class="flex-grow-1">
            @yield('content')
        </main>
    @else
        @yield('content')
    @endif
    <!-- ==================== STREAMING_CHUNK:Rendering workspace modal elements... ==================== -->
    <div id="custom-modal-backdrop" class="modal-custom-backdrop d-none">
        <div id="custom-modal-card" class="modal-custom-card">
            <div class="p-4 border-bottom d-flex align-items-center gap-3 bg-light">
                <div id="modal-icon-bg" class="p-2.5 rounded-3 text-white">
                    <i id="modal-icon" class="bi fs-5"></i>
                </div>
                <h3 id="modal-title" class="fw-extrabold text-dark mb-0" style="font-size: 1.2rem;">Modal Title</h3>
            </div>
            <div id="modal-body" class="p-4 text-muted border-bottom" style="font-size: 14px; line-height: 1.6;">
                <!-- Inner content dynamically formatted -->
            </div>
            <div class="p-4 bg-light d-flex justify-content-end gap-2" id="modal-actions">
                <!-- Action Buttons injected dynamically -->
            </div>
        </div>
    </div>
</body>
<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showNotification(type, title, bodyHtml, onOkCallback = null, cancelCallback = null) {
        const backdrop = document.getElementById('custom-modal-backdrop');
        const card = document.getElementById('custom-modal-card');
        const iconBg = document.getElementById('modal-icon-bg');
        const icon = document.getElementById('modal-icon');

        document.getElementById('modal-title').innerText = title;
        document.getElementById('modal-body').innerHTML = bodyHtml;

        // Apply styling depending on severity/type
        if (type === 'success') {
            iconBg.className = "p-2.5 rounded-3 text-white bg-success shadow-sm";
            icon.className = "bi bi-check2-circle fs-5";
        } else if (type === 'warning') {
            iconBg.className = "p-2.5 rounded-3 text-white bg-danger shadow-sm";
            icon.className = "bi bi-exclamation-triangle-fill fs-5";
        } else {
            iconBg.className = "p-2.5 rounded-3 text-white bg-primary shadow-sm";
            icon.className = "bi bi-info-circle-fill fs-5";
        }

        // Map and load action controls
        const actionContainer = document.getElementById('modal-actions');
        actionContainer.innerHTML = '';

        if (cancelCallback) {
            const cancelBtn = document.createElement('button');
            cancelBtn.className = "btn btn-outline-secondary px-3 py-2 text-sm fw-semibold";
            cancelBtn.innerText = "Cancel";
            cancelBtn.onclick = function() {
                hideModal();
                cancelCallback();
            };
            actionContainer.appendChild(cancelBtn);
        }

        const okBtn = document.createElement('button');
        okBtn.className = `btn px-4 py-2 text-sm fw-bold ${type === 'warning' ? 'btn-danger' : 'btn-navy-900'}`;
        okBtn.innerText = "Proceed";
        okBtn.onclick = function() {
            hideModal();
            if (onOkCallback) onOkCallback();
        };
        actionContainer.appendChild(okBtn);

        // Display animation transitions
        backdrop.classList.remove('d-none');
        setTimeout(() => {
            card.classList.add('show');
        }, 10);
    }

    function hideModal() {
        const backdrop = document.getElementById('custom-modal-backdrop');
        const card = document.getElementById('custom-modal-card');

        card.classList.remove('show');
        setTimeout(() => {
            backdrop.classList.add('d-none');
        }, 200);
    }

    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-fill';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye-slash-fill';
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
@endif
@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
@endif
@yield('script')

</html>
