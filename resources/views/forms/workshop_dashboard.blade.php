@extends('forms.app')
@section('content')
    <style>
        <style> :root {
            --bs-body-bg: #f3f5f9;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --accent-primary: #101a36;
            /* Deep Navy */
            --accent-blue: #3f51b5;
            /* Royal SPREAD Blue */
            --accent-hover: #2c387e;
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

        .summary-container {
            max-width: 1200px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Metric Indicators Styling */
        .metric-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* Primary layout panel styling */
        .summary-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            padding: 2.5rem;
        }

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
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
            padding: 14px 16px;
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

        .progress-bar-sm {
            height: 8px;
            border-radius: 4px;
            background-color: #eaeaf5;
        }

        .progress-bar-sm-fill {
            height: 100%;
            background-color: #5c6bc0;
            border-radius: 4px;
        }

        .btn-view-details {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 6px 14px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .btn-view-details:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }

        .form-control-search {
            max-width: 300px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 14px;
            font-size: 0.85rem;
        }

        .form-control-search:focus {
            border-color: #5c6bc0;
            box-shadow: 0 0 0 3px rgba(92, 107, 192, 0.15);
            outline: none;
        }
    </style>
    </style>
    <div class="survey-header-section">
        <h1 class="survey-title"><b>{{ $quiz->title }}</b></h1>
        <p class="survey-subtitle">
            {{ $quiz->description }}
        </p>
    </div>
    <div class="summary-card">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h3 class="fw-bold text-dark m-0">Groups Participation</h3>
                <p class="text-muted small mb-0">Overview of submission counts grouped by workshop tracks from
                    image_5e295c.png.</p>
            </div>
            <div>
                <input type="text" id="search-group" class="form-control-search" placeholder="Search workspace group..."
                    oninput="filterGroupsTable()">
            </div>
        </div>

        <!-- Main Data Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-spread align-middle">
                    <thead>
                        <tr>
                            <th>Group Name</th>
                            <th>Submissions Count</th>
                            <th>Participation Weight</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="groups-table-body">
                        <!-- Populated dynamically via JS -->
                    </tbody>
                </table>
            </div>
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
            renderGroupsTable();
        };
    </script>
@endsection
