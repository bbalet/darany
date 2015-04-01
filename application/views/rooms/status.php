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

<h2><?php echo lang('rooms_status_title');?>&nbsp;<span class="muted">(<?php echo $room['name']; ?>)</span></h2>

<div class="row-fluid">
    <div class="span12">
        <?php if (is_null($end_timeslot)) { ?>
        <?php echo lang('rooms_status_msg_available');?><br />
        <?php if (is_null($next_timeslot)) { ?>
            <?php echo lang('rooms_status_msg_no_timeslot');?><br />
            <?php } else { ?>
            <?php echo lang('rooms_status_msg_next_timeslot');?> <?php echo $next_timeslot; ?>
        <?php } ?>
        <?php } else { ?>
        <?php echo lang('rooms_status_msg_not_available');?><br />
        <?php echo lang('rooms_status_msg_booked_by');?> <a href="mailto:<?php echo $end_timeslot->creator_email; ?>"><?php echo $end_timeslot->creator_name; ?></a>.<br />
        <?php echo lang('rooms_status_msg_next_free');?> <?php echo $end_timeslot->enddate; ?>.<br />
        <?php } ?>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
        <a href="<?php echo base_url();?>locations/<?php echo $room['location_id']; ?>/rooms" class="btn btn-primary"><i class="icon-arrow-left icon-white"></i>&nbsp;<?php echo lang('rooms_status_button_back');?></a>
        <a href="<?php echo base_url();?>rooms/calendar/<?php echo $room['id']; ?>" class="btn btn-primary"><i class="icon-calendar icon-white"></i>&nbsp;<?php echo lang('rooms_status_button_calendar');?></a>
    </div>
</div>
    
<div class="row-fluid"><div class="span12">&nbsp;</div></div>
