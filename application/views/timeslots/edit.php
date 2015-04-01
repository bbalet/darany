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
<h2><?php echo lang('timeslots_edit_title');?></h2>

<?php echo validation_errors(); ?>

<?php
$attributes = array('id' => 'target');

//  'rooms/(:num)/timeslots/(:num)/edit
echo form_open('rooms/' . $timeslot['room_id'] . '/timeslots/' . $timeslot['id'] . '/edit', $attributes); ?>
    <input type="hidden" name="id" value="<?php echo $timeslot['id'];?>" />

    <label for="viz_startdate"><?php echo lang('timeslots_edit_field_startdate');?></label>
    <input type="text" id="viz_startdate" name="viz_startdate" value="<?php $date = new DateTime($timeslot['startdate']); echo $date->format(lang('global_datetime_format'));?>" /><br />
    <input type="hidden" name="startdate" id="startdate" value="<?php echo $timeslot['startdate']?>" />
    
    <label for="viz_enddate"><?php echo lang('timeslots_edit_field_enddate');?></label>
    <input type="text" id="viz_enddate" name="viz_enddate" value="<?php $date = new DateTime($timeslot['enddate']); echo $date->format(lang('global_datetime_format'));?>" /><br />
    <input type="hidden" name="enddate" id="enddate" value="<?php echo $timeslot['enddate']?>" />
    
    <label for="note"><?php echo lang('timeslots_edit_field_note');?></label>
    <textarea type="input" name="note" id="note" /><?php echo $timeslot['note']?></textarea>

    <label for="status"><?php echo lang('timeslots_edit_field_status');?></label>
    <select name="status">
        <!--<option value="1" <?php if ($timeslot['status'] == 1) echo 'selected'; ?>><?php echo lang('Planned');?></option>//-->
        <option value="2" <?php if ($timeslot['status'] == 2) echo 'selected'; ?>><?php echo lang('Requested');?></option>
        <?php if ($is_admin) {?>
        <option value="3" <?php if ($timeslot['status'] == 3) echo 'selected'; ?>><?php echo lang('Accepted');?></option>
        <option value="4" <?php if ($timeslot['status'] == 4) echo 'selected'; ?>><?php echo lang('Rejected');?></option>        
        <?php } ?>
    </select><br />

    <br /><br />
    <button id="send" class="btn btn-primary"><i class="icon-ok icon-white"></i>&nbsp;<?php echo lang('timeslots_edit_button_book');?></button>
    &nbsp;
    <a href="<?php echo base_url(); ?>locations/<?php /*echo $location;*/ ?>/rooms" class="btn btn-danger"><i class="icon-remove icon-white"></i>&nbsp;<?php echo lang('timeslots_edit_button_cancel');?></a>
</form>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui-1.10.4.custom.min.css">
<link href="<?php echo base_url();?>assets/css/jquery-ui-timepicker-addon.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/js/jquery-ui-1.10.4.custom.min.js"></script>
<?php //Prevent HTTP-404 when localization isn't needed
if ($language_code != 'en') { ?>
<script src="<?php echo base_url();?>assets/js/i18n/jquery.ui.datepicker-<?php echo $language_code;?>.js"></script>
<?php } ?>
<script src="<?php echo base_url();?>assets/js/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript">
    
   $(function () {
        var startDateTextBox = $('#viz_startdate');
        var endDateTextBox = $('#viz_enddate');

        startDateTextBox.datetimepicker({ 
            timeFormat: 'HH:mm',
            altField: "#startdate",
            altFieldTimeOnly: false,
            altFormat: "yy-mm-dd",
            altTimeFormat: "H:m",
            onClose: function(dateText, inst) {
                    if (endDateTextBox.val() != '') {
                            var testStartDate = startDateTextBox.datetimepicker('getDate');
                            var testEndDate = endDateTextBox.datetimepicker('getDate');
                            if (testStartDate > testEndDate)
                                    endDateTextBox.datetimepicker('setDate', testStartDate);
                    }
                    else {
                            endDateTextBox.val(dateText);
                    }
            },
            onSelect: function (selectedDateTime){
                    endDateTextBox.datetimepicker('option', 'minDate', startDateTextBox.datetimepicker('getDate') );
            }
        });
        endDateTextBox.datetimepicker({ 
            timeFormat: 'HH:mm',
            altField: "#enddate",
            altFieldTimeOnly: false,
            altFormat: "yy-mm-dd",
            altTimeFormat: "H:m",
            onClose: function(dateText, inst) {
                    if (startDateTextBox.val() != '') {
                            var testStartDate = startDateTextBox.datetimepicker('getDate');
                            var testEndDate = endDateTextBox.datetimepicker('getDate');
                            if (testStartDate > testEndDate)
                                    startDateTextBox.datetimepicker('setDate', testEndDate);
                    }
                    else {
                            startDateTextBox.val(dateText);
                    }
            },
            onSelect: function (selectedDateTime){
                    startDateTextBox.datetimepicker('option', 'maxDate', endDateTextBox.datetimepicker('getDate') );
            }
        });
    });
</script>
