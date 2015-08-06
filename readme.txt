=== EM Dashboard ===
Contributors: Cybr
Tags: easy, mode, dashboard, theme, menu, metabox, meta, box, layout, admin, superadmin, multisite, users
Requires at least: 3.8.0
Tested up to: 4.3.0
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

EM Dashboard adds an Easy Mode feature in your admin dashboard so you can work distraction free. It's super elegant for new users.

== Description ==

= Easy Mode Dashboard =

**Simplify your dashboard with an easy button**

This plugin allows you to:

* Clean up your dashboard with a single button
* Load a completely new theme
* Set default Color Scheme for new users
* Remove the Color Scheme selection
* Enable or disable the Easy Mode button
* Set the default Easy Mode setting for new users
* Force the Easy Mode setting
* Coming soon: Customize menu's and metaboxes

> <strong>About EM Dashboard</strong><br>
> This plugin allows you to control how the dashboard looks like.
>
> Simply, with an Easy Mode button.
> It removes all menu's except for the easiest ones.
> It also removes all meta boxes except for a few easy ones.
>
> Try it out, in the near future you can costumize which menu's/metaboxes you can view in Easy Mode :)

= Translating =

This plugin is fully translatable. If you wish to submit a translation, please contact me at the [CyberWire contact page](https://cyberwire.nl/contact/).

== Installation ==

1. Install EM Dashboard either via the WordPress.org plugin directory, or by uploading the files to your server.
1. Either Network Activate this plugin or activate it on a single site.
1. If you're on a MultiSite network, you can set up the default options for the whole network in your Network Settings menu.
1. If you're on a Single Site installation, you can set up the default options within the Settings menu.
1. That's it! Enjoy!

== Screenshots ==
1. The EM Dashboard Settings (as of 1.0.0). In MultiSite environments it's located under Network Settings
2. Easy Mode removes many meta (confusing) boxes and menu's
3. Experimental EM Dashboard Theme (as of 1.0.0)
4. Experimental EM Dashboard Theme with Easy Mode on (as of 1.0.0)

== Changelog ==

= 1.0.1 =
* Fixed the Merriweather font calling
* Changed default minimum forced mode to Administrator (only has effect on MultiSite)
* Changed access modifier for get_option
* Tested out the filters, they work! :)
* Added filters in the Other Notes tab for developers including rescue mode.
* Greatly improved (i.e. speed & security) removal of pages/metaboxes.
* Fixed link metabox removal
* Added update-core.php to the not-easy list.
* Cleaned up code

= 1.0.0 =
* Initial Release
* PHP5.2 to PHP7 compatible
* MultiSite and Single Site compatible.

== Other Notes ==

When Easy Mode is on, this plugin tries to automatically remove pages and meta boxes that aren't allowed in the options.
If you do find pages that aren't removed, add them to a filter as described below or report them to me in the Plugin Support pages and I'll make sure they will be removed :)

== Filters ==

***Turn rescue mode on. Handy when you're stuck in Force Mode.***
`add_filter( 'em_dashboard_forced_rescue', '__return_true' );`

***The filter below only changes the DEFAULT settings. Not touched settings (e.g. from the settings page after hitting the save button).***

*@Plugin authors, please do not modify the filter below in your public plugins. Unless absolutely nessecary for code quality. Let them be used in per-user installations.*

*NOTE: Most of these filters will be obsolete in an upcoming update.*

