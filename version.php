<?php // $Id: version.php,v 1.5.2.2 2009/03/19 12:23:11 mudrd8mz Exp $

/**
 * Code fragment to define the version of etutorium
 * This fragment is called by moodle_needs_upgrading() and /admin/index.php
 *
 * @author  Your Name <your@email.address>
 * @version $Id: version.php,v 1.5.2.2 2009/03/19 12:23:11 mudrd8mz Exp $
 * @package mod/etutorium
 */

defined('MOODLE_INTERNAL') || die();
$plugin->component = 'mod_etutorium';
$plugin->version = 2015101500;
$plugin->release = 'v0.1';
$plugin->requires = 2015101500;
$plugin->maturity = MATURITY_ALPHA;
$plugin->cron = 0;
$plugin->dependencies = array();

?>
