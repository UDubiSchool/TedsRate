<?php
    if(!isset($_GET['t'])) {
        exit;
    }

    $target = $_GET['t'];

    switch ($target) {
        case 'screenshot':

            $data['res'] = false;

            $file = $_FILES['file'];

           $data['file'] = $file;

            $errors= array();
            $file_name = $file['name'];
            $file_size =$file['size'];
            $file_tmp =$file['tmp_name'];
            $file_type=$file['type'];
            $file_ext=strtolower(end(explode('.',$file['name'])));

            $extensions= array("jpeg","jpg","png");

            if(in_array($file_ext,$extensions)=== false){
               $errors[]="extension $file_ext not allowed, please choose a JPEG or PNG file.";

            }

            if($file_size > 2097152){
               $errors[]='File size must be less than 2 MB';
            }

            if(empty($errors)==true){
              $path = "upload/screenshots/".$file_name;
              if(move_uploaded_file($file_tmp, $path)) {
                $data['res'] = true;
                $data['path'] = $path;

                // $screen_sql = "INSERT INTO screenshot (screenshotPath, ratingID) VALUES ('$path' , $ratingID)";
                // $dbq->query($screen_sql);
              } else {
               $data['errors'] = "could not upload file at $file_tmp";

                // echo "could not upload file at " . $file_tmp;
              }
            }
            else{
               $data['res'] = false;
               $data['errors'] = $errors;
            }
            header('Content-Type: application/json');
            echo json_encode($data, TRUE);

            break;

        default:
            # code...
            break;
    }



?>