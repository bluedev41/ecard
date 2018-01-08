<?php

/*----- Shortcode ------*/

function esl_shortcode()
{
    wp_enqueue_style('esl-style');
    wp_enqueue_style('sliderPro-style');
    wp_enqueue_script('sliderPro-js');
    wp_enqueue_script('ecard-super-light-js');
    wp_localize_script('ecard-super-light-js', 'esl_data', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));

    // get images //
    $options = get_option('esl_fields'); ?>
    <div id="esl-wrapper">
    <div class="rotator">
    <div class="slider-pro" id="my-esl-slider">
	<div class="sp-slides">
    <?php
    $i = 0;
    foreach ($options['images'] as $key => $val) {
        if ($val) {
            $i++;
            $image_attributes = wp_get_attachment_image_src($options['images'][$key], array(600, 400));
            $src = $image_attributes[0];
            // echo '<img data-src="' . $src . '" src="' . $src . '" width="' . $width . 'px" height="' . $height . 'px" />';
            echo '<!-- Slide ' . $i . ' -->
                    <div class="sp-slide">
                        <img class="sp-image" src="' . $src . '"/>
                        <p class="sp-layer">consectetur adipisicing elit</p>
                    </div>';
        }
    } ?>
	</div>
    <div class="sp-thumbnails">
    <?php
    $i = 0;
    foreach ($options['images'] as $key => $val) {
        if ($val) {
            $i++;

            $image_attributes = wp_get_attachment_image_src($options['images'][$key], array(600, 400));
            $src = $image_attributes[0];
            // echo '<img data-src="' . $src . '" src="' . $src . '" width="' . $width . 'px" height="' . $height . 'px" />';
            echo '<!-- Slide ' . $i . ' thumbnail -->

                    <img class="sp-thumbnail" src="' . plugins_url('/public/images/loading.png', __FILE__) . '" data-src="' . $src . '"/>';
        }
    } ?>
	</div>
</div>
    </div>
    <div class="mail-form">
    <form id="esl-form">
    <input type="hidden" name="action" value="esL_send" />
  <p><label for="fromName">Your Name:</label><input type="text" name="fromName" class="input"></p>
  <p><label for="fromEmail">Your E-mail:</label><input type="text" name="fromEmail" class="input"></p>

    <label for="toEmail1">To:<small>(up to 3 individuals may receive the e-card)</small></label>
  <p><input type="text" name="toEmail[]" placeholder="email@domain.com" class="input"></p>
  <p><input type="text" name="toEmail[]" placeholder="email@domain.com" class="input"></p>
  <p><input type="text" name="toEmail[]" placeholder="email@domain.com" class="input"></p>
    <?php wp_nonce_field( 'ecard-submit','ecard-super-light-save-nonce' ); ?>
  <p><input type="submit" value="Deliver Card" class="button" />
    </form>
    </div>
    </div>
    <?php
}
add_shortcode('esl-form', 'esl_shortcode');
