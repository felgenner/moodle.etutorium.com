<?php  // $Id: lib.php,v 1.7.2.5 2009/04/22 21:30:57 skodak Exp $

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

/// (replace etutorium with the name of your module and delete this line)

$etutorium_EXAMPLE_CONSTANT = 42;     /// for example


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
    global $DB,$USER;
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

    # You may have to add extra stuff in here #

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
    return false;  //  True if anything was printed, otherwise false
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


/**
 * Execute post-uninstall custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
//function etutorium_uninstall() {
//    return true;
//}


//////////////////////////////////////////////////////////////////////////////////////
/// Any other etutorium functions go here.  Each of them must have a name that
/// starts with etutorium_
/// Remember (see note in first lines) that, if this section grows, it's HIGHLY
/// recommended to move all funcions below to a new "localib.php" file.


?>
