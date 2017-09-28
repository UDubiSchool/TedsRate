<?php
require_once "classes/user_model.php";
use models\user_model;
$model = new user_model();
$model->get();