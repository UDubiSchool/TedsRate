<?php
    // log the user
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // delete session
    unset($_SESSION['user']);
    session_destroy();
    header("Location: index.php?notice=logout");
?>