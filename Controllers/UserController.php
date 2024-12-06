<?php

require "../Models/Users.php";
class UserController
{

    public function list()
    {
        $request = json_decode(file_get_contents('php://input'), true);

        // get pagination details
        $page = isset($request["page"]) ? $request["page"] : 1;
        $limit = isset($request["page"]) ? $request["limit"] : 0;
        $order_by = isset($request["sort_by"]) ? $request["sort_by"] : [];

        // get users
        $data = Users::get([["role", "!=", 1]], $order_by, $page, $limit);
        $response = ["code" => 0, "data" => $data];

        Helper::jsonResponse($response);
    }

    public function create()
    {
        $request = json_decode(file_get_contents('php://input'), true);
        $error_msg = "";

        // validate the inputs
        $email_pattern = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
        $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@.#$!%*?&^])[A-Za-z\d@.#$!%*?&]{8,}$/';
        if (!isset($request["name"]) || !$request["name"]) $error_msg = "Name is required";
        else if (!strlen($request["name"]) > 20) $error_msg = "Name may not be greater than 20 characters";
        else if (!isset($request["email"]) || !$request["email"]) $error_msg = "Email is required";
        else if (!preg_match($email_pattern, $request["email"])) $error_msg = "Email is invalid";
        else if (!isset($request["password"]) || !$request["password"]) $error_msg = "Password is required";
        else if (strlen($request["password"]) < 8) $error_msg = "Password must be at least 8";
        else if (!preg_match($password_pattern, $request["password"])) $error_msg = "Password is invalid";
        else if (!isset($request["message"]) || !$request["message"]) $error_msg = "Message is required";
        else if (!strlen($request["message"]) > 250) $error_msg = "Message may not be greater than 250 characters";
        else if (!isset($request["subscribe"])) $error_msg = "Subscribe is required";
        else if (!in_array($request["subscribe"], [1, 2, true, false])) $error_msg = "Subscribe field must be true or false";

        // check email dublicate
        if (isset($request["email"])) {
            $user = Users::first([["email", "=", $request["email"]]]);
            if (!is_null($user)) $error_msg = "Email is already taken";
        }

        // validation message
        if ($error_msg) {
            $response = ["code" => 1, "message" => $error_msg];
            Helper::jsonResponse($response);
        }

        try {
            // create user
            $input = [
                "name" => $request["name"],
                "email" => $request["email"],
                "password" => password_hash($request["password"], PASSWORD_DEFAULT),
                "subscribe" => $request["subscribe"],
                "message" => $request["message"],
                "role" => 2,    // 2-user
                "status" => 3,  // 3-pending
            ];
            $create = Users::create($input);

            if ($create)
                $response = ["code" => 0, "message" => "User created"];
            else
                $response = ["code" => 1, "message" => "User not created"];
        } catch (Exception $ex) {
            $response = ["code" => 1, "message" => $ex->getMessage()];
        }
        Helper::jsonResponse($response);
    }

    public function view($id)
    {
        // get user by id
        try {
            $data = Users::first([["id", "=", $id]]);
            $response = ["code" => 0, "data" => $data];
        } catch (Exception $ex) {
            $response = ["code" => 1, "message" => $ex->getMessage()];
        }

        Helper::jsonResponse($response);
    }

    public function update($id) {}

    public function delete($id)
    {
        // delete the user by id
        try {
            $delete = Users::delete([["id", "=", $id]]);

            if ($delete)
                $response = ["code" => 0, "message" => "User deleted"];
            else
                $response = ["code" => 1, "message" => "User not deleted"];
        } catch (Exception $ex) {
            $response = ["code" => 1, "message" => $ex->getMessage()];
        }

        Helper::jsonResponse($response);
    }

    public function approve()
    {
        $request = json_decode(file_get_contents('php://input'), true);
        $error_msg = "";

        // validate the inputs
        if (!isset($request["id"]) || !$request["id"]) $error_msg = "Id is required";
        else if (!isset($request["status"]) || !$request["status"]) $error_msg = "Status is required";
        else if (!in_array($request["status"], [1, 2])) $error_msg = "Status field does not exist in 1, 2";

        // validation message
        if ($error_msg) {
            $response = ["code" => 1, "message" => $error_msg];
            Helper::jsonResponse($response);
        }

        $status = $request["status"];
        $id = $request["id"];

        // check user already approved or rejected
        $user = Users::first([["id", "=", $id]]);
        if (!is_null($user)) {

            if ($user["status"] == 1 && $status == 2)
                $response = ["code" => 0, "message" => "User already approved, can't be changed."];
            else if ($user["status"] == 2 && $status == 1)
                $response = ["code" => 0, "message" => "User already rejected, can't be changed."];
            else if ($user["status"] == $status)
                $response = ["code" => 0, "message" => $status == 1 ? "User already approved" : "User already rejected"];

            Helper::jsonResponse($response);
        }

        // update the user
        try {

            $update = Users::update(["status" => $status], [["id", "=", $id]]);

            if ($update)
                $response = ["code" => 0, "message" => $status == 1 ? "User approved" : "User rejected"];
            else
                $response = ["code" => 1, "message" => "User not updated"];
        } catch (Exception $ex) {
            $response = ["code" => 1, "message" => $ex->getMessage()];
        }

        Helper::jsonResponse($response);
    }
}
