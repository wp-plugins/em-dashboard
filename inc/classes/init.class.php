<?php
/**
 * Class EM_Dashboard_Init
 *
 * Initializes the plugin
 * Adds Easy Mode Button
 * Determines Easy mode activation
 *
 * @since 1.0.0
 */
class EM_Dashboard_Init {

	/**
	 * Constructor. Init actions and options.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'user_register', array( &$this, 'register_color_scheme' ) ); // @todo find out a way to change it for everyone, without permanent damage???
		add_action( 'admin_init', array( &$this, 'color_scheme_selection' ) );

		// Fires after the admin menu, will add item after "Collapse Menu"
		add_action( 'adminmenu', array( $this, 'easy_mode_button' ) );

		add_action( 'admin_menu', array( &$this, 'easy_mode_active_menu' ), 9999 );
		add_action( 'wp_dashboard_setup', array( &$this, 'easy_mode_active_dashboard' ) );
		add_action( 'admin_menu', array( &$this, 'easy_mode_active_link' ) );
		add_action( 'add_meta_boxes', array( &$this, 'easy_mode_active_post' ) );

		// Removal of plugin boxes, should be more elegant? e.g. add extra array field
		add_action( 'admin_init', array( &$this, 'easy_mode_active_plugin_post' ), 9999 );
		add_action( 'add_meta_boxes', array( &$this, 'easy_mode_active_plugin_post' ), 9999 );
		add_action( 'admin_init', array( &$this, 'easy_mode_active_plugin_dashboard' ), 9999 );
		add_action( 'add_meta_boxes', array( &$this, 'easy_mode_active_plugin_dashboard' ), 9999 );
		add_action( 'admin_init', array( &$this, 'easy_mode_active_plugin_metabox_advanced' ), 9999 );
		add_action( 'add_meta_boxes', array( &$this, 'easy_mode_active_plugin_metabox_advanced' ), 9999 );

		add_action( 'plugins_loaded', array( $this, 'easy_mode_switch' ) );

		add_filter( 'query_vars', array( $this, 'allow_query_vars' ) );
	}

	/**
	 * Determines if WP is above or below a version
	 *
	 * @since 1.0.0
	 *
	 * @return bool wp version is "compare" to
	 */
	protected function wp_version( $version = '4.0.0', $compare = '>=' ) {
		global $wp_version;

		if ( version_compare( $wp_version, $version, $compare ) )
			return true;

		return false;
	}

	/**
	 * Determines if the user option is on or off.
	 *
	 * @since 1.0.0
	 *
	 * @return bool easy mode on
	 */
	public function easy_mode_on( $easy = array() ) {

		$user_id = get_current_user_id();

		$easy = get_user_meta( $user_id, 'em_dashboard_easy_mode', true );

		if ( empty( $easy ) ) {
			$option = $this->option();
			$easy = $option['easy_mode'];
		}

		if ( $easy === 'off' ) {
			return false;
		} else {
			return true;
		}

	}

	/**
	 * Adds easy-mode to the allowed query args
	 *
	 * @since 1.0.0
	 *
	 * @param array $vars the allowed query args
	 */
	public function allow_query_vars( $vars ){
		$vars[] = "easy-mode";
		return $vars;
	}

	/**
	 * Parse, sanitize and save the user option
	 *
	 * @since 1.0.0
	 */
	public function easy_mode_switch() {

		$user_id = get_current_user_id();
		$option_name = 'em_dashboard_easy_mode';

		/**
		 * get_query_var isn't allowed at this point. Needs init.
		 *
		 * To give a suitable experience e.g. direct settings change we
		 * want to bind the change to get_header and update the values before
		 * init is called.
		 *
		 * I'm not sure how this is affected security wise, the $_GET value
		 * will be parsed correctly and is sanitized so only accepts on or off.
		 *
		 * //	$query = get_query_var( 'easy-mode', false )
		 */
		$query = !empty( $_GET["easy-mode"] ) ? $_GET["easy-mode"] : false;

		if ( $query !== false ) {
			if ( $query === 'on' ) {
				$newvalue = 'on';
				update_user_meta( $user_id, $option_name, $newvalue, '' );
			} else if ( $query === 'off' ) {
				$newvalue = 'off';
				update_user_meta( $user_id, $option_name, $newvalue, '' );
			}
		}
	}

