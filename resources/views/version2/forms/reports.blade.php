@extends('layouts.appV2')
@section('content')
    <style>
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

        /* Primary action button */
        .btn-spread-submit {
            background-color: #101a36;
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
            padding: 1rem;
        }

        .table-spread {
            margin-bottom: 0;
            font-size: 0.9rem;
            width: 100% !important;
        }

        .table-spread th {
            background-color: #ffffff;
            color: #475569;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color) !important;
            padding: 14px 16px;
            text-transform: capitalize;
        }

        .table-spread td {
            padding: 14px 16px;
            vertical-align: middle;
            color: #334155;
            border-bottom: 1px solid var(--border-color) !important;
        }

        /* Styling DataTables UI Components to match portal theme */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 14px;
            background-color: #ffffff;
            color: var(--text-dark);
            font-size: 0.85rem;
            outline: none;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #5c6bc0;
            box-shadow: 0 0 0 3px rgba(92, 107, 192, 0.15);
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 30px 6px 12px;
            outline: none;
            font-size: 0.85rem;
        }

        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #5c6bc0;
        }

        .page-item.active .page-link {
            background-color: #5c6bc0 !important;
            border-color: #5c6bc0 !important;
            color: #ffffff !important;
        }

        .page-link {
            color: #5c6bc0;
            border-radius: 6px;
            margin: 0 2px;
            font-weight: 600;
        }

        .page-link:hover {
            color: var(--accent-hover);
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
    </style>
    <div class="container-xl py-5">
        <div class="survey-header-section text-center">
            <h1 class="survey-title"><b>{{ $quiz->title }}</b></h1>
            <p class="survey-subtitle">
                {{ $quiz->description }}
            </p>
        </div>
        <div class="card">
            <div class="card-body">
                <!-- Main Data Grid Table -->
                <div class="survey-card">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h3 class="fw-bold text-dark m-0">Scribes List</h3>
                        <div class="dropdown">
                            <button class="btn btn-spread-submit px-4 dropdown-toggle" style="background-color: #5c6bc0;"
                                type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                EXPORT REPORT{{--  <i class="bi bi-chevron-down ms-1"></i> --}}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="exportDropdown"
                                style="border: 1px solid var(--border-color); border-radius: 8px;">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('quiz.onlyExportPdf', $quiz->id) }}">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>Export as PDF
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('quiz.onlyExport', $quiz->id) }}">
                                        <i class="bi bi-file-earmark-excel-fill text-success me-2"></i>Export as Excel
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table id="" class="table table-spread align-middle mb-0 scribesTable">
                                <thead>
                                    <tr>
                                        <th>Scribe</th>
                                        <th>Answered</th>
                                        <th>Item</th>
                                        <th>Workshop</th>
                                        <th>Company / Group</th>
                                        <th>Value</th>
                                        <th>Effort</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Loaded dynamically via DataTables mapping -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('script')
    <script src="/metronic/js/plugins/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
        integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
        crossorigin="anonymous"></script>
    <script>
        var tables = {};
        $(document).ready(function() {
            $('.scribesTable').DataTable({
                ajax: {
                    url: `{{ route('admin.builder.view-report-participant', base64_encode($quiz->id)) }}`,
                },
                columns: [{
                        data: 'name',
                        render: function(data, type, row) {
                            return `<span class="fw-semibold text-dark">${(data)}</span><br><small class="text-muted font-mono">${row.group_name}</small>`;
                        }
                    },
                    {
                        data: 'answerCount',
                        render: function(data) {
                            return `<span class="badge bg-secondary-subtle text-secondary px-2 py-1">${data}</span>`;
                        }
                    },
                    {
                        data: 'questionCount',
                        render: function(data) {
                            return `<span class="text-muted small">${(data)}</span>`;
                        }
                    },
                    {
                        data: 'quizTitle',
                        render: function(data) {
                            return (data);
                        }
                    },
                    {
                        data: 'company',
                        render: function(data) {
                            return `<span class="text-muted small font-semibold">${data}</span>`;
                        }
                    },
                    {
                        data: 'value',
                        render: function(data) {
                            return `<span class="text-muted small font-semibold">${data}</span>`;
                        }
                    },
                    {
                        data: 'effort',
                        render: function(data) {
                            return `<span class="text-muted small font-semibold">${data}</span>`;
                        }
                    },
                    {
                        data: 'report_status',
                        render: function(data, type, row) {
                            return `
                                <select class="form-select form-select-sm" onchange="updateSubmissionStatus('${row.id}', this.value)">
                                    <option value="Approved" ${data === 'Approved' ? 'selected' : ''}>Approved</option>
                                    <option value="Pending" ${data === 'Pending' ? 'selected' : ''}>Pending</option>
                                    <option value="Rejected" ${data === 'Rejected' ? 'selected' : ''}>Rejected</option>
                                </select>
                            `;
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        className: 'text-end',
                        render: function(data, type, row) {
                            // 1. Generate the URL with a temporary placeholder for the report ID
                            let viewRoute =
                                "{{ route('forms.scribes-view', [base64_encode($quiz->quiz_code), 'PLACEHOLDER']) }}";

                            // 2. Base64 encode the dynamic JS 'data' and replace the placeholder
                            viewRoute = viewRoute.replace('PLACEHOLDER', btoa(String(data)));
                            return `
                                <a class="action-btn view-btn" title="View Details" href="${viewRoute}"><i class="bi bi-eye"></i></a>
                                <button class="action-btn edit-btn" title="Edit Form" onclick="editSubmission('${row.id}')"><i class="bi bi-pencil-square"></i></button>
                                <button class="action-btn delete-btn" title="Delete Form" onclick="deleteSubmission('${row.id}')"><i class="bi bi-trash"></i></button>
                            `;
                        }
                    }
                ],
                // Custom DOM Positioning to layout length menu, search filters, and page links beautifully
                dom: "<'row mb-3 align-items-center'<'col-md-6'l><'col-md-6 text-end'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row mt-3 align-items-center'<'col-md-6'i><'col-md-6'p>>",
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search scribes...",
                    lengthMenu: "Show _MENU_ entries"
                },
                pageLength: 10,
                responsive: true,
                order: [
                    [0, "asc"]
                ]
            });
            $('.table-for-scribe').each(function() {

                const table = $(this).find('.dataTable');
                table.DataTable({
                    ajax: {
                        url: `{{ route('forms.scribes-data', base64_encode($quiz->id)) }}`,
                    },
                    processing: true,
                    responsive: true,
                    autoWidth: true,
                    columns: [{
                            data: 'name'
                        },
                        {
                            data: 'answerCount'
                        },
                        {
                            data: 'questionCount'
                        },
                        {
                            data: 'quizTitle'
                        },
                        {
                            data: 'company'
                        },
                        {
                            data: 'value'
                        },
                        {
                            data: 'effort'
                        },
                        {
                            data: 'report_status',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return `
                            <select class="form-select border border-primary select_status"
                                    data-key="${row.report_status}"
                                    data-id="${row.id}" >
                                <option value="">Choose status</option>
                               @foreach (config()->get('report_status') as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                                </select>
                            </select>
                        `;
                            }
                        },
                        {
                            data: 'actions',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                table.columns.adjust().draw();
            });

            $(document).on('change', '.select_status', function() {
                $.ajax({
                    url: `/dashboard/api/quiz-status/change/${$(this).data('id')}`,
                    method: 'PATCH',
                    data: {
                        key: $(this).val()
                    }
                });
            });

            function viewDetailedAlert(params) {
                alert(params)
            }

        });


        $('#scribe-search').on('input', function(e) {
            for (let key in tables) {
                tables[key].columns(2).search(e.target.value).draw();
            }
        });
        $('#company-select').on('change', function(e) {
            for (let key in tables) {
                tables[key].columns(4).search(e.target.value).draw();
            }
        });
    </script>
@endsection
