<?php
require_once '../Library/PHPMailer/src/Exception.php';
require_once '../Library/PHPMailer/src/PHPMailer.php';
require_once '../Library/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
            $value = DATABASE->real_escape_string($row[2]);
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

    public static function log($content, $file_path)
    {
        $myfile = fopen($file_path, "a+") or die("Unable to open file!");
        fwrite($myfile, $content);
        fclose($myfile);
    }

    public static function notification($mail_data)
    {
        $log_path = APP_PATH . "/storage/logs/" . date("Y-m-d") . "-email_log.txt";

        $mail = new PHPMailer(true);

        try {
            $to_mail = $mail_data["to"];
            $subject = $mail_data["subject"];
            $content = $mail_data["content"];

            // get env variables
            $env = parse_ini_file('../.env');

            // config mail
            $mail->isSMTP();
            $mail->Host = $env["MAIL_HOST"];
            $mail->Port = $env["MAIL_PORT"];
            $mail->SMTPAuth = true;
            $mail->Username = $env["MAIL_USERNAME"];
            $mail->Password = $env["MAIL_PASSWORD"];
            $mail->SMTPSecure = $env["MAIL_ENCRYPTION"];

            // Recipients
            $mail->setFrom($env["MAIL_FROM_ADDRESS"], $env["MAIL_FROM_NAME"]);
            $mail->addAddress($to_mail);     //Add a recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $content;

            // send the message
            if (!$mail->send()) {
                self::log("\nMail: $to_mail\nMessage could not be sent. Error: " . $mail->ErrorInfo, $log_path);
            } else {
                self::log("\nMail: $to_mail\nMessage has been sent", $log_path);
            }
        } catch (Exception $e) {
            self::log("\nMail: $to_mail\nMessage could not be sent. Error: " . $mail->ErrorInfo, $log_path);
        }
    }
}
