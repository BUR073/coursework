<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

$action = $_POST['action']; 

if ($action == 'gym') {
    header('Location: gym.html');
} else {
    if ($action == 'leisure') {
        header('Location: leisure.html');
    } else {
        if ($action == 'cancel') {
            header('Location: cancel.html'); 
        }
    }
}

?> 
