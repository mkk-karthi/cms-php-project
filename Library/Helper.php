<?php
class Helper
{
    public static function jsonResponse($data, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    public static function generateWhere($where)
    {
        $where_arr = [];
        foreach ($where as $row) {
            $column = $row[0];
            $condition = $row[1];
            $value = $row[2];
            $where_arr[] = "$column $condition '$value'";
        }
        $where_data = implode(" AND ", $where_arr);

        return $where_data;
    }

    public static function middleware($type)
    {

        if ($type == "auth") {
            if (!isset($_SESSION["auth"]) || !$_SESSION["auth"]) {
                self::jsonResponse(["code" => 401, "message" => "Unauthorized"], 401);
            }
        } else if ($type == "auth:admin") {
            if (!isset($_SESSION["auth"]) || !$_SESSION["auth"] || !isset($_SESSION["is_admin"]) || !$_SESSION["is_admin"]) {
                self::jsonResponse(["code" => 401, "message" => "Unauthorized"], 401);
            }
        }
    }
}
