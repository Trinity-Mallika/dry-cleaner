<?php
include("action.php");

if (isset($_POST['login'])) {

    $username = $obj->test_input($_POST['username']);
    $password = $obj->test_input($_POST['password']);

    if ($username != "" && $password != "") {

        $count = $obj->login_method("user", $username, $password);

        if ($count >= 1) {

            $session_data = $obj->session_method("user", $username, $password);
            $_SESSION['userid']   = $session_data['userid'];
            $_SESSION['usertype'] = $session_data['usertype'];

            header("Location: admin/dashboard.php");
            exit;

        } else {

            header("Location: index.php?error=1");
            exit;
        }

    } else {

        header("Location: index.php?msg=blank");
        exit;
    }
}
