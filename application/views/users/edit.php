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

$CI =& get_instance();
$CI->load->library('polyglot');
$CI->load->helper('language');
$this->lang->load('users', $language);
$this->lang->load('global', $language);?>

<h2><?php echo lang('users_edit_title');?><?php echo $users_item['id']; ?></h2>

<?php echo validation_errors(); ?>

<?php if (isset($_GET['source'])) {
    echo form_open('users/edit/' . $users_item['id'] .'?source=' . $_GET['source']);
} else {
    echo form_open('users/edit/' . $users_item['id']);
} ?>

    <input type="hidden" name="id" value="<?php echo $users_item['id']; ?>" required /><br />

    <label for="firstname"><?php echo lang('users_edit_field_firstname');?></label>
    <input type="text" name="firstname" value="<?php echo $users_item['firstname']; ?>" required /><br />

    <label for="lastname"><?php echo lang('users_edit_field_lastname');?></label>
    <input type="text" name="lastname" value="<?php echo $users_item['lastname']; ?>" required /><br />

    <label for="login"><?php echo lang('users_edit_field_login');?></label>
    <input type="text" name="login" value="<?php echo $users_item['login']; ?>" required /><br />
	
    <label for="email"><?php echo lang('users_edit_field_email');?></label>
    <input type="email" id="email" name="email" value="<?php echo $users_item['email']; ?>" required /><br />
		
    <label for="role[]"><?php echo lang('users_edit_field_role');?></label>
    <select name="role[]" multiple="multiple" size="2">
    <?php foreach ($roles as $roles_item): ?>
        <option value="<?php echo $roles_item['id'] ?>" <?php if ((((int)$roles_item['id']) & ((int) $users_item['role']))) echo "selected" ?>><?php echo $roles_item['name'] ?></option>
    <?php endforeach ?>
    </select>

    <br />
    <input type="hidden" name="manager" id="manager" value="<?php echo $users_item['manager']; ?>" /><br />
    <label for="txtManager"><?php echo lang('users_edit_field_manager');?></label>
    <div class="input-append">
        <input type="text" id="txtManager" name="txtManager" value="<?php echo $manager_label; ?>" required readonly/>
        <a id="cmdSelfManager" class="btn btn-primary"><?php echo lang('users_edit_button_self');?></a>
        <a id="cmdSelectManager" class="btn btn-primary"><?php echo lang('users_edit_button_select');?></a>
    </div><br />
    <i><?php echo lang('users_edit_field_manager_description');?></i>
    <br /><br />
    
    <input type="hidden" name="position" id="position" value="<?php echo $users_item['position']; ?>" /><br />
    <label for="txtPosition"><?php echo lang('users_create_field_position');?></label>
    <div class="input-append">
        <input type="text" id="txtPosition" name="txtPosition" value="<?php echo $position_label; ?>" required readonly />
        <a id="cmdSelectPosition" class="btn btn-primary"><?php echo lang('users_edit_button_select');?></a>
    </div>    
    <br />
    
    <label for="language"><?php echo lang('users_edit_field_language');?></label>
    <select name="language">
         <?php 
         $languages = $CI->polyglot->nativelanguages($this->config->item('languages'));
         foreach ($languages as $code => $language): ?>
        <option value="<?php echo $code; ?>" <?php if ($code == $users_item['language']) echo "selected" ?>><?php echo $language; ?></option>
        <?php endforeach ?>
    </select>
    
    <br />
    <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i>&nbsp;<?php echo lang('users_edit_button_update');?></button>
    &nbsp;
    <?php if (isset($_GET['source'])) {?>
        <a href="<?php echo base_url() . $_GET['source']; ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i>&nbsp;<?php echo lang('users_edit_button_cancel');?></a>
    <?php } else {?>
        <a href="<?php echo base_url();?>users" class="btn btn-danger"><i class="icon-remove icon-white"></i>&nbsp;<?php echo lang('users_edit_button_cancel');?></a>
    <?php } ?>
</form>

<div id="frmSelectManager" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectManager').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('users_edit_popup_manager_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectManagerBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_manager();" class="btn secondary"><?php echo lang('users_edit_popup_manager_button_ok');?></a>
        <a href="#" onclick="$('#frmSelectManager').modal('hide');" class="btn secondary"><?php echo lang('users_edit_popup_manager_button_cancel');?></a>
    </div>
</div>

<div id="frmSelectPosition" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectPosition').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('users_edit_popup_position_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectPositionBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_position();" class="btn secondary"><?php echo lang('users_edit_popup_position_button_ok');?></a>
        <a href="#" onclick="$('#frmSelectPosition').modal('hide');" class="btn secondary"><?php echo lang('users_edit_popup_position_button_cancel');?></a>
    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/flick/jquery-ui-1.10.4.custom.min.css">
<script src="<?php echo base_url();?>assets/js/jquery-ui-1.10.4.custom.min.js"></script>
<?php //Prevent HTTP-404 when localization isn't needed
if ($language_code != 'en') { ?>
<script src="<?php echo base_url();?>assets/js/i18n/jquery.ui.datepicker-<?php echo $language_code;?>.js"></script>
<?php } ?>
<script type="text/javascript">
    
    function select_manager() {
        var manager = $('#employees .row_selected td:first').text();
        var text = $('#employees .row_selected td:eq(1)').text();
        text += ' ' + $('#employees .row_selected td:eq(2)').text();
        $('#manager').val(manager);
        $('#txtManager').val(text);
        $("#frmSelectManager").modal('hide');
    }
    
    function select_position() {
        var position = $('#positions .row_selected td:first').text();
        var text = $('#positions .row_selected td:eq(1)').text();
        $('#position').val(position);
        $('#txtPosition').val(text);
        $("#frmSelectPosition").modal('hide');
    }

    $(document).ready(function() {
        $("#viz_datehired").datepicker({
            changeMonth: true,
            changeYear: true,
            altFormat: "yy-mm-dd",
            altField: "#datehired"
        }, $.datepicker.regional['<?php echo $language_code;?>']);
        
        //Popup select position
        $("#cmdSelectManager").click(function() {
            $("#frmSelectManager").modal('show');
            $("#frmSelectManagerBody").load('<?php echo base_url(); ?>users/employees');
        });
        
        //Popup select position
        $("#cmdSelectPosition").click(function() {
            $("#frmSelectPosition").modal('show');
            $("#frmSelectPositionBody").load('<?php echo base_url(); ?>positions/select');
        });

        //Load alert forms
        $("#frmSelectEntity").alert();
        //Prevent to load always the same content (refreshed each time)
        $('#frmSelectEntity').on('hidden', function() {
            $(this).removeData('modal');
        });
        
        //Self manager button
        $("#cmdSelfManager").click(function() {
            $("#manager").val('-1');
            $('#txtManager').val('<?php echo lang('users_edit_field_manager_alt');?>');
        });
    });
</script>
