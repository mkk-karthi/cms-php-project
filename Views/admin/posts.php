<?php include("../Views/admin/layout/header.php"); ?>

<section class="container-fluid py-3">
    <div class="text-center">
        <h3 class="fw-bold mb-4">Posts</h3>
    </div>
    <div class="card">
        <div class="card-body py-3">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal"
                    data-bs-target="#postModal">Create</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th style="width:50%">Content</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="post-list">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>


<!-- Post Create/Edit modal -->
<div class="modal modal-lg fade" id="postModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5 post-title" id="staticBackdropLabel">Create Post</h1>
                <button type="button" class="btn-close post-close" id="postModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="post-form" data-id="">
                    <div class="mb-4">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" id="image" accept="image/*">
                        <div class="invalid-feedback" id="image-error"></div>
                    </div>


                    <div class="mb-4">
                        <label for="title" class="form-label">Title*</label>
                        <input type="text" name="title" class="form-control" id="title">
                        <div class="invalid-feedback" id="title-error"></div>
                    </div>

                    <div class="mb-4">
                        <label for="content" class="form-label">Content*</label>
                        <textarea name="content" class="form-control" id="content" rows="3"></textarea>
                        <div class="invalid-feedback" id="content-error"></div>
                    </div>

                    <!-- Submit button -->
                    <div class="d-flex justify-content-end mb-2">
                        <button type="button" class="btn btn-outline-secondary m-1 post-close" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary m-1"
                            id="post-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../Views/admin/layout/footer.php"); ?>