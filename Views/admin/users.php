<?php include("../Views/admin/layout/header.php"); ?>

<section class="container-fluid py-3">
    <div class="text-center">
        <h3 class="fw-bold mb-4">Users</h3>
    </div>
    <div class="card">
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="user-list">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include("../Views/admin/layout/footer.php"); ?>