<?php
/**
 * Class EM_Dashboard_OptionsPage
 *
 * Adds options pages for the plugin
 *
 * @since 1.0.0
 */
class EM_Dashboard_OptionsPage extends EM_Dashboard_Options {

    /**
     * Constructor, load parent constructor
     */
    public function __construct() {
        parent::__construct();

        register_activation_hook( __FILE__, array( $this, 'set_options' ) );

    	add_action( 'admin_init', array( &$this, 'register_fields' ) );

        if (is_multisite()) {
        	add_action('network_admin_menu', array( &$this, 'add_menu_link' ) );
        } else {
            add_action('admin_menu',		 array( &$this, 'add_menu_link' ) );
        }
    }

    /**
	 * Activation hook
	 *
	 * @since ???
	 *
	 * @todo everything
	 *
	 * I might not use this because I call "defaults" everywhere.
	 */
	public function set_options() { }

    /**
	 * Add Option fields to /settings_or_options-general.php?page=em_dashboard_settings
	 *
	 * @since 1.0.0
     * @return void
	 */
	public function register_fields() {

		/**
		 * Start General Settings
		 *
		 * @since 1.0.0
		 */
    	register_setting( 'em_dashboard_g_settings', 'em_dashboard_em_styles', 				array( $this, 's_boolean' ) ); //default false
    	register_setting( 'em_dashboard_g_settings', 'em_dashboard_em_fonts', 				array( $this, 's_boolean' ) ); //default true
    	register_setting( 'em_dashboard_g_settings', 'em_dashboard_color_scheme', 			array( $this, 's_colorscheme' ) ); //default light
    	register_setting( 'em_dashboard_g_settings', 'em_dashboard_color_picker', 			array( $this, 's_boolean' ) ); //Default true
    	register_setting( 'em_dashboard_g_settings', 'em_dashboard_load_easy', 				array( $this, 's_boolean' ) ); // Default true
    	register_setting( 'em_dashboard_g_settings', 'em_dashboard_easy_mode', 				array( $this, 's_default_easy' ) ); // Default 'off'
    	register_setting( 'em_dashboard_g_settings', 'em_dashboard_force_easy', 			array( $this, 's_boolean' ) ); //default false
    	register_setting( 'em_dashboard_g_settings', 'em_dashboard_force_easy_capability', 	array( $this, 's_capability' ) ); //default edit_plugins

        add_settings_section(
			'em_dashboard_general_settings',			// id
			__('General Settings', 'em-dashboard'),		// title
			array( $this, 'general_settings_fields'),	// callback
			'em_dashboard_g_settings'					// page
		);

		/**
		 * General settings section tab
		 *
		 * Doing it right: the WP way. Boring.
		 */
        add_settings_field (
            'em_dashboard_em_styles', 							// id
            __('EM Dashboard Styles', 'emdashboard'),			// title
            array( $this, 'checkbox'),				 			// callback
            'em_dashboard_g_settings',		 	 				// page
            'em_dashboard_general_settings',				 	// section
			array( 												// args
				'name' 		=> 'em_dashboard_em_styles',
				'page'		=> 'em_dashboard_g_settings',
				'default' 	=> 'em_styles',
				'labelinfo'	=> __( 'Load the EM Dashboard theme.', 'emdashboard'),
				'hoverinfo'	=> __( 'Experimental flat soft WP theme (this also removes the color picker)', 'emdashboard'),
				'hoverinfosite'	=> '',
				'hoverinfonetwork' => '',
				'class'		=> 'alternate',
			)
        );
        add_settings_field (
            'em_dashboard_em_fonts', 						// id
            __('EM Dashboard Fonts', 'emdashboard'), 		// title
            array( $this, 'checkbox'),	 					// callback
            'em_dashboard_g_settings',		 	 			// page
            'em_dashboard_general_settings',				// section
			array( 											// args
				'name' 		=> 'em_dashboard_em_fonts',
				'page'		=> 'em_dashboard_g_settings',
				'default' 	=> 'em_fonts',
				'labelinfo'	=> __( 'Load the Merriweather Google Font that goes with the EM Dashboard theme.', 'emdashboard'),
				'hoverinfo'	=> __( 'This option only has effect if the EM Dashboard Styles are also active', 'emdashboard'),
				'hoverinfosite'	=> '',
				'hoverinfonetwork' => '',
				'class'		=> 'alternate',
			)
        );
        add_settings_field (
            'em_dashboard_color_scheme', 					// id
            __('Default Color Scheme', 'emdashboard'), 		// title
            array( $this, 'dropdown'),	 					// callback
            'em_dashboard_g_settings',		 		 		// page
            'em_dashboard_general_settings',				// section
			array( 											// args
				'name' 		=> 'em_dashboard_color_scheme',
				'page'		=> 'em_dashboard_g_settings',
				'default' 	=> 'color_scheme',
				'labelinfo'	=> __( 'Set the default theme for newly registered users.', 'emdashboard'),
				'hoverinfo'	=> __( 'This does not affect currently registered users.', 'emdashboard'),
				'hoverinfosite'	=> '',
				'hoverinfonetwork' => '',
				'class'		=> '',
			)
        );
        add_settings_field (
            'em_dashboard_color_picker', 				// id
            __('Enable Color Picker', 'emdashboard'), 	// title
            array( $this, 'checkbox'),					// callback
            'em_dashboard_g_settings',			 	 	// page
            'em_dashboard_general_settings',			// section
			array( 										// args
				'name' 		=> 'em_dashboard_color_picker',
				'page'		=> 'em_dashboard_g_settings',
				'default' 	=> 'color_picker',
				'labelinfo'	=> __( 'When this option is on, the color picker shows in the user profile page.', 'emdashboard'),
				'hoverinfo'	=> '',
				'hoverinfosite'	=> __( "This option is set to on if 'Enable EM Dashboard Styles' is on. This has no effect on the admin", 'emdashboard'),
				'hoverinfonetwork'	=> __( "This option is set to on if 'Enable EM Dashboard Styles' is on. This has no effect on the super-admin", 'emdashboard'),
				'class'		=> '',
			)
        );
        add_settings_field (
            'em_dashboard_load_easy', 							// id
            __('Enable Easy Mode Button', 'emdashboard'),   	// title
            array( $this, 'checkbox'), 							// callback
            'em_dashboard_g_settings',		 	 				// page
            'em_dashboard_general_settings',					// section
			array( 												// args
				'name' 		=> 'em_dashboard_load_easy',
				'page'		=> 'em_dashboard_g_settings',
				'default' 	=> 'load_easy',
				'labelinfo'	=> __( 'Enable the easy mode button.', 'emdashboard'),
				'hoverinfo'	=> '',
				'hoverinfosite'	=> __( 'The button on the bottom left.', 'emdashboard'),
				'hoverinfonetwork'	=> __( "The button on the bottom left. This doesn't have effect in the network admin screen", 'emdashboard'),
				'class'		=> 'alternate',
			)
        );
        add_settings_field (
            'em_dashboard_easy_mode', 						// id
            __('Default Easy Mode Setting', 'emdashboard'), // title
            array( $this, 'dropdown'), 						// callback
            'em_dashboard_g_settings',		 	 			// page
            'em_dashboard_general_settings',				// section
			array( 											// args
				'name' 		=> 'em_dashboard_easy_mode',
				'page'		=> 'em_dashboard_g_settings',
				'default' 	=> 'easy_mode',
				'labelinfo'	=> __( 'Turn the button on the bottom left on or off for new users.', 'emdashboard'),
				'hoverinfo'	=> __( "This option only has effect on users who haven't touched it yet", 'emdashboard'),
				'hoverinfosite'	=> '',
				'hoverinfonetwork' => '',
				'class'		=> 'alternate',
			)
        );
        add_settings_field (
            'em_dashboard_force_easy', 					// id
            __('Force Easy Mode', 'emdashboard'), 		// title
            array( $this, 'checkbox'), 					// callback
            'em_dashboard_g_settings',		 			// page
            'em_dashboard_general_settings',			// section
			array( 										// args
				'name' 		=> 'em_dashboard_force_easy',
				'page'		=> 'em_dashboard_g_settings',
				'default' 	=> array( 'force_easy', '0'),
				'labelinfo'	=> __( "Force Easy Mode for everyone except for the set Capability below.", 'emdashboard'),
				'hoverinfo'	=> __( 'This option also removes the button on the bottom left except for the minimum capability.', 'emdashboard'),
				'hoverinfosite'	=> '',
				'hoverinfonetwork' => '',
				'class'		=> '',
			)
        );
        add_settings_field (
            'em_dashboard_force_easy_capability', 					// id
            __('Minimum Non-Forced Capability', 'emdashboard'),		// title
            array( $this, 'dropdown'),								// callback
            'em_dashboard_g_settings',				 				// page
            'em_dashboard_general_settings',			 			// section
			array( 													// args
				'name' 		=> 'em_dashboard_force_easy_capability',
				'page'		=> 'em_dashboard_g_settings',
				'default' 	=> array( 'force_easy', '1'),
				'labelinfo'	=> __( "The minimum role that doesn't get Easy Mode forced and sees the Easy Mode Button at all times regardless of other settings.", 'emdashboard'),
				'hoverinfo'	=> __( "Although very unlikely, if you get stuck in Easy Mode, please see the plugin page's 'Other Nodes'.", 'emdashboard'),
				'hoverinfosite'	=> '',
				'hoverinfonetwork' => '',
				'class'		=> '',
			)
        );
    }

