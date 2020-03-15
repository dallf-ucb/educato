<?php
require("dispatcher.php");
require("db.php");
require("model.php");
require("controller.php");

function base_url() {
    if (isset($_SERVER['HTTP_HOST'])) {
        $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        $hostname = $_SERVER['HTTP_HOST'];
        $base_url = sprintf( "%s://%s/", $http, $hostname );
    }
    else $base_url = 'http://localhost/';

    return $base_url;
}
?>