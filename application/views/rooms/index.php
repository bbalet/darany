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
$this->lang->load('datatable', $language);?>

<div class="row-fluid">
    <div class="span12">

<?php if($this->session->flashdata('msg')){ ?>
<div class="alert fade in" id="flashbox">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php echo $this->session->flashdata('msg'); ?>
</div>
 
<script type="text/javascript">
//Flash message
$(document).ready(function() {
    $("#flashbox").alert();
});
</script>
<?php } ?>
        
<h1><?php echo lang('rooms_index_title');?> <span class="muted">(<?php echo $location['name']; ?>)</span></h1>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="locations" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('rooms_index_thead_id');?></th>
            <th><?php echo lang('rooms_index_thead_name');?></th>
            <th><?php echo lang('rooms_index_thead_manager');?></th>
            <th><?php echo lang('rooms_index_thead_floor');?></th>
            <th><?php echo lang('rooms_index_thead_description');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($rooms as $room): ?>
    <tr>
        <td data-order="<?php echo $room['id']; ?>">
            <?php echo $room['id'];?>
            <div class="pull-right">
                <a href="#" class="confirm-delete" data-id="<?php echo $room['id'];?>" title="<?php echo lang('rooms_index_thead_tip_delete');?>"><i class="icon-trash"></i></a>&nbsp; 
                <a href="<?php echo base_url();?>locations/edit/<?php echo $room['id']; ?>" title="<?php echo lang('rooms_index_thead_tip_edit');?>"><i class="icon-pencil"></i></a>
                <a href="<?php echo base_url();?>locations/<?php echo $room['id']; ?>/rooms"><?php echo lang('rooms_index_thead_link_rooms');?></a>&nbsp;
                
                 
                <a href="<?php echo base_url();?>rooms/timeslots/<?php echo $room['id'];?>" title="View timeslot"><i class="icon-list-alt"></i></a>&nbsp;
                <a href="<?php echo base_url();?>rooms/calendar/<?php echo $room['id'];?>" title="View calendar"><i class="icon-calendar"></i></a>&nbsp;
                <a href="#myModal" role="button" data-toggle="modal"><i class="icon-qrcode"></i></a>
                
                <?php if ($room['free']) { ?>
                <a href="<?php echo base_url();?>rooms/status/<?php echo $room['id'];?>" title="The room is available"><i class="icon-ok-circle"></i></a>&nbsp;
                <?php } else { ?>
                <a href="<?php echo base_url();?>rooms/status/<?php echo $room['id'];?>" title="The room is already booked"><i class="icon-ban-circle"></i></a>&nbsp;
                 <?php } ?>
                
            </div>
        </td>
        <td><?php echo $room['name']; ?></td>
        <td><?php echo $room['manager_name']; ?></td>
        <td><?php echo $room['floor']; ?></td>
        <td><?php echo $room['description']; ?></td>
    </tr>
<?php endforeach ?>
	</tbody>
</table>
	</div>
</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Modal header</h3>
  </div>
  <div class="modal-body">
      <p><img src="<?php echo base_url();?>rooms/qrcode/1" /></p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" onclick="$('#myModal').modal('hide');">Close</a>
  </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span2">
      <a href="<?php echo base_url();?>locations/export" class="btn btn-primary"><i class="icon-file icon-white"></i>&nbsp;<?php echo lang('rooms_index_button_export');?></a>
    </div>
    <div class="span3">
      <a href="<?php echo base_url();?>locations/<?php echo $location['id']; ?>/rooms/create" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;<?php echo lang('rooms_index_button_create');?></a>
    </div>
    <div class="span7">&nbsp;</div>
</div>

<link href="<?php echo base_url();?>assets/datatable/css/jquery.dataTables.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/js/jquery.dataTables.min.js"></script>

<div id="frmDeleteLocation" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmDeleteLocation').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('rooms_index_popup_title');?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo lang('rooms_index_popup_description');?></p>
        <p><?php echo lang('rooms_index_popup_confirm');?></p>
    </div>
    <div class="modal-footer">
        <a href="#" id="lnkDeleteLocation" class="btn danger"><?php echo lang('rooms_index_popup_button_yes');?></a>
        <a href="#" onclick="$('#frmDeleteLocation').modal('hide');" class="btn secondary"><?php echo lang('rooms_index_popup_button_no');?></a>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    //Transform the HTML table in a fancy datatable
    $('#locations').dataTable({
		"oLanguage": {
                    "sEmptyTable":     "<?php echo lang('datatable_sEmptyTable');?>",
                    "sInfo":           "<?php echo lang('datatable_sInfo');?>",
                    "sInfoEmpty":      "<?php echo lang('datatable_sInfoEmpty');?>",
                    "sInfoFiltered":   "<?php echo lang('datatable_sInfoFiltered');?>",
                    "sInfoPostFix":    "<?php echo lang('datatable_sInfoPostFix');?>",
                    "sInfoThousands":  "<?php echo lang('datatable_sInfoThousands');?>",
                    "sLengthMenu":     "<?php echo lang('datatable_sLengthMenu');?>",
                    "sLoadingRecords": "<?php echo lang('datatable_sLoadingRecords');?>",
                    "sProcessing":     "<?php echo lang('datatable_sProcessing');?>",
                    "sSearch":         "<?php echo lang('datatable_sSearch');?>",
                    "sZeroRecords":    "<?php echo lang('datatable_sZeroRecords');?>",
                    "oPaginate": {
                        "sFirst":    "<?php echo lang('datatable_sFirst');?>",
                        "sLast":     "<?php echo lang('datatable_sLast');?>",
                        "sNext":     "<?php echo lang('datatable_sNext');?>",
                        "sPrevious": "<?php echo lang('datatable_sPrevious');?>"
                    },
                    "oAria": {
                        "sSortAscending":  "<?php echo lang('datatable_sSortAscending');?>",
                        "sSortDescending": "<?php echo lang('datatable_sSortDescending');?>"
                    }
                }
            });
    
    $("#frmDeleteLocation").alert();
    
    //On showing the confirmation pop-up, add the contract id at the end of the delete url action
    $('#frmDeleteLocation').on('show', function() {
        var link = "<?php echo base_url();?>locations/delete/" + $(this).data('id');
        $("#lnkDeleteLocation").attr('href', link);
    });

    //Display a modal pop-up so as to confirm if a contract has to be deleted or not
    //We build a complex selector because datatable does horrible things on DOM...
    //a simplier selector doesn't work when the delete is on page >1 
    $("#locations tbody").on('click', '.confirm-delete',  function(){
            var id = $(this).data('id');
            $('#frmDeleteLocation').data('id', id).modal('show');
    });
});
</script>
