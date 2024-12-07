<?php
require_once "../Models/Widget.php";

class WidgetController
{

    public function list()
    {
        $request = json_decode(file_get_contents('php://input'), true);

        // generate where
        $where = [];
        if (isset($request["type"]) && $request["type"]) $where[] = ["widget_type", "=", $request["type"]];
        if (isset($request["id"]) && $request["id"]) $where[] = ["id", "=", $request["id"]];

        // get widgets
        $data = Widget::get($where);
        $response = [
            "code" => is_null($data) ? 1 : 0,
            "data" => $data,
            "message" => is_null($data) ? "Data not found" : "Data found"
        ];

        Helper::jsonResponse($response);
    }

    public function create()
    {
        $request = $_POST;
        if (!$request) $request = json_decode(file_get_contents('php://input'), true);

        $error_msg = "";
        $type = "";

        // validate the inputs
        if (!isset($request["type"]) || !$request["type"]) $error_msg = "Type is required";
        else if (!in_array($request["type"], [1, 2, 3])) $error_msg = "Type field must be 1,2,3";
        else $type = $request["type"];

        if ($type == 1) {   // slider

            if (!isset($_FILES["image"]) || !$_FILES["image"]) $error_msg = "Image is required";
        } else if ($type == 2) {   // testimonials

            if (!isset($request["name"]) || !$request["name"]) $error_msg = "Name is required";
            else if (!strlen($request["name"]) > 50) $error_msg = "Name may not be greater than 50 characters";
            else if (!isset($request["description"]) || !$request["description"]) $error_msg = "Description is required";
            else if (!strlen($request["description"]) > 250) $error_msg = "Description may not be greater than 250 characters";
            else if (!isset($_FILES["image"]) || !$_FILES["image"]) $error_msg = "Image is required";
        } else if ($type == 3) {   // about

            if (!isset($request["title"]) || !$request["title"]) $error_msg = "Title is required";
            else if (!strlen($request["title"]) > 120) $error_msg = "Title may not be greater than 120 characters";
            else if (!isset($request["content"]) || !$request["content"]) $error_msg = "Content is required";
            else if (!strlen($request["content"]) > 1500) $error_msg = "Content may not be greater than 1500 characters";
        }

        try {
            // check and upload image file
            $upload_file_path = "";

            if (isset($_FILES["image"]) && ($type == 1 || $type == 2)) {
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
                    $target_dir = "/uploads/widgets/";
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

            // create widget
            if ($type == 1) {   // slider
                $input = [
                    "image" => $upload_file_path
                ];
            } else if ($type == 2) {   // testimonials
                $input = [
                    "name" => $request["name"],
                    "description" => $request["description"],
                    "image" => $upload_file_path
                ];
            } else if ($type == 3) {   // about
                $input = [
                    "title" => $request["title"],
                    "content" => $request["content"]
                ];
            }
            $create = Widget::create($type, $input);

            if ($create)
                $response = ["code" => 0, "message" => "Widget created"];
            else
                $response = ["code" => 1, "message" => "Widget not created"];
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
        // get widget by id
        try {
            $data = Widget::first([["id", "=", $id]]);
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
        if (!isset($request["type"]) || !$request["type"]) $error_msg = "Type is required";
        else if (!in_array($request["type"], [1, 2, 3])) $error_msg = "Type field must be 1,2,3";
        else $type = $request["type"];

        if ($type == 1) {   // slider

            if (!isset($_FILES["image"]) || !$_FILES["image"]) $error_msg = "Image is required";
        } else if ($type == 2) {   // testimonials

            if (!isset($request["name"]) || !$request["name"]) $error_msg = "Name is required";
            else if (!strlen($request["name"]) > 50) $error_msg = "Name may not be greater than 50 characters";
            else if (!isset($request["description"]) || !$request["description"]) $error_msg = "Description is required";
            else if (!strlen($request["description"]) > 250) $error_msg = "Description may not be greater than 250 characters";
            else if (!isset($_FILES["image"]) || !$_FILES["image"]) $error_msg = "Image is required";
        } else if ($type == 3) {   // about

            if (!isset($request["title"]) || !$request["title"]) $error_msg = "Title is required";
            else if (!strlen($request["title"]) > 120) $error_msg = "Title may not be greater than 120 characters";
            else if (!isset($request["content"]) || !$request["content"]) $error_msg = "Content is required";
            else if (!strlen($request["content"]) > 1500) $error_msg = "Content may not be greater than 1500 characters";
        }

        try {
            // check and upload image file
            $upload_file_path = "";

            if (isset($_FILES["image"]) && ($type == 1 || $type == 2)) {
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
                    $target_dir = "/uploads/widgets/";
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

            $widget = Widget::first([["id", "=", $id]]);

            if (is_null($widget)) {
                $response = ["code" => 1, "message" => "Widget not found"];
            } else {

                $old_image = $widget["image"];

                // update widget
                if ($type == 1) {   // slider
                    $input = [
                        "image" => $upload_file_path
                    ];
                } else if ($type == 2) {   // testimonials
                    $input = [
                        "name" => $request["name"],
                        "description" => $request["description"],
                        "image" => $upload_file_path
                    ];
                } else if ($type == 3) {   // about
                    $input = [
                        "title" => $request["title"],
                        "content" => $request["content"]
                    ];
                }

                $update = Widget::update($input, $id);

                if ($update) {

                    // delete old image
                    if ($old_image) {
                        $file_path = PUPLIC_PATH . $old_image;
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }

                    $response = ["code" => 0, "message" => "Widget updated"];
                } else
                    $response = ["code" => 1, "message" => "Widget not updated"];
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

            $widget = Widget::first([["id", "=", $id]]);

            if (is_null($widget)) {
                $response = ["code" => 1, "message" => "Widget not found"];
            } else {

                $old_image = $widget["image"];

                // delete the widget by id
                $delete = Widget::delete($id);

                if ($delete) {
                    // delete old image
                    if ($old_image) {
                        $file_path = PUPLIC_PATH . $old_image;
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }

                    $response = ["code" => 0, "message" => "Widget deleted"];
                } else
                    $response = ["code" => 1, "message" => "Widget not deleted"];
            }
        } catch (Exception $ex) {
            $response = ["code" => 1, "message" => $ex->getMessage()];
        }

        Helper::jsonResponse($response);
    }
}
