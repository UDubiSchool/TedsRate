<?php

$userID = intval($_POST['id']);
$email = $_POST['email'];
$password = $_POST['password'];
$authID = 3;

$addAuthority = $dbq->prepare("INSERT INTO user_authority (userID, authorityID) VALUES (:userID, 3)");
$addAuthority->bindValue(':userID', $userID, PDO::PARAM_INT);
$addAuthority->execute();
$addAuthority->closeCursor();

$updateUser = $dbq->prepare("UPDATE user SET email=:email, passwordValue=:password WHERE userID=:id");
$updateUser->bindValue(':id', $userID, PDO::PARAM_INT);
$updateUser->bindValue(':email', $email, PDO::PARAM_STR);
$updateUser->bindValue(':password', $password, PDO::PARAM_STR);
$updateUser->execute();
$numberOfRows = $dbq->query("SELECT ROW_COUNT();");
$numberOfRows = $numberOfRows->fetchColumn();
$updateUser->closeCursor();


if ($numberOfRows == 1) {
    $data['user'] = true;
} else {
    $data['user'] = false;
}

$updateUser->closeCursor();


header('Content-Type: application/json');
echo json_encode($data, TRUE);
?>