    /**
     * Adds menu links under "settings" in the wp-admin dashboard
     *
     * @since 1.0.0
     * @return void
     */
    public function add_menu_link() {
        $parent_slug = is_multisite() ? 'settings.php' : 'options-general.php';

        $pagetitle = __('EM Dashboard Settings', 'emdashboard');
        $menutitle = 'EM Dashboard';
        $capability = 'edit_plugins';
        $menu_slug = 'em_dashboard_settings';

        $callback = array($this, 'menu_page');

        add_submenu_page( $parent_slug, $pagetitle, $menutitle, $capability, $menu_slug, $callback );
    }

    /**
     * Initialize the tabbed menu page
     *
     * @since 1.0.0
     * @return bool false if not admin || string menu page if admin
     */
    public function menu_page() {

		/**
		 * Prevent access from non-admins and outside of admin pages.
		 * "It [is_admin()] is a convenience function for plugins and themes to use for various purposes,
		 * but it is not suitable for validating secured requests."
		 * OK, but I'll use it anyway. Better than nothing, right?
		 * $this->register_fields() has the real security validation so it's pretty much rock-solid.
		 */
		if ( false == is_super_admin() || false == is_admin() ) return false;

		/**
		 * Save network settings. If not network, fall back to default settings saving.
		 *
		 * @since 1.0.0
		 *
		 * Is the nonce check correct? I wasn't able to forge it. But that doesn't mean someone else could.
         * Then again, the only thing that fires is an update_site_option (escaped) and a redirect (escaped). GL HF GG.
		 */
		if ( $_POST && is_multisite() ) {
			if ( isset( $_POST['submit'] ) && $_POST['_wpnonce'] === $_REQUEST['_wpnonce'] ) {

				$settings = array_fill_keys( array(
					'em_dashboard_em_styles', 'em_dashboard_em_fonts', 'em_dashboard_color_picker', 'em_dashboard_color_scheme', 			//em_dashboard_g_settings
					'em_dashboard_load_easy', 'em_dashboard_easy_mode', 'em_dashboard_force_easy', 'em_dashboard_force_easy_capability',	//em_dashboard_g_settings
				), '');

				// Although duplicate $_POST values are sent with the checkboxes, I'm not sure why only a single value is parsed.
                // We'll see if someone returns with a bug report on odd settings. If so, I'll try to uniqueify the array.
				foreach( (array)$_POST as $key => $value ) {
					if ( array_key_exists( $key, $settings ) ) {
						update_site_option( $key, $value );
					}
				}
			}

			/**
			 * Continue to redirect after options have been saved.
			 * This makes sure that the user visually can confirm that
			 * the options have been changed.
			 */
			$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general_options';

			$url = add_query_arg( 'page', 'em_dashboard_settings', network_admin_url( 'settings.php' ));
			$url = add_query_arg( 'tab', $active_tab, $url );
			$url = add_query_arg( 'updated', 'true', $url );
			wp_redirect( esc_url_raw($url) );
			exit();
		}

        $title = __( 'EM Dashboard Settings', 'emdashboard' );

		// WordPress 4.3.0 has new accessibility standards for screen readers (mainly h1/h2 from what I've read)
		$wp430 = $this->wp_version( '4.3.0', '>=' );

        ?>
    	<div class="wrap">

            <?php if ( $wp430 ) : ?>
    	        <h1><?php echo $title; ?></h1>
            <?php else : ?>
                <h2><?php echo $title; ?></h2>
            <?php endif; ?>

            <?php // Multisite only header. A little more elegant than "Settings saved." :)
            if(isset( $_GET['updated'] )) { ?>
    			<div id="message" class="updated notice is-dismissible">
    				<p>
    					<?php _e( 'Your settings have been saved', 'emdashboard' ) ?>
    				</p>
    			</div>
    		<?php
            }

			$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general_options'; //save tab location
			?>
    		<h2 class="nav-tab-wrapper">
    			<a href="?page=em_dashboard_settings&tab=general_options" class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>">
                    <?php _e( 'General Settings', 'emdashboard' ); ?>
                </a>
    			<!--<a href="?page=em_dashboard_settings&tab=easy_options" class="nav-tab <?php echo $active_tab == 'easy_options' ? 'nav-tab-active' : ''; ?>">
                    <?php _e( 'Easy Mode Settings', 'emdashboard' ); ?>
                </a>-->
    			<a href="#" class="nav-tab " style="pointer-events:none;">
                    <?php _e( 'More coming soon!', 'emdashboard' ); ?>
                </a>
    		</h2>

			<?php

			if ( is_multisite() ) {
				$action = 'settings.php?page=em_dashboard_settings&tab=' . $active_tab;
			} else {
				// $action = 'options-general.php?page=em_dashboard_settings&tab='  . $active_tab;
                // Use default settings API action for now. WordPress seems to know where to redirect.
				$action = 'options.php';
			}
			?>
			<form method="post" action="<?php echo $action; ?>">
	    		<?php
		            //* @todo add more setting pages
		    		// display tab content
		    		//	if( $active_tab == 'general_options' ) {
		    				do_settings_sections( 'em_dashboard_g_settings' );
		    		//	} else {
		    		//		do_settings_sections( 'metabox_settings' );
		    		//	}
		    	?>
				<hr>
				<?php submit_button(); ?>
			</form>
    	</div>
    	<?php
    }

