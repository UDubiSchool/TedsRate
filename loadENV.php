<?php
$overwriteENV = true;
$Loader = new ('Dotenv/Loader/config.env');
// Parse the .env file
$Loader->parse();
// Send the parsed .env file to the $_ENV variable
$Loader->toEnv($overwriteENV);
?>