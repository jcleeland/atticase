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
<div class="row h-50 justify-content-center align-items-center">
    <form class="col-5" method="post" style='z-index: 9999'>
        <input type='hidden' name='login' value='true' />
        <div class='col mb-3 p-0'>
            <h3 style="font-weight: bold"><img src='images/logo.png'>AttiCase</h3>
        </div>
        <div class="col header mb-3">
            Login
        </div>
        <div class='form-group'>
            <input type='text' class='form-control' name='username' id='username' placeholder='Username' />
        </div> 
        <div class='form-group'>
            <input type='password' class='form-control' name='password' id='password' placeholder='Password' />       
        </div>
        <div class='form-group'>
            <button class='btn btn-primary' style='z-index: 9999'>Login</button>
        </div>
    </form> 
    <div class="row mb-3" style='position: fixed; bottom: 0;'>
        <div class="col"></div>
        <div class="col-8 text-center smaller border rounded p-3 text-muted">
            This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
            the Free Software Foundation, either version 3 of the License, or (at your option) any later version. See the GNU General Public License 
            for more details.<br /><br />

            <a href='https://www.gnu.org/licenses/' target='_blank'>https://www.gnu.org/licenses/</a><br />
            Copyright 2022 Jason Cleeland
        </div>
        <div class="col"></div>
    </div>
</div>