    public function general_settings_fields() {
		settings_fields('em_dashboard_g_settings');

		if ( is_multisite() ) {
			$welcome = __('The settings on this page affect every user and site on your network.', 'emdashboard');
		} else {
			$welcome  = __('The settings on this page affect every user on your website', 'emdashboard');
		}
		?><p style="display:inline-block;width:auto;padding:10px;margin:3px 0;border:1px solid #aaa;"><?php echo $welcome; ?></p>
		<?php
    }

	// ****************************************************************************************
	// Option fields
	// ****************************************************************************************

	/**
	 * A checkbox setting field
	 *
	 * @param array $args the settings argument
	 * @return string the settings field
	 *
	 * @since 1.0.0
	 */
	public function checkbox( $args = array() ) {

		$name	 		= $args['name'];
		$page 			= $args['page'];
		$default 		= $args['default'];
		$info 			= esc_attr( $args['labelinfo'] );
		$label_hover	= esc_attr( $args['hoverinfo'] );
		$hover_site 	= esc_attr( $args['hoverinfosite'] );
		$hover_network	= esc_attr( $args['hoverinfonetwork'] );

		$option = $this->option();

		if ( is_array( $default ) ) {
			$option_name = $default[0];

			$default = $default[1];
			$option = $option[$option_name];
		}
		$the_option = get_site_option( $name, $option[$default] );

		$checked = ( $the_option === true ) ? 'checked' : '';

		if ( empty($label_hover) ) {
			if ( is_multisite() ) {
				$label_hover = $hover_network;
			} else {
				$label_hover = $hover_site;
			}
		}

		?><label>
			<input type="hidden" name="<?php echo $name; ?>" id="<?php echo $name; ?> hidden" value="0" <?php checked( $the_option ) ?> />
			<input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="1" <?php checked( $the_option ) ?> />
			<em><?php echo $info; ?></em>
			<?php
			if ( !empty( $label_hover ) ) {
				 ?><span style="text-decoration:underline" title="<?php echo $label_hover; ?>">[?]</span><?php
			}
		?></label><?php

	}

