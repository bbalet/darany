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
<h2><?php echo lang('rooms_book_title');?></h2>

<?php echo validation_errors(); ?>

<?php
$attributes = array('id' => 'target');
echo form_open('rooms/book/' . $room['id'], $attributes); ?>
    <input type="hidden" name="creator" value="<?php echo $user_id;?>" />
    <input type="hidden" name="room" value="<?php echo $room['id'];?>" />
    <input type="hidden" name="status" value="2" />

    <label for="viz_startdate"><?php echo lang('rooms_book_field_startdate');?></label>
    <input type="text" id="viz_startdate" name="viz_startdate" /><br />
    <input type="hidden" name="startdate" id="startdate" />
    
    <label for="viz_enddate"><?php echo lang('rooms_book_field_enddate');?></label>
    <input type="text" id="viz_enddate" name="viz_enddate" /><br />
    <input type="hidden" name="enddate" id="enddate" />
    
    <label for="note"><?php echo lang('rooms_book_field_note');?></label>
    <textarea type="input" name="note" id="note" /></textarea>

    <br /><br />
    <button id="send" class="btn btn-primary"><i class="icon-ok icon-white"></i>&nbsp;<?php echo lang('rooms_book_button_book');?></button>
    &nbsp;
    <a href="<?php echo base_url(); ?>locations/<?php echo $room['location_id']; ?>/rooms" class="btn btn-danger"><i class="icon-remove icon-white"></i>&nbsp;<?php echo lang('rooms_book_button_cancel');?></a>
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
                    $('#viz_startdate').datepicker('setDate', this.value).show();
                    $('#viz_enddate').datepicker('setDate', this.value).show();
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
                    $('#viz_startdate').datepicker('setDate', this.value).show();
                    $('#viz_enddate').datepicker('setDate', this.value).show();
            }
        });
    });
</script>
