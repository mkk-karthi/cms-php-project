<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="/assets/app.css" rel="stylesheet">
    <script src="/assets/common.js"></script>
    <script src="/assets/app.js"></script>
</head>

<body>
    <!-- notifications -->
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
                <!-- <li><a href="/" class="nav-link px-2 link-secondary">Home</a></li>
                <li><a href="/posts" class="nav-link px-2">Posts</a></li> -->
            </ul>

            <div class="col-3 col-md-3 text-end" id="header-action">
                <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal"
                    data-bs-target="#loginModal" id="loginBtn">Login</button>
            </div>
        </header>
    </div>

    <!-- slider image -->
    <section id="carousel">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner" id="carousel-slider">
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>

        </div>
    </section>

    <!-- testimonials -->
    <section class="container py-5" id="testimonials">
        <div class="text-center">
            <h3 class="fw-bold mb-4">Testimonials</h3>
        </div>

        <div class="row text-center justify-content-center" id="testimonials-list">
        </div>
    </section>

    <!-- About Us -->
    <section class="container py-3" id="about">
        <div class="text-center">
            <h3 class="fw-bold mb-4">About Us</h3>
        </div>
        <div class="row align-items-center gx-4">
            <div class="col-12 col-md-4 p-2">
                <img class="img-fluid rounded-3" src="" id="about-img">
            </div>
            <div class="col-12 col-md-6 offset-md-1">
                <p class="lead" id="about-content"></p>
            </div>
        </div>
    </section>

    <!-- Posts -->
    <section class="container py-3" id="posts">
        <div class="text-center">
            <h3 class="fw-bold mb-4">Posts</h3>
        </div>

        <div class="row" id="post-content"></div>
    </section>

    <!-- Contact -->
    <section class="container py-3" id="contact-container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9">
                <div class="card">
                    <div class="card-title">
                        <div class="text-center">
                            <h3 class="fw-bold m-4">Contact Us</h3>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row justify-content-center">
                            <form class="col-12 col-md-9" id="contact-form">
                                <div class="mb-4">
                                    <label for="name" class="form-label">Name*</label>
                                    <input type="text" name="name" class="form-control" id="name" autocomplete="off">
                                    <div class="invalid-feedback" id="name-error"></div>
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="form-label">Email*</label>
                                    <input type="email" name="email" class="form-control" id="email" autocomplete="off">
                                    <div class="invalid-feedback" id="email-error"></div>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">Password*</label>
                                    <div class="input-group has-validation">
                                        <input type="password" name="password" class="form-control" id="password" autocomplete="off">
                                        <span class="input-group-text" id="inputGroupAppend">
                                            <i class="bi bi-eye-slash" id="password-eye" style="cursor: pointer;"></i>
                                        </span>
                                        <div class="invalid-feedback" id="password-error"></div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="message" class="form-label">Message*</label>
                                    <textarea name="message" class="form-control" id="message" rows="3"></textarea>
                                    <div class="invalid-feedback" id="message-error"></div>
                                </div>

                                <!-- subscribe Checkbox -->
                                <div class="form-check d-flex justify-content-center mb-4">
                                    <input type="checkbox" name="subscribe" class="form-check-input me-2" id="subscribe"
                                        checked />
                                    <label class="form-check-label" for="subscribe">
                                        Subscribe to our newsletter
                                    </label>
                                </div>

                                <!-- Submit button -->
                                <div class="d-grid mb-4">
                                    <button type="button" class="btn btn-primary btn-block"
                                        id="contact-submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Post View Modal -->
    <div class="modal modal-lg fade" id="postModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="post-modal">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Post</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <img src="" class="w-100 mb-3"
                        style="object-fit: contain; height: 300px;" id="post-modal-img" />
                    <h5 class="font-weight-bold" id="post-modal-title"></h5>
                    <p class="mb-2" id="post-modal-body"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Login*</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="loginModalClose"></button>
                </div>
                <div class="modal-body">
                    <form id="login-form">
                        <div class="mb-4">
                            <label for="email" class="form-label">Email*</label>
                            <input type="email" name="email" class="form-control" id="email" autocomplete="off">
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group has-validation">
                                <input type="password" name="password" class="form-control" id="password" autocomplete="off">
                                <span class="input-group-text" id="inputGroupAppend">
                                    <i class="bi bi-eye-slash" id="password-eye" style="cursor: pointer;"></i>
                                </span>
                                <div class="invalid-feedback" id="password-error"></div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="d-grid mb-4">
                            <button type="button" class="btn btn-primary btn-block" id="login-submit">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>