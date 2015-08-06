/**
 * EM Dashboard JS
 *
 * Plugin URI: https://wordpress.org/plugins/em-dashboard/
 * Author: Sybre Waaijer
 * Author URI: https://cyberwire.nl/
 */

 /**
 * Holds EM Dashboard values and adds CSS enhancements based on content.
 *
 * @since 1.0.0
 *
 * @constructor
 */
window['emdashboard'] = {

    /**
	 * Hides slug box if it contains nothing.
	 *
	 * @since 1.0.0
	 *
	 * @function
     * @uses jQuery($)
	 */
	hideslugbox: function($) {

        var box = $('#edit-slug-box');
        var sample = $('#sample-permalink');

        if (box.text().length < 10) {
            box.hide();
        }
    },

    /**
     * Shows slugbox on slug creation.
     * Triggers on WP Admin Ajax autosave or update slug.
     *
     * @since 1.0.0
     *
     * @function
     * @uses jQuery($)
     */
    showslugbox: function($) {
        $(document).on('before-autosave.update-post-slug.after-autosave', function() {
            $('#edit-slug-box').show();
        });
    },

	/**
	 * Initialises all aspects of the scripts.
	 *
	 * @since 1.0.0
	 *
	 * @function
     * @uses jQuery($)
	 */
	ready: function($) {
		'use strict';

		// Hide the slugbox
        emdashboard.hideslugbox($);

        // Show the slugbox
        emdashboard.showslugbox($);

	}

};
jQuery( emdashboard.ready );
