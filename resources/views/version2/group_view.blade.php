@extends('layouts.appV2')
@section('content')
    <div class="container-xl py-5">
        <div class="card-custom p-4 mb-4">
            <div
                class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 border-bottom pb-3 mb-4">
                <div class="text-start">
                    <h3 class="fw-extrabold text-dark mb-1">GROUP</h3>
                    {{--  <p class="text-muted small mb-0">Add or edit operational companies, sub-contractors, and education
                        institutions.</p> --}}
                </div>
                <button onclick="openCompanyForm()"
                    class="btn btn-spreadBlue py-2.5 px-3 rounded-3 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-building-plus"></i> Add Group
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-start">
                    <thead class="bg-light">
                        <tr class="text-uppercase text-muted small fw-bold" style="font-size: 11px;">
                            <th>Group Name</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="company-table-body" class="text-muted" style="font-size: 14px;">
                        @foreach ($groupList as $item)
                            <tr>
                                <td class="fw-bold text-navy-900">{{ $item->name }}</td>

                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        <button onclick="openCompanyForm('{{ $item->id }}')"
                                            class="btn btn-light btn-sm text-muted p-2 rounded-3 border-0"
                                            title="Edit User">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button onclick="confirmDelete('user', '{{ $item->id }}')"
                                            class="btn btn-light btn-sm text-muted p-2 rounded-3 hover-text-danger border-0"
                                            title="Delete User">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- ==================== DYNAMIC CONFIGURATION MODAL ==================== -->
    <div class="modal fade" id="adminConfigModal" tabindex="-1" aria-labelledby="adminConfigModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header bg-navy-900 text-white border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold" id="adminConfigModalLabel">Configure Resource</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-start" id="adminConfigModalBody">
                    <form action="{{ route('groups.store') }}" method="post">
                        @csrf
                        <div class="mb-3 text-start">
                            <label for="group-name"
                                class="form-label text-uppercase text-muted fw-bold small tracking-wider"
                                style="font-size: 11px;">Group Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0"><i
                                        class="bi bi-organization"></i></span>
                                <input id="group-name" type="text" name="name" value="{{ old('name') }}"
                                    class="form-control border-start-0 py-2.5 shadow-none" placeholder="">

                            </div>
                            @error('name')
                                <small class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-navy-900 w-100 py-3 rounded-3 shadow-sm fw-bold">
                            SUBMIT
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let modalInstance = null;
        let confirmInstance = null;
        let activeDeleteAction = null;
        let groupList = []
        window.onload = function() {
            modalInstance = new bootstrap.Modal(document.getElementById('adminConfigModal'));
            confirmInstance = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            groupList = @json($groupList)
        };

        function confirmDelete(type, id) {
            const body = document.getElementById('deleteConfirmModalBody');
            let confirmMsg = '';

            if (type === 'company') {
                const item = companies.find(c => c.id === id);
                if (!item) return;
                confirmMsg =
                    `<p>Are you sure you want to permanently delete the company profile of <strong>"${escapeHtml(item.name)}"</strong>?</p>
                <p class="text-danger mb-0"><i class="bi bi-exclamation-circle-fill"></i> Warning: Users associated with this company will lose their organizational binding context.</p>`;
                activeDeleteAction = () => {
                    companies = companies.filter(c => c.id !== id);
                    renderCompanies();
                };
            } else if (type === 'group') {
                const item = groups.find(g => g.id === id);
                if (!item) return;
                confirmMsg =
                    `<p>Are you sure you want to permanently delete the sector track <strong>"${escapeHtml(item.name)}"</strong>?</p>
                <p class="text-danger mb-0"><i class="bi bi-exclamation-circle-fill"></i> Warning: All active dashboard registrations and counts associated with this track will be deleted.</p>`;
                activeDeleteAction = () => {
                    groups = groups.filter(g => g.id !== id);
                    renderGroups();
                };
            } else if (type === 'user') {
                const item = users.find(u => u.id === id);
                if (!item) return;
                confirmMsg =
                    `<p>Are you sure you want to permanently revoke system privileges of user <strong>"${escapeHtml(item.name)}"</strong>?</p>
                <p class="text-danger mb-0"><i class="bi bi-exclamation-circle-fill"></i> Warning: This profile directory cannot be recovered.</p>`;
                activeDeleteAction = () => {
                    users = users.filter(u => u.id !== id);
                    renderUsers();
                    renderGroups(); // Refactor active counter
                };
            }

            body.innerHTML = confirmMsg;

            // Bind Proceed Buttons
            document.getElementById('deleteConfirmBtn').onclick = function() {
                if (activeDeleteAction) activeDeleteAction();
                confirmInstance.hide();
            };

            confirmInstance.show();
        }

        function openCompanyForm(editId = null) {
            const label = document.getElementById('adminConfigModalLabel');
            const body = document.getElementById('adminConfigModalBody');

            let companyObj = {
                id: '',
                name: '',
                type: 'Oil Co Partner',
                location: ''
            };
            if (editId) {
                companyObj = groupList.find(c => c.id === editId) || companyObj;
                console.log(companyObj)
                label.innerText = "Modify Group Details";
            } else {
                label.innerText = "Add Group";
            }

            modalInstance.show();
        }

        function saveCompany(e, editId) {
            e.preventDefault();
            const name = document.getElementById('comp-form-name').value.trim();
            const type = document.getElementById('comp-form-type').value;
            const location = document.getElementById('comp-form-loc').value.trim() || 'N/A';

            if (editId) {
                // Update
                const idx = companies.findIndex(c => c.id === editId);
                if (idx !== -1) {
                    companies[idx] = {
                        id: editId,
                        name,
                        type,
                        location
                    };
                }
            } else {
                // Insert New
                companies.push({
                    id: 'C-' + Date.now().toString(36),
                    name,
                    type,
                    location
                });
            }

            renderCompanies();
            renderUsers(); // Update company affiliations
            modalInstance.hide();
        }
    </script>
@endsection
