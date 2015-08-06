<?php

/**
 * Load plugin files
 * @uses EM_DASHBOARD_LOCALDIR
 *
 * @since 1.0.0
 */
require_once( EM_DASHBOARD_LOCALDIR . '/inc/define.php');
require_once( EM_DASHBOARD_LOCALDIR . '/inc/classes/init.class.php' );
require_once( EM_DASHBOARD_LOCALDIR . '/inc/classes/styles.class.php' );
require_once( EM_DASHBOARD_LOCALDIR . '/inc/classes/options.class.php' );
require_once( EM_DASHBOARD_LOCALDIR . '/inc/classes/optionspage.class.php' );

/**
 * Last child class
 *
 * Extended upon parent classes
 *
 * @since 1.0.0
 * @return void
 */
class EM_Dashboard_Load extends EM_Dashboard_OptionsPage { }

/**
 * Inherited class, load this and construct parent.
 */
new EM_Dashboard_Load();
