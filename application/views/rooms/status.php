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

<h2><?php echo lang('locations_edit_title');?><?php echo $room['name']; ?></h2>

<div class="row-fluid">
    <div class="span12">
        <?php if (is_null($end_timeslot)) { ?>
        The room is free. 
        
            <?php if (is_null($next_timeslot)) { ?>
            But next timeslot is <?php echo $next_timeslot['startdate']; ?> to <?php echo $next_timeslot['enddate']; ?>
            <?php } ?>
        <?php } else { ?>
        The room is not available.
        It has been booked by <?php echo $end_timeslot->creator_name; ?>.
        It will be free at <?php echo $end_timeslot->enddate; ?>.
        <?php } ?>
    </div>
</div>

Free ?
Next available ?
Free until
If timeslot, see creator and its email
Link to calendar of room

<?php echo var_dump($room); ?>
<?php echo var_dump($end_timeslot); ?>
<?php echo var_dump($next_timeslot); ?>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
        <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i>&nbsp;<?php echo lang('locations_edit_button_update');?></button>
    </div>
</div>
    
<div class="row-fluid"><div class="span12">&nbsp;</div></div>
