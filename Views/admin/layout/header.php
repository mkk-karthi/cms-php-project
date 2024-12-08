<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="/assets/app.css" rel="stylesheet">
    <script src="/assets/common.js"></script>
    <script src="/assets/admin.js"></script>
</head>

<body>
    <!-- notification -->
    <div class="position-fixed d-flex flex-column align-items-end" style="bottom: 10px; right: 10px; z-index: 1055;"
        id="notifications">
    </div>

    <!-- header -->
    <div class="container-fluid bg-light">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3">
            <div class="col-12 col-md-3 mb-2 mb-md-0 text-center">
                <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                    <?php echo APP_NAME; ?>
                </a>
            </div>

            <ul class="nav col-9 col-md-auto mb-2 mb-md-0 justify-content-center">
                <li><a href="/admin" class="nav-link px-2">Home</a></li>
                <li><a href="/admin/posts" class="nav-link px-2">Posts</a></li>
                <li><a href="/admin/users" class="nav-link px-2">Users</a></li>
            </ul>

            <div class="col-3 col-md-3 text-end">
                <?php if (!isset($_SESSION['auth']) || !$_SESSION['auth']) { ?>
                    <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal"
                        data-bs-target="#loginModal" id="loginBtn">Login</button>
                <?php } else { ?>
                    <button type="button" class="btn btn-outline-primary me-3" id="logoutBtn">Logout</button>
                <?php } ?>
            </div>
        </header>
    </div>