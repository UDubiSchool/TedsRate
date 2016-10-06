<?php

$userID = $_POST['id'];

$getUser = $dbq->prepare("SELECT * FROM user WHERE userID=:id");
$getUser->bindValue(':id', $userID, PDO::PARAM_INT);
$getUser->execute();
$numberOfRows = $dbq->query("SELECT FOUND_ROWS();");
$numberOfRows = $numberOfRows->fetchColumn();
if ($numberOfRows == 1) {
    $data['user'] = $getUser->fetch();
} else {
    $data['user'] = false;
}

$getUser->closeCursor();


header('Content-Type: application/json');
echo json_encode($data, TRUE);
?>