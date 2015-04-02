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
     * Book a room (create a timeslot). Inserted data are coming from an HTML form.
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function book_room() {
        $room = $this->input->post('room');
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $status = $this->input->post('status');
        $creator = $this->input->post('creator');
        $note = $this->input->post('note');
        $this->set_timeslots($room, $startdate, $enddate, $status, $creator, $note);
        return $this->db->insert_id();
    }
    
    /**
     * Insert a new timeslot
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function set_timeslots($room, $startdate, $enddate, $status, $creator, $note) {
        $data = array(
            'room' => $room,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'status' => $status,
            'creator' => $creator,
            'note' => $note
        );
        return $this->db->insert('timeslots', $data);
    }
    
    /**
     * Update a timeslot. Modified data are coming from an HTML form.
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function update_timeslots() {
        $data = array(
            'startdate' => $this->input->post('startdate'),
            'enddate' => $this->input->post('enddate'),
            'note' => $this->input->post('note'),
            'status' => $this->input->post('status')
        );
        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('timeslots', $data);
    }
    
    /**
     * Return the list of timeslots for a given room
     * @param int $room id of a room
     * @return array record of timeslots
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
     * Return the list of timeslots booked by a given user
     * @param int $user id of the user
     * @return array record of timeslots
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function get_timeslots_user($user) {
        $this->db->select('status.name as status_name');
        $this->db->select('rooms.name as room_name');
        $this->db->select('locations.name as location_name');
        $this->db->select('timeslots.*');
        $this->db->join('status', 'status.id = timeslots.status');
        $this->db->join('rooms', 'timeslots.room = rooms.id');
        $this->db->join('locations', 'rooms.location = locations.id');
        $this->db->where('timeslots.creator', $user);
        $this->db->order_by('startdate' , 'desc');
        $query = $this->db->get('timeslots');
        return $query->result_array();
    }

    /**
     * Return the list of timeslots that must be validated by the room manager
     * @param int $user id of the manager
     * @return array record of timeslots
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function get_timeslots_validation($user) {
        $this->db->select('rooms.name as room_name');
        $this->db->select('locations.name as location_name');
        $this->db->select('CONCAT_WS(\' \', users.firstname, users.lastname) as creator_name', FALSE);
        $this->db->select('timeslots.*');
        $this->db->join('rooms', 'timeslots.room = rooms.id');
        $this->db->join('locations', 'rooms.location = locations.id');
        $this->db->join('users', 'users.id = timeslots.creator');
        $this->db->where('rooms.manager', $user);
        $this->db->where('timeslots.status', 2);    //Requested
        $this->db->order_by('startdate' , 'desc');
        $query = $this->db->get('timeslots');
        return $query->result_array();
    }
    
    /**
     * Delete a timeslot from the database
     * @param int $id identifier of the timeslot
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function delete_timeslot($id) {
        $this->db->delete('timeslots', array('id' => $id));
    }
    
    /**
     * Return a timeslot by using its ID
     * @param int $room id of the timeslot
     * @return array record of timeslots
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function get_timeslot($id) {
        $this->db->select('mgr.id as manager_id');
        $this->db->select('mgr.email as manager_email');
        $this->db->select('usr.language as creator_language');
        $this->db->select('usr.email as creator_email');
        $this->db->select('status.name as status_name');
        $this->db->select('CONCAT_WS(\' \', usr.firstname, usr.lastname) as creator_name', FALSE);
        $this->db->select('rooms.id as room_id');
        $this->db->select('rooms.name as room_name');
        $this->db->select('locations.id as location_id');
        $this->db->select('locations.name as location_name');
        $this->db->select('timeslots.*');
        $this->db->join('users usr', 'timeslots.creator = usr.id');
        $this->db->join('rooms', 'timeslots.room = rooms.id');
        $this->db->join('locations', 'rooms.location = locations.id');
        $this->db->join('users mgr', 'rooms.manager = mgr.id');
        $this->db->join('status', 'status.id = timeslots.status');
        $this->db->where('timeslots.id', $id);
        $query = $this->db->get('timeslots');
        $result = $query->result_array();
        return $result[0];
    }
    
    /**
     * Accept a booking request
     * @param int $id timeslot identifier
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function accept($id) {
        $data = array(
            'status' => 3
        );
        $this->db->where('id', $id);
        return $this->db->update('timeslots', $data);
    }

    /**
     * Reject a booking request
     * @param int $id timeslots identifier
     * @return int number of affected rows
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function reject($id) {
        $data = array(
            'status' => 4
        );
        $this->db->where('id', $id);
        return $this->db->update('timeslots', $data);
    }
    
    /**
     * Returns the end of the current booking for a given room.
     * Check if we are in a timeslot for a given room (i.e. the room is booked)
     * You can use this method to know if a room is available (returns NULL)
     * @param int $location id of a location
     * @return array record of timeslots
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
     * Returns the end of the current booking for a given room. The difference with the function end_timeslot
     * is that this one return more data
     * @see end_timeslot
     * @param int $location id of a location
     * @return array record of timeslots
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function end_full_timeslot($room) {
        $this->db->select('status.name as status_name');
        $this->db->select('CONCAT_WS(\' \', users.firstname, users.lastname) as creator_name', FALSE);
        $this->db->select('users.email as creator_email', FALSE);
        $this->db->select('timeslots.*');
        $this->db->join('status', 'status.id = timeslots.status');
        $this->db->join('users', 'users.id = timeslots.creator');
        $this->db->where('room', $room);
        $this->db->where('status', 3);  //Only accepted status
        $this->db->where('NOW() >= startdate');
        $this->db->where('NOW() <= enddate');
        $this->db->order_by('enddate' , 'asc');   //The software is simple, so we didn't check if another timeslot exists
        $this->db->limit('1');
        $query = $this->db->get('timeslots');
        if ($query->num_rows() > 0) {
           return $query->row(); 
        } else {
            return NULL;
        }
    }
    
    /**
     * Return the next timeslots (nearest date in the future when the room is booked)
     * Or NULL if no timeslot has been found
     * @param int $room id of a room
     * @return array record of timeslots
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

    /**
     * Calendar feed for FullCalendar widget
     * @param int $room id of the meeting room
     * @param string $start Unix timestamp / Start date displayed on calendar
     * @param string $end Unix timestamp / End date displayed on calendar
     * @return string JSON encoded list of full calendar events
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function events($room, $start = "", $end = "") {
        $this->db->select('CONCAT_WS(\' \', users.firstname, users.lastname) as creator_name', FALSE);
        $this->db->select('timeslots.*');
        $this->db->join('users', 'users.id = timeslots.creator');
        $this->db->where('room', $room);
        $this->db->where('status != ', 4);       //Exclude rejected requests
        $this->db->where('( (startdate <= DATE(\'' . $start . '\') AND enddate >= DATE(\'' . $start . '\'))' .
                                   ' OR (startdate >= DATE(\'' . $start . '\') AND enddate <= DATE(\'' . $end . '\')))');
        $this->db->order_by('startdate', 'desc');
        $this->db->limit(255);  //Security limit
        $events = $this->db->get('timeslots')->result();
        
        $jsonevents = array();
        foreach ($events as $entry) {
            switch ($entry->status)
            {
                case 1: $color = '#999'; break;     // Planned
                case 2: $color = '#f89406'; break;  // Requested
                case 3: $color = '#468847'; break;  // Accepted
                case 4: $color = '#ff0000'; break;  // Rejected
            }
            
            $jsonevents[] = array(
                'id' => $entry->id,
                'title' => $entry->creator_name,
                'start' => $entry->startdate,
                'color' => $color,
                'allDay' => false,
                'end' => $entry->enddate
            );
        }
        return json_encode($jsonevents);
    }
}
	