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
 * Class used to fetch participants based on a filterset.
 *
 * @package    block_newcourses
 * @copyright  2021 Grzegorz Bębenista <gbebenista@ahe.lodz.pl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 namespace block_newcourses;

 defined('MOODLE_INTERNAL') || die();
 require_once($CFG->libdir . '/tablelib.php');
 require_once('newcourses_helpers.php');
 
 /**
  * Table to display list of teacher's students
  *
  * @copyright  2021 Grzegorz Bębenista <gbebenista@ahe.lodz.pls>
  * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
  */
 class newcourses_table extends \table_sql {
    
    //public function __construct(string $filter = '', int $resultfilter = null) {
    public function __construct($userid) {
        global $DB;

        parent::__construct("newcourses-table-$userid");

        $columnheaders = [
            'course'   => get_string('courses'),
            'courseid' => get_string('idnumber'),
            'category' => get_string('category'),
        ];
        $this->define_columns(array_keys($columnheaders));
        $this->define_headers(array_values($columnheaders));

        // The name column is a header.
        $this->define_header_column('courseid');

        // This table is not collapsible.
        $this->collapsible(false);

        // Allow pagination.
        $this->pageable(true);

        //$this->initialbars(true);

        // Allow sorting. Default to sort by lastname DESC.
        $this->sortable(true, 'course', SORT_ASC);

        //Add filtering.
        $where='';
        $params = [];

        $params['teacherid'] = $userid;

        $courses_helper = new \newcourses_helper();
        $courses_list = $courses_helper->fetch_courses($userid);

        if(!empty($courses_list)){
            list($insql, $inparams) = $DB->get_in_or_equal($courses_list, SQL_PARAMS_NAMED, 'instanceid');
            $where = "mc.id $insql AND ";
            $params += $inparams;
        }
        else{
            $where = "mc.id = 0 AND ";
        }

        $this->set_sql('', '', $where, $params);
    }

    /**
     * Query the db. Store results in the table object for use by build_table.
     *
     * @param int $pagesize size of page for paginated displayed table.
     * @param bool $useinitialsbar do you want to use the initials bar. Bar
     * will only be used if there is a fullname column defined for the table.
     */
     public function query_db($pagesize, $useinitialsbar = true) {
        global $DB;

        // Fetch the attempts.
        $sort = $this->get_sql_sort();
        if ($sort) {
            $sort = "ORDER BY $sort";
        }

        $where = '';
        if (!empty($this->sql->where)) {
            $where = "{$this->sql->where}";
        }

        $params = '';
        if (!empty($this->sql->params)) {
            $params = $this->sql->params;
        }

        $courses_helper = new \newcourses_helper();

        $sql= "SELECT mc.id as courseid, mc.fullname as course, mc.category as categoryid, mcc.name as categoryname
        from mdl_course mc, mdl_course_categories mcc  
        where $where mc.category = mcc.id";

        
        $this->pagesize($pagesize, $courses_helper->fetch_courses_count($params['teacherid']), $this->sql->params);

        if (!$this->is_downloading()) {
            $this->rawdata = $DB->get_records_sql($sql, $this->sql->params, $this->get_page_start(), $this->get_page_size());
        } else {
            $this->rawdata = $DB->get_records_sql($sql, $this->sql->params);
        }
    }

    /**
     * Format the fullname cell.
     *
     * @param   \stdClass $row
     * @return  string
     */
     public function col_course($row) : string {
        if (empty($row->course)) {
            return '';
        }
        return \html_writer::link(new \moodle_url('/course/view.php', array('id'=>$row->courseid)), $row->course);
        return $row->course;
    }
    
        /**
     * Format the fullname cell.
     *
     * @param   \stdClass $row
     * @return  string
     */
    public function col_courseid($row) : string {
        if (empty($row->courseid)) {
            return '';
        }
        return $row->courseid;
    }

        /**
     * Format the fullname cell.
     *
     * @param   \stdClass $row
     * @return  string
     */
    public function col_category($row) : string {
        if (empty($row->categoryname)) {
            return '';
        }
        return \html_writer::link(new \moodle_url('/course/index.php', array('categoryid'=>$row->categoryid)), $row->categoryname);
        //return $row->category;
    }


 }


?>