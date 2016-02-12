<?php
    //add or update a comment for a given assessment

    if(!isset($_POST['attribute'])) {
        exit;
    }

    $attribute = $_POST['attribute'];
    $ratingID = intval($attribute['ratingID']);
    $attributeID = intval($attribute['attributeID']);
    $comment = $attribute['comment'];



    $stmt = $dbq->prepare("CALL addComment(:comment, :ratingID, @commentID)");
    $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindValue(':ratingID', $ratingID, PDO::PARAM_INT);
    $stmt->execute();

    $commentID = $dbq->query('SELECT @commentID')->fetchColumn();
    $data['commentID'] = $commentID;

    header('Content-Type: application/json');
    echo json_encode($data, TRUE);
?>