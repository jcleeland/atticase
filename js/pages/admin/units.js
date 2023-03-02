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
$(function() {
    $('.updateunitfield').change(function() {
        if($(this).is(':checkbox')) {
            var value=$(this).is(':checked') ? 1 : 0;
        } else {
            var value=$(this).val();
        }
        var field=$(this).attr('action');
        var unitid=$(this).attr('unitid');
        var currentId=$(this).attr('id');
        $('#'+currentId).removeClass('fieldUpdated');        
        console.log(unitid, field, value);
        $.when(unitUpdate(unitid, field, value)).done(function(output) {
            console.log(output);
            $('#'+currentId).addClass('fieldUpdated'); 
            window.location.href="?page=options&option=units";
        })
    })
    
    $('.createunit').click(function() {
        var unitdescrip=$('#unitdescrip').val();
        var listpos=$('#listposition').val();
        var showin=$('#showinlist').is(':checked') ? 1 : 0;
        var parentid=$('#parentid').val();
        console.log(unitdescrip, listpos, showin, parentid);
        
        $.when(unitCreate(unitdescrip, listpos, showin, parentid)).done(function(output) {
            if(output.results != "Error - No unit description provided") {
                window.location.href="?page=options&option=units";
            }
        })
        
    });
});

function deleteUnit(id) {
    if(confirm("Are you sure you want to delete this unit? Note that deleting removes the item from the list of options, and also deletes any children it has - but does not delete already existing data stored against a case.")) {
        $.when(unitDelete(id)).done(function() {
            window.location.href="?page=options&option=units"; 
        })
    }
}