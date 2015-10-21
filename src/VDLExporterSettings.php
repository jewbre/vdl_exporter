<?php
/**
 * Created by PhpStorm.
 * User: Vilim Stubičan
 * Date: 30.9.2015.
 * Time: 0:02
 */

class VDLExporterSettings
{
    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'check_download'));
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_submenu_page(
            "tools.php" ,
            "VDL Exporter",
            "VDL Exporter",
            "manage_options",
            "my-setting-admin",
            array( $this, 'create_admin_page' )
        );
    }

    public function check_download()
    {
        if(isset($_REQUEST["VDLExporterForm"])) {
            global $wpdb;
            require_once 'exporter.php';
            $posts = array();
            foreach($_REQUEST["VDLExporterForm"] as $type) {
                $posts = array_merge($posts, $type["select"]);
            }

            $posts = array_merge($posts,
                    $wpdb->get_col("
                        SELECT meta_value FROM wp_postmeta WHERE post_id IN (0,".implode(",",$posts).") AND meta_key = '_thumbnail_id'
                    ")
                );
            
            vdl_export_wp( $posts );
            die();
        };
    }
    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        require_once "_form.php";
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'my_option_group', // Option group
            'my_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'My Custom Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );

        add_settings_field(
            'id_number', // ID
            'ID Number', // Title
            array( $this, 'id_number_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'title',
            'Title',
            array( $this, 'title_callback' ),
            'my-setting-admin',
            'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = absint( $input['id_number'] );

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="text" id="id_number" name="my_option_name[id_number]" value="%s" />',
            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="my_option_name[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
    }
}

if( is_admin() )
    $vdlExporterSettings = new VDLExporterSettings();