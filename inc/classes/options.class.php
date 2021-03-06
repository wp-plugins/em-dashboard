<?php
/**
 * Class EM_Dashboard_Options
 *
 * Adds default options to the plugin
 * Sanitizes the default options
 *
 * @since 1.0.0
 */
class EM_Dashboard_Options extends EM_Dashboard_Styles {

	/**
	 * Settings array, providing defaults.
	 *
	 * @since 1.0.0
	 *
	 * @var array Holds dashboard settings
	 */
	protected $options = array();

    /**
     * Constructor, load parent constructor
     */
    public function __construct() {
        parent::__construct();

        //* Default settings
		$this->options = array(
			'em_styles'			=> false,		// Load the EM Dashboard styles
			'em_fonts'			=> true,		// Load the EM Dashboard enhanced fonts
			'color_scheme'		=> 'light', 	// Default WP register color scheme
			'color_picker'		=> true,		// Show the color picker in wp-admin/profile.php
			'load_easy'			=> true,		// Set easy mode to be active
			'easy_mode'			=> 'off',		// Easy mode default setting
			'force_easy'		=> array( '0' => false, '1' => 'manage_options' ), // Force easy mode true/false, unless capability
			'force_rescue'		=> false, 		// Used to override the force if user is stuck. Worked best a long time ago in a galaxy far, far away...
			'update_nag'		=> true, 		// @todo USE THIS. Notifies the users with 'update_plugins' capability that there's an update when EM is on. Transient?

			/**
			 * @plugin authors,
			 * Please do not preemptively manipulate this to allow your plugin to be shown regardless of the mode.
			 * It's meant to create a super simple overview of the very basics of WordPress
			 *
			 * i.e. Add new pages and posts and view the Dashboard
			 *
			 * The "easy mode" switch turns your option pages on and off.
			 * And is configurable by the plugin user.
			 *
			 * If I hear from a user that it doesn't remove a plugin's page/metabox, I'll add it to the delete list.
			 * This is almost always the case, caused by intentional input or not.
			 */

			//* Allowed top pages
			'easy_pages'		=> array(
				'index.php', 								// Dashboard
				'separator1',								// First separator
				'edit.php',									// Posts
				'edit.php?post_type=page', 					// Pages
				'upload.php',								// Media
			//	'edit-comments.php',						// Comments
			//	'themes.php',								// Appearance
			//	'edit-tags.php',							// Tags
			//	'edit-tags.php?taxonomy=link_category',		// Taxonomy
			//	'plugins.php',								// Plugins
			//	'users.php',								// Users
			//	'tools.php',								// Tools
			//	'options-general.php',						// Settings
				'gf_edit_forms',							// Gravity Forms
			//	'w3tc_dashboard',							// W3 Total Cache
			//	'shortcodes-ultimate',						// ShortCodes Ultimate
			//	'genesis',									// Genesis Settings
			),

			// Allowed Sub pages
			// @todo use this in favor over easy_sub_pages_del. It's used nowhere now.
			'easy_sub_pages'	=> array (
			),

			// Disallowed unregistered pages
			// @todo use this? This plugin is probably powerful enough already.
			'easy_pages_del' 	=> array (
			),

			// Disallowed submenu pages
			'easy_sub_pages_del' => array (
				array ( 'index.php', 'google-analytics-statistics' ), 		// WPMUdev Google Analytics +

				array ( 'edit.php', 'edit-tags.php?taxonomy=category' ), 	// Category Tags
				array ( 'edit.php', 'edit-tags.php?taxonomy=post_tag' ), 	// Post Tags
				array ( 'index.php', 'update-core.php' ),					// Update core

			//	array ( 'upload.php', 'media-new.php' ),					// Media: new

			//	array ( 'gf_edit_forms', 'gf_new_form' ),							// Gravity Forms New Form
			//	array ( 'gf_edit_forms', 'gf_entries' ), 							// Gravity Forms Entries
				array ( 'gf_edit_forms', 'gf_settings' ), 							// Gravity Forms Settings
				array ( 'gf_edit_forms', 'gf_export' ), 							// Gravity Forms Export
				array ( 'gf_edit_forms', 'gf_update' ), 							// Gravity Forms Updates
				array ( 'gf_edit_forms', 'gf_addons' ), 							// Gravity Forms Addons
				array ( 'gf_edit_forms', 'gf_help' ), 								// Gravity Forms Help
				array ( 'gf_edit_forms', 'gf_user_registration' ),  				// Gravity Forms User Registration plugin
				array ( 'gf_edit_forms', 'edit.php?post_type=pronamic_pay_gf' ),	// Gravity Forms iDeal plugin
			),

			/**
			 * Start meta boxes
			 * Leave "screen" (2nd array value) empty for any Dashboard Page,
			 * However, This isn't the case for 'easy_metabox_del_dashboard'.
			 */
			'easy_metabox_del_dashboard' => array (
			//	array ('dashboard_right_now', 'dashboard', 'normal'),   	// Right Now
				array ('dashboard_recent_comments', 'dashboard', 'normal'), // Recent Comments
				array ('dashboard_incoming_links', 'dashboard', 'normal'),  // Incoming Links
				array ('dashboard_plugins', 'dashboard', 'normal'),   		// Plugins

				array ('dashboard_quick_press', 'dashboard', 'side'),  		// Quick Press
				array ('dashboard_recent_drafts', 'dashboard', 'side'),  	// Recent Drafts
				array ('dashboard_primary', 'dashboard', 'side'),   			// WordPress blog
				array ('dashboard_secondary', 'dashboard', 'side'),   			// Other WordPress News
				// @todo:
				// Gravity Forms
				// Concept
				// Activity
			),

			'easy_metabox_del_link' => array (
			//	array ('linkcategorydiv', 'link', 'normal'),	// Link Categories
			//	array ('linktargetdiv', 'link', 'normal'),		// Link Target
				array ('linkxfndiv', 'link', 'normal'),			// Link Relationship
				array ('linkadvanceddiv', 'link', 'normal'),	// Link Advanced

			//	array ('linksubmitdiv', 'link', 'side'),		// Link Submit
			),

			'easy_metabox_del_post' => array (
				array ('revisionsdiv', array('post', 'page'), 'normal'),		// Revisions

			//	array ('submitdiv', array('post', 'page'), 'side'),				// Save/Publish

				array ('attachment-id3', array('post', 'page'), 'normal'),		// Audio Attachments

				array ('formatdiv', array('post', 'page'), 'side'),				// Post Formats

				array ('tagsdiv-post_tag', array('post', 'page'), 'side'),		// Post Tags

				array ('pageparentdiv', array('post', 'page'), 'side'),			// Page attributes
			//	array ('postimagediv', '', 'side'),								// Featured image
				array ('postexcerpt', array('post', 'page'), 'normal'),			// Post Excerpt
				array ('trackbacksdiv', array('post', 'page'), 'normal'),		// Trackbacks
				array ('postcustom', array('post', 'page'), 'normal'),			// Custom Post Types

				array ('commentstatusdiv', array('post', 'page'), 'normal'),	// Comments status (discussion)
				array ('commentsdiv', array('post', 'page'), 'normal'),			// Comments
				array ('slugdiv', array('post', 'page'), 'normal'),				// Page/post slug
			//	array ('authordiv', '', 'normal'),								// Post Author selection
			),


			'easy_metabox_del_plugin' => array (
				array ('genesis_inpost_scripts_box', '', 'normal' ), 			// Genesis scripts (loaded on almost every page)
				array ('genesis_inpost_layout_box', '', 'normal' ), 			// Genesis layout (loaded on almost every page)

				array ('google_analytics_dashboard', '', 'normal' ), 			// WPMUdev Google Analytics+ Dashboard + Page/Post box
			),
		);

    }

	/**
	 * Return the compiled options.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options The options
	 * @return array EM Dashboard options
	 */
	protected function get_option( $options = array() ) {

		/**
		 * Filter the EM Dashboard options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $options {
		 *      Arguments for EM Dashboard settings.
		 *
		 *		Contains some extremely advanced arrays. Which probably shouldn't be used.
		 *		@todo sanitize the default options. Make sure the e.g. "easy_sub_pages_del" array contains 3 values, no more, no less.
		 * }
		 */
		$this->options = apply_filters( 'the_em_dashboard_options', wp_parse_args( $options, $this->options ) );

		return $this->options;
	}

	/**
	 * Sanitize the options.
	 * Prevents wrong filters.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options The options
	 * @return array Sanitized the Em dashboard options
	 */
	protected function option( $options = array() ) {

		$this->options = wp_parse_args( $options, $this->get_option() );

		return $this->options;
	}
}
