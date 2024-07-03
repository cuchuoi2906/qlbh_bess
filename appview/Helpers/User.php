<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 12/31/19
 * Time: 20:19
 */

namespace AppView\Helpers;


use App\Models\Users\Users;
use db_query;

class User extends \user
{

    public function __construct(string $login_name = "", string $password = "")
    {
        $checkcookie = 0;
        $this->logged = 0;
        $this->pre_cookie = "";
        if ($login_name == "") {
            if (isset($_COOKIE[$this->pre_cookie . "loginname"]))
                $login_name = $_COOKIE[$this->pre_cookie . "loginname"];
        }
        if ($password == "") {
            if (isset($_COOKIE[$this->pre_cookie . "PHPSESSlD"]))
                $password = $_COOKIE[$this->pre_cookie . "PHPSESSlD"];
            $checkcookie = 1;
        } else {
            //remove \' if gpc_magic_quote = on
            $password = str_replace("\'", "'", $password);
        }

        $login_name = trim($login_name);
        $password = trim($password);

        if ($login_name == "" && $password == "")
            return;

        $sqlWhere = " AND use_loginname = '" . $this->removequote($login_name) . "'";
        if (filter_var($login_name, FILTER_VALIDATE_EMAIL)) {
            $sqlWhere = " AND use_email = '" . $this->removequote($login_name) . "'";
            //kiểu số đt
        } elseif (preg_match('/^[0-9]*$/', $login_name)) {
            $sqlWhere = " AND use_phone = '" . $this->removequote($login_name) . "'";
        }

        $user = Users::where('1 ' . $sqlWhere)->find();


        if ($user) {
            $row = $user;
            //kiem tra password va use_active
            if ($checkcookie == 0)
                $password = md5($password . $row["use_security"]);
            if ($password != '' && $password == $row["use_password"] && $row["use_active"] == 1) {
                $this->logged = 1;
                $this->login_name = $login_name;
                $this->use_name = $row["use_name"];
                $this->password = $password;
                $this->use_security = $row["use_security"];
                $this->u_id = intval($row["use_id"]);

                $this->use_email = $row["use_email"];
                $this->use_phone = $row["use_phone"];
                $this->use_address = $row["use_address"];


                $this->useField = $row;
                $this->useInfoFake = $row;
            }
        }
        unset($db_user);

        //get right list
        $db_right = new db_query("SELECT *
                                          FROM user_right
                                          ORDER BY ur_quantity DESC");
        $i = -1;
        foreach ($db_right->fetch() as $row) {
            if ((intval($row["ur_code"]) & intval($this->group_right)) != 0) {
                $i++;
                $this->user_right_name_array[$i] = $row["ur_variable"];
                $this->user_right_quantity_array[$i] = $row["ur_quantity"];
            }
        }
    }
}