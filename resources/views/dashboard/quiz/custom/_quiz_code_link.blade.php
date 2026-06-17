<div class="d-flex">
    <button class="btn btn-outline-primary" data-title="Copy Workshop Link"
        data-link="{{ route('forms.view-code', base64_encode($quiz->quiz_code)) }}">
        {{ $quiz->quiz_code }}
    </button>
    <a href="{{ route('forms.show-qrcode', ['code' => base64_encode($quiz->quiz_code)]) }}"
        class="fw-bolder text-primary">
        SHARE LINK
    </a>
</div>
