const BaseURL = location.origin;
const path = location.pathname;
const paths = ["/admin", "/admin/posts", "/admin/users"]

document.addEventListener("DOMContentLoaded", () => {

    // menu active
    document.querySelectorAll(".nav .nav-link").forEach(ele => {
        ele.classList.remove("link-secondary")
    })
    paths.forEach(item => {
        if (item == path) {
            document.querySelector(`.nav .nav-link[href="${path}"]`).classList.add("link-secondary")
        }
    })

    if (path == "/admin/users") {
        getUsers();
    } else if (path == "/admin/posts") {
        getPosts();

        // post form action
        document.getElementById("post-submit").addEventListener("click", event => {

            event.preventDefault();
            postSubmit();
        })

        // clear the input when post model close
        document.querySelectorAll('.post-close').forEach(function (element) {
            element.addEventListener('click', event => {
                event.preventDefault();
                document.querySelector(`.post-title`).innerText = "Create Post";
                document.querySelector(`#post-form`).setAttribute("data-id", 0);
                document.querySelector(`#post-form #title`).value = "";
                document.querySelector(`#post-form #content`).value = "";
                document.querySelector(`#post-form #image`).value = "";
            });
        });
    } else if (path == "/admin") {
        getWidgets();

        // testimonials form action
        document.getElementById("widget-2-submit").addEventListener("click", event => {

            event.preventDefault();
            widgetSubmit(2);
        })
        // about us form action
        document.getElementById("widget-3-submit").addEventListener("click", event => {

            event.preventDefault();
            widgetSubmit(3);
        })

        // clear input when testimonials model close
        document.querySelectorAll('.widget-2-close').forEach(function (element) {
            element.addEventListener('click', event => {
                event.preventDefault();
                document.querySelector(`#widget-2-form`).setAttribute("data-id", 0);
                document.querySelector(`#widget-2-form #name`).value = "";
                document.querySelector(`#widget-2-form #description`).value = "";
                document.querySelector(`#widget-2-form #image`).value = "";
            });
        });

        // clear input when about model close
        document.querySelectorAll('.widget-3-close').forEach(function (element) {
            element.addEventListener('click', event => {
                event.preventDefault();
                document.querySelector(`#widget-3-form`).setAttribute("data-id", 0);
                document.querySelector(`#widget-3-form #content`).value = "";
                document.querySelector(`#widget-3-form #image`).value = "";
            });
        });
    }

    // logout event
    if (document.getElementById("logoutBtn")) {
        document.getElementById("logoutBtn").addEventListener("click", (event) => {

            event.preventDefault();
            logoutApi();
        })
    }
})

// logout api
function logoutApi() {

    AjaxPost(`${BaseURL}/api/logout`)
        .then(res => JSON.parse(res))
        .then(async res => {

            if (res.code == 0) {
                // clear the session
                sessionStorage.setItem("auth", 0);
                sessionStorage.setItem("is_admin", 0);

                appNotification("success", res.message, 1000, () => location.href = BaseURL)
            } else {
                appNotification("danger", res.message)
            }
        })
        .catch(error => {
            console.log(error)
        })
}

// get users
function getUsers() {

    AjaxPost(`${BaseURL}/api/users`)
        .then(res => JSON.parse(res))
        .then(async res => {

            if (res.code == 0) {
                if (res.data && res.data.length > 0) {
                    var content = "";

                    for await (const item of res.data) {
                        // assign user datas
                        content += `<tr>
                                <td>${item.name}</td>
                                <td>${item.email}</td>
                                <td>${item.message}</td>
                                <td>`

                        // check user approve or not
                        if (item.status == 1) {
                            content += `<p class="text-success">Approved</p>`
                        } else if (item.status == 2) {
                            content += `<p class="text-danger">Rejected</p>`
                        } else {
                            content += `<button type="button" class="btn btn-success my-1" id="user-action" data-id="${item.id}" data-action="1" >Approve</button>
                                    <button type="button" class="btn btn-danger my-1" id="user-action" data-id="${item.id}" data-action="2">Reject</button>`
                        }
                        content += `</td>
                            </tr>`
                    }

                    if (content) {
                        document.getElementById("user-list").innerHTML = content;

                        // user table actions
                        document.querySelectorAll("#user-action").forEach(function (element) {
                            element.addEventListener('click', event => {
                                event.preventDefault();
                                approveUser(event.target.getAttribute("data-id"), event.target.getAttribute("data-action"))
                            });
                        });
                    }
                } else {
                    document.getElementById("user-list").innerHTML = `<tr><td cols="4"><p class="text-center">Users not found</p></td></tr>`;
                }
            } else {
                appNotification("danger", res.message)
            }
        })
        .catch(error => {
            console.log(error)
        })
}

