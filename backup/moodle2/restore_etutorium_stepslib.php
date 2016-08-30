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
 * Structure step to restore one etutorium activity
 */
class restore_etutorium_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();

        $paths[] = new restore_path_element('etutorium', '/activity/etutorium');
        $paths[] = new restore_path_element('etutorium_examlist', '/activity/etutorium/webinars/webinar');

        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_etutorium($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timemodified = $this->apply_date_offset($data->timemodified);

        $newitemid = $DB->insert_record('etutorium', $data);
        $this->apply_activity_instance($newitemid);
    }

    protected function process_etutorium_examlist($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->etutorium_id = $this->get_new_parentid('etutorium');

        $newitemid = $DB->insert_record('etutoriumwebinars', $data);
        $this->set_mapping('webinar', $oldid, $newitemid);
    }

    protected function after_execute() {
        $this->add_related_files('mod_etutorium', 'intro', null);
    }
}