`add_filter( 'the_em_dashboard_options', 'my_em_dashboard_filter' );
function my_em_dashboard_filter( $args = array() ) {

	// Set the following argument to true to disable forced mode.
	// There's another specific filter for this as well described before this filter.
	$args['force_rescue'] = false;

	// Uncomment any line of the top page you want to allow when Easy Mode is active.
	$args['easy_pages'] = array(
		'index.php', 								// Dashboard
		'separator',								// First separator
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
	//	'gf_edit_forms',							// Gravity Forms
	//	'w3tc_dashboard',							// W3 Total Cache
	//	'shortcodes-ultimate',						// ShortCodes Ultimate
	//	'genesis',									// Genesis Settings
	);

	// These pages get removed regardless of allowed pages in Easy Mode.
	// Combine this with the easy_pages filter and forced mode to create a fully customized dashboard!
	// This does NOT prevent access from URL forgers to the pages. Forced or not.
	$args['easy_pages_del'] = array(
	//	'index.php', 								// Dashboard
	//	'separator',								// First separator
	//	'edit.php',									// Posts
	//	'edit.php?post_type=page', 					// Pages
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
		'w3tc_dashboard',							// W3 Total Cache
	//	'shortcodes-ultimate',						// ShortCodes Ultimate
	//	'genesis',									// Genesis Settings
	);

	// Same for subpages, a little more advanced with multidimensional arrays.
	// Top pages which are already removed will automatically remove sub-pages of the type.
	$args['easy_sub_pages_del'] = array(
		array ( 'index.php', 'google-analytics-statistics' ), 		// WPMUdev Google Analytics +

		array ( 'edit.php', 'edit-tags.php?taxonomy=category' ), 	// Post Categories
		array ( 'edit.php', 'edit-tags.php?taxonomy=post_tag' ), 	// Post Tags

	//	array ( 'upload.php', 'media-new.php' ),					// Media: new

		array ( 'gf_edit_forms', 'gf_new_form' ),					// Gravity Forms New Form
	//	array ( 'gf_edit_forms', 'gf_entries' ), 					// Gravity Forms Entries
		array ( 'gf_edit_forms', 'gf_settings' ), 					// Gravity Forms Settings
		array ( 'gf_edit_forms', 'gf_export' ), 					// Gravity Forms Export
		array ( 'gf_edit_forms', 'gf_update' ), 					// Gravity Forms Updates
		array ( 'gf_edit_forms', 'gf_addons' ), 					// Gravity Forms Addons
		array ( 'gf_edit_forms', 'gf_help' ), 						// Gravity Forms Help
		array ( 'gf_edit_forms', 'gf_user_registration' ),  		// Gravity Forms User Registration plugin
		array ( 'gf_edit_forms', 'edit.php?post_type=pronamic_pay_gf' ),	// Gravity Forms Pronamic iDeal plugin
	);

	// Meta boxes to delete, e.g.
	$args['easy_metabox_del_dashboard'] = array(
		array ('dashboard_recent_comments', 'dashboard', 'normal'), // Recent Comments
	);

	// Link page metaboxes, leave the second parameter empty so the plugin will determine its page automatically
	$args['easy_metabox_del_link'] = array(
		array ('linkcategorydiv', '', 'normal'),	// Link Categories
	);

	// Post page metaboxes.
	// Fill in post or page in the second parameter to only remove them from the page specified.
	// Leave empty and the plugin will remove them from all pages.
	$args['easy_metabox_del_post'] = array(
		array ('revisionsdiv', '', 'normal'),		// Revisions

	//	array ('submitdiv', '', 'side'),			// Save/Publish

		array ('attachment-id3', '', 'normal'),		// Audio Attachments

		array ('formatdiv', '', 'side'),			// Post Formats

		array ('tagsdiv-post_tag', '', 'side'),		// Post Tags

		array ('pageparentdiv', '', 'side'),		// Page attributes
		array ('postimagediv', '', 'side'),			// Featured image
		array ('postexcerpt', '', 'normal'),		// Post Excerpt
		array ('trackbacksdiv', '', 'normal'),		// Trackbacks
		array ('postcustom', '', 'normal'),			// Custom Post Types

		array ('commentstatusdiv', '', 'normal'),	// Comments status (discussion)
		array ('commentsdiv', '', 'normal'),		// Comments
		array ('slugdiv', '', 'normal'),			// Page/post slug
		array ('authordiv', '', 'normal'),			// Post Author selection
	);

	// Plugin metaboxes, not much different from easy_metabox
	// The second parameter now also accepts 'dashboard'
	$args['easy_metabox_del_plugin'] = array(
		array ('genesis_inpost_scripts_box', '', 'normal', '1' ), // Genesis scripts
		array ('genesis_inpost_layout_box', '', 'normal', '1' ), // Genesis layout

		array ('google_analytics_dashboard', 'dashboard', 'normal', '1' ), 	// WPMUdev Google Analytics+ Dashboard
		array ('google_analytics_dashboard', '', 'normal', '1' ), 			// WPMUdev Google Analytics+ Page/Post box
	);

	// Return the new values to the plugin.
	return $args;
}``