// approve/reject the user
function approveUser(id, status) {

    const confirmed = confirm("Are you sure to the porcess?")

    if (confirmed) {
        AjaxPost(`${BaseURL}/api/user/approve`, { id, status })
            .then(res => JSON.parse(res))
            .then(async res => {

                if (res.code == 0) {
                    appNotification("success", res.message)
                    getUsers();
                } else {
                    appNotification("danger", res.message)
                }
            })
            .catch(error => {
                console.log(error)
            })
    }
}

// get posts
function getPosts() {

    AjaxPost(`${BaseURL}/api/posts`)
        .then(res => JSON.parse(res))
        .then(async res => {

            if (res.code == 0) {
                if (res.data && res.data.length > 0) {
                    var content = "";

                    for await (const item of res.data) {

                        // assign post datas
                        let imgContent = "";
                        if (item.image) {
                            imgContent = `<img src="${BaseURL + item.image}" style="height:100px; width: 100px; object-fit: contain;">`
                        }

                        content += `<tr>
                                <td>${imgContent}</td>
                                <td>${item.title}</td>
                                <td>${item.content}</td>
                                <td><button type="button" class="btn btn-success my-1" id="post-action" data-id="${item.id}" data-action="edit" data-bs-toggle="modal"
                    data-bs-target="#postModal"><i class="bi bi-pencil"></i></button>
                                    <button type="button" class="btn btn-danger my-1" id="post-action" data-id="${item.id}" data-action="delete"><i class="bi bi-trash3"></i></button></td>
                            </tr>`
                    }

                    if (content) {
                        document.getElementById("post-list").innerHTML = content;

                        // post table actions
                        document.querySelectorAll("#post-action").forEach(function (element) {
                            element.addEventListener('click', event => {
                                event.preventDefault();
                                postEdit(element.getAttribute("data-id"), element.getAttribute("data-action"))
                            });
                        });
                    }
                } else {
                    document.getElementById("post-list").innerHTML = `<tr><td cols="4"><p class="text-center">Posts not found</p></td></tr>`;
                }
            } else {
                appNotification("danger", res.message)
            }
        })
        .catch(error => {
            console.log(error)
        })
}

// create/update post
async function postSubmit() {

    const FormFields = ["title", "content"];
    const FormData = {};
    document.getElementById("post-submit").disabled = true

    // dynamic validation
    let error = false;
    let error_message = "";

    for await (const key of FormFields) {
        const ele = document.querySelector(`#post-form #${key}`);
        error_message = "";

        if (!ele.value) {
            error_message = capitalize(`${key} is required`);
        } else if (key == "title" && ele.value.length > 120) {
            error_message = "Title may not be greater than 120 characters";
        } else if (key == "content" && ele.value.length > 500) {
            error_message = "Content may not be greater than 500 characters";
        }

        if (error_message) {
            error = true;
            document.querySelector(`#post-form #${key}-error`).innerText = error_message;
            ele.classList.add("is-invalid");
        } else {
            ele.classList.remove("is-invalid");
        }
        FormData[key] = ele.value;
    }

    // image file validation
    const FileData = {};
    const file = document.querySelector(`#post-form #image`);

    if (file.files[0]) {
        error_message = "";

        let { size, type } = file.files[0];
        let allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif"];

        // check file size and types
        if (size > 5 * 1024 * 1024)
            error_message = "Image should be less than 5MB";
        else if (!allowedTypes.includes(type))
            error_message = "Image must be " + allowedTypes.map(v => v.replace("image/", "")).join(", ");

        if (error_message) {
            error = true;
            document.querySelector(`#post-form #image-error`).innerText = error_message;
            file.classList.add("is-invalid");
        } else {
            FileData["image"] = file.files[0];
            file.classList.remove("is-invalid");
        }

    }

    // call api
    if (!error) {

        // check and get id
        let id = document.querySelector(`#post-form`).getAttribute("data-id");

        let url = `${BaseURL}/api/post/create`;
        if (id > 0) url = `${BaseURL}/api/post/update/${id}`;

        fileUpload(url, FormData, FileData)
            .then(res => JSON.parse(res))
            .then(async res => {

                document.getElementById("post-submit").disabled = false

                if (res.code == 0) {
                    getPosts();
                    appNotification("success", res.message)

                    // close the model
                    document.getElementById('postModalClose').click()

                    // clear the inputs
                    document.querySelector(`#post-form`).setAttribute("data-id", 0);
                    document.querySelector(`#post-form #title`).value = "";
                    document.querySelector(`#post-form #content`).value = "";
                    document.querySelector(`#post-form #image`).value = "";
                } else {
                    appNotification("danger", res.message)
                }
            })
            .catch(error => {
                document.getElementById("post-submit").disabled = false
                console.log(error)
            })
    } else {
        document.getElementById("post-submit").disabled = false
    }

}

