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

/*CI_Controller::get_instance()->load->helper('language');
$this->lang->load('datatable', $language);*/?>

<h2><?php echo lang('locations_create_title');?></h2>

<?php echo validation_errors(); ?>

<?php
$attributes = array('id' => 'target');
echo form_open('locations/create', $attributes); ?>


  `location` RO
  `manager` pop-up select
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `floor` int(11) DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,


    <label for="name"><?php echo lang('locations_create_field_name');?></label>
    <input type="text" name="name" id="name" autofocus required /><br />

    <label for="description"><?php echo lang('locations_create_field_description');?></label>
    <textarea type="input" name="description" id="description" /></textarea>

    <label for="address"><?php echo lang('locations_create_field_address');?></label>
    <textarea type="input" name="address" id="address" /></textarea>
    
    <br /><br />
    <button id="send" class="btn btn-primary"><i class="icon-ok icon-white"></i>&nbsp;<?php echo lang('locations_create_button_create');?></button>
    &nbsp;
    <a href="<?php echo base_url(); ?>positions" class="btn btn-danger"><i class="icon-remove icon-white"></i>&nbsp;<?php echo lang('locations_create_button_cancel');?></a>
</form>
