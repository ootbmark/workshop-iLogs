@extends('layouts.appV2')
@section('content')
    <div class="container-xl py-5">
        <div class="card-custom p-4 mb-4">
            <div
                class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 border-bottom pb-3 mb-4">
                <div class="text-start">
                    <h3 class="fw-extrabold text-dark mb-1">Participants</h3>
                    {{--  <p class="text-muted small mb-0">Add or edit operational companies, sub-contractors, and education
                        institutions.</p> --}}
                </div>
                {{-- <button onclick="openCompanyForm(null)"
                    class="btn btn-spreadBlue py-2.5 px-3 rounded-3 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-building-plus"></i> Add Group
                </button> --}}
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-start">
                    <thead class="bg-light">
                        <tr class="text-uppercase text-muted small fw-bold" style="font-size: 11px;">
                            <th>Participant Name</th>
                            <th>Group</th>
                            <th>Quiz</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="company-table-body" class="text-muted" style="font-size: 14px;">
                        @foreach ($participantList as $item)
                            <tr>
                                <td class="fw-bold text-navy-900">
                                    @if ($item->user)
                                        {{ $item->user->name }}
                                    @else
                                        {{ $item->participate_name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->group)
                                        {{ $item->group->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->quiz)
                                        <a href="{{ route('admin.builder.view-report', base64_encode($item->quiz_id)) }}">
                                            {{ $item->quiz->title }}</a>
                                    @endif
                                </td>
                                <td></td>
                                {{-- 
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        <button onclick="openCompanyForm({{ $item->id }})"
                                            class="btn btn-light btn-sm text-muted p-2 rounded-3 border-0"
                                            title="Edit User">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button onclick="confirmDelete('{{ base64_encode($item->id) }}')"
                                            class="btn btn-light btn-sm text-muted p-2 rounded-3 hover-text-danger border-0"
                                            title="Delete User">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </div>
                                    <form id="{{ base64_encode($item->id) }}"
                                        action="{{ route('groups.destroy', $item->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td> --}}
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
                    <form action="{{ route('groups.store') }}" method="post" id="modal-form">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3 text-start">
                            <label for="group-name"
                                class="form-label text-uppercase text-muted fw-bold small tracking-wider"
                                style="font-size: 11px;">Group Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0"><i
                                        class="bi bi-organization"></i></span>
                                <input id="form-group-name" type="text" name="name" value="{{ old('name') }}"
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
        let participantList = []
        document.addEventListener('DOMContentLoaded', function() {
            participantList = @json($participantList)
        });
        window.onload = function() {
            modalInstance = new bootstrap.Modal(document.getElementById('adminConfigModal'));
            confirmInstance = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));

        };

        function confirmDelete(data) {
            console.log(data)
            const form = document.getElementById(data);
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to undo this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function openCompanyForm(editId) {
            const label = document.getElementById('adminConfigModalLabel');
            const body = document.getElementById('adminConfigModalBody');
            const form = document.getElementById('modal-form');
            const name = document.getElementById('form-group-name');
            const methodInput = document.getElementById('formMethod');
            if (editId) {
                console.log(groupList)
                companyObj = groupList.find(c => c.id === editId);
                console.log(companyObj)
                name.value = companyObj.name
                label.innerText = "Modify Group Details";
                form.action = `/admin/groups/${companyObj.id}`;
                form.method = "POST"; // stays POST (HTML limitation)
                methodInput.value = "PUT"; // Laravel spoof
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
