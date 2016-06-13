<?php
    unset($_GET);unset($_POST);unset($_REQUEST);
    array_shift($argv);
    $_SERVER['PATH_INFO'] = '/'.implode('/', $argv);
    define('APP_NAME', 'Study');
    define('APP_PATH', './');
    define('THINK_PATH', '../../Think/');
    define('APP_DEBUG', TRUE);
    define('ILC_ENCRYPT_KEY', 'jsq130502');
    require(THINK_PATH.'ThinkPHP.php');
?>
