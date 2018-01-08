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

if (! defined('WPINC')) {
    die;
}
define('ecard-super-light-version', '1.0.0');

/* register public styles and js */

function register_css()
{
    wp_register_style('esl-style', plugins_url('public/css/style.css', __FILE__));
    wp_register_style('sliderPro-style', plugins_url('bower_components/slider-pro/dist/css/slider-pro.min.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'register_css');

function register_js()
{
    wp_register_script('sliderPro-js', plugins_url('bower_components/slider-pro/dist/js/jquery.sliderPro.min.js', __FILE__), array('jquery'), false, true);
    wp_register_script('ecard-super-light-js', plugins_url('public/js/ecard-super-light.js', __FILE__), array('jquery'), false, true);
}
add_action('wp_enqueue_scripts', 'register_js');


/* Include admin backend functions */

if (is_admin()) {
    require_once(dirname(__FILE__) . '/admin/admin-settings.php');
} else {
    /* Include shortcodes for display */
    require_once(dirname(__FILE__) . '/shortcodes.php');
}
/* Include MailChimp API */
require_once(dirname(__FILE__) . '/MailChimp.php');


/* Deliver the Ecard E-mail */

function esl_send_form()
{
    $i=0;
    $validToEmails = array();
    $options = get_option('esl_fields');

    try {
        if (empty($_POST['fromName'])) {
            throw new Exception('Missing from name.');
        }
        if (count($_POST['toEmail'])==0) {
            throw new Exception('You must enter at least one recipient.');
        }
        foreach ($_POST['toEmail'] as $toEmail) {
            $i++;
            if (! empty($toEmail) && ! is_email($toEmail)) {
                throw new Exception('Email address ' . $i . ' is not formatted correctly.');
            } else {
                $validToEmails[]=sanitize_email($toEmail);
            }
        }
        if (! isset($_POST['ecard-super-light-save-nonce']) || ! wp_verify_nonce($_POST['ecard-super-light-save-nonce'], 'ecard-submit')) {
            throw new Exception('Invalid nonce');
        }

        $fromName    = sanitize_text_field($_POST["fromName"]);
        
        $fromEmail   = sanitize_email($_POST['fromEmail']);

        $testEmails = implode($validToEmails, '<BR>');

        // if no fromEmail set, use blog admin e-mail //
        $adminFrom = ($options['fromEmail'] ? $options['fromEmail']  : get_option( 'admin_email' ));
        
        if (! is_email($adminFrom)){
          throw new Exception('The e-card has an invalid From address configuration.');
        }
        if (! $adminFrom){
          throw new Exception('You have not set a From: address is the plugin, and your Wordpress install does not have an admin e-mail set');
        }

        $headers = 'From: '.$fromName.' <'.$adminFrom.'>';
        $send_to = 'chad@flap.tv';
        $subject = "An E-card From WeRepair.org";
        $message = "Message from ".$fromName.": \n\n ". $testEmails  . " \n\n Reply to: " . $toEmail3;
   
        if (wp_mail($send_to, $subject, $message, $headers)) {
            echo json_encode(array('status' => 'success', 'message' => 'message sent to '. $testEmails . ' from ' . $adminFrom ));
            exit;
        } else {
            throw new Exception('Failed to send email. Check AJAX handler.');
        }
    } catch (Exception $e) {
        echo json_encode(array('status' => 'error', 'message' => $e->getMessage() . ' attempt to sent to '. $testEmails . ' from ' . $adminFrom));
        exit;
    }
}
  add_action("wp_ajax_esL_send", "esl_send_form");
  add_action("wp_ajax_nopriv_esL_send", "esl_send_form");
