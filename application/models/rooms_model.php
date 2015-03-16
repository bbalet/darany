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

class Rooms_model extends CI_Model {

    /**
     * Default constructor
     */
    public function __construct() {
        
    }

    /**
     * Get the list of rooms for a given location
     * @param int $location id of a location
     * @return array record of rooms
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function get_rooms($location = 0) {
        $this->db->select('rooms.*, CONCAT_WS(\' \', users.firstname, users.lastname) as manager_name', FALSE);
        $this->db->join('users', 'users.id = rooms.manager');
        $query = $this->db->get_where('rooms', array('location' => $location));
        return $query->result_array();
    }
    
    /**
     * Get the list of all rooms whatever the location is
     * @return array record of rooms
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function get_all_rooms() {
        $this->db->select('locations.name as location_name');
        $this->db->select('CONCAT_WS(\' \', users.firstname, users.lastname) as manager_name', FALSE);
        $this->db->select('rooms.*');
        $this->db->join('users', 'users.id = rooms.manager');
        $this->db->join('locations', 'locations.id = rooms.location');
        $query = $this->db->get('rooms');
        return $query->result_array();
    }
    
    /**
     * Insert a new position
     * Inserted data are coming from an HTML form
     * @return type
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function set_positions() {
        
        $data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description')
        );
        return $this->db->insert('positions', $data);
    }
    
    /**
     * Delete a position from the database
     * Cascade update all users having this postion (filled with 0)
     * @param int $id identifier of the position record
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function delete_position($id) {
        $delete = $this->db->delete('positions', array('id' => $id));
        $data = array(
            'position' => 0
        );
        $this->db->where('position', $id);
        $update = $this->db->update('users', $data);
        return $delete && $update;
    }
    
    /**
     * Update a given position in the database. Update data are coming from an
     * HTML form
     * @param int $id Identifier of the database
     * @return type
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function update_positions($id) {
        $data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description')
        );

        $this->db->where('id', $id);
        return $this->db->update('positions', $data);
    }
}
	