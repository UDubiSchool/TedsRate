<?php

$userID = intval($_POST['userID']);

$getUser = $dbq->prepare("DELETE FROM user WHERE userID=:userID");
$getUser->bindValue(':userID', $userID, PDO::PARAM_INT);
$getUser->execute();
$numberOfRows = $dbq->query("SELECT ROW_COUNT();");
$numberOfRows = $numberOfRows->fetchColumn();
if ($numberOfRows == 1) {
    $data['deleted'] = true;
} else {
    $data['deleted'] = false;
}

$getUser->closeCursor();


header('Content-Type: application/json');
echo json_encode($data, TRUE);
?>