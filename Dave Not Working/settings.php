<?php
if(!class_exists('WP_PetRescue_Plugin_Settings'))
{
	class WP_PetRescue_Plugin_Settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct
		
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
        	// register your plugin's settings
        	register_setting('wp_petrescue_plugin-group', 'api_key');
        	register_setting('wp_petrescue_plugin-group', 'group_id');

        	// add your settings section
        	add_settings_section(
        	    'wp_plugin_template-section', 
        	    'WP PetRescue Plugin Settings', 
        	    array(&$this, 'settings_section_wp_petrescue_plugin'), 
        	    'wp_petrescue_plugin'
        	);
        	
        	// add your setting's fields
            add_settings_field(
                'wp_petrescue_plugin-api_key', 
                'PetRescue API Key', 
                array(&$this, 'settings_field_input_text'), 
                'wp_petrescue_plugin', 
                'wp_petrescue_plugin-section',
                array(
                    'field' => 'api_key'
                )
            );
            add_settings_field(
                'wp_petrescue_plugin-group_id', 
                'PetRescue Group ID', 
                array(&$this, 'settings_field_input_text'), 
                'wp_petrescue_plugin', 
                'wp_petrescue_plugin-section',
                array(
                    'field' => 'group_id'
                )
            );
            // Possibly do additional admin_init tasks
        } // END public static function activate
        
        public function settings_section_wp_petrescue_plugin()
        {
            // Think of this as help text for the section.
            echo 'These are the only paramaters needed to get the PetRescue API Functionality to work';
        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="wp_petrescue_plugin-api_key" id="api_key" value="" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)
        
        /**
         * add a menu
         */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'WP PetRescue Plugin Settings', 
        	    'WP PetRescue Plugin', 
        	    'manage_options', 
        	    'wp_petrescue_plugin', 
        	    array(&$this, 'plugin_settings_page')
        	);
        } // END public function add_menu()
    
        /**
         * Menu Callback
         */		
        public function plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class WP_PetRescue_Plugin_Settings
} // END if(!class_exists('WP_PetRescue_Plugin_Settings'))
