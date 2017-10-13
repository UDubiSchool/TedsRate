<?php
// ============================== authentication ===============================
// require_once "session_inc.php";
// ============================== authentication ===============================

require_once "dbconnect.php";

if ($_POST) {
    $source        = $_POST['source']; // source param
    $authenticated = false;
    $userID = '';
    try {
        $dbq = db_connect();

        switch ($source) {

            case "index":

                if (isset($_POST['user_email']) && isset($_POST['password'])) {
                    $user_email = $_POST['user_email'];
                    $password   = $_POST['password'];
                    if (!preg_match("/^\s*$/i", $user_email) && !preg_match("/^\s*$/i", $password)) {
                        $auth_query = "SELECT * FROM user u
                                                JOIN user_authority ua on ua.userID = u.userID
                                                JOIN authority a ON a.authorityID = ua.authorityID
                                                WHERE email = '$user_email'
                                                AND a.AuthorityLevel = 2";
                        $result = $dbq->query($auth_query);
                        $result = $result->fetch_assoc();
                        if ($result) {
                            if ($password == $result['passwordValue']) {
                                $authenticated = true;
                            }
                            $userID = $result['userID'];
                        }
                    }
                }
                break;

            case "logout":
                // log the user
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                // delete session
                unset($_SESSION['user']);
                session_destroy();
                break;

            default:
                break;
        }
    }
    catch (PDOException $e) {
        // Report errors
        printf ($e->getMessage());
    }
}
// redirect based on source param

if ($source == "index") {
    if ($authenticated) {
        // authenticate the user
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // auth okay, setup session
        $_SESSION['user_email'] = $_POST['user_email'];
        $_SESSION['teds.userID'] = $userID;
        $source_url             = "admin.php";


    } else {
        $source_url = "index.php?notice=no_access";
    }
} elseif ($source == "logout") {
    $source_url = "index.php?notice=logout";
} else {
    $source_url = "index.php?notice=no_access";
}

header("Location: $source_url");
?>