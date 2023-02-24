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
?>
        <div class="col-sm-12 mb-1">
            <div class="row  w-100">
                <div class="col-sm-3 filter m-1 p-2 bg-light rounded">
                <?php
                    include("pages/reportlist.php");
                ?>
                </div>
                <div class='col-sm report border rounded m-1 bg-white'>
                <?php
                    include("reports/default.php");
                ?>
                </div>
            </div>
        </div>