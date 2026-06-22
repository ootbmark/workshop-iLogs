<div class="d-flex justify-content-between align-items-center ">

    <!-- Export to PDF -->
    <div class="d-flex align-items-center gap-1 flex-wrap">
        <a href="{{ route('quiz.onlyExportPdf', $quiz->id) }}"
            class="btn btn-light btn-sm text-muted p-2 rounded-3 border-0 shadow-sm hover-text-danger"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Export to PDF') }}">
            <i class="bi bi-file-pdf-fill text-danger fs-5"></i>
        </a>

        <!-- Export to Excel -->
        <a href="{{ route('quiz.onlyExport', $quiz->id) }}"
            class="btn btn-light btn-sm text-muted p-2 rounded-3 border-0 shadow-sm hover-text-success"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Export to Excel') }}">
            <i class="bi bi-file-excel-fill text-success fs-5"></i>
        </a>

        <!-- View Reports -->
        <a href="{{ route('quiz-reports', $quiz->id) }}"
            class="btn btn-light btn-sm text-muted p-2 rounded-3 border-0 shadow-sm hover-text-primary"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Report') }}">
            <i class="bi bi-file-earmark-bar-graph-fill text-primary fs-5"></i>
        </a>
    </div>
    <div class="d-flex align-items-center gap-1 flex-wrap">
        <!-- Live Preview -->
        <a href="{{ route('quiz.preview', $quiz->slug) }}" target="_blank"
            class="btn btn-light btn-sm text-muted p-2 rounded-3 border-0 shadow-sm hover-text-info"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Preview') }}">
            <i class="bi bi-eye-fill text-info fs-5"></i>
        </a>

        <!-- Edit Details -->
        <a href="{{ route('admin.builder.edit', base64_encode($quiz->id)) }}"
            class="btn btn-light btn-sm text-muted p-2 rounded-3 border-0 shadow-sm hover-text-warning"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit details') }}">
            <i class="bi bi-pencil-square text-warning fs-5"></i>
        </a>
        <button type="button"
            onclick="triggerDeleteConfirmation('{{ $quiz->id }}', '{{ addslashes($quiz->title) }}')"
            class="btn btn-light btn-sm text-muted p-2 rounded-3 border-0 shadow-sm hover-text-danger"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Delete') }}">
            <i class="bi bi-trash3-fill text-danger fs-5"></i>
        </button>
        <!-- Clone / Copy Quiz -->
        {{--  <button type="button"
            class="btn btn-light btn-sm text-muted p-2 rounded-3 border-0 shadow-sm hover-text-secondary quiz-clone"
            style="border: none" data-id="{{ $quiz->id }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Copy') }}">
            <i class="bi bi-copy text-secondary fs-5"></i>
        </button> --}}
    </div>
    <div class="d-flex align-items-center gap-1 flex-wrap">
        {{--  <a href="{{ route('archive_by_form.index', $quiz->id) }}"
            class="btn btn-light btn-sm text-muted p-2 rounded-3 border-0 shadow-sm hover-text-dark"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Archive') }}">
            <i class="bi bi-archive-fill text-dark fs-5"></i>
        </a> --}}

        <!-- Safe Delete Actions (No raw prompt blocks) -->
        <form id="delete-form-{{ $quiz->id }}" action="{{ route('forms.destroy', $quiz->id) }}" method="POST"
            style="display: none">
            @csrf
            @method('DELETE')
        </form>


    </div>
</div>

<!-- ==================== PREMIUM REMOVAL CONFIRMATION MODAL ==================== -->
<div class="modal fade" id="deleteConfirmModal-{{ $quiz->id }}" tabindex="-1" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-0 bg-light py-3 px-4">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i> Remove Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-start text-muted" style="font-size: 14px; line-height: 1.6;">
                <p>Are you sure you want to permanently delete the evaluation quiz <strong>"<span
                            id="del-modal-title-{{ $quiz->id }}"></span>"</strong>?</p>
                <p class="text-danger mb-0"><i class="bi bi-exclamation-circle-fill"></i> Warning: This will erase all
                    student data, scores, and active logs associated with this form.</p>
            </div>
            <div class="modal-footer border-0 py-3 px-4 bg-light d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary px-3 py-2 text-sm fw-semibold"
                    data-bs-dismiss="modal">Cancel</button>
                <button type="button" onclick="executeDelete('{{ $quiz->id }}')"
                    class="btn btn-danger px-4 py-2 text-sm fw-bold">Proceed Delete</button>
            </div>
        </div>
    </div>
</div>
