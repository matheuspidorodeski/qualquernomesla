<?php
session_start();

if(isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../dashboard/dashboard.php');
    exit;
}
?>
