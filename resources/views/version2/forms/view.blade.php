@extends('layouts.appV2')
@section('content')
    <section id="view-dashboard" class="container-xl py-5">

        <!-- Workshop / Quiz Forms List -->
        <div class="card-custom shadow-sm overflow-hidden mb-5 text-start">
            <div
                class="p-4 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                <div>
                    <h2 class="fw-extrabold text-dark tracking-tight mb-1" style="font-size: 1.25rem;">Workshop iLogs</h2>
                </div>
                <div class="position-relative" style="max-width: 400px; width: 100%;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <button type="button" class="btn btn-primary w-100 me-2" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            CREATE NEW FORMS
                        </button>

                        <div class="dropdown ">
                            <button class="btn btn-outline-success w-100 px-4 dropdown-toggle" type="button"
                                id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                EXPORT REPORT{{--  <i class="bi bi-chevron-down ms-1"></i> --}}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="exportDropdown"
                                style="border: 1px solid var(--border-color); border-radius: 8px;">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('quiz.onlyExportPdf', 1) }}">
                                        Import Forms
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('quiz.onlyExport', 1) }}">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i> Export all Forms to PDF
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('quiz.onlyExport', 1) }}">
                                        <i class="bi bi-file-earmark-excel-fill text-success me-2"></i> Export all Forms to
                                        Excel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('quiz.onlyExport', 1) }}">
                                        <i class="bi bi-file-earmark-excel-fill text-success me-2"></i>Export all Report to
                                        Excel
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="quiz-list-search" oninput="filterQuizList()"
                            class="form-control bg-light border-start-0 shadow-none text-sm"
                            placeholder="Filter iLogs...">
                    </div>

                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-uppercase text-muted small fw-bold"
                            style="font-size: 11px; border-bottom: 2px solid #cbd5e1;">
                            <th class="py-3 px-4">Quiz / Form Title</th>
                            <th class="py-3 px-4">Assigned Group Track</th>
                            <th class="py-3 px-4 text-center">COMPANY</th>
                            <th class="py-3 px-4 text-center">FACILITATOR</th>
                            <th class="py-3 px-4">QR-CODE</th>
                            <th class="py-3 px-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="quiz-list-tbody" class="text-muted" style="font-size: 14px;">
                        <!-- Populated Dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Form
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.builder.store') }}" class="" method="POST">
                        @csrf
                        <div class="form-group">
                            <small class="text-muted">TITLE <span class="text-danger fw-bolder">*</span></small>
                            <input type="text" name="title" class="form-control border border-primary">
                            @error('title')
                                <small class="mt-2 badge bg-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <small class="text-muted">DESCRIPTION <span class="text-danger fw-bolder">*</span></small>
                            <textarea name="description" id="" cols="30" rows="3" class="form-control border border-primary"></textarea>
                            @error('description')
                                <small class="mt-2 badge bg-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <small class="text-muted">WORKSHOP DATE <span class="text-danger fw-bolder">*</span></small>
                            <input type="date" name="date" class="form-control border border-primary">
                            @error('date')
                                <small class="mt-2 badge bg-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <small class="text-muted">SELECT GROUPS <span
                                    class="text-danger fw-bolder">*</span></small><sup>(Multiple Select)</sup>
                            <select class="select-groups border border-primary" name="groups[]" multiple>
                                @foreach ($groupList as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                            @error('group')
                                <small class="mt-2 badge bg-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <small class="text-muted">SELECT COMPANY <span class="text-danger fw-bolder">*</span></small>
                            <select name="company" id="" class="form-select border border-primary">
                                @foreach ($companies as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach

                            </select>
                            @error('company')
                                <small class="mt-2 badge bg-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button class="btn btn-primary w-100 mt-3">SAVE</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: white !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #0d6efd !important;
            /* Bootstrap primary color */
            border-radius: 0.375rem;
            min-height: 38px;
        }
    </style>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select-groups').select2({
                placeholder: "Select groups",
                width: '100%',
                dropdownParent: $('#exampleModal')
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            renderQuizList()
        });
        let quizList = []; // <-- must be let
        // Render Quizzes Lists
        function renderQuizList() {
            const tbody = document.getElementById('quiz-list-tbody');
            tbody.innerHTML = '';

            const quizzes = @json($quizList);
            quizList = quizzes
            quizzes.forEach(quiz => {

                const groups = quiz.groups
                    .map(group => `
                    <span class="badge bg-secondary me-1">
                        ${group.name}
                    </span>
                    `)
                    .join('');

                const tr = document.createElement('tr');

                tr.innerHTML = `
            <td class="py-3 px-4">
                <div class="fw-extrabold text-navy-900">${quiz.title}</div>
                <div class="text-muted small mt-0.5">
                    ${quiz.description ?? ''}
                </div>
            </td>

            <td class="py-3 px-4">
                ${groups}
            </td>

            <td class="py-3 px-4">
                ${quiz.company ?? ''}
            </td>

            <td class="py-3 px-4">
                ${quiz.facilitator ?? ''}
            </td>

            <td class="py-3 px-4">
                <div class="d-flex align-items-center gap-1">
                    <button onclick="goTo('${quiz.shareLink}')" class="btn btn-info btn-sm text-white p-2 rounded-3 hover-text-danger border-0">
                        ${quiz.quizCode ?? ''}
                    </button>
                    <button onclick="viewQRCode(${quiz.id})" class="btn btn-light btn-sm text-muted p-2 rounded-3 hover-text-primary border-0" title="View Access QR Code">
                        <i class="bi bi-qr-code fs-5"></i>
                    </button>
                    
                </div>
            </td>

            <td class="py-3 px-4 text-end">
                ${quiz.actions}
            </td>
        `;

                tbody.appendChild(tr);
            });
        }

        function filterQuizList() {
            const query = document.getElementById('quiz-list-search').value.toLowerCase();
            const rows = document.querySelectorAll('#quiz-list-tbody tr');
            rows.forEach(row => {
                const title = row.querySelector('td:first-child').innerText.toLowerCase();
                const track = row.querySelector('td:nth-child(2)').innerText.toLowerCase();
                if (title.includes(query) || track.includes(query)) {
                    row.classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            });
        }

        function viewQRCode(quizId) {
            const code = quizList.find(q => q.id === quizId);
            console.log(quizId)
            console.log(code)
            console.log(quizList)
            if (!code) return;

            const qrMockupUrl =
                `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(code.shareLink)}&color=101a36`;
            showNotification(
                "info",
                "Evaluation Access Poster",
                `
                    <div class="text-center vstack gap-3 text-start">
                        <p class="text-muted small mb-0">Scan this code with a mobile device or reference the static identifier below to join this seminar.</p>
                        <div class="bg-light border p-2 rounded-3 d-inline-block mx-auto mb-2">
                            <span class="font-monospace text-uppercase fw-bold text-navy-900 px-3 py-1 bg-white border rounded d-block" style="font-size: 1.25rem; letter-spacing: 2px;">${code.quizCode}</span>
                        </div>
                        <div class="border border-dashed p-3 rounded-3 bg-light d-inline-block mx-auto">
                            <img src="${qrMockupUrl}" alt="QR Access Code Poster" class="mx-auto border shadow-sm rounded-3" style="width: 180px; height: 180px;" onerror="this.onerror=null; this.src='https://placehold.co/200x200/ffffff/101a36?text=QR+Code';">
                        </div>
                        <p class="text-dark fw-bold small mb-0 font-sans">${code.title}</p>
                    </div>
                `
            );
        }

        function goTo(url) {
            window.open(url, '_blank', 'noopener,noreferrer');
        }
    </script>
    <script>
        // Initialize standard Bootstrap tooltips on render
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        /**
         * Intercept standard deletion and toggle modern confirmation modal
         */
        function triggerDeleteConfirmation(quizId, quizTitle) {
            // Set dynamic title inside confirm message
            document.getElementById('del-modal-title-' + quizId).innerText = quizTitle;

            // Show the custom Bootstrap modal
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal-' + quizId));
            deleteModal.show();
        }

        /**
         * Safely executes deletion request
         */
        function executeDelete(quizId) {
            document.getElementById('delete-form-' + quizId).submit();
        }
    </script>
@endsection
