<?php // $Id: index.php,v 1.7.2.3 2009/08/31 22:00:00 mudrd8mz Exp $

/**
 * This page lists all the instances of etutorium in a particular course
 *
 * @author  Your Name <your@email.address>
 * @version $Id: index.php,v 1.7.2.3 2009/08/31 22:00:00 mudrd8mz Exp $
 * @package mod/etutorium
 */

/// Replace etutorium with the name of your module and remove this line

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = required_param('id', PARAM_INT);   // course

if (! $course = get_record('course', 'id', $id)) {
    error('Course ID is incorrect');
}

require_course_login($course);

add_to_log($course->id, 'etutorium', 'view all', "index.php?id=$course->id", '');


/// Get all required stringsetutorium

$stretutoriums = get_string('modulenameplural', 'etutorium');
$stretutorium  = get_string('modulename', 'etutorium');


/// Print the header

$navlinks = array();
$navlinks[] = array('name' => $stretutoriums, 'link' => '', 'type' => 'activity');
$navigation = build_navigation($navlinks);

print_header_simple($stretutoriums, '', $navigation, '', '', true, '', navmenu($course));

/// Get all the appropriate data

if (! $etutoriums = get_all_instances_in_course('etutorium', $course)) {
    notice('There are no instances of etutorium', "../../course/view.php?id=$course->id");
    die;
}

/// Print the list of instances (your module will probably extend this)

$timenow  = time();
$strname  = get_string('name');
$strweek  = get_string('week');
$strtopic = get_string('topic');

if ($course->format == 'weeks') {
    $table->head  = array ($strweek, $strname);
    $table->align = array ('center', 'left');
} else if ($course->format == 'topics') {
    $table->head  = array ($strtopic, $strname);
    $table->align = array ('center', 'left', 'left', 'left');
} else {
    $table->head  = array ($strname);
    $table->align = array ('left', 'left', 'left');
}

foreach ($etutoriums as $etutorium) {
    if (!$etutorium->visible) {
        //Show dimmed if the mod is hidden
        $link = '<a class="dimmed" href="view.php?id='.$etutorium->coursemodule.'">'.format_string($etutorium->name).'</a>';
    } else {
        //Show normal if the mod is visible
        $link = '<a href="view.php?id='.$etutorium->coursemodule.'">'.format_string($etutorium->name).'</a>';
    }

    if ($course->format == 'weeks' or $course->format == 'topics') {
        $table->data[] = array ($etutorium->section, $link);
    } else {
        $table->data[] = array ($link);
    }
}

print_heading($stretutoriums);
print_table($table);

/// Finish the page

print_footer($course);

?>
