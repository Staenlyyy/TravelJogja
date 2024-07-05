<?php
session_start();
session_destroy();
header('Location: ../templates/sign_in.html');
exit;
?>
