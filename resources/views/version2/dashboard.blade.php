@extends('layouts.appV2')
@section('content')
    <section id="view-dashboard" class="container-xl py-5">

        <!-- Welcome Banner -->
        {{--   <div class="header-gradient text-white rounded-4 p-4 p-md-5 shadow-sm mb-5">
            <div class="row align-items-center">
                <div class="col-lg-8 text-start mb-3 mb-lg-0">
                    <span
                        class="badge bg-warning text-dark text-uppercase fw-extrabold px-3 py-2 rounded-pill tracking-wider mb-3">Live
                        Session</span>
                    <h1 class="fw-extrabold tracking-tight">Hello, <span id="dash-welcome-name">Perci Banting</span>!</h1>
                    <p class="text-light opacity-90 mb-0">Manage, analyze, and build custom questionnaires for active
                        workshop tracks.</p>
                </div>
                <div class="col-lg-4 text-start text-lg-end">
                    <button onclick="switchView('quiz-builder')"
                        class="btn btn-light text-navy-900 hover-bg-light fw-bold py-3 px-4 rounded-3 shadow-md">
                        <i class="bi bi-file-earmark-plus-fill text-danger me-2"></i>
                        Create Quiz Form
                    </button>
                </div>
            </div>
        </div> --}}

        <!-- KPI Metric Grid -->
        <div class="row g-4 mb-5 text-start">
            <!-- Stats Card 1 -->
            <div class="col-sm-6 col-lg-3">
                <div class="card-custom p-4 d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="text-uppercase text-muted fw-bold tracking-wider d-block mb-1"
                            style="font-size: 11px;">Total Quizzes</span>
                        <h3 class="fw-extrabold text-dark mb-0" id="stat-active-quizzes">{{ $monitoring['quizzes'] }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                        <i class="bi bi-grid-3x3-gap-fill fs-3"></i>
                    </div>
                </div>
            </div>
            <!-- Stats Card 2 -->
            <div class="col-sm-6 col-lg-3">
                <div class="card-custom p-4 d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="text-uppercase text-muted fw-bold tracking-wider d-block mb-1"
                            style="font-size: 11px;">Companies</span>
                        <h3 class="fw-extrabold text-dark mb-0" id="stat-submissions">{{ $monitoring['companies'] }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-3">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                </div>
            </div>
            <!-- Stats Card 3 -->
            {{--  <div class="col-sm-6 col-lg-3">
                <div class="card-custom p-4 d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="text-uppercase text-muted fw-bold tracking-wider d-block mb-1"
                            style="font-size: 11px;">Focal Points Checked</span>
                        <h3 class="fw-extrabold text-dark mb-0">100%</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3">
                        <i class="bi bi-patch-check-fill fs-3"></i>
                    </div>
                </div>
            </div> --}}
            <!-- Stats Card 4 -->
            <div class="col-sm-6 col-lg-3">
                <div class="card-custom p-4 d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="text-uppercase text-muted fw-bold tracking-wider d-block mb-1"
                            style="font-size: 11px;">Group Tracks</span>
                        <h3 class="fw-extrabold text-dark mb-0" id="stat-group-tracks">{{ $monitoring['groups'] }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-3">
                        <i class="bi bi-diagram-3-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workshop / Quiz Forms List -->
        <div class="card-custom shadow-sm overflow-hidden mb-5 text-start">
            <div
                class="p-4 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                <div>
                    <h2 class="fw-extrabold text-dark tracking-tight mb-1" style="font-size: 1.25rem;">Workshop Quizzes</h2>
                </div>
                <div class="position-relative" style="max-width: 280px; width: 100%;">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="quiz-list-search" oninput="filterQuizList()"
                            class="form-control bg-light border-start-0 shadow-none text-sm"
                            placeholder="Filter quizzes...">
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
                            <th class="py-3 px-4 text-center">Questions</th>
                            <th class="py-3 px-4 text-center">Responses</th>
                            <th class="py-3 px-4">Target Date</th>
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
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
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
            alert(url)
            window.location.href = url;
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
