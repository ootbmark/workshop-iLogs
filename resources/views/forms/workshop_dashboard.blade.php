@extends('forms.app')
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
    <div class="survey-header-section">
        <h1 class="survey-title"><b>{{ $quiz->title }}</b></h1>
        <p class="survey-subtitle">
            {{ $quiz->description }}
        </p>
    </div>
    <div class="survey-card">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h3 class="fw-bold text-dark m-0">Groups Participation</h3>
        </div>
        <div class="table-container">
            <div class="table-responsive">
                <table id="groups-table-v2" class="table table-spread align-middle">
                    <thead>
                        <tr>
                            <th>Group Name</th>
                            <th>Submissions Count</th>
                            <th>Participation Weight</th>
                            {{--    <th class="text-end">Actions</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Drill-down Group Directory Modal -->
    <div class="modal fade" id="membersModal" tabindex="-1" aria-labelledby="membersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header border-0 bg-light py-3 px-4">
                    <h5 class="modal-title fw-bold text-dark" id="membersModalLabel">Group Registry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4 px-4">
                    <div class="table-responsive border rounded bg-white">
                        <table class="table align-middle mb-0 text-sm" style="font-size: 0.9rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 py-3 text-muted fw-bold">Scribe Name</th>
                                    <th class="py-3 text-muted fw-bold">Verification ID</th>
                                    <th class="py-3 text-muted fw-bold">Primary Framework</th>
                                    <th class="py-3 text-muted fw-bold">Status</th>
                                </tr>
                            </thead>
                            <tbody id="modal-table-body">
                                <!-- Dynamic directory list output -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0 py-3 px-4">
                    <button type="button" class="btn btn-spread-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Simulated synchronized state resembling Scribes list database
        // Containing EXACTLY 5 high-quality participant entries for each of the 4 groups from image_5e295c.png
        const submissions = [
            // --- Group 1: Riserless Casing, BOP ---
            {
                id: "GD1001",
                name: "Jason Lavis",
                group: "Riserless Casing, BOP",
                q3: "React Functional Hooks",
                status: "Approved"
            },
            {
                id: "AB8842",
                name: "PERCI BANTING",
                group: "Riserless Casing, BOP",
                q3: "Vue 3 Standard",
                status: "Approved"
            },
            {
                id: "GD1003",
                name: "Sarah Connor",
                group: "Riserless Casing, BOP",
                q3: "React Functional Hooks",
                status: "Approved"
            },
            {
                id: "GD1004",
                name: "David Miller",
                group: "Riserless Casing, BOP",
                q3: "Bootstrap Static Template",
                status: "Pending"
            },
            {
                id: "GD1005",
                name: "Alice Johnson",
                group: "Riserless Casing, BOP",
                q3: "Web Components Native",
                status: "Approved"
            },

            // --- Group 2: Driller Liner(s), P&A ---
            {
                id: "GD2001",
                name: "Mark Anthony",
                group: "Driller Liner(s), P&A",
                q3: "Web Components Native",
                status: "Approved"
            },
            {
                id: "GD2002",
                name: "Bob Smith",
                group: "Driller Liner(s), P&A",
                q3: "React Functional Hooks",
                status: "Rejected"
            },
            {
                id: "GD2003",
                name: "Charlie Davis",
                group: "Driller Liner(s), P&A",
                q3: "Vue 3 Standard",
                status: "Approved"
            },
            {
                id: "GD2004",
                name: "Emily Watson",
                group: "Driller Liner(s), P&A",
                q3: "Bootstrap Static Template",
                status: "Approved"
            },
            {
                id: "GD2005",
                name: "John Doe",
                group: "Driller Liner(s), P&A",
                q3: "React Functional Hooks",
                status: "Pending"
            },

            // --- Group 3: Deeper Drilling ---
            {
                id: "GD3001",
                name: "Michael Chang",
                group: "Deeper Drilling",
                q3: "Vue 3 Standard",
                status: "Approved"
            },
            {
                id: "GD3002",
                name: "Sophia Loren",
                group: "Deeper Drilling",
                q3: "React Functional Hooks",
                status: "Approved"
            },
            {
                id: "GD3003",
                name: "Robert Downey",
                group: "Deeper Drilling",
                q3: "Web Components Native",
                status: "Approved"
            },
            {
                id: "GD3004",
                name: "Amanda Seyfried",
                group: "Deeper Drilling",
                q3: "Bootstrap Static Template",
                status: "Approved"
            },
            {
                id: "GD3005",
                name: "Chris Evans",
                group: "Deeper Drilling",
                q3: "Vue 3 Standard",
                status: "Pending"
            },

            // --- Group 4: Casing Integrity ---
            {
                id: "GD4001",
                name: "Jessica Alba",
                group: "Casing Integrity",
                q3: "React Functional Hooks",
                status: "Approved"
            },
            {
                id: "GD4002",
                name: "Daniel Craig",
                group: "Casing Integrity",
                q3: "Vue 3 Standard",
                status: "Approved"
            },
            {
                id: "GD4003",
                name: "Scarlett Johansson",
                group: "Casing Integrity",
                q3: "Web Components Native",
                status: "Approved"
            },
            {
                id: "GD4004",
                name: "Benedict Cumberbatch",
                group: "Casing Integrity",
                q3: "Bootstrap Static Template",
                status: "Rejected"
            },
            {
                id: "GD4005",
                name: "Keanu Reeves",
                group: "Casing Integrity",
                q3: "React Functional Hooks",
                status: "Approved"
            }
        ];

        let groupedData = {};
        let bootstrapMembersModal = null;

        function processGroupedMetrics() {
            groupedData = {};

            // Loop through all items and group participant objects
            submissions.forEach(sub => {
                if (!groupedData[sub.group]) {
                    groupedData[sub.group] = {
                        name: sub.group,
                        count: 0,
                        members: []
                    };
                }
                groupedData[sub.group].count += 1;
                groupedData[sub.group].members.push(sub);
            });

            const groupsArray = Object.values(groupedData);
            const totalGroups = groupsArray.length;
            const totalSubmissions = submissions.length;
            const avgPerGroup = totalGroups > 0 ? (totalSubmissions / totalGroups).toFixed(1) : "0.0";

            // Update top layout indicators
            /*   document.getElementById("total-groups-count").innerText = totalGroups;
              document.getElementById("total-submissions-count").innerText = totalSubmissions;
              document.getElementById("avg-submissions").innerText = avgPerGroup; */
        }

        function renderGroupsTable(filterText = "") {
            const tableBody = document.getElementById("groups-table-body");
            tableBody.innerHTML = "";

            const totalSubmissions = submissions.length;
            const groupsArray = Object.values(groupedData);

            // Filter if text search occurs
            const filteredArray = groupsArray.filter(g =>
                g.name.toLowerCase().includes(filterText.toLowerCase())
            );

            if (filteredArray.length === 0) {
                tableBody.innerHTML =
                    `<tr><td colspan="4" class="text-center text-muted py-4">No groups matched your query.</td></tr>`;
                return;
            }

            filteredArray.forEach(group => {
                const percentage = totalSubmissions > 0 ? ((group.count / totalSubmissions) * 100).toFixed(1) : 0;

                const row = document.createElement("tr");
                row.innerHTML = `
                    <td class="fw-bold text-dark">${escapeHtml(group.name)}</td>
                    <td><span class="badge bg-indigo-subtle text-primary px-3 py-2" style="background-color: rgba(92, 107, 192, 0.1); color: #5c6bc0 !important;">${group.count} Submissions</span></td>
                    <td style="width: 35%;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="progress-bar-sm flex-grow-1">
                                <div class="progress-bar-sm-fill" style="width: ${percentage}%;"></div>
                            </div>
                            <span class="text-muted small fw-semibold">${percentage}%</span>
                        </div>
                    </td>
                    <td class="text-end">
                        <button class="btn-view-details" onclick="openGroupDirectory('${escapeJsString(group.name)}')">
                            <i class="bi bi-eye-fill me-1"></i> View Directory
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        function filterGroupsTable() {
            const searchInput = document.getElementById("search-group").value;
            renderGroupsTable(searchInput);
        }

        function openGroupDirectory(groupName) {
            const groupInfo = groupedData[groupName];
            if (!groupInfo) return;

            document.getElementById("membersModalLabel").innerText = `Directory: ${groupName}`;
            const modalBody = document.getElementById("modal-table-body");
            modalBody.innerHTML = "";

            groupInfo.members.forEach(member => {
                let badgeClass = "bg-success-subtle text-success";
                if (member.status === "Pending") badgeClass = "bg-warning-subtle text-warning";
                if (member.status === "Rejected") badgeClass = "bg-danger-subtle text-danger";

                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td class="ps-3 fw-semibold text-dark">${escapeHtml(member.name)}</td>
                    <td class="font-mono text-muted">${member.id}</td>
                    <td><span class="small text-muted">${escapeHtml(member.q3)}</span></td>
                    <td><span class="badge ${badgeClass} px-2.5 py-1.5" style="font-size:0.8rem;">${member.status}</span></td>
                `;
                modalBody.appendChild(tr);
            });

            bootstrapMembersModal.show();
        }

        // Helper string sanitizers
        function escapeHtml(str) {
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(
                /'/g, "&#039;");
        }

        function escapeJsString(str) {
            return str.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '\\"');
        }

        window.onload = function() {
            bootstrapMembersModal = new bootstrap.Modal(document.getElementById('membersModal'));
            processGroupedMetrics();
            // renderGroupsTable();
        };
    </script>
@endsection
@section('scripts')
    <script src="/metronic/js/plugins/jquery.dataTables.min.js" type="text/javascript"></script>
    <script>
        let table = $('#groups-table-v2').DataTable({
            ajax: {
                url: `{{ route('forms.group-details', base64_encode($quiz->quiz_code)) }}`,
                dataSrc: 'data'
            },
            columns: [{
                    data: 'name',
                    render: function(data) {
                        return `
                    <span class="fw-bold text-dark">
                        ${data}
                    </span>
                `;
                    }
                },
                {
                    data: 'totalParticipants',
                    className: 'text-start',
                    render: function(data) {
                        return `
                    <span class="badge rounded-pill px-3 py-2"
                          style="background:#eef2ff;color:#5b5bd6;">
                        ${data} Submissions
                    </span>
                `;
                    }
                },
                {
                    data: 'participationWeight',
                    render: function(data) {
                        return `
                    <div class="d-flex align-items-center gap-3">
                        <div class="progress flex-grow-1"
                             style="height:8px;background:#eef0ff;">
                            <div class="progress-bar"
                                 style="width:${data}%;background:#6267d9;">
                            </div>
                        </div>
                        <span class="text-muted fw-semibold">
                            ${data}%
                        </span>
                    </div>
                `;
                    }
                },
                /* {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: function(data) {
                        return `
                <a href="#"
                   class="btn btn-outline-secondary btn-sm px-3">
                    <i class="bi bi-eye me-1"></i>
                    View Directory
                </a>
            `;
                    }
                } */
            ],
            paging: false,
            info: false,
            ordering: false,
            responsive: true
        });
        // Refresh every 5 seconds
        setInterval(function() {
            console.log('Reloading')
            table.ajax.reload(null, false); // false = keep current page
        }, 50000);
    </script>
@endsection
