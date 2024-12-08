<?php include("../Views/admin/layout/header.php"); ?>

<section class="container-fluid py-3">
    <div class="card">
        <div class="card-body py-3">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="slider-tab" data-bs-toggle="tab" data-bs-target="#slider"
                        type="button" role="tab" aria-controls="slider" aria-selected="true">Slider</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="testimonials-tab" data-bs-toggle="tab"
                        data-bs-target="#testimonials" type="button" role="tab" aria-controls="testimonials"
                        aria-selected="false">Testimonials</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about"
                        type="button" role="tab" aria-controls="about" aria-selected="false">About</button>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <!-- slider image -->
                <div class="tab-pane active" id="slider" role="tabpanel" aria-labelledby="slider-tab" tabindex="0">
                    <div class="row row-gap-2 d-flex justify-content-center" id="slider-content">
                    </div>
                </div>

                <!-- testimonials -->
                <div class="tab-pane" id="testimonials" role="tabpanel" aria-labelledby="testimonials-tab"
                    tabindex="0">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal"
                            data-bs-target="#testimonialsModal">Create</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="testimonials-list">
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- about us -->
                <div class="tab-pane" id="about" role="tabpanel" aria-labelledby="about-tab" tabindex="0">
                    <div class="row align-items-center gx-4">
                        <div class="col-12 col-md-3 p-2">
                            <img class="img-fluid rounded-3" src="" id="about-img">
                        </div>
                        <div class="col-12 col-md-6 offset-md-1">
                            <p class="lead" id="about-content"></p>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-success my-1" id="about-action" data-id="" data-type="3" data-action="edit" data-bs-toggle="modal" data-bs-target="#aboutModal"><i class="bi bi-pencil"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About edit modal -->
    <div class="modal modal-lg fade" id="aboutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">About</h1>
                    <button type="button" class="btn-close widget-3-close" id="aboutModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="widget-3-form" data-id="">
                        <div class="mb-4">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" id="image" accept="image/*">
                            <div class="invalid-feedback" id="image-error"></div>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label">Content*</label>
                            <textarea name="content" class="form-control" id="content" rows="3"></textarea>
                            <div class="invalid-feedback" id="content-error"></div>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex justify-content-end mb-2">
                            <button type="button" class="btn btn-outline-secondary m-1 widget-3-close" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary m-1"
                                id="widget-3-submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Create/Edit modal -->
    <div class="modal modal-lg fade" id="testimonialsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h1 class="modal-title fs-5 widget-2-title" id="staticBackdropLabel">Create Post</h1>
                    <button type="button" class="btn-close widget-2-close" id="testimonialsModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="widget-2-form" data-id="">
                        <div class="mb-4">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" id="image" accept="image/*">
                            <div class="invalid-feedback" id="image-error"></div>
                        </div>

                        <div class="mb-4">
                            <label for="name" class="form-label">Name*</label>
                            <input type="text" name="name" class="form-control" id="name">
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description*</label>
                            <textarea name="description" class="form-control" id="description" rows="3"></textarea>
                            <div class="invalid-feedback" id="description-error"></div>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex justify-content-end mb-2">
                            <button type="button" class="btn btn-outline-secondary m-1 widget-2-close" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary m-1"
                                id="widget-2-submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>
<?php include("../Views/admin/layout/footer.php"); ?>