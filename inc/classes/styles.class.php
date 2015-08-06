<?php
/**
 * Class EM_Dashboard_Styles
 *
 * Initializes the styles
 * Adds extra fonts
 * Adds new theme (overwrite)
 *
 * @since 1.0.0
 *
 * @todo use wp_admin_css_color instead (with admin_init)
 */
class EM_Dashboard_Styles extends EM_Dashboard_Init {

    /**
     * Constructor, load parent constructor
     */
    public function __construct() {
        parent::__construct();

        add_action( 'admin_enqueue_scripts', array( &$this, 'dashboard_styles' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'em_button_styles' ) );
    }

    /**
     * Loads EM Dashboard button styles
     *
     * @since 1.0.0
     *
     * @uses EM_ADMIN_BUTTON
     * @uses EM_PLUGIN_VER
     *
     * @dependencies dashicons-css
     * @return void
     */
    public function em_button_styles() {

        $option = $this->option();

		$load_easy = get_site_option( 'em_dashboard_load_easy', $option['load_easy'] );
		$forced = $this->forced();

        //* Load default plugable styles
        // Always load on HMPL
        if ( $load_easy && !$forced ) {
            wp_register_style( 'em-button', EM_ADMIN_BUTTON, array( 'dashicons' ), EM_PLUGIN_VER, 'all' );
            wp_enqueue_style( 'em-button' );
        }

    }

    /**
	 * Initialize EM-Dashboard flat space style
	 *
	 * @since 1.0.0
	 *
	 * @uses EM_ADMIN_STYLE
     * @uses USE_EM_DASHBOARD_THEME
	 * @uses EM_PLUGIN_VER
	 *
	 * @dependencies colors-css /wp-admin/css/colors/midnight/colors.min.css
	 * @translation WP_CORE wp_em_styles()
     *
     * @todo translations need testing, am I doing it right?
     *
     * @return void
	 */
	public function dashboard_styles() {

		$option = $this->option();

		$em_styles	= get_site_option( 'em_dashboard_em_styles', $option['em_styles'] );
		$em_fonts	= get_site_option( 'em_dashboard_em_fonts', $option['em_fonts'] );

		//* Load default plugable styles
		// Always load on HMPL
		if ( $em_styles || ( defined( 'USE_EM_DASHBOARD_THEME') && USE_EM_DASHBOARD_THEME ) || ( defined( 'IS_HMPL' ) && IS_HMPL ) ) {
			wp_register_style( 'em-dashboard', EM_ADMIN_STYLE, array( 'colors' ), EM_PLUGIN_VER, 'all' );
			wp_enqueue_style( 'em-dashboard' );

            wp_register_script( 'em-dashboard-js', EM_ADMIN_SCRIPT, array( 'jquery' ), EM_PLUGIN_VER, true );
            wp_enqueue_script( 'em-dashboard-js');
		}

		//* Load default fonts
		// Always load on HMPL
		if ( ( $em_styles && $em_fonts ) || ( defined( 'USE_EM_DASHBOARD_THEME') && USE_EM_DASHBOARD_THEME ) || ( defined( 'IS_HMPL' ) && IS_HMPL ) ) {
			wp_dequeue_style( 'open-sans' );
			wp_deregister_style( 'open-sans' );

			$fonts_url = '';

			/* translators: If there are characters in your language that are not supported
			 * by Open Sans & Merriweather, translate this to 'off'. Do not translate into your own language.
			 */
			if ( 'off' !== _x( 'on', 'Open Sans font: on or off' ) ) {
				$subsets = 'latin,latin-ext';

				/* translators: To add an additional Open Sans & Merriweather character subset specific to your language,
				 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
				 */
				$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)' );

				if ( 'cyrillic' == $subset ) {
					$subsets .= ',cyrillic,cyrillic-ext';
				} elseif ( 'greek' == $subset ) {
					$subsets .= ',greek,greek-ext';
				} elseif ( 'vietnamese' == $subset ) {
					$subsets .= ',vietnamese';
				}

				// Hotlink Open Sans & Merriweather, for now?
				$fonts_url = "//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,300,400,600|Merriweather:300,400,600&subset=$subsets";
			}

			//* Named "Open Sans" because of dependencies
			wp_register_style( 'open-sans', $fonts_url, array(), false, 'all' );
			wp_enqueue_style( 'open-sans' );
		}

	}
}
