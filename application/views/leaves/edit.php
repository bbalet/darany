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


CI_Controller::get_instance()->load->helper('language');
$this->lang->load('leaves', $language);
$this->lang->load('status', $language);
$this->lang->load('global', $language);?>

<h2><?php echo lang('leaves_edit_title');?><?php echo $leave['id']; ?></h2>

<div class="row-fluid">
    <div class="span8">

<?php echo validation_errors(); ?>

<?php if (isset($_GET['source'])) {
    echo form_open('leaves/edit/' . $id . '?source=' . $_GET['source']);
} else {
    echo form_open('leaves/edit/' . $id);
} ?>

    <label for="viz_startdate" required><?php echo lang('leaves_edit_field_start');?></label>
    <input type="text" name="viz_startdate" id="viz_startdate" value="<?php $date = new DateTime($leave['startdate']); echo $date->format(lang('global_date_format'));?>" />
    <input type="hidden" name="startdate" id="startdate" value="<?php echo $leave['startdate'];?>" />
    <select name="startdatetype" id="startdatetype">
        <option value="Morning" <?php if ($leave['startdatetype'] == "Morning") {echo "selected";}?>><?php echo lang('leaves_date_type_morning');?></option>
        <option value="Afternoon" <?php if ($leave['startdatetype'] == "Afternoon") {echo "selected";}?>><?php echo lang('leaves_date_type_afternoon');?></option>
    </select><br />
    
    <label for="viz_enddate" required><?php echo lang('leaves_edit_field_end');?></label>
    <input type="text" name="viz_enddate" id="viz_enddate" value="<?php $date = new DateTime($leave['enddate']); echo $date->format(lang('global_date_format'));?>" />
    <input type="hidden" name="enddate" id="enddate" value="<?php echo $leave['startdate'];?>" />
    <select name="enddatetype" id="enddatetype">
        <option value="Morning" <?php if ($leave['enddatetype'] == "Morning") {echo "selected";}?>><?php echo lang('leaves_date_type_morning');?></option>
        <option value="Afternoon" <?php if ($leave['enddatetype'] == "Afternoon") {echo "selected";}?>><?php echo lang('leaves_date_type_afternoon');?></option>
    </select><br />
    
    <label for="type" required><?php echo lang('leaves_edit_field_type');?></label>
    <select name="type" id="type">
    <?php foreach ($types as $types_item): ?>
        <option value="<?php echo $types_item['id'] ?>" <?php if ($types_item['id'] == $leave['type']) echo "selected" ?>><?php echo $types_item['name'] ?></option>
    <?php endforeach ?>    
    </select><br />
    
    <label for="duration" required><?php echo lang('leaves_edit_field_duration');?></label>
    <input type="text" name="duration" id="duration" value="<?php echo $leave['duration']; ?>" />
    
    <div class="alert hide alert-error" id="lblCreditAlert">
        <button type="button" class="close">&times;</button>
        <?php echo lang('leaves_edit_field_duration_message');?>
    </div>
    
    <label for="cause"><?php echo lang('leaves_edit_field_cause');?></label>
    <textarea name="cause"><?php echo $leave['cause']; ?></textarea>
    
    <label for="status" required><?php echo lang('leaves_edit_field_status');?></label>
    <select name="status">
        <option value="1" <?php if ($leave['status'] == 1) echo 'selected'; ?>><?php echo lang('Planned');?></option>
        <option value="2" <?php if ($leave['status'] == 2) echo 'selected'; ?>><?php echo lang('Requested');?></option>
        <?php if ($is_hr) {?>
        <option value="3" <?php if ($leave['status'] == 3) echo 'selected'; ?>><?php echo lang('Accepted');?></option>
        <option value="4" <?php if ($leave['status'] == 4) echo 'selected'; ?>><?php echo lang('Rejected');?></option>        
        <?php } ?>
    </select><br />

    <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i>&nbsp;<?php echo lang('leaves_edit_button_update');?></button>
    &nbsp;
    <?php if (isset($_GET['source'])) {?>
        <a href="<?php echo base_url() . $_GET['source']; ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i>&nbsp;<?php echo lang('leaves_edit_button_cancel');?></a>
    <?php } else {?>
        <a href="<?php echo base_url(); ?>leaves" class="btn btn-danger"><i class="icon-remove icon-white"></i>&nbsp;<?php echo lang('leaves_edit_button_cancel');?></a>
    <?php } ?>
    
</form>

    </div>
    <div class="span4">
        <span id="spnDayOff">&nbsp;</span>
    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui-1.10.4.custom.min.css">
<script src="<?php echo base_url();?>assets/js/jquery-ui-1.10.4.custom.min.js"></script>
<?php //Prevent HTTP-404 when localization isn't needed
if ($language_code != 'en') { ?>
<script src="<?php echo base_url();?>assets/js/i18n/jquery.ui.datepicker-<?php echo $language_code;?>.js"></script>
<?php } ?>

<script type="text/javascript">
    
var languageCode = '<?php echo $language_code;?>';
    
$(function () {
    $("#viz_startdate").datepicker({
        changeMonth: true,
        changeYear: true,
        altFormat: "yy-mm-dd",
        altField: "#startdate",
        numberOfMonths: 1,
              onClose: function( selectedDate ) {
                $( "#viz_enddate" ).datepicker( "option", "minDate", selectedDate );
              }
    }, $.datepicker.regional[languageCode]);
    $("#viz_enddate").datepicker({
        changeMonth: true,
        changeYear: true,
        altFormat: "yy-mm-dd",
        altField: "#enddate",
        numberOfMonths: 1,
              onClose: function( selectedDate ) {
                $( "#viz_startdate" ).datepicker( "option", "maxDate", selectedDate );
              }
    }, $.datepicker.regional[languageCode]);

    //Force decimal separator whatever the locale is
    $( "#days" ).keyup(function() {
        var value = $("#days").val();
        value = value.replace(",", ".");
        $("#days").val(value);
    });
});
</script>
