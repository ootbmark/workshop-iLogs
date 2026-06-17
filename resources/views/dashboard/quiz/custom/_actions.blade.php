<span style="overflow: visible; position: relative; width: 110px;">
    <div class="d-flex justify-content-between align-items-center ">
      {{--   <button class="btn btn-outline-primary btn-copy" data-title="Copy Workshop Link"
            data-link="{{ route('forms.view-code', base64_encode($quiz->quiz_code)) }}">
            {{ $quiz->quiz_code }}
        </button>
        <a target="_blank" href="{{ route('forms.show-qrcode', ['code' => base64_encode($quiz->quiz_code)]) }}"
            class="fw-bolder text-primary">
            SHARE LINK
        </a> --}}
    </div> <br>
    @if (isset($quiz))
        <a href="{{ route('quiz.onlyExportPdf', $quiz->id) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md"
            title="{{ __('Export to PDF') }}">
            <i class="far fa-file-pdf"></i>
        </a>
        <a href="{{ route('quiz.onlyExport', $quiz->id) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md"
            title="{{ __('Export to Excel') }}">
            <i class="far fa-file-excel"></i>
        </a>
        <a href="{{ route('quiz-reports', $quiz->id) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md"
            title="{{ __('Report') }}">
            <i class="flaticon2-paper"></i>
        </a>

        <a href="{{ route('quiz.preview', $quiz->slug) }}" target="_blank"
            class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{ __('Preview') }}">
            <i class="flaticon-eye"></i>
        </a>

        <a href="{{ route('forms.edit', $quiz->id) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md"
            title="{{ __('Edit details') }}">
            <i class="flaticon-edit"></i>
        </a>

        <form action="{{ route('forms.destroy', $quiz->id) }}" method="POST" style="display: none"
            onsubmit="return confirm('Are You Sure?')">
            @csrf
            @method('DELETE')
        </form>

        <a href="#" onclick="$(this).prev().submit();return false"
            class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{ __('Delete') }}">
            <i class="flaticon2-trash"></i>
        </a>
        <button title="{{ __('Copy') }}" class="btn btn-sm btn-clean btn-icon btn-icon-md quiz-clone"
            style="border: none" data-id="{{ $quiz->id }}"><i class="flaticon2-copy"></i></button>
        <a href="{{ route('archive_by_form.index', $quiz->id) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md"
            title="{{ __('Archive') }}">
            <i class="fa fa-archive"></i>
        </a>
    @endif

    @if (isset($quiz_report))
        <a href="{{ route('quiz.report', $quiz_report->id) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md"
            title="{{ __('Show Details') }}">
            <i class="flaticon-eye"></i>
        </a>

        <form action="{{ route('quiz.report.destroy', $quiz_report->id) }}" method="POST" style="display: none"
            onsubmit="return confirm('Are You Sure?')">
            @csrf
            @method('DELETE')
        </form>
        <a href="#" onclick="$(this).prev().submit();return false"
            class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{ __('Delete') }}">
            <i class="flaticon2-trash"></i>
        </a>
    @endif

</span>
