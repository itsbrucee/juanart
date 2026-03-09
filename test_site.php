<?php
// Simple test to verify the site works
ob_start();
$_GET['page'] = 'dashboard';
$_SERVER['REQUEST_URI'] = '/juanartdev/';

include 'public/index.php';
