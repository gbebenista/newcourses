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
 * Lists the new courses enrolled to the teacher.
 *
 * @copyright 2021 Grzegorz Bębenista  <gbebenista@ahe.lodz.pl>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package block_newcourses
 */

require_once('../../config.php');
require_once('newcourses_table.php');


require_login();
$context = context_system::instance();
$pageurl = new \moodle_url('/blocks/newcourses/view.php');
$PAGE->set_url($pageurl);
$PAGE->set_context($context);
//set navbar
$PAGE->navbar->add(get_string('newcourses', 'block_newcourses'));
//set other things related to page
$PAGE->set_title(get_string('newcourses', 'block_newcourses'));
$PAGE->set_heading(get_string('newcourses', 'block_newcourses'));
$PAGE->set_pagetype('newcourses-view');

//output the content of the page
echo $OUTPUT->header();

//create an instance of the table
$newcoursestable = new \block_newcourses\newcourses_table($USER->id);
$newcoursestable->baseurl = $pageurl;
$newcoursestable->out(20, false);

echo $OUTPUT->footer();
