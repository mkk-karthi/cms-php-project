const BaseURL = location.origin;

// get widget datas
function getWidgets() {

    const carouselEle = document.getElementById("carousel");
    const testimonialsEle = document.getElementById("testimonials");
    const aboutEle = document.getElementById("about");

    // hide the widgets
    carouselEle.style.display = "none";
    testimonialsEle.style.display = "none";
    aboutEle.style.display = "none";

    AjaxPost(`${BaseURL}/api/widgets`)
        .then(res => JSON.parse(res))
        .then(async res => {

            if (res.code == 0 && res.data && res.data.length > 0) {
                var sliderContent = "";
                var testimonialsContent = "";

                let i = 0;
                for await (const item of res.data) {
                    if (item.type == 1) {
                        // slider image content
                        sliderContent += `<div class="carousel-item ${i == 0 ? 'active' : ''}">
                                        <img src="${BaseURL + item.image}" class="w-100 vh-30 vh-md-90" style="object-fit: cover;" />
                                    </div>`
                        i++;
                    } else if (item.type == 2) {

                        // testimonials content
                        testimonialsContent += `<div class="col-md-4 mb-4 mb-md-0">
                                <div class="card h-100">
                                    <div class="card-body py-4 mt-2">
                                        <div class="d-flex justify-content-center mb-4">
                                            <img src="${BaseURL + item.image}" class="rounded-circle shadow-1-strong" width="100" height="100" alt="${item.name}" />
                                        </div>
                                        <h5 class="font-weight-bold">${item.name}</h5>
                                        <p class="mb-2">L${item.description}
                                        </p>
                                    </div>
                                </div>
                            </div>`
                    } else if (item.type == 3) {

                        // set about us content
                        document.getElementById("about-img").src = BaseURL + item.image;
                        document.getElementById("about-content").innerHTML = item.content;
                        aboutEle.style.display = "block";
                    }
                }

                // set slider
                if (sliderContent) {
                    document.getElementById("carousel-slider").innerHTML = sliderContent;
                    carouselEle.style.display = "block";
                }

                // set testimonials
                if (testimonialsContent) {
                    document.getElementById("testimonials-list").innerHTML = testimonialsContent;
                    testimonialsEle.style.display = "block";
                }
            }
        })
        .catch(error => {
            console.log(error)
        })
}

