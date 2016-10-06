<?php
// ============================== authentication ===============================
    require_once "session_inc.php";
// ============================== authentication ===============================
    require_once "dbconnect.php";
    require 'PHPMailer/PHPMailerAutoload.php';

    // $root_url = "https://depts.washington.edu/";

    $url = $_SERVER['REQUEST_URI']; //returns the current URL
    $parts = explode('/',$url);
    $root_url = $_SERVER['SERVER_NAME'];
    for ($i = 0; $i < count($parts) - 1; $i++) {
     $root_url .= $parts[$i] . "/";
    }
    if ($root_url == 'depts.washington.edu/tedsrate/tedsrate/') {
        $root_url = 'https://' . $root_url;
    }
if (isset($_GET['email'])) {
    try {
        $dbq = db_connect();


        $email = $_GET['email'];
        $assessmentID = $_GET['assessmentID'];
        $sendEmail = $_GET['sendEmail'];

        $first_query = "SELECT * FROM assessment ass
                             join user u on ass.userID = u.userID
                             where ass.`assessmentID` = " . $assessmentID . "
                             and u.email = '" . (string)$email . "'";
        $flag = $dbq->query($first_query)->fetchColumn();
//        $flag->execute();
//        echo($flag);

        if ($flag) {
            $query = $dbq->prepare("SELECT * FROM assessment
                                    join user u on u.userID = assessment.userID
                                    where assessment.`assessmentID` = " . $assessmentID);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                // $language = $result['preferredLanguage'];
                $userName = $result['firstName'] . " " . $result['lastName'];
                $assessmentIDHashed = $result['assessmentIDHashed'];
                $targetURL = "assessment.php?asid=" . $assessmentIDHashed;

                $email_flag = false;
                $email_message = "Invalid email! Please try again!";
                // sending email function
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $to = (string)$email;

                    // subject
                    $subject = 'You are invited: please help us fill out this evaluation form -- TEDS';

                    // message
                    $message = '
                                <html>
                                <head>
                                  <title>You are invited: please help us fill out this evaluation form -- TEDS</title>
                                </head>
                                <body>
                                  <p>Dear ' . $userName . ', </p>
                                  <p>Please help us make a better web! Here is the link to access the TEDS evaluation form for you:</p>
                                  <p><a href="' . $root_url . $targetURL . '" target="blank"><b>Link</b></a></p>
                                  <br />
                                  <br />
                                  <p>Your help is greatly appreciated!</p>
                                  <p>Sincerely,</p>
                                  <p><a href="https://www.washington.edu/" target="blank">University of Washington</a></p>
                                  <p><a href="https://ischool.uw.edu/" target="blank">Information School</a></p>
                                </body>
                                </html>
                                ';

                    $mail = new PHPMailer;

                    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'smtp.gmail.com;';  // Specify main SMTP server
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'tedsratemailer@gmail.com';                 // SMTP username FOR INFO REGARDING THIS EMAIL CONTACT wtmenten@gmail.com
                    $mail->Password = 'Tedsrate2015';                           // SMTP password
                    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                    // TCP port to connect to

                    $mail->setFrom('tedsratemailer@gmail.com', 'TEDSRate');
                    $mail->addAddress($to);     // Add a recipient
                    // $mail->addAddress('ellen@example.com', "John Doe");               // Name is optional
                    // $mail->addReplyTo('info@example.com', 'Information');
                    // $mail->addCC('cc@example.com');
                    // $mail->addBCC('bcc@example.com');

                    $mail->isHTML(true);                                  // Set email format to HTML

                    $mail->Subject = $subject;
                    $mail->Body    = $message;
                    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                    if(!$mail->send()) {
                        $email_flag = false;
                        $email_message = "Url failed to send to " . $email;
                    } else {
                        $email_flag = true;
                        $email_message = "Url sent successfully to " . $email;
                    }

                }

                //
                $final_url = $root_url . $targetURL;
                $final_result = [];
                array_push($final_result,$email_flag, $email_message, $final_url);
                print json_encode($final_result);
            }
        }
    }
    catch (PDOException $e){
        // Report errors
         printf ($e->getMessage());
    }
}