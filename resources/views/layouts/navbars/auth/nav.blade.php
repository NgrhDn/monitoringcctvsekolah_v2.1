<!-- <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="../assets/img/sipmlogos.svg" alt="Logo" class="navbar-brand-img h-100">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <i>Logout</i>
                </li>
            </ul>
        </div>
    </div>
</nav> -->

<style>
    /* Custom Navbar Styling */
    .custom-navbar {
        background-color: #4CAF50;
        height: 70px;
        position: fixed;
        width: 100%;
        top: 0;
        left: 0;
        right: 0;
        z-index: 999;
        padding: 0 20px;
    }

    .custom-navbar .navbar-brand-img {
        height: 40px;
        /* Adjust the logo height */
        width: auto;
        /* Maintain aspect ratio */
    }

    .custom-navbar .navbar-toggler {
        border-color: transparent;
        /* Optional: Remove border from toggle button */
    }

    .custom-navbar .navbar-nav {
        margin-left: auto;
        /* Align the logout button and other items to the right */
    }

    .custom-navbar .nav-link {
        color: #fff;
        /* Set text color to white */
        font-weight: bold;
    }

    .custom-navbar .nav-item i {
        color: black;
        /* Set the logout text color to white */
        cursor: pointer;
        /* Change cursor to pointer on hover */
    }

    /* Optional: Hover effect for navbar items */
    .custom-navbar .nav-item:hover {
        background-color: #45a049;
        /* Slightly darker green when hovering */
    }
</style>

