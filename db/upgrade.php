<?php  //$Id: upgrade.php,v 1.2 2007/08/08 22:36:54 stronk7 Exp $

// This file keeps track of upgrades to
// the etutorium module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

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

?>