	/**
	 * Determines if the easy mode is forced
	 *
	 * @since 1.0.0
	 *
	 * @param bool $forced
	 * @param string $capability minimum capability ( default: edit_plugins );
	 *
	 * @param bool rescue used if the user is stuck to gain access back again to the dashboard
	 *
	 * @return bool forced
	 */
	public function forced() {
		$option = $this->option();

		$force_default = $option['force_easy'];

		$forced 	= get_site_option( 'em_dashboard_force_easy', $force_default[0]);
		$capability = get_site_option( 'em_dashboard_force_easy_capability', $force_default[1]);

		/**
		 * Stuck? Add the following filter to your theme's functions.php
		 * (or in a mu-plugin).
		 *
		 * add_filter( 'em_dashboard_forced_rescue', '__return_true');
 		*/
		$rescue = apply_filters( 'em_dashboard_forced_rescue', '__return_false' );

		if ( $rescue ) {
			return false;
		}

		if ( $forced && !current_user_can( $capability ) ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Sets default theme
	 *
	 * I chose midnight as default since some plugins set the icon color to white at that theme.
	 * This does force an extra style sheet (colors.css) to be loaded. Which adds uneccesary load.
	 *
	 * I still need to figure out what to do with this. Or rather, force the user's color scheme to default
	 * if the user has em_styles active?
	 *
	 * Yup, let's go with that.
	 *
	 * I also chose Charmander.
	 *
	 * @since 1.0.0
	 */
	public function register_color_scheme() {
		global $user_id;

		$option = $this->option();

		$color_scheme = get_site_option( 'em_dashboard_color_scheme', $option['color_scheme'] );

		$args = array(
			'ID' => $user_id,
			'admin_color' => $color_scheme,
		);

		wp_update_user( $args );
	}

	/**
	 * Removes the color switcher
	 *
	 * @since 1.0.0
	 */
	public function color_scheme_selection() {

		$option = $this->option();

		$em_styles = get_site_option( 'em_dashboard_em_styles', $option['em_styles'] );
		$color_picker = get_site_option( 'em_dashboard_color_picker', $option['color_picker'] );

		// Edit users capability is only available to Super Admins on mutltisite
		// Or Admins on single site
		if ( ( !current_user_can('edit_users') && $em_styles ) || ! $color_picker ) {
			remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker', 10 );
		}

	}

	/**
	 * Initializes the admin option button
	 *
	 * @since 1.0.0
	 */
	public function easy_mode_button() {

		$option = $this->option();

		$load_easy = get_site_option( 'em_dashboard_load_easy', $option['load_easy'] );
		$forced = $this->forced();

		/**
		 * Shows button if easy mode is loaded
		 * Also, it shows for the minimum capability regardless of forced setting
		 */
		if ( $load_easy && !$forced ) {

			$easy_activate = esc_html__( 'Easy mode', 'emdashboard' );

			$easy_on = $this->easy_mode_on();
			$on_off = $easy_on ? 'on' : 'off';
			$checked = $easy_on ? ' checked="checked"' : '';
			$value = $easy_on ? '1' : '0';

			// Seperator
			echo '<li class="wp-not-current-submenu wp-menu-separator" id="separator" aria-hidden="true"><div class="separator"></div></li>';

			// JS: Ajax
			// The ajax function needs some different implemenetation, not only needs it to set the user option (for future requests), but it also needs to (jQuery.hide()) stuff.
			// How I'm going to do this is still in discussion (with myself) as I don't want to "mess up" other plugins.
	/*		$contentAjax = '<span>' . '<label for="easy-switch">' . $easy_activate . '<input name="easy-switch[easy_mode]" id="easy-switch" type="checkbox" value="' . $value . '"' . $checked . ' /><span></span></label>' . '</span>';

			echo '<li id="easy-mode-ajax" class="hide-if-no-js"><div id="easy-activate"><div></div></div>';
			echo $contentAjax;
			echo '</li>';
	*/
			// No JS
			if ( is_ssl() ) {
				$scheme = 'https://';
				$protocol = 'https';
			} else {
				$scheme = 'http://';
				$protocol = 'http';
			}

			//* Reversed as its readable in the URL
			$qa_on_off = $easy_on ? 'off' : 'on';

			$url = $scheme . "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$url = add_query_arg( array ( "easy-mode" => $qa_on_off ), $url);
			$url = esc_url_raw ( $url, $protocol );

			$contentPlain = '<span>' . '<span id="easy-switch" class="' . $on_off . '" target="_self">' . $easy_activate . '<span></span></span>' . '</span>';
			//@TODO change class back to hide-if-js when ajax button is present
			echo '<li id="easy-mode-reload" class="wp-not-current-submenu menu-top menu-top-last toplevel_page_easymode">';
			echo '<div>'; // has been added to prevent jQuery bugs (related to "current" page because the URL has the same url as current page.).
			echo '<a href="' . $url . '" target="_self" id="easy-switch-no-js"><div id="easy-activate"><div></div></div>'; // yup, block inside inline. oops? WP does it too. I blame Matt.
			echo $contentPlain;
			echo '</a>';
			echo '</div>'; // has been added to prevent jQeury bugs
			echo '</li>';
		}
	}

	/**
	 * Runs when Easy Mode is active
	 * Removes menus to ease up wp-admin
	 *
	 * @since 1.0.0
	 */
	public function easy_mode_active_menu() {

		$easy_mode = $this->easy_mode_on();
		$forced = $this->forced();

		if ( $easy_mode || $forced ) {
			global $submenu,$menu;

			$option = $this->option();

			/**
			 * Start removal of menu's
			 *
			 * @since 1.0.0
			 */
			$easy_pages 	= get_site_option( 'em_dashboard_nodes_allowed', $option['easy_pages'] );
			// @todo
		//	$easy_sub_pages = get_site_option( 'em_dashboard_allowed_nodes', $option['easy_sub_pages'] );

			$easy_del 		= get_site_option( 'em_dashboard_nodes_delete', $option['easy_pages_del'] );
			$easy_sub_del 	= get_site_option( 'em_dashboard_nodes_sub_delete', $option['easy_sub_pages_del'] );

			//* @ todo array split / merge / something /delete with the allowed thingies

			if ( ! empty($menu) ) {
				if ( is_array( $menu ) ) {
					foreach ($menu as $menukey => $item) {
						if ( !in_array( $item[2], $easy_pages ) ) {
							remove_menu_page( $item[2] );
					//		print_r ( $item[2] ); echo "\r\n";
						}
					}
				}
				if ( is_array( $easy_del ) ) {
					foreach ( $easy_del as $slug ) {
						remove_menu_page( $slug );
					}
				}

				if ( is_array($easy_sub_del ) ) {
					foreach ( $easy_sub_del as $sub ) {
						$menu_slug 	  = $sub[0];
						$submenu_slug = $sub[1];

						remove_submenu_page( $menu_slug, $submenu_slug );
					}
				}
			}
		}
	}

	/**
	 * Runs when Easy Mode is active
	 * Removes menus to ease up the dashboard page
	 *
	 * @since 1.0.0
	 */
	public function easy_mode_active_dashboard() {

		$easy_mode = $this->easy_mode_on();
		$forced = $this->forced();

		if ( $easy_mode || $forced ) {

			$option = $this->option();

			/**
			 * Start removal of meta boxes
			 *
			 * @since 1.0.0
			 */
			$easy_metabox_del = get_site_option( 'em_dashboard_nodes_meta_dashboard', $option['easy_metabox_del_dashboard'] );

			foreach ( $easy_metabox_del as $box ) {
				$id		 = $box[0];
				$page 	 = $box[1];
				$context = $box[2];

				if ( is_array( $page ) ) {
					if ( $page == 'dashboard') {
						remove_meta_box( $id, $page, $context );
					}
				}
			}
		}
	}

	/**
	 * Runs when Easy Mode is active
	 * Removes menus to ease up the link page
	 *
	 * @since 1.0.0
	 */
	public function easy_mode_active_link() {

		$easy_mode = $this->easy_mode_on();
		$forced = $this->forced();

		if ( $easy_mode || $forced ) {

			$option = $this->option();

			/**
			 * Start removal of meta boxes
			 *
			 * @since 1.0.0
			 */
			$easy_metabox_del = get_site_option( 'em_dashboard_nodes_meta_link', $option['easy_metabox_del_link'] );

			if ( is_array( $easy_metabox_del ) ) {
				foreach ( $easy_metabox_del as $box ) {
					$id		 = $box[0];
					$page 	 = $box[1];
					$context = $box[2];

					remove_meta_box( $id, $page, $context );
				}
			}
		}
	}

	/**
	 * Runs when Easy Mode is active
	 * Removes post meta boxes to ease up the edit pages
	 *
	 * @since 1.0.0
	 */
	public function easy_mode_active_post() {

		$easy_mode = $this->easy_mode_on();
		$forced = $this->forced();

		if ( $easy_mode || $forced ) {

			$option = $this->option();

			/**
			 * Start removal of meta boxes
			 *
			 * @since 1.0.0
			 */
			$easy_metabox_del = get_site_option( 'em_dashboard_nodes_meta_post', $option['easy_metabox_del_post'] );

			foreach ( $easy_metabox_del as $box ) {
				$id		 = $box[0];
				$page 	 = $box[1];
				$context = $box[2];

				// Debugging
				/*
				echo "id: $id \r\n";
				echo "page: $page \r\n";
				echo "context: $context \r\n";
				*/

				if ( empty( $page ) ) {
					$page = array('page', 'post');
				}

				if ( is_array( $page ) ) {
					foreach ( $page as $p ) {
						remove_meta_box( $id, $p, $context );
					}
				}
			}
		}
	}

	/**
	 * Runs when Easy Mode is active
	 * Removes plugin meta boxes to ease up the post pages
	 *
	 * @since 1.0.0
	 */
	public function easy_mode_active_plugin_post() {

		$easy_mode = $this->easy_mode_on();
		$forced = $this->forced();

		if ( $easy_mode || $forced ) {

			$option = $this->option();

			/**
			 * Start removal of meta boxes
			 *
			 * @since 1.0.0
			 */
			$easy_metabox_del = get_site_option( 'em_dashboard_nodes_meta_dashboard', $option['easy_metabox_del_plugin'] );

			foreach ( $easy_metabox_del as $box ) {
				$id		 = $box[0];
				$page 	 = $box[1];
				$context = $box[2];
				$plugin  = $box[3];

				if ( empty( $page ) ) {
					$page = array('page', 'post');
				}

				if ( is_array( $page ) ) {
					foreach ( $page as $p ) {
						if ( $plugin === '1' && ( $p == 'page' || $p == 'post' ) ) {
							remove_meta_box( $id, $p, $context );
						}
					}
				}
			}
		}
	}

	/**
	 * Runs when Easy Mode is active
	 * Removes plugin metaboxes to ease up the dashboard
	 *
	 * @since 1.0.0
	 */
	public function easy_mode_active_plugin_dashboard() {

		$easy_mode = $this->easy_mode_on();
		$forced = $this->forced();

		if ( $easy_mode || $forced ) {

			$option = $this->option();

			/**
			 * Start removal of meta boxes
			 *
			 * @since 1.0.0
			 */
			$easy_metabox_del = get_site_option( 'em_dashboard_nodes_meta_dashboard', $option['easy_metabox_del_plugin'] );

			if ( is_array( $easy_metabox_del ) ) {
				foreach ( $easy_metabox_del as $box ) {
					$id		 = $box[0];
					$page 	 = $box[1];
					$context = $box[2];
					$plugin  = $box[3];

					if ( $plugin === '1' && $page == 'dashboard') {
						remove_meta_box( $id, $page, $context );
					}
				}
			}
		}
	}

	/**
	 * Runs when Easy Mode is active
	 * Removes advanced meta boxes. Advanced is the default setting and is mostly only added by plugins.
	 *
	 * I don't know many advanced boxes.
	 * @requires testing
	 *
	 * @since 1.0.0
	 */
	public function easy_mode_active_plugin_metabox_advanced() {
		$easy_mode = $this->easy_mode_on();
		$forced = $this->forced();

		if ( $easy_mode || $forced ) {
			global $wp_meta_boxes;

			foreach ( $wp_meta_boxes as $page => $array_1 ) {

				// Continue only on default pages
				if ( $page == 'page' || $page == 'post' || $page == 'dashboard' || $page == 'link' ) {
					foreach ( $array_1 as $context => $array_2 ) {

						// Continue only if advanced
						if ( $context == 'advanced') {
							foreach ( $array_2 as $priority => $array_3 ) {
								foreach ( $array_3 as $box) {
									$id = $box["id"];
								//	$title = $box["title"];
								//	$callback = $box["callback"];
								//	$args = $box["args"];

									// Use unset instead of remove_meta_box?
									// Because of "callback", or use "callback" to remove the action call? <- but could break some WP installations?
									// Let's stick to remove_meta_box() for now.
									remove_meta_box( $id, $page, $context );
								}
							}
						}

					}
				}

			}
		}
	}

}
