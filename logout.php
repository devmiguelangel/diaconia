<?php
require('session.class.php');
$session = new Session();
$session->remove_session();

if (isset($_GET['logout'])) {
    echo 1;
} else {
    echo '<meta http-equiv="refresh" content="0;url=index.php" >';
}
?>