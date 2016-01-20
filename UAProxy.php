<?php
    if($_GET['target'] && $_GET['ua']) {

        // ini_set("user_agent", $_GET['ua']);
        // echo '<iframe src="' . $_GET['target'] . '" frameborder="0" width="' . $_GET['w'] . '" height="' . $_GET['h'] . '"></iframe>';
        $ch=curl_init();
        $agent = $_GET['ua'];
        $url = $_GET['target'];
        $timeout = 10;
        // echo $agent;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        $data = curl_exec($ch);
        curl_close($ch);
        echo $data;
    } else {
        echo "You are not using this page correctly.";
    }

?>
