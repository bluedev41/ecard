<?php
class Submenu_Page
{
    private $esl_num_of_cards = 10;

    public function init_admin()
    {
        ?>
        <h1>E-card Super Light Options</h1>
        <form method="post" action="options.php">
        <?php
        settings_fields('esl_fields');
        do_settings_sections('esl_fields');
        submit_button(); ?>
        </form>
        <?php
    }
    
    public function plugin_admin_init()
    {
        register_setting('esl_fields', 'esl_fields', 'plugin_options_validate');
        
        add_settings_section('esl_main_section', 'Main Settings', array($this,'settings_section_callback'), 'esl_fields');
        add_settings_field('plugin_setting_images', 'Image Selection:', array($this,'plugin_setting_images'), 'esl_fields', 'esl_main_section');
        
        add_settings_section('esl_email_section', 'Mail Settings', array($this,'settings_section_callback'), 'esl_fields');
        add_settings_field('plugin_setting_from_email', 'From Email:', array($this,'plugin_setting_from_email'), 'esl_fields', 'esl_email_section');

        add_settings_section('esl_mailchimp_section', 'MailChimp Settings', array($this,'settings_section_callback'), 'esl_fields');
        add_settings_field('plugin_setting_api', 'MailChimp API Key:', array($this,'plugin_setting_api'), 'esl_fields', 'esl_mailchimp_section');
    }
    
    public function settings_section_callback($args)
    {
        switch ($args['id']) {
            case 'esl_main_section':
                echo '<p>Upload your e-card images below: (600px width minimum)</p>';
            break;
            case 'esl_mailchimp_section':
                echo '<p>Enter your MailChimp API Key below</p>';
            break;
        }
    }
    
    public function plugin_setting_images()
    {
       
        // Loop over number of cards and create upload code for each
       
        for ($i=0;$i<$this->esl_num_of_cards;$i++) {
            $this->image_uploader_code('images', '200', '150', $i);
        }
    }
    
    public function plugin_setting_from_email()
    {
        
           // Create mail fields
        $options = get_option('esl_fields');
        
        if (!empty($options['fromEmail'])) {
            $value = $options['fromEmail'];
        } else {
            $value = '';
        }
        print '<p><input name="esl_fields[fromEmail]" placeholder="youremail@thisdomain.com" id="esl_fields[fromEmail]" type="text" value="' .  $value . '" />';
        print '<p class="description">The e-mail address from which your card will be sent</p>';
        
        register_setting('esl_fields', 'fromEmail');
    }

    public function plugin_setting_api()
    {
        
        // Create field for MailChimp API Key
    
        $options = get_option('esl_fields');
        
        if (!empty($options['mailchimp_api_key'])) {
            $value = $options['mailchimp_api_key'];
        } else {
            $value = '';
        }
        print '<p><input name="esl_fields[mailchimp_api_key]" placeholder="your api key" id="esl_fields[mailchimp_api_key]" type="text" value="' .  $value . '" />';
        print '<p class="description">leave blank to not use MailChimp</p>';
        
        register_setting('esl_fields', 'mailchimp_api_key');
        
        //$options = get_option('esl_fields');
    }
    
    public function image_uploader_code($name, $width, $height, $number)
    {
    
        //  Get plugin option values

        $options = get_option('esl_fields');
       
        $empty_image = plugins_url('../public/images/blank.gif', __FILE__);
    
        if (!empty($options[$name][$number])) {
            $image_attributes = wp_get_attachment_image_src($options[$name][$number], array( $width, $height ));
            $src = $image_attributes[0];
            $value = $options[$name][$number];
        } else {
            $src = $empty_image;
            $value = '';
        }

        // Output HTML for Upload and Remove buttons

        echo '
            <div class="upload" style="float:left;margin-right:20px">
                <img data-src="' .$empty_image . '" src="' . $src . '" width="' . $width . 'px" height="' . $height . 'px" />
                <div>
                    <input type="hidden" name="esl_fields[' . $name . ']['.$number.']" id="esl_fields[' . $name . ']['.$number.']" value="' . $value . '" />
                    <button type="submit" class="upload_image_button button">Upload</button>
                    <button type="submit" class="remove_image_button button">&times;</button>
                </div>
            </div>';
    }
}
