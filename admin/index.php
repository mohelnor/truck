<?php
session_start();

if($_SESSION['user']['permit'] == 'admin'){
    header('Location: users');
}
?>