<?php

/*

Plugin Name: Add Google Analytics
Plugin URI: http://thefold.no
Description: A plugin to add google analytics to a wordpress site
Version: 1
Author: The Fold
Author URI: http://thefold.no
License: GPL2

*/


// Let's be safe
defined('ABSPATH') or die('No script kiddies please!');

class addGoogleAnalytics {

    /* Vars */
    private $options;

    /* Set needed wp actions */
    public function __construct() {
        $this->options = get_option('add_google_analytics_optins');
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_action_links'));
        add_action('wp_head', array($this, 'add_google_analytics_code'));

    }

    /* Add options page */
    public function add_plugin_page() {

        add_options_page('Add Google Analytics', 'Add Google Analytics', 'manage_options', 'add-google-analytics', array($this, 'create_admin_page'));

    }

    /* Settings form */
    public function create_admin_page() {

        ?>
        <div class="wrap">
            <h1>Google Analytics</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('add_google_analytics_group');
                do_settings_sections('add-google-analytics');
                submit_button();
                ?>
            </form>
        </div>
        <?php

    }

    /* Register and add settings */
    public function page_init() {

        register_setting('add_google_analytics_group', 'add_google_analytics_optins', array($this, 'sanitize'));
        add_settings_section('add_google_analytics_section', 'Add yout Google Analytics id', array($this, 'print_section_info'), 'add-google-analytics');
        add_settings_field('add_google_analytics', 'Google Analytics ID:', array($this, 'add_google_analytics_callback'), 'add-google-analytics', 'add_google_analytics_section');

    }

    /* Sanitize */
    public function sanitize($input) {

        $new_input = array();
        if(isset($input['add_google_analytics'])) $new_input['add_google_analytics'] = sanitize_text_field($input['add_google_analytics']);
        return $new_input;

    }

    /* Add section text */
    public function print_section_info() {

        # print 'Section info here!' ; Not used in my example, but you never know when you will need it

    }

    /* Output post type input field */
    public function add_google_analytics_callback() {

        printf('<input type="text" id="add_google_analytics" name="add_google_analytics_optins[add_google_analytics]" value="%s" />', isset( $this->options['add_google_analytics'] ) ? esc_attr( $this->options['add_google_analytics']) : '');

    }

    /* Add plugin action link */
    function add_action_links($links) {

        array_push($links, '<a href="' . admin_url('options-general.php?page=add-google-analytics') . '">Set ID</a>');
        return $links;

    }

    /* Add google analytics code to website */
    function add_google_analytics_code() {
        if(isset($this->options['add_google_analytics'])) {
            print '<script>' . PHP_EOL;
            print "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create', '" . $this->options['add_google_analytics'] . "', 'auto');ga('send', 'pageview');" . PHP_EOL;
            print '</script>' . PHP_EOL;
        }
    }

}

// If class exists - run google analytics
if(class_exists('addGoogleAnalytics')) new addGoogleAnalytics;