// get posts
function getPosts() {
    const postsEle = document.getElementById("posts");
    if (postsEle) {
        postsEle.style.display = "none";

        AjaxPost(`${BaseURL}/api/posts`)
            .then(res => JSON.parse(res))
            .then(async res => {

                if (res.code == 0) {
                    if (res.data && res.data.length > 0) {
                        var content = "";

                        for await (const item of res.data) {

                            // generate post lists
                            content += `<div class="col-12 mb-2">
                                <div class="card">
                                    <div class="card-body py-3">
                                        <div class="row column-gap-3">
                                            <div class="col-12 col-md-3 col-lg-2">
                                                <img src="${BaseURL + item.image}" class="w-100 h-100"
                                                    style="object-fit: contain;" alt="${item.title}" />
                                            </div>
                                            <div class="col-12 col-md-8 col-lg-9">
                                                <div class="d-flex align-items-center h-100 my-3">
                                                    <div class="w-100">
                                                        <h5 class="font-weight-bold">${item.title}</h5>
                                                        <p class="text-hidden-2 mb-0">${item.content}
                                                        </p>
                                                        <p class="text-primary text-end m-2" style="cursor: pointer;"
                                                            data-bs-toggle="modal" data-bs-target="#postModal" data-id="${item.id}" id="read-more">Read more</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                        }

                        if (content) {
                            // set post lists
                            document.getElementById("post-content").innerHTML = content;
                            postsEle.style.display = "block";

                            // post actions
                            document.querySelectorAll("#read-more").forEach(function (element) {
                                element.addEventListener('click', event => {
                                    event.preventDefault();
                                    getPost(event.target.getAttribute("data-id"))
                                });
                            });
                        }
                    } else {
                        document.getElementById("about-content").innerHTML = `<p class="text-center">Posts not found</p>`;
                        postsEle.style.display = "block";
                    }
                } else {
                    document.getElementById("post-content").innerHTML = `<p class="text-danger text-center">${res.message}</p>`;
                    postsEle.style.display = "block";
                }
            })
            .catch(error => {
                console.log(error)
            })
    }
}

// get post
function getPost(id) {
    const postModal = document.getElementById("post-modal-body");
    if (postModal) {
        postModal.style.display = "none";

        AjaxGet(`${BaseURL}/api/post/view/${id}`)
            .then(res => JSON.parse(res))
            .then(async res => {

                if (res.code == 0) {

                    // set post modal content
                    document.getElementById("post-modal-title").innerText = res.data.title;
                    document.getElementById("post-modal-img").src = res.data.image;
                    document.getElementById("post-modal-body").innerHTML = res.data.content;
                    postModal.style.display = "block";
                } else {

                    // error print
                    document.getElementById("post-modal-title").innerText = "Error!";
                    document.getElementById("post-modal-body").innerHTML = `<p class="text-danger text-center">${res.message}</p>`;
                    postModal.style.display = "block";
                }
            })
            .catch(error => {
                console.log(error)
            })
    }
}

// contact submit
async function registerSubmit() {

    const FormFields = ["name", "email", "password", "message"];
    const FormData = {};
    document.getElementById("contact-submit").disabled = true

    // dynamic validation
    let error = false;
    let emailPattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@.#$%*?&^])[A-Za-z\d@.#$!%*?&]{8,}$/;

    for await (const key of FormFields) {
        const ele = document.querySelector(`#contact-form #${key}`);
        let error_message = "";

        if (!ele.value) {
            error_message = capitalize(`${key} is required`);
        } else if (key == "name" && ele.value.length > 20) {
            error_message = "Name may not be greater than 20 characters";
        } else if (key == "email" && !emailPattern.test(ele.value)) {
            error_message = "Email is invalid";
        } else if (key == "password") {

            if (ele.value.length < 8) {
                error_message = "Password must be at least 8";
            } else if (!passwordPattern.test(ele.value)) {
                error_message = "Password is invalid";
            }

        } else if (key == "message" && ele.value.length > 250) {
            error_message = "Message may not be greater than 250 characters";
        }

        if (error_message) {
            error = true;
            document.querySelector(`#contact-form #${key}-error`).innerText = error_message;
            ele.classList.add("is-invalid");
        } else {
            ele.classList.remove("is-invalid");
        }
        FormData[key] = ele.value;
    }
    const subscribe = document.querySelector(`#contact-form #subscribe`).checked;
    FormData["subscribe"] = subscribe;

    if (!error) {
        let confirmed = confirm("Are you sure to confirm?");
        if (confirmed)
            registerApi(FormData)
    } else {
        document.getElementById("contact-submit").disabled = false
    }
}

// register api
function registerApi(FormData) {

    AjaxPost(`${BaseURL}/api/user/create`, FormData)
        .then(res => JSON.parse(res))
        .then(async res => {

            document.getElementById("contact-submit").disabled = false

            if (res.code == 0) {
                // clear contact fields
                document.querySelector(`#contact-form #name`).value = "";
                document.querySelector(`#contact-form #email`).value = "";
                document.querySelector(`#contact-form #password`).value = "";
                document.querySelector(`#contact-form #message`).value = "";

                // login
                loginApi({ email: FormData.email, password: FormData.password })
            } else {
                appNotification("danger", res.message)
            }
        })
        .catch(error => {
            document.getElementById("contact-submit").disabled = false
            console.log(error)
        })
}

async function loginSubmit() {

    const FormFields = ["email", "password"];
    const FormData = {};
    document.getElementById("login-submit").disabled = true

    // dynamic validation
    let error = false;
    let emailPattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@.#$%*?&^])[A-Za-z\d@.#$!%*?&]{8,}$/;

    for await (const key of FormFields) {
        const ele = document.querySelector(`#login-form #${key}`);
        let error_message = "";

        if (!ele.value) {
            error_message = capitalize(`${key} is required`);
        } else if (key == "email" && !emailPattern.test(ele.value)) {
            error_message = "Email is invalid";
        } else if (key == "password") {

            if (ele.value.length < 8) {
                error_message = "Password must be at least 8";
            } else if (!passwordPattern.test(ele.value)) {
                error_message = "Password is invalid";
            }

        }

        if (error_message) {
            error = true;
            document.querySelector(`#login-form #${key}-error`).innerText = error_message;
            ele.classList.add("is-invalid");
        } else {
            ele.classList.remove("is-invalid");
        }
        FormData[key] = ele.value;
    }

    if (!error) {
        loginApi(FormData)
    } else {

        document.getElementById("login-submit").disabled = false
    }
}

// login api
function loginApi(FormData) {

    AjaxPost(`${BaseURL}/api/login`, FormData)
        .then(res => JSON.parse(res))
        .then(async res => {

            document.getElementById("login-submit").disabled = false

            if (res.code == 0) {

                // clear login fields
                document.querySelector(`#login-form #email`).value = "";
                document.querySelector(`#login-form #password`).value = "";

                // set the session data
                sessionStorage.setItem("auth", 1);
                sessionStorage.setItem("is_admin", res.is_admin ? 1 : 2);
                if (!res.is_admin) appRun();

                appNotification("success", res.message, 3000, () => {
                    if (res.is_admin)
                        location.href = BaseURL + "/admin";
                })
                document.getElementById('loginModalClose').click()
            } else {
                document.getElementById("login-submit").disabled = false
                appNotification("danger", res.message)
            }
        })
        .catch(error => {
            console.log(error)
        })
}

// logout api
function logoutApi() {

    AjaxPost(`${BaseURL}/api/logout`)
        .then(res => JSON.parse(res))
        .then(async res => {

            if (res.code == 0) {

                // clear the session
                sessionStorage.setItem("auth", 0);
                sessionStorage.setItem("is_admin", 0);

                appNotification("success", res.message)
                appRun()
            } else {
                appNotification("danger", res.message)
            }
        })
        .catch(error => {
            console.log(error)
        })
}

function appRun() {
    document.getElementById("posts").style.display = "none";

    if (sessionStorage.getItem("auth") == 1) {
        getPosts();

        // add logout button
        document.getElementById("header-action").innerHTML = `<button type="button" class="btn btn-outline-primary me-3" id="logoutBtn">Logout</button>`;
        document.getElementById("contact-container").style.display = "none";

        // logout event
        if (document.getElementById("logoutBtn")) {
            document.getElementById("logoutBtn").addEventListener("click", (event) => {

                event.preventDefault();
                logoutApi();
            })
        }
    } else {

        // add login button
        document.getElementById("header-action").innerHTML = `<button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#loginModal" id="loginBtn">Login</button>`;
        document.getElementById("contact-container").style.display = "block";

        // login form actions
        if (document.getElementById("login-submit")) {
            document.getElementById("login-submit").addEventListener("click", (event) => {

                event.preventDefault();
                loginSubmit();
            })

            // login password
            const LoginPassEyeIcon = document.querySelector("#login-form #password-eye")
            if (LoginPassEyeIcon)
                LoginPassEyeIcon.addEventListener("click", (event) => {

                    event.preventDefault();
                    LoginPassEyeIcon.classList.toggle("bi-eye")
                    LoginPassEyeIcon.classList.toggle("bi-eye-slash")

                    const LoginPassInput = document.querySelector("#login-form #password")
                    if (LoginPassInput.type == "text") LoginPassInput.type = "password"
                    else LoginPassInput.type = "text"
                })
        }

        // clear login form fields when close the popup
        document.getElementById("loginModalClose").addEventListener("click", (event) => {
            event.preventDefault();

            document.querySelector(`#login-form #email`).value = "";
            document.querySelector(`#login-form #password`).value = "";
        })

        // contact form actions
        if (document.getElementById("contact-submit")) {

            document.getElementById("contact-submit").addEventListener("click", (event) => {

                event.preventDefault();
                registerSubmit();
            })

            // contact password
            const ContactPassEyeIcon = document.querySelector("#contact-form #password-eye")

            if (ContactPassEyeIcon)
                ContactPassEyeIcon.addEventListener("click", (event) => {
                    event.preventDefault();
                    ContactPassEyeIcon.classList.toggle("bi-eye")
                    ContactPassEyeIcon.classList.toggle("bi-eye-slash")

                    const ContactPassInput = document.querySelector("#contact-form #password")
                    if (ContactPassInput.type == "text") ContactPassInput.type = "password"
                    else ContactPassInput.type = "text"
                })
        }
    }
}

document.addEventListener("DOMContentLoaded", () => {
    appRun();
    getWidgets();
})