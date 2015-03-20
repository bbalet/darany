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

<h2><?php echo lang('rooms_edit_title');?>&nbsp;<span class="muted">(<?php echo $room['name']; ?>)</span></h2>

<?php echo validation_errors(); ?>

<?php echo form_open('locations/' . $room['location_id'] . '/rooms/' . $room['id'] . '/edit') ?>
    <input type="hidden" name="id" value="<?php echo $room['id']; ?>" /><br />

    <label for="name"><?php echo lang('rooms_edit_field_name');?></label>
    <input type="text" name="name" id="name" value="<?php echo $room['name']; ?>" autofocus required /><br />

    <input type="hidden" name="manager" id="manager" value="<?php echo $room['manager']; ?>" /><br />
    <label for="txtManager"><?php echo lang('rooms_edit_field_manager');?></label>
    <div class="input-append">
        <input type="text" id="txtManager" name="txtManager" value="<?php echo $room['manager_name']; ?>" required readonly />
        <a id="cmdSelectManager" class="btn btn-primary"><?php echo lang('rooms_edit_button_select');?></a>
    </div><br />
    
    <label for="floor"><?php echo lang('rooms_edit_field_floor');?></label>
    <input type="text" name="floor" id="floor" value="<?php echo $room['floor']; ?>" /><br />
    
    <label for="description"><?php echo lang('rooms_edit_field_description');?></label>
    <textarea type="input" name="description" id="description" value="<?php echo $room['description']; ?>" /></textarea>

    <br /><br />
    <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i>&nbsp;<?php echo lang('rooms_edit_button_update');?></button>
    &nbsp;
    <a href="<?php echo base_url();?>positions" class="btn btn-danger"><i class="icon-remove icon-white"></i>&nbsp;<?php echo lang('rooms_edit_button_cancel');?></a>
</form>

<div id="frmSelectManager" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectManager').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('rooms_edit_popup_manager_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectManagerBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_manager();" class="btn secondary"><?php echo lang('rooms_edit_popup_manager_button_ok');?></a>
        <a href="#" onclick="$('#frmSelectManager').modal('hide');" class="btn secondary"><?php echo lang('rooms_edit_popup_manager_button_cancel');?></a>
    </div>
</div>

<script type="text/javascript">

    function select_manager() {
        var manager = $('#employees .row_selected td:first').text();
        var text = $('#employees .row_selected td:eq(1)').text();
        text += ' ' + $('#employees .row_selected td:eq(2)').text();
        $('#manager').val(manager);
        $('#txtManager').val(text);
        $("#frmSelectManager").modal('hide');
    }
    
   $(function () {
        //Popup select manager
        $("#cmdSelectManager").click(function() {
            $("#frmSelectManager").modal('show');
            $("#frmSelectManagerBody").load('<?php echo base_url(); ?>users/employees');
        });
    });

</script>