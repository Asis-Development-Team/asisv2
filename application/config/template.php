<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$template_conf = 	array(
						'template' => 'default',
						'template_admin' => 'default',
						
						'site_name' => '',
						'site_title' => '',
						
						'devmode' => false,
						'content' => '',
						'css' => '',
						'js' => '',
						'head' => '',
						'messages' => '',
						'assets_dir' => 'assets/'
					);

$template_css = array('base');

$template_js = array();

$template_head = array();

/*
$template_head = array(
	'jquery' => '<script type="text/javascript" src="http://www.google.com/jsapi"></script>
					<script type="text/javascript">
					google.load("jquery", "1.5.0");
					</script>'
);
*/