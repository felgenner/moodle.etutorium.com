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

    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2016081500) {

        $alldata = $DB->get_records_sql('select * from {etutoriumwebinars}');

        $res = $DB->get_records_sql('SHOW COLUMNS FROM {etutoriumwebinars}');

        $table = new xmldb_table('etutoriumwebinars');

        if ($res['start_time']->type != 'bigint(10)') {
            $field = new xmldb_field('start_time');
            $dbman->drop_field($table, $field);
            $field = new xmldb_field('start_time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'description');
            $dbman->add_field($table, $field);
        }

        if ($res['finish_time']->type != 'bigint(10)') {
            $field = new xmldb_field('finish_time');
            $dbman->drop_field($table, $field);
            $field = new xmldb_field('finish_time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'start_time');
            $dbman->add_field($table, $field);
        }
        
        foreach ($alldata as $row) {
            $change = false;

            if (preg_match('/[0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}\:[0-9]{2}\:[0-9]{2}/', $row->start_time)) {
                $newtime = new DateTime($row->start_time);
                $row->start_time = $newtime->getTimestamp();
                $change = true;
            }

            if (preg_match('/[0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}\:[0-9]{2}\:[0-9]{2}/', $row->finish_time)) {
                $newtime = new DateTime($row->finish_time);
                $row->finish_time = ($row->finish_time != '0000-00-00 00:00:00')?$newtime->getTimestamp():0;
                $change = true;
            }

            if ($change) {
                $DB->update_record('etutoriumwebinars', $row);
            }
        }
    }

    upgrade_mod_savepoint(true, 2016081500, 'etutorium');
    return true;
}

