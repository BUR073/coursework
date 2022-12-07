<?php
session_start();
session_destroy();
// Delete session
// Redirect to the login page:
header('Location: index.html');
?>

