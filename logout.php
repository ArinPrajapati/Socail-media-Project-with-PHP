<?php
session_start();

session_destroy();

header('Location: LoginInAccount.php');
exit();
