<?php

$email = $_POST['email'];

$getUser = $dbq->prepare("SELECT * FROM user WHERE email=:email");
$getUser->bindValue(':email', $email, PDO::PARAM_STR);
$getUser->execute();
$numberOfRows = $dbq->query("SELECT FOUND_ROWS();");
$numberOfRows = $numberOfRows->fetchColumn();
if ($numberOfRows == 1) {
    $data['exists'] = true;
} else {
    $data['exists'] = false;
}

$getUser->closeCursor();


header('Content-Type: application/json');
echo json_encode($data, TRUE);
?>