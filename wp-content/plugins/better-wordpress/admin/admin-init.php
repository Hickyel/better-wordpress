<?php

// Load classes and controllers for the admin part of the plugin
require_once plugin_dir_path(__FILE__) . 'classes/class-bw-admin.php';


// Create an instance of the admin class
$bw_admin = new BW_Admin();
$bw_admin->init();