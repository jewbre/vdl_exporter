<?php
/**
 * @package vdl_exporter
 * @version 1.6
 */
/*
Plugin Name: VDL Exporter
Plugin URI: http://wordpress.org/plugins#
Description: Extension to default wordpress exporter
Author: Vilim Stubičan
Version: 1.0
Author URI: http://vdl.hr/
*/
add_action("init", "importRequired");
function importRequired(){
    require_once("src/VDLExporterSettings.php");
}
