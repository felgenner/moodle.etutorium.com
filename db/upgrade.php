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

function xmldb_etutorium_upgrade($oldversion=0) {

    global $CFG, $THEME, $db;

    $result = true;

    if ($result && $oldversion < 2007040100) {

        $table = new XMLDBTable('etutorium');
        $field = new XMLDBField('course');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'id');

        $result = $result && add_field($table, $field);

        $table = new XMLDBTable('etutorium');
        $field = new XMLDBField('intro');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'medium', null, null, null, null, null, null, 'name');

        $result = $result && add_field($table, $field);

        $table = new XMLDBTable('etutorium');
        $field = new XMLDBField('introformat');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'intro');

        $result = $result && add_field($table, $field);
    }

    if ($result && $oldversion < 2007040101) {

        $table = new XMLDBTable('etutorium');
        $field = new XMLDBField('timecreated');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'introformat');

        $result = $result && add_field($table, $field);

        $table = new XMLDBTable('etutorium');
        $field = new XMLDBField('timemodified');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'timecreated');

        $result = $result && add_field($table, $field);

        $table = new XMLDBTable('etutorium');
        $index = new XMLDBIndex('course');
        $index->setAttributes(XMLDB_INDEX_NOTUNIQUE, array('course'));

        $result = $result && add_index($table, $index);
    }

    if ($result && $oldversion < 2007040200) {

        $rec = new stdClass;
        $rec->module = 'etutorium';
        $rec->action = 'add';
        $rec->mtable = 'etutorium';
        $rec->filed  = 'name';

        $result = insert_record('log_display', $rec);

        $rec->action = 'update';
        $result = insert_record('log_display', $rec);

        $rec->action = 'view';
        $result = insert_record('log_display', $rec);
    }
    return $result;
}

