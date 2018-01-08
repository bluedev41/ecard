<?php
/*
Plugin Name: Ecard Super Light
Plugin URL: http://flap.tv
Description: A super-simple e-card tool
Version: 1.0
Author: Chad Lieberman, Plattion
Author URI: http://flap.tv
*/

/* Queue Styles and Scripts */

if ( ! defined( 'WPINC' ) ) {
	die;
}
define ('ecard-super-light-version', '1.0.0');

/* register public styles and js */

function register_css () {
   wp_register_style('esl-style', plugins_url('public/css/style.css', __FILE__));
   wp_register_style('sliderPro-style', plugins_url('bower_components/slider-pro/dist/css/slider-pro.min.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'register_css');

function register_js(){
    wp_register_script('sliderPro-js', plugins_url('bower_components/slider-pro/dist/js/jquery.sliderPro.min.js', __FILE__), array('jquery'), false, true);
    wp_register_script('ecard-super-light-js',  plugins_url('public/js/ecard-super-light.js', __FILE__),array('jquery'), false, true);

}
add_action('wp_enqueue_scripts', 'register_js');


/* Include admin backend functions */

if ( is_admin() ){
    require_once (dirname( __FILE__) . '/admin/admin-settings.php');
} else {
/* Include shortcodes for display */
    require_once (dirname( __FILE__) . '/shortcodes.php');
}
/* Include MailChimp API */
require_once (dirname( __FILE__) . '/MailChimp.php');


/* Deliver the Ecard E-mail */

function esl_send_form() {
    try {
      if (empty($_POST['toEmail1']) || empty($_POST['fromName'])) {
        throw new Exception('Bad form parameters. Check the markup to make sure you are naming the inputs correctly.');
      }
      if (!is_email($_POST['toEmail1'])) {
        throw new Exception('Email address not formatted correctly.');
      }
   
      $subject = 'Contact Form: '.$reason.' - '.$_POST['toEmail1'];
      $headers = 'From: My Blog Contact Form <contact@myblog.com>';
      $send_to = "chad@flap.tv";
      $subject = "An E-card From WeRepair.org";
      $message = "Message from ".$_POST['fromName'].": \n\n ". $_POST['toEmail1'] . " \n\n Reply to: " . $_POST['toEmail1'];
   
      if (wp_mail($send_to, $subject, $message, $headers)) {
        echo json_encode(array('status' => 'success', 'message' => 'Contact message sent.'));
        exit;
      } else {
        throw new Exception('Failed to send email. Check AJAX handler.');
      }
    } catch (Exception $e) {
      echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
      exit;
    }
  }
  add_action("wp_ajax_esL_send", "esl_send_form");
  add_action("wp_ajax_nopriv_esL_send", "esl_send_form");