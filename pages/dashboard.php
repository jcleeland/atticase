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

        <div class="row justify-content-sm-center">

            <div class="col-lg border rounded m-1">
                <?php include("pages/dashboard/statistics.php"); ?>
            </div>

            <div class="col-lg border rounded filter m-1">
                <?php include("pages/dashboard/mycases.php"); ?>

            </div>

        </div>

        <div class="row justify-content-sm-center">

            <div class="col-lg border rounded m-1">
                <?php include("pages/dashboard/mytodo.php"); ?>
            </div>

            <div class="col-lg border rounded m-1">
                <?php include("pages/dashboard/myrecent.php"); ?>
            </div>

        </div>

    </div>