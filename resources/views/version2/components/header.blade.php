@section('navigation')
    <header id="main-nav" class="navbar navbar-expand-lg bg-white border-b border-light sticky-top shadow-sm p-0">
        <div class="container-xl d-flex flex-column align-items-stretch">

            <!-- ROW 1: Branding & User Profile -->
            <div class="d-flex align-items-center justify-content-between py-2 w-100">
                <!-- Branding / Logo -->
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-navy-900 text-white p-2 rounded-3 leading-none d-flex align-items-center">
                        <i class="bi bi-diagram-3-fill fs-5"></i>
                    </div>
                    <div>
                        <span class="fw-extrabold text-navy-900 tracking-tight fs-5 d-block lh-1">i-LOGS</span>
                    </div>
                </div>

                <!-- User Profile & Logout -->
                <div class="d-flex align-items-center gap-3">
                    <div class="d-none d-md-flex flex-column text-end">
                        <span id="nav-user-name" class="fw-bold fs-6 text-dark">{{ auth()->user()->name }}</span>
                        <span id="nav-user-role" class="text-muted" style="font-size: 11px;">Administrator</span>
                    </div>
                    <div class="bg-navy-100 text-navy-900 fw-bold rounded-circle border d-flex align-items-center justify-content-center shadow-inner"
                        style="width: 40px; height: 40px; text-align: center; line-height: 40px;">
                        <span id="nav-user-avatar" class="m-auto">A</span>
                    </div>

                    <a class="btn btn-link text-muted p-2 hover-text-danger" title="Sign Out" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right fs-4"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- ROW 2: Sub-Header Navigation -->
            <div class="border-top border-light py-2 w-100" id="subNavRow">
                <ul class="navbar-nav d-flex flex-row gap-4 mb-0">
                    <li class="nav-item">
                        <a class="nav-link text-secondary fw-semibold px-0 py-1 hover-text-navy transition-all"
                            href="/">
                            <i class="bi bi-list-nested me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary fw-semibold px-0 py-1 hover-text-navy transition-all"
                            href="{{-- {{ route('forms.view') }} --}}/dashboard/forms/">
                            <i class="bi bi-file-earmark-text me-1"></i> Forms
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary fw-semibold px-0 py-1 hover-text-navy transition-all"
                            href="{{ route('groups.index') }}">
                            <i class="bi bi-people me-1"></i> Groups
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary fw-semibold px-0 py-1 hover-text-navy transition-all"
                            href="{{ route('admin.companies.index') }}">
                            <i class="bi bi-building me-1"></i> Company
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary fw-semibold px-0 py-1 hover-text-navy transition-all"
                            href="/dashboard/scribes">
                            <i class="bi bi-person-lines-fill me-1"></i> Participants
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </header>
@endsection
