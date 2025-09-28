<!-- Start Sidebar -->
<nav class="sidebar d-flex flex-column p-3">
    <h4 class="text-white">CardinalStone</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li>
            <a href="{{ route('dashboard') }}" class="nav-link text-white d-flex align-items-center">
                <span class="me-2 bi bi-speedometer2"></span> Dashboard
            </a>
        </li>
        @admin
        <li>
            <a href="#userMenu" data-bs-toggle="collapse" class="nav-link text-white d-flex align-items-center">
                <span class="me-2 bi bi-people"></span>User Management
                <span class="ms-auto bi bi-caret-down-fill"></span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="userMenu">
                <li><a href="{{ route('users.index') }}" class="nav-link">Users</a></li>
            </ul>
        </li>
        <li>
            <a href="#companyMenu" data-bs-toggle="collapse" class="nav-link text-white d-flex align-items-center">
                <span class="me-2 bi bi-building"></span> Company Management
                <span class="ms-auto bi bi-caret-down-fill"></span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="companyMenu">
                <li><a href="{{ route('companies.index') }}" class="nav-link">Company</a></li>
                <li><a href="{{ route('shareholders.index') }}" class="nav-link">Shareholders</a></li>
                <li><a href="{{ route('agms.index') }}" class="nav-link">AGM</a></li>
            </ul>
        </li>
        @endadmin
    </ul>
</nav>
<!-- End Sidebar -->