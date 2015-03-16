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
?>

<h2><?php echo lang('locations_edit_title');?><?php echo $location['id']; ?></h2>

<?php echo validation_errors(); ?>

<?php echo form_open('locations/edit/' . $location['id']) ?>
    <input type="hidden" name="id" value="<?php echo $location['id']; ?>" /><br />
    <label for="name"><?php echo lang('locations_edit_field_name');?></label>
    <input type="text" name="name" id="name" value="<?php echo $location['name']; ?>" autofocus required /><br />

    <label for="description"><?php echo lang('locations_edit_field_description');?></label>
    <textarea type="input" name="description" id="description" /><?php echo $location['description']; ?></textarea>

    <label for="address"><?php echo lang('locations_edit_field_address');?></label>
    <textarea type="input" name="address" id="address" /><?php echo $location['address']; ?></textarea>

    <br /><br />
    <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i>&nbsp;<?php echo lang('locations_edit_button_update');?></button>
    &nbsp;
    <a href="<?php echo base_url();?>positions" class="btn btn-danger"><i class="icon-remove icon-white"></i>&nbsp;<?php echo lang('locations_edit_button_cancel');?></a>
</form>