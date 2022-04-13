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
 * Block displaying all of the students enrolled in courses where user is enrolled as teacher.
 *
 *
 * @package    block_newcourses
 * @copyright  Grzegorz Bębenista <gbebenista@ahe.lodz.pl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once('newcourses_helpers.php');



class block_newcourses extends block_base {
    /**
     * block initializations
     */
    public function init() {
        $this->title   = get_string('newcourses', 'block_newcourses');
    }

    /**
     * block contents
     * @return object
     */
    public function get_content() {
        global $CFG, $OUTPUT, $USER;

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }
        
        $this->content = new stdClass();

        $courses = new \newcourses_helper();
        $courses_count = $courses->fetch_courses_count($USER->id);
        
        if($courses_count == 0) {
            $this->content->text = html_writer::div(get_string('nocourses', 'block_newcourses'), 'alert alert-success m-auto');
        }
        else{
        $this->content->text = html_writer::div(
            get_string('coursescount', 'block_newcourses').
            html_writer::span($courses_count,'ml-1 font-weight-bold').
            html_writer::empty_tag('br').
            html_writer::link(new moodle_url($CFG->wwwroot.'/blocks/newcourses/view.php'),get_string('frontpageenrolledcourselist'),array('class'=>'text-danger font-weight-bold')),
            'alert alert-danger pr-0');
        }
        return $this->content;
        
    }

    public function hide_header() {
        return true;
      }

    /**
     * allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return false;
    }

    /**
     * allow more than one instance of the block on a page
     *
     * @return boolean
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * allow instances to have their own configuration
     *
     * @return boolean
     */
    public function instance_allow_config() {
        return false;
    }

    /**
     * instance specialisations (must have instance allow config true)
     *
     */
    public function specialization() {
    }

    /**
     * locations where block can be displayed
     *
     * @return array
     */
    public function applicable_formats() {
        return array('all' => false, 'my' => false, 'site-index'=>true);
    }

    /**
     * post install configurations
     *
     */
    public function after_install() {
    }

    /**
     * post delete configurations
     *
     */
    public function before_delete() {
    }

}
