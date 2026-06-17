@extends('forms.app')
@section('content')
    <div class="qr-wrapper">
        <div class="qr-card">

            <h1 class="qr-title" id="survey-title">{{ $quiz->title }}</h1>
            <p class="qr-subtitle">Scan to participate or enter the entry verification key below</p>

            <!-- Access Code Viewer -->
            <div>
                <div class="access-code-badge" id="access-code-text">{{ $quiz->quiz_code }}</div>
            </div>

            <!-- QR Code Render canvas wrapper -->
            <div class="qr-box-outer">
                <canvas id="qr-canvas"></canvas>
            </div>

            <p class="text-muted small mb-4 mx-auto" style="max-width: 400px;">
                Point your mobile camera at this code to join the seminar workshop evaluation directly.
            </p>

            <!-- Interactive sharing and printing utilities -->
            <div class="d-flex justify-content-center gap-3 actions-panel">
                <button class="btn btn-spread-secondary" onclick="copyShareLink()">
                    <i class="bi bi-link-45deg me-1"></i> Copy URL
                </button>
                <button class="btn btn-spread-primary" onclick="window.print()">
                    <i class="bi bi-printer-fill me-1"></i> Print
                </button>

            </div>
            <div class="d-flex justify-content-center gap-3 actions-panel mt-2">
                <a class="btn btn-spread-primary w-100"
                    href="{{ route('forms.workshop-dashboard', base64_encode($quiz->quiz_code)) }}">
                    Workshop Status
                </a>
            </div>
        </div>
    </div>
    <!-- QR Code Generator Library QRious -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script>
        function generateQRCode() {
            // Target survey link containing the entry code parameter
            const targetUrl = `{{ route('forms.view-code', base64_encode($quiz->quiz_code)) }}`;

            new QRious({
                element: document.getElementById('qr-canvas'),
                value: targetUrl,
                size: 240,
                background: '#ffffff',
                foreground: '#101a36',
                level: 'H' // High error correction level for secure scanning
            });
        }

        function copyShareLink() {
            const targetUrl = `{{ route('forms.view-code', base64_encode($quiz->quiz_code)) }}`;

            // Temporary container element for copy utility (supports iframe secure permissions check)
            const tempInput = document.createElement("input");
            tempInput.value = targetUrl;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);

            // Display Toast notification banner
            const toastEl = document.getElementById('copyToast');
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }

        // Load workflow
        window.onload = function() {
            generateQRCode();
        };
    </script>
@endsection
