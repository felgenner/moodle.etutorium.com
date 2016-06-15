<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * @author Petrina Alexandr <info@aktivcorp.com>
 * @copyright (c) 2016, Aktive Corporation
 * @version 1.0
 * @package mod/etutorium
 */

/**
 * Library of functions and constants for module etutorium
 * This file should have two well differenced parts:
 *   - All the core Moodle functions, neeeded to allow
 *     the module to work integrated in Moodle.
 *   - All the etutorium specific functions, needed
 *     to implement all the module logic. Please, note
 *     that, if the module become complex and this lib
 *     grows a lot, it's HIGHLY recommended to move all
 *     these module specific functions to a new php file,
 *     called "locallib.php" (see forum, quiz...). This will
 *     help to save some memory when Moodle is performing
 *     actions across all modules.
 */

function etutorium_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:
            return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_GROUPS:
            return true;
        case FEATURE_GROUPINGS:
            return true;
        case FEATURE_GROUPMEMBERSONLY:
            return true;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;

        default:
            return null;
    }
}
/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $etutorium An object from the form in mod_form.php
 * @return int The id of the newly inserted etutorium record
 */
function etutorium_add_instance(stdClass $etutorium, mod_etutorium_mod_form $mform = null) {
    global $DB, $USER;
    $etutorium->timecreated = time();
    return $DB->insert_record('etutorium', $etutorium);
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $etutorium An object from the form in mod_form.php
 * @return boolean Success/Fail
 */
function etutorium_update_instance(stdClass $etutorium, mod_etutorium_mod_form $mform = null) {
    global $DB;

    $etutorium->timemodified = time();
    $etutorium->id = $etutorium->instance;

    return $DB->update_record('etutorium', $etutorium);
}


/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function etutorium_delete_instance($id) {

    global $DB;
    if (! $etutorium = $DB->get_record('etutorium', array('id' => $id))) {
        return false;
    }

    $DB->delete_records('etutorium', array('id' => $etutorium->id));

    return true;
}


/**
 * Return a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 */
function etutorium_user_outline($course, $user, $mod, $etutorium) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 */
function etutorium_user_complete($course, $user, $mod, $etutorium) {
    return true;
}


/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in etutorium activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 * @todo Finish documenting this function
 */
function etutorium_print_recent_activity($course, $isteacher, $timestart) {
    return false;
}


/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function etutorium_cron () {
    return true;
}


/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of etutorium. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $etutoriumid ID of an instance of this module
 * @return mixed boolean/array of students
 */
function etutorium_get_participants($etutoriumid) {
    return false;
}


/**
 * This function returns if a scale is being used by one etutorium
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $etutoriumid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 */
function etutorium_scale_used($etutoriumid, $scaleid) {
    global $DB;
    if ($scaleid and $DB->record_exists('etutorium', array('id' => $etutoriumid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}


/**
 * Checks if scale is being used by any instance of etutorium.
 * This function was added in 1.9
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any etutorium
 */
function etutorium_scale_used_anywhere($scaleid) {
    global $DB;
    if ($scaleid and $DB->record_exists('etutorium', array('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}


/**
 * Execute post-install custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function etutorium_install() {
    return true;
}

function renderfile($view, $etutoriummynewdata = array(), $path = '') {
    global $CFG;
    if (empty($path)) {
        $path = 'mod/'.basename(__DIR__);
    }
    $filepath = $CFG->dirroot.'/'.$path.'/'.$view.'View.php';
    $content = '';
    if (file_exists($filepath)) {
        foreach ($etutoriummynewdata as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include($filepath);
        $content = ob_get_contents();
        ob_end_clean();
    }
    return $content;
}

function renderjson($result, $error = '') {
    $resultarr = array(
        'error' => $error,
        'result' => $result,
    );
    echo json_encode($resultarr);
    exit;
}