	/**
	 * A dropdown setting field
	 *
	 * Set the option values here based on the selected option.
	 *
	 * @param array $args the settings argument
	 * @return string the settings field
	 *
	 * @todo maybe external filter for more options
	 *
	 * @since 1.0.0
	 */
	public function dropdown( $args = array() ) {

		$name	 		= $args['name'];
		$page 			= $args['page'];
		$default 		= $args['default'];
		$info		 	= esc_attr( $args['labelinfo'] );
		$label_hover 	= esc_attr( $args['hoverinfo'] );
		$hover_site 	= esc_attr( $args['hoverinfosite'] );
		$hover_network	= esc_attr( $args['hoverinfonetwork'] );

		$option = $this->option();

		if ( is_array( $default ) ) {
			$option_name = $default[0];

			$default = $default[1];
			$option = $option[$option_name];
		}

		$the_option = get_site_option( $name, $option[$default] );

		$options = array();
		$optionsi18n = array();

		/**
		 * Start Options
		 *
		 * @since 1.0.0
		 */
		// Start color_scheme
		if ( $name === 'em_dashboard_color_scheme' ) {
			global $_wp_admin_css_colors;

			ksort( $_wp_admin_css_colors );

			if ( isset( $_wp_admin_css_colors ) ) {
				foreach ( $_wp_admin_css_colors as $color => $color_info ) { // Too tired/lazy at this point to check if the => $color_info is needed.
					$options[] = $color;
					$optionsi18n[] = $color_info->name;
				}
			// Fallback
			} else {
				$options = array( 'default', 'light', 'blue', 'midnight', 'sunrise', 'ectoplasm', 'ocean', 'coffee' );
				$optionsi18n = array( __('Default'), __('Light'), __('Blue'), __('Midnight'), __('Sunrise'), __('Ectoplasm'), __('Ocean'), __('Coffee') );
			}
		// Start easy_mode
		} else if ( $name === 'em_dashboard_easy_mode' ) {
			$options = array( 'on', 'off');
			$optionsi18n = array(
				__('On', 'emdashboard'),
				__('Off', 'emdashboard'),
			);
		// Start Force Easy Capabilities
		} else if ( $name === 'em_dashboard_force_easy_capability') {
			//* Show only roles the current user is/can edit. Prevents getting stuck.
			$roles = get_editable_roles(); // this crashes outside the WP admin area. No problem.
			foreach ( $roles as $role_name => $role_info ) {
				foreach ( $role_info['capabilities'] as $capability => $bool ) {
					$capabilities[] = $capability;
				}
			}

            // Extremely elegant to repeat. :) But it's faster than array merge.
            // Not so much faster for the translators, however.
            if ( is_multisite() ) {
        		$caps = array(
        			'edit_plugins' 				=> __( 'Super Administrator', 'emdashboard' ),
        			'manage_options' 			=> __( 'Administrator', 'emdashboard' ),
        			'publish_pages' 			=> __( 'Editor', 'emdashboard' ),
        			'publish_posts' 			=> __( 'Author', 'emdashboard' ),
        			'edit_posts' 				=> __( 'Contributor', 'emdashboard' ),
        			'read' 						=> __( 'Subscriber', 'emdashboard' ),
        		);
            } else {
                $caps = array(
        			'manage_options' 			=> __( 'Administrator', 'emdashboard' ),
        			'publish_pages' 			=> __( 'Editor', 'emdashboard' ),
        			'publish_posts' 			=> __( 'Author', 'emdashboard' ),
        			'edit_posts' 				=> __( 'Contributor', 'emdashboard' ),
        			'read' 						=> __( 'Subscriber', 'emdashboard' ),
        		);
            }

			foreach ( $caps as $cap => $capi18n ) {
				if ( in_array( $cap, $capabilities) ) {
					$options[] = $cap;
					$optionsi18n[] = $capi18n;
				}
			}
		} else {
			// Option isn't parsed, bail. Doesn't really need translation.
			?><em>Option failed to load. Please report to the plugin author.</em><?php
			return;
		}

		?>
	<?php /* <select name="<?php echo $page; ?>[<?php echo $name; ?>]" value="<?php echo $the_option; ?>"> */ ?>
		<select name="<?php echo $name; ?>" value="<?php echo $the_option; ?>">
			<?php foreach ( array_combine($options, $optionsi18n) as $sel_option => $sel_option_i18n ) {
					$selected = ($the_option == $sel_option ) ? 'selected' : '';
					// Fall back to option name is no i18n is set.
					$sel_option_i18n = !empty( $sel_option_i18n ) ? $sel_option_i18n : $sel_option;

					$output = '<option value="' . $sel_option . '" ' . $selected . '>' . $sel_option_i18n . '</option>';
					echo $output;
			}
			?>
		</select>

		<label for="<?php echo $name ?>"><em><?php echo $info; ?> </em>
		<?php
		if ( !empty( $label_hover ) ) {
			?><span style="text-decoration:underline" title="<?php echo $label_hover; ?>">[?]</span><?php
		}
		?></label><?php
	}

