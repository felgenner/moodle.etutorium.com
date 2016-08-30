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

defined('MOODLE_INTERNAL') || die();

/**
 * Define the complete etutorium structure for backup, with file and id annotations
 */
class backup_etutorium_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo

        // Define each element separated
        $etutorium = new backup_nested_element('etutorium', array('id'), array('course',
            'name', 'intro', 'introformat', 'timecreated', 'timemodified', 'apikey'));

        $webinars = new backup_nested_element('webinars');
        $webinar = new backup_nested_element('webinar', array('id'), array('etutorium_id',
            'webinar_id', 'title', 'description', 'start_time', 'finish_time'));

        // Build the tree
        // (nice mono-tree, lol)
        $etutorium->add_child($webinars);
        $webinars->add_child($webinar);

        // Define sources
        $etutorium->set_source_table('etutorium', array('id' => backup::VAR_ACTIVITYID));

        $webinar->set_source_table('etutoriumwebinars', array('etutorium_id' => backup::VAR_PARENTID));

        // Define id annotations

        // Define file annotations
        $etutorium->annotate_files('mod_etutorium', 'intro', null);

        // Return the root element (etutorium), wrapped into standard activity structure
        return $this->prepare_activity_structure($etutorium);
    }
}
