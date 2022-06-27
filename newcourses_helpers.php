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
 * Class used to find courses not viewed by user.
 *
 * @package    block_newcourses
 * @copyright  2021 Grzegorz Bębenista <gbebenista@ahe.lodz.pl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 /**
  * Helper class to find courses not viewed by user.
  *
  * @copyright  2021 Grzegorz Bębenista <gbebenista@ahe.lodz.pls>
  * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
  */
class newcourses_helper {

    /**
     * Query the db. Prepare values shown in the form.
     *
     * @return  array
     */
    function fetch_courses($teacherid){
        global $DB;

        $teacher=array($teacherid);

        $sql = "SELECT mc.id
                from mdl_course mc 
                inner join mdl_context mctx on mctx.instanceid = mc.id 
                inner join mdl_role_assignments mra on mra.contextid = mctx.id and mra.userid = $teacher[0] and mra.roleid = 12 and (now()::date - to_timestamp(mra.timemodified)::date) < 365
                inner join mdl_course_categories mcc on mc.category = mcc.id and mcc.visible = 1
                where mc.visible = 1
                except 
                select distinct mul.courseid 
                from mdl_user_lastaccess mul 
                inner join mdl_role_assignments mra on mra.userid = mul.userid and mul.userid = $teacher[0] and mra.roleid = 12 
                inner join mdl_context mctx on mctx.id = mra.contextid and mctx.instanceid = mul.courseid
                where (now()::date - to_timestamp(mul.timeaccess)::date) < 365";

        $courses=$DB->get_fieldset_sql($sql, $teacher);
        return $courses;
    }

    function fetch_courses_count($teacherid){
        global $DB;

        $teacher=array($teacherid);

        $sql = "SELECT count(*) from
                ((select mc.id
                from mdl_course mc 
                inner join mdl_context mctx on mctx.instanceid = mc.id 
                inner join mdl_role_assignments mra on mra.contextid = mctx.id and mra.userid = $teacher[0] and mra.roleid = 12 and (now()::date - to_timestamp(mra.timemodified)::date) < 365
                inner join mdl_course_categories mcc on mc.category = mcc.id and mcc.visible = 1
                where mc.visible = 1)
                except 
                (select distinct mul.courseid 
                from mdl_user_lastaccess mul 
                inner join mdl_role_assignments mra on mra.userid = mul.userid and mul.userid = $teacher[0] and mra.roleid = 12 
                inner join mdl_context mctx on mctx.id = mra.contextid and mctx.instanceid = mul.courseid
                where (now()::date - to_timestamp(mul.timeaccess)::date) < 365)) as temptable";


        $courses_count=$DB->count_records_sql($sql, $teacher);
        return $courses_count;
    }

}
?>