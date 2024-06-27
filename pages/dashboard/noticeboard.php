<?php
/*
 * Copyright [2022] [Jason Alexander Cleeland, Melbourne Australia]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */  
$notices=$oct->fetchMany("SELECT nb.*, users.*, count(nc.noticeboard_id) as comment_count FROM ".$oct->dbprefix."noticeboard as nb INNER JOIN ".$oct->dbprefix."users as users ON nb.created_by=users.user_id LEFT JOIN ".$oct->dbprefix."noticeboard_comments AS nc ON nb.id=nc.noticeboard_id WHERE published=1 AND publish_date <= CURDATE() and expiry_date > CURDATE() GROUP BY nb.id, users.user_id ORDER BY publish_date DESC", null, 0, 10000);

?>
<div class="row overflow-hidden flex-grow h-100">
    <div class="col-12 h-100">
        <script src="js/pages/dashboard/noticeboard.js"></script>
        <h4 class="header">Noticeboard</h4>
        <div class="pager rounded-bottom">&nbsp;</div> 

        <div class="overflow-auto flex-grow h-fullleft" id="noticeboard">
        <?php
        //echo "SELECT * FROM ".$oct->dbprefix."noticeboard as nb INNER JOIN ".$oct->dbprefix."users as users ON nb.created_by=users.user_id WHERE published=1 AND publish_date <= CURDATE() and expiry_date > CURDATE() ORDER BY publish_date DESC";
        $count=0;
        $total=count($notices['output']);
        foreach($notices['output'] as $notice) {
            if($count==0) {
                //First noticeboard item
            ?>
            <div class="border rounded noticeboarditem p-0">
                <div class="row m-0">
                    <div class="col-sm-12 noticeboardheader">
                        <div class="w-100 noticeboardpostedby text-right">
                            <?php echo date("d M Y", strtotime($notice['publish_date'])); ?> - 
                            <?php echo $notice['real_name']; ?> 
                        </div>
                        <div class="noticeboardtitle text-center">
                            <?php echo $notice['title']; ?>
                        </div>

                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-sm-12 m-1 noticeboardmessage">
                        <?php echo htmlspecialchars_decode($notice['message']); ?>
                    </div>
                </div>
                <div class="row m-0">
                    <div class="col-sm-12 noticeboardfooter">
                        <div class="pr-2 text-right smallest">
                            <?php
                                //If comments are enabled, show "Comments" coount and expander link
                                if($notice['allow_comments']==1) {
                            ?>
                                <a href="#" class="mr-1" data-toggle="modal" data-target="#noticeboardcommentsmodal" data-id="<?php echo $notice['id']; ?>">[<?= $notice['comment_count'] ?> comments]</a>
                            <?php
                                }    
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            } elseif ($count > 0 && $count < 3) {
                //Second and third noticeboard items    
                //Using bootstrap flex model, two columns each containing a noticeboard item
                if($count==1) {
                ?>
            <div class="row m-0 p-0 smaller">
                <?php
                }
                ?>
                <div class="col-sm-6 m-0 p-1">
                    <div class="border rounded noticeboarditem p-0">
                        <div class="row m-0 p-0">
                            <div class="col-sm-12 noticeboardheader">
                                <div class="w-100 noticeboardpostedby text-right">
                                    <?php echo date("d M Y", strtotime($notice['publish_date'])); ?> - 
                                    <?php echo $notice['real_name']; ?> 
                                </div>
                                <div class="noticeboardtitle text-center">
                                <?php echo $notice['title']; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-sm-12 m-1 noticeboardmessage">
                                <?php echo htmlspecialchars_decode($notice['message']); ?>
                            </div>
                        </div>
                        <div class="row m-0">
                    <div class="col-sm-12 noticeboardfooter">
                        <div class="pr-2 text-right smallest">
                            <?php
                                //If comments are enabled, show "Comments" coount and expander link
                                if($notice['allow_comments']==1) {
                            ?>
                                <a href="#" class="mr-1" data-toggle="modal" data-target="#noticeboardcommentsmodal" data-id="<?php echo $notice['id']; ?>">[<?= $notice['comment_count'] ?> comments]</a>
                            <?php
                                }    
                            ?>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
                <?php
                if($count==2 || $count==$total-1) {
                ?>
            </div>
                <?php
                }
            } else {
                //Fourth and subsequent noticeboard items
                //Using bootstrap flex model, four columns each containing a noticeboard item's title but not the message
                // - start a new div/row every fourth noticeboard item, eg: 3 to 7, 8 to 11, 12 to 15, etc
                if(($count-3) % 4 == 0) {
                    ?>
            <div class="row m-0 p-0 smallest">
                    <?php
                }
                ?>
                <div class="col-sm-3 m-0 p-0">
                    <div class="border rounded noticeboarditem">
                        <div class="row mb-1 noticeboardheader">
                            <div class="col-sm-12 noticeboardtitle">
                                <?php echo $notice['title']; ?>
                            </div>
                        </div>
                    </div>
                <?php
                if(($count-3) % 4 == 3) {
                    ?>
            </div>
                    <?php
                }
            }
            $count++;
        }
        ?>
        </div>
    </div>
</div>
