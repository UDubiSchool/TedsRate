<?php

$email = $_POST['email'];
$password = $_POST['password'];

$getUser = $dbq->prepare("SELECT * FROM user u
                                                JOIN user_authority ua ON u.userID = ua.userID
                                                JOIN authority a ON a.authorityID = ua.authorityID
                                                WHERE u.email=:email
                                                AND u.passwordValue=:password
                                                AND a.authorityLevel  IN  (1,3)");
$getUser->bindValue(':email', $email, PDO::PARAM_STR);
$getUser->bindValue(':password', $password, PDO::PARAM_STR);
$getUser->execute();
$numberOfRows = $dbq->query("SELECT FOUND_ROWS();");
$numberOfRows = $numberOfRows->fetchColumn();
if ($numberOfRows == 1) {
    $data['user'] = $getUser->fetch();
} else {
    $data['user'] = $getUser->fetch();
    $data['rows'] = $numberOfRows;
    $data['email'] = $email;
    $data['password'] = $password;
}

$getUser->closeCursor();


header('Content-Type: application/json');
echo json_encode($data, TRUE);
?>