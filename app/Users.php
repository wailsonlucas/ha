<?php
require_once "Database.php";



class Users
{
    function loginAccount($email, $password)
    {
        $db = new Database();
        $get_account = $db->GetRow("SELECT email,password FROM `users` WHERE email = ? LIMIT 1", [$email]);
        if ($get_account) {
            $db_password = $get_account["password"];
            if (password_verify($password, $db_password)) {
                $_SESSION['email'] = $email;
                $_SESSION['Auth'] = "Auth";
                redirect("admin/Settings", "مرحبا بك في لوحة التحكم");
            } else {
                $_SESSION['status'] =  'كلمة سر خاطئة';
            }
        } else {
            $_SESSION['status'] =  "بريد الكتروني غير موجود";
        }
    }



    function UpdateUser(
        $logo,
        $firstname,
        $lastname,
        $phone,
        $name_website
    ) {
        $db = new Database();
        $update = $db->Update(
            "UPDATE `users` SET  `logo`= ? , `firstname`= ?,`lastname`= ? , `phone` = ? ,`name_website`= ?  WHERE email = ?",
            [$logo, $firstname, $lastname, $phone, $name_website]
        );
    }






    function search($input)
    {
        $db = new Database();
        $search_result = $db->GetRows("SELECT * FROM `app` WHERE name LIKE ? OR description LIKE ?", ["%$input%", "%$input%"]);
        if ($search_result) {
            return $search_result;
        }
    }
}
