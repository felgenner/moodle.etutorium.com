<?php  // $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $

/**
 * This page prints a particular instance of etutorium
 *
 * @author  Petrina Alexandr <your@email.address>
 * @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
 * @package mod/etutorium
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // etutorium instance ID

if ($id) {
    if (! $cm = get_coursemodule_from_id('etutorium', $id)) {
        error('Course Module ID was incorrect');
    }

    if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
        error('Course is misconfigured');
    }

    if (! $etutorium = $DB->get_record('etutorium', array('id' => $cm->instance))) {
        error('Course module is incorrect');
    }

} else if ($a) {
    if (! $etutorium = $DB->get_record('etutorium', array('id' => $a))) {
        error('Course module is incorrect');
    }
    if (! $course = $DB->get_record('course', array('id' => $etutorium->course))) {
        error('Course is misconfigured');
    }
    if (! $cm = get_coursemodule_from_instance('etutorium', $etutorium->id, $course->id)) {
        error('Course Module ID was incorrect');
    }

} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

//add_to_log($course->id, "etutorium", "view", "view.php?id=$cm->id", "$etutorium->id");

/// Print the page header
$stretutoriums = get_string('modulenameplural', 'etutorium');
$stretutorium  = get_string('modulename', 'etutorium');

//$navlinks = array();
//$navlinks[] = array('name' => format_string($etutorium->name), 'link' => '', 'type' => 'activityinstance');
//
//$navigation = build_navigation($navlinks);

//print_header_simple(format_string($etutorium->name), '', $navigation, '', '', true,
//              update_module_button($cm->id, $course->id, $stretutorium), navmenu($course, $cm));
$PAGE->set_url('/mod/etutorium/view.php', array('id' => $cm->id));

$context = context_course::instance($course->id);

echo $OUTPUT->header();
/// Print the main part of the page

$usewebinar = $DB->get_records('etutoriumwebinars',array('etutorium_id' => $etutorium->id));
$PAGE->requires->js('/mod/etutorium/js/send.js');

if (has_capability('mod/etutorium:addwebinar', $context)) {
    $PAGE->requires->js('/mod/etutorium/js/edit.js');
    $PAGE->requires->strings_for_js(array(
        'title',
        'description',
        'start_time',
        'finish_time',
        'adderror',
    ), 'etutorium');
    echo renderfile('admin', array(
        'etutorium' => $etutorium,
        'userweblist' => $usewebinar,
        'id' => 'useweblist',
    ));
} elseif (has_capability('mod/etutorium:connect', $context)) {
    $PAGE->requires->js('/mod/etutorium/js/connect.js');
    $PAGE->requires->strings_for_js(array(
        'fullingfields',
        'participant-webinar-not-found',
    ), 'etutorium');
    echo renderfile('student', array(
        'etutorium' => $etutorium,
        'userweblist' => $usewebinar,
    ));
}

echo $OUTPUT->footer();

?>