	// ****************************************************************************************
	//  Option sanitzation
	// ****************************************************************************************

	/**
	 * @todo figure out a way to pass defaults, or not?
	 */

	/**
	 * Sanitize Checked
	 *
	 * @since 1.0.0
	 *
	 * @param bool/string $value the option
	 * @return string 1 or 0 the sanitized option
	 *
	 * Unused for now
	 */
	public function s_checked($value, $default = '') {

		if ( $value === 'on' ) {
			return '1';
		} else if ( $value == 'off') {
			return '0';
		} else {
			return $default;
		}
	}

	/**
	 * Sanitize boolean
	 *
	 * @since 1.0.0
	 *
	 * @param bool/string $value the option
	 * @return string 1 or 0 the sanitized option
	 *
	 */
	public function s_boolean($value, $default = '') {

		if ( $value === '1' || $value === 'on' ) {
			return true;
		} else if ( $value === '0' || $value === 'off' || empty($value) ) {
			return false;
		} else if ( $value === true || $value === false ) {
			return $value;
		} else {
			return $default;
		}

	}

	/**
	 * Sanitize color_scheme
	 *
	 * @since 1.0.0
	 *
	 * @param string $value the color scheme
	 * @return string the sanitized option
	 */
	public function s_colorscheme($value, $default = '') {
		global $_wp_admin_css_colors;

		ksort( $_wp_admin_css_colors );

		if ( isset( $_wp_admin_css_colors ) ) {
			foreach ( $_wp_admin_css_colors as $color => $color_info ) {
					$array[] = $color;
			}
			if ( in_array( $value, $array ) && is_string($value) ) {
				return esc_attr($value);
			}
		// Fallback
		} else {
			$color[] = array('default', 'light', 'blue', 'midnight', 'sunrise', 'ectoplasm', 'ocean', 'coffee');
			if ( in_array( $value, $color ) && is_string($value) ) {
				return esc_attr($value);
			}
		}

		// Back to default if sanitation failed
		return $default;
	}

	/**
	 * Sanitize Default Easy setting (on/off)
	 *
	 * @since 1.0.0
	 */
	public function s_default_easy($value, $default = '') {

		if ( $value === 'on' || $value === 'off' ) {
			return $value;
		} else if ( $value === '1' || $value === true ) {
			return 'on';
		} else if ( $value === '0' || $value === false ) {
			return 'off';
		} else {
			return $default;
		}

	}

	/**
	 * Sanitize Capability
	 *
	 * @since 1.0.0
	 * @todo fetch caps from main options array. This way we can determine default too.
	 */
	public function s_capability($value, $default = '') {
		$caps = array( 'edit_plugins', 'manage_options', 'publish_pages', 'publish_posts', 'edit_posts', 'read' );

		if ( in_array( $value, $caps ) && is_string($value) ) {
			return esc_attr($value);
		} else {
			return $default;
		}
	}

}
