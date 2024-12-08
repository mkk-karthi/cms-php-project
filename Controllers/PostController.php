<?php
require_once "../Models/Posts.php";
require_once "../Models/Users.php";

class PostController
{

    public function list()
    {
        $request = json_decode(file_get_contents('php://input'), true);

        // get auth user and check approved
        $auth_user = $_SESSION["user"];
        $user = Users::first([["id", "=", $auth_user["id"]], ["status", "=", 1]]);

        if (is_null($user)) {
            $response = [
                "code" => 1,
                "message" => "Admin not approved"
            ];
        } else {

            // get pagination details
            $page = isset($request["page"]) ? $request["page"] : 1;
            $limit = isset($request["page"]) ? $request["limit"] : 0;
            $order_by = isset($request["sort_by"]) ? $request["sort_by"] : [];

            // get posts
            $data = Posts::get([], $order_by, $page, $limit);
            $response = [
                "code" => is_null($data) ? 1 : 0,
                "data" => $data,
                "message" => is_null($data) ? "Data not found" : "Data found"
            ];
        }

        Helper::jsonResponse($response);
    }

    public function create()
    {
        $request = $_POST;
        if (!$request) $request = json_decode(file_get_contents('php://input'), true);

        $error_msg = "";

        // validate the inputs
        if (!isset($request["title"]) || !$request["title"]) $error_msg = "Title is required";
        else if (!strlen($request["title"]) > 120) $error_msg = "Title may not be greater than 120 characters";
        else if (!isset($request["content"]) || !$request["content"]) $error_msg = "Content is required";
        else if (!strlen($request["content"]) > 500) $error_msg = "Content may not be greater than 500 characters";

        try {
            // check and upload image file
            $upload_file_path = "";

            if (isset($_FILES["image"])) {
                $img_file = $_FILES["image"];

                // get file details
                $extension = strtolower(pathinfo(basename($img_file["name"]), PATHINFO_EXTENSION));
                $file_size = $img_file["size"];
                $file_type = $img_file["type"];

                // allowed file types
                $allowed_file_types = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
                $allowed_extensions = ["jpg", "jpeg", "png", "gif"];

                // validate image extension and types
                if (!in_array($file_type, $allowed_file_types) || !in_array($extension, $allowed_extensions)) {
                    $error_msg = "Image must be " . implode(", ", $allowed_extensions);
                } else if ($file_size > 5 * 1024 * 1024) {  // validate image size
                    $error_msg = "Image should be less than 5MB";
                }

                if (!$error_msg) {

                    // upload file
                    $target_dir = "/uploads/posts/";
                    $file_name = time() . "-" . basename($img_file["name"]);

                    if (move_uploaded_file($img_file["tmp_name"], PUPLIC_PATH . $target_dir . $file_name)) {
                        $upload_file_path = $target_dir . $file_name;
                    } else {
                        $error_msg = "Image file not uploaded.";
                    }
                }
            }

            // validation message
            if ($error_msg) {
                $response = ["code" => 1, "message" => $error_msg];
                Helper::jsonResponse($response);
            }

            // get auth user
            $auth_user = $_SESSION["user"];

            // create post
            $input = [
                "title" => $request["title"],
                "content" => $request["content"],
                "image" => $upload_file_path,
                "created_by" => $auth_user["id"]
            ];
            $create = Posts::create($input);

            if ($create) {

                // send notifications
                $users = Users::get([["role", "!=", 1], ["subscribe", "=", 1], ["status", "=", 1]]);

                if (!is_null($users)) {
                    foreach ($users as $user) {

                        $name = $user["name"];
                        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';
                        $post_link = $protocol . $_SERVER["HTTP_HOST"] . "/posts";
                        $app_name = APP_NAME;
                        $post_title = $request["title"];

                        $content = "Dear $name,<br> We hope you're doing well! We're excited to bring you the latest update from $app_name.<br><br><b>$post_title</b><br><br>Click below to read the full post:<br><a href='$post_link' target='_blank'>See more</a>";

                        $mail_data = [
                            "subject" => "New Post - " . APP_NAME,
                            "content" => $content,
                            "to" => $user["email"]
                        ];
                        Helper::notification($mail_data);
                    }
                }
                $response = ["code" => 0, "message" => "Post created"];
            } else
                $response = ["code" => 1, "message" => "Post not created"];
        } catch (Exception $ex) {

            // delete uploaded image
            if ($upload_file_path) {
                $file_path = PUPLIC_PATH . $upload_file_path;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            $response = ["code" => 1, "message" => $ex->getMessage()];
        }
        Helper::jsonResponse($response);
    }

    public function view($id)
    {
        // get post by id
        try {
            $data = Posts::first([["id", "=", $id]]);
            $response = [
                "code" => is_null($data) ? 1 : 0,
                "data" => $data,
                "message" => is_null($data) ? "Data not found" : "Data found"
            ];
        } catch (Exception $ex) {
            $response = ["code" => 1, "message" => $ex->getMessage()];
        }

        Helper::jsonResponse($response);
    }

    public function update($id)
    {
        $request = $_POST;
        if (!$request) $request = json_decode(file_get_contents('php://input'), true);

        $error_msg = "";
        $old_image = "";

        // validate the inputs
        if (!isset($request["title"]) || !$request["title"]) $error_msg = "Title is required";
        else if (!strlen($request["title"]) > 120) $error_msg = "Title may not be greater than 120 characters";
        else if (!isset($request["content"]) || !$request["content"]) $error_msg = "Content is required";
        else if (!strlen($request["content"]) > 1500) $error_msg = "Content may not be greater than 1500 characters";

        try {
            // check and upload image file
            $upload_file_path = "";

            if (isset($_FILES["image"])) {
                $img_file = $_FILES["image"];

                // get file details
                $extension = strtolower(pathinfo(basename($img_file["name"]), PATHINFO_EXTENSION));
                $file_size = $img_file["size"];
                $file_type = $img_file["type"];

                // allowed file types
                $allowed_file_types = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
                $allowed_extensions = ["jpg", "jpeg", "png", "gif"];

                // validate image extension and types
                if (!in_array($file_type, $allowed_file_types) || !in_array($extension, $allowed_extensions)) {
                    $error_msg = "Image must be " . implode(", ", $allowed_extensions);
                } else if ($file_size > 5 * 1024 * 1024) {  // validate image size
                    $error_msg = "Image should be less than 5MB";
                }

                if (!$error_msg) {

                    // upload file
                    $target_dir = "/uploads/posts/";
                    $file_name = time() . "-" . basename($img_file["name"]);

                    if (move_uploaded_file($img_file["tmp_name"], PUPLIC_PATH . $target_dir . $file_name)) {
                        $upload_file_path = $target_dir . $file_name;
                    } else {
                        $error_msg = "Image file not uploaded.";
                    }
                }
            }

            // validation message
            if ($error_msg) {
                $response = ["code" => 1, "message" => $error_msg];
                Helper::jsonResponse($response);
            }

            // get auth user
            $auth_user = $_SESSION["user"];

            $post = Posts::first([["id", "=", $id]]);

            if (is_null($post)) {
                $response = ["code" => 1, "message" => "Post not found"];
            } else {

                $old_image = $post["image"];

                // update post
                $input = [
                    "title" => $request["title"],
                    "content" => $request["content"],
                    "image" => $upload_file_path,
                    "updated_by" => $auth_user["id"]
                ];
                $update = Posts::update($input, [["id", "=", $id]]);

                if ($update) {

                    // delete old image
                    if ($old_image) {
                        $file_path = PUPLIC_PATH . $old_image;
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }

                    $response = ["code" => 0, "message" => "Post updated"];
                } else
                    $response = ["code" => 1, "message" => "Post not updated"];
            }
        } catch (Exception $ex) {

            // delete uploaded image
            if ($upload_file_path) {
                $file_path = PUPLIC_PATH . $upload_file_path;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            $response = ["code" => 1, "message" => $ex->getMessage()];
        }
        Helper::jsonResponse($response);
    }

    public function delete($id)
    {
        try {

            $post = Posts::first([["id", "=", $id]]);

            if (is_null($post)) {
                $response = ["code" => 1, "message" => "Post not found"];
            } else {

                $old_image = $post["image"];
                // delete the post by id
                $delete = Posts::delete([["id", "=", $id]]);

                if ($delete) {
                    // delete old image
                    if ($old_image) {
                        $file_path = PUPLIC_PATH . $old_image;
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }

                    $response = ["code" => 0, "message" => "Post deleted"];
                } else
                    $response = ["code" => 1, "message" => "Post not deleted"];
            }
        } catch (Exception $ex) {
            $response = ["code" => 1, "message" => $ex->getMessage()];
        }

        Helper::jsonResponse($response);
    }
}