// edit/delete post
function postEdit(id, action) {

    // edit post
    if (action == "edit") {
        document.querySelector(`.post-title`).innerText = "Edit Post";
        document.querySelector(`#post-form`).style.display = "none";

        AjaxGet(`${BaseURL}/api/post/view/${id}`)
            .then(res => JSON.parse(res))
            .then(async res => {

                document.querySelector(`#post-form`).style.display = "block";
                if (res.code == 0 && res.data) {

                    // set form datas
                    document.querySelector(`#post-form`).setAttribute("data-id", id);
                    document.querySelector(`#post-form #title`).value = res.data.title;
                    document.querySelector(`#post-form #content`).value = res.data.content;
                } else {
                    appNotification("danger", res.message)
                }
            })
            .catch(error => {
                document.querySelector(`#post-form`).style.display = "block";
                console.log(error)
            })
    } else {

        // delete post
        let confirmed = confirm("Are you sure to confirm delete?");
        if (confirmed) {
            AjaxPost(`${BaseURL}/api/post/delete/${id}`)
                .then(res => JSON.parse(res))
                .then(async res => {

                    if (res.code == 0) {

                        getPosts();
                        appNotification("success", res.message)
                    } else {
                        appNotification("danger", res.message)
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        }
    }
}


// get Widget datas
function getWidgets() {

    AjaxPost(`${BaseURL}/api/widgets`)
        .then(res => JSON.parse(res))
        .then(async res => {
            var sliderContent = "";
            var testimonialsContent = "";

            if (res.code == 0 && res.data && res.data.length > 0) {

                for await (const item of res.data) {
                    if (item.type == 1) {

                        // slider image data
                        sliderContent += `<div class="col-6 col-md-3 col-lg-2 p-1">
                            <div class="admin-slider h-100">
                                <img src="${BaseURL + item.image}" class="img-thumbnail">
                                <div class="slider-action">
                                    <button type="button" class="btn btn-danger my-1" id="widget-action" data-id="${item.id}" data-type="1" data-action="delete"><i class="bi bi-trash3"></i></button>
                                </div>
                            </div>
                        </div>`
                    } else if (item.type == 2) {

                        // testimonials
                        testimonialsContent += `<tr>
                                <td><img src="${BaseURL + item.image}" style="height:100px; width: 100px; object-fit: contain;"></td>
                                <td>${item.name}</td>
                                <td>${item.description}</td>
                                <td><button type="button" class="btn btn-success my-1" id="widget-action" data-id="${item.id}" data-type="2" data-action="edit" data-bs-toggle="modal"
                    data-bs-target="#testimonialsModal"><i class="bi bi-pencil"></i></button>
                                    <button type="button" class="btn btn-danger my-1" id="widget-action" data-id="${item.id}" data-type="2" data-action="delete"><i class="bi bi-trash3"></i></button></td>
                            </tr>`
                    } else if (item.type == 3) {

                        // set about us
                        document.getElementById("about-img").src = BaseURL + item.image;
                        document.getElementById("about-content").innerHTML = item.content;
                        document.getElementById("about-action").setAttribute("data-id", item.id);
                    }
                }

                // set slider image
                sliderContent += `<div class="col-6 col-md-3 col-lg-2 p-1">
                        <div class="d-flex justify-content-center align-items-center border border-3 rounded-1 h-100 fs-2 text-secondary" style="border-style: dotted !important;cursor: pointer;" id="slider-upload">+</div>
                    </div>`
                document.getElementById("slider-content").innerHTML = sliderContent

                // file upload action in slider
                document.getElementById("slider-upload").addEventListener("click", event => {
                    event.preventDefault();

                    var input = document.createElement("input");
                    input.type = "file";
                    input.accept = "image/*";
                    input.onchange = (e) => { widgetFileUpload(e.target) }
                    input.click();
                })


                // set testimonials
                if (testimonialsContent) {
                    document.getElementById("testimonials-list").innerHTML = testimonialsContent;
                }

                // widget actions (testimonials, about)
                document.querySelectorAll("#widget-action, #about-action").forEach(function (element) {
                    element.addEventListener('click', event => {
                        event.preventDefault();
                        widgetEdit(element.getAttribute("data-id"), element.getAttribute("data-action"), element.getAttribute("data-type"))
                    });
                });
            } else {

                // set slider image
                sliderContent += `<div class="col-6 col-md-3 col-lg-2 p-1">
                        <div class="d-flex justify-content-center align-items-center border border-3 rounded-1 h-100 fs-2 text-secondary" style="border-style: dotted !important;cursor: pointer;" id="slider-upload">+</div>
                    </div>`
                document.getElementById("slider-content").innerHTML = sliderContent

                // file upload action in slider
                document.getElementById("slider-upload").addEventListener("click", event => {
                    event.preventDefault();

                    var input = document.createElement("input");
                    input.type = "file";
                    input.accept = "image/*";
                    input.onchange = (e) => { widgetFileUpload(e.target) }
                    input.click();
                })
            }

        })
        .catch(error => {
            console.log(error)
        })
}

// create/update widget
async function widgetSubmit(type) {

    // 1- slider, 2- testimonials, 3- about us
    // set form fields
    let FormFields = [];
    if (type == 2)  // testimonials
        FormFields = ["name", "description"];
    else if (type == 3) // about us
        FormFields = ["content"];

    const FormData = {};
    document.getElementById(`widget-${type}-submit`).disabled = true

    // get id
    let id = document.querySelector(`#widget-${type}-form`).getAttribute("data-id");

    // dynamic validation
    let error_message = "";
    let error = false;

    for await (const key of FormFields) {
        const ele = document.querySelector(`#widget-${type}-form #${key}`);
        error_message = "";

        if (!ele.value) {
            error_message = capitalize(`${key} is required`);
        } else if (key == "name" && ele.value.length > 50) {
            error_message = "Name may not be greater than 50 characters";
        } else if (key == "description" && ele.value.length > 250) {
            error_message = "Description may not be greater than 250 characters";
        } else if (key == "content" && ele.value.length > 500) {
            error_message = "Content may not be greater than 500 characters";
        }

        if (error_message) {
            error = true;
            document.querySelector(`#widget-${type}-form #${key}-error`).innerText = error_message;
            ele.classList.add("is-invalid");
        } else {
            ele.classList.remove("is-invalid");
        }
        FormData[key] = ele.value;
    }

    // image file validation
    const FileData = {};
    let file = document.querySelector(`#widget-${type}-form #image`);

    if (file.files[0]) {
        error_message = "";

        let { size, type: fileType } = file.files[0];
        let allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif"];

        // check file size and types
        if (size > 5 * 1024 * 1024)
            error_message = "Image should be less than 5MB";
        else if (!allowedTypes.includes(fileType))
            error_message = "Image must be " + allowedTypes.map(v => v.replace("image/", "")).join(", ");

        if (error_message) {
            error = true;
            document.querySelector(`#widget-${type}-form #image-error`).innerText = error_message;
            file.classList.add("is-invalid");
        } else {
            FileData["image"] = file.files[0];
            file.classList.remove("is-invalid");
        }
    } else if (!(id > 0)) { // image required only create action
        error = true;
        document.querySelector(`#widget-${type}-form #image-error`).innerText = "Image is required";
        file.classList.add("is-invalid");
    }

    // call api
    if (!error) {

        let url = `${BaseURL}/api/widget/create`;
        if (id > 0) url = `${BaseURL}/api/widget/update/${id}`;

        FileData["type"] = type;

        fileUpload(url, FormData, FileData)
            .then(res => JSON.parse(res))
            .then(async res => {

                document.getElementById(`widget-${type}-submit`).disabled = false

                if (res.code == 0) {
                    getWidgets();
                    appNotification("success", res.message)

                    if (type == 2) {
                        // close testimonials model
                        document.getElementById('testimonialsModalClose').click()

                        // clear inputs
                        document.querySelector(`#widget-${type}-form`).setAttribute("data-id", 0);
                        document.querySelector(`#widget-${type}-form #name`).value = "";
                        document.querySelector(`#widget-${type}-form #description`).value = "";
                        document.querySelector(`#widget-${type}-form #image`).value = "";
                    } else if (type == 3) {
                        // close about model
                        document.getElementById('aboutModalClose').click()

                        // clear inputs
                        document.querySelector(`#widget-${type}-form`).setAttribute("data-id", 0);
                        document.querySelector(`#widget-${type}-form #content`).value = "";
                        document.querySelector(`#widget-${type}-form #image`).value = "";
                    }
                } else {
                    appNotification("danger", res.message)
                }
            })
            .catch(error => {
                document.getElementById(`widget-${type}-submit`).disabled = false
                console.log(error)
            })
    } else {
        document.getElementById(`widget-${type}-submit`).disabled = false
    }

}

// file upload only slider
async function widgetFileUpload(file = null) {

    const FormData = {};
    const FileData = {};
    let error_message = "";
    let error = false;

    // image file validation
    if (file.files[0]) {
        error_message = "";

        let { size, type: fileType } = file.files[0];
        let allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif"];

        // check file size and types
        if (size > 5 * 1024 * 1024)
            error_message = "Image should be less than 5MB";
        else if (!allowedTypes.includes(fileType))
            error_message = "Image must be " + allowedTypes.map(v => v.replace("image/", "")).join(", ");

        if (error_message) {
            error = true;
            appNotification("danger", error_message)
        } else {
            FileData["image"] = file.files[0];
        }
    } else {
        error = true;
        appNotification("danger", "Image is required")
    }

    // call api
    if (!error) {
        FileData["type"] = 1;

        fileUpload(`${BaseURL}/api/widget/create`, FormData, FileData)
            .then(res => JSON.parse(res))
            .then(async res => {

                if (res.code == 0) {
                    getWidgets();
                    appNotification("success", res.message)
                } else {
                    appNotification("danger", res.message)
                }
            })
            .catch(error => {
                console.log(error)
            })
    }

}

// edit/delete widget
function widgetEdit(id, action, type) {

    // edit widget
    if (action == "edit") {
        document.querySelector(`#widget-${type}-form`).style.display = "none";

        AjaxGet(`${BaseURL}/api/widget/view/${id}`)
            .then(res => JSON.parse(res))
            .then(async res => {

                document.querySelector(`#widget-${type}-form`).style.display = "block";
                if (res.code == 0 && res.data) {

                    if (type == 2) {

                        // set testimonials form inputs
                        document.querySelector(`#widget-2-form`).setAttribute("data-id", id);
                        document.querySelector(`#widget-2-form #name`).value = res.data.name;
                        document.querySelector(`#widget-2-form #description`).value = res.data.description;
                    } else if (type == 3) {

                        // set about form inputs
                        document.querySelector(`#widget-3-form`).setAttribute("data-id", id);
                        document.querySelector(`#widget-3-form #content`).value = res.data.content;
                    }
                } else {
                    appNotification("danger", res.message)
                }
            })
            .catch(error => {
                document.querySelector(`#widget-${type}-form`).style.display = "block";
                console.log(error)
            })
    } else {

        // delete widget
        let confirmed = confirm("Are you sure to confirm delete?");
        if (confirmed) {
            AjaxPost(`${BaseURL}/api/widget/delete/${id}`)
                .then(res => JSON.parse(res))
                .then(async res => {

                    if (res.code == 0) {

                        getWidgets();
                        appNotification("success", res.message)
                    } else {
                        appNotification("danger", res.message)
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        }
    }
}