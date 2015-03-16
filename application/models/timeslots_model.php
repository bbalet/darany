<?php
/* 
 * This file is part of darany.
 *
 * darany is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * darany is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with darany. If not, see <http://www.gnu.org/licenses/>.
 */

class Timeslots_model extends CI_Model {

    /**
     * Default constructor
     */
    public function __construct() {
        
    }
    
    /**
     * Return the list of timeslots for a given room
     * @param int $room id of a room
     * @return array record of types
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function get_timeslots($room) {
        $this->db->select('status.name as status_name');
        $this->db->select('CONCAT_WS(\' \', users.firstname, users.lastname) as creator_name', FALSE);
        $this->db->select('timeslots.*');
        $this->db->join('status', 'status.id = timeslots.status');
        $this->db->join('users', 'users.id = timeslots.creator');
        $this->db->where('room', $room);
        $this->db->order_by('startdate' , 'desc');
        $query = $this->db->get('timeslots');
        return $query->result_array();
    }

    /**
     * Returns the end of the current booking for a given room.
     * Check if we are in a timeslot for a given room (i.e. the room is booked)
     * You can use this method to know if a room is available (returns NULL)
     * @param int $location id of a location
     * @return array record of types
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function end_timeslot($room) {
        $this->db->select('enddate');
        $this->db->where('room', $room);
        $this->db->where('status', 3);  //Only accepted status
        $this->db->where('NOW() >= startdate');
        $this->db->where('NOW() <= enddate');
        $this->db->order_by('enddate' , 'asc');   //The software is simple, so we didn't check if another timeslot exists
        $this->db->limit('1');
        $query = $this->db->get('timeslots');
        if ($query->num_rows() > 0) {
           $row = $query->row(); 
           return $row->enddate;
        } else {
            return NULL;
        }
    }
    
    /**
     * Return the next timeslots (nearest date in the future when the room is booked)
     * Or NULL if no timeslot has been found
     * @param int $room id of a room
     * @return array record of types
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function next_timeslot($room) {
        $this->db->select('startdate');
        $this->db->where('room', $room);
        $this->db->where('status', 3);  //Only accepted status
        $this->db->where('startdate > NOW()');
        $this->db->order_by('startdate' , 'desc');
        $this->db->limit('1');
        $query = $this->db->get('timeslots');
        
        if ($query->num_rows() > 0) {
           $row = $query->row(); 
           return $row->startdate;
        } else {
            return NULL;
        }
    }

}
	