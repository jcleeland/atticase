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

use Microsoft\Graph\Generated\Applications\Item\Synchronization\Jobs\Item\Pause\PauseRequestBuilder;

?>
<html>
    <head>
        <title>
            AttiCase 3 - Installation
        </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">        
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="css/default.css" />
    <!-- Jquery -->
    <script src="js/jquery/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="js/jquery/jquery-ui-1.12.1/jquery-ui.min.css" />
    <script src="js/jquery/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <!-- Popper.js (must be before bootstrap and after jquery) -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap -->
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <!-- Encryption -->
    <script src="js/crypto-js.min.js"></script>
    <!-- Casetracker javascripts -->
    <!-- <script src="js/default.js"></script> -->
    <!-- <script src="js/index.js"></script> -->

    </head>
    <body>

    <input type='hidden' name='today_start' id='today_start' value='<?php echo $todaystart ?>' />
    <input type='hidden' name='today_end' id='today_end' value='<?php echo $todayend ?>' />
        <div class="row h-25 justify-content-center align-items-center">
            <div class="col-5">
                <div class='col mb-3 p-0'>
                    <h3 style="font-weight: bold"><img src='images/logo.png'>AttiCase</h3>
                </div>
            </div>
        </div>
        <div class="row justify-content-center align-items-center" id="buildingdatabase">
           <div class="col-5">
                <div class="mb-3 center">
                   <h2>Building Database</h2>
                </div>
                <div class="mb-3">
                    <div id="statusMessage">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <script>$('#statusMessage').html("Reading database structure from setup file...");</script>

<?php
ob_flush();
flush();
 require_once(__DIR__."/databasestructure.php");

$sqlStatements = [];

foreach ($tables as $tableName => $fields) {
    // Initialize the CREATE TABLE statement
    $prefixedTableName = $oct->dbprefix . $tableName;
    echo "<script>$('#statusMessage').html('Creating sql for $prefixedTableName...');</script>\n";
    ob_flush();
    flush();
    $sql = "CREATE TABLE IF NOT EXISTS `$prefixedTableName` (\n";
    // Add the field definitions
    $fieldDefinitions = [];
    foreach ($fields as $fieldName => $fieldDefinition) {
        echo "      <script>$('#statusMessage').html('Initialising field $fieldName...');</script>\n";
        ob_flush();
        flush();
        $fieldDefinitions[] = "  `$fieldName` $fieldDefinition";
    }
    $sql .= implode(",\n", $fieldDefinitions);

    // Add the index definitions
    if (isset($indexes[$tableName])) {
        $indexDefinitions = [];
        foreach ($indexes[$tableName] as $indexType => $indexDefinition) {
            echo "<script>$('#statusMessage').html('Building the index $indexDefinition...');</script>\n";
            ob_flush();
            flush();
            $indexDefinitions[] = "  $indexType $indexDefinition";
        }
        $sql .= ",\n" . implode(",\n", $indexDefinitions);
    }

    // Close the CREATE TABLE statement
    $sql .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    // Add the generated SQL to the array of statements
    $sqlStatements[$tableName] = $sql;
}

$results=[];
foreach($sqlStatements as $tableName => $sql) {
    echo "<script>$('#statusMessage').html('Constructing $tableName...');</script>\n";
    ob_flush();
    flush();
    $results[$tableName]=$oct->execute($sql);
    usleep(31000);
}
?>

        <script>$('#statusMessage').html("<span style='color: green;'>&#10004;</span> Database structure ready.");</script>
        <div class="row justify-content-center align-items-center" id="basicsettings">
            <div class="col-5">
                <div class="mb-3 center">
                    <h3>Creating basic settings</h3>
                </div>
                <div class="mb-3">
                    <div id="statusMessage2">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
<?php
$results2=[];
foreach($initialdata as $tableName => $rows) {
    echo "<script>$('#statusMessage2').html('Inserting initial data into $tableName...');</script>\n";
    ob_flush();
    flush();
    foreach($rows as $data) {
        $results2[$tableName]=$oct->insertTable($tableName, $data, 0);
        usleep(31000);
    }
    
    
}

?>
        <script>$('#statusMessage2').html("<span style='color: green;'>&#10004;</span> Initial data generated.");</script>
        <div class="row justify-content-center align-items-center">
            <div class="col-5">
                <div class="mb-3 center">
                    <h3>Creating Administration Account</h3>
                    <div id="statusMessage3">
                        <p>Create the administration account. You need this to log in for the first time and for all future administration purposes, including creating other users.</p>
                        <p class='smaller'>The administrator account is different to other user accounts. If you use LDAP or Active Directory this account will be the only one that doesn't require confirmation from your domain that there is a matching user.</p>
                        <p class='smaller'>Generally speaking, it is best for this account to have a role based name such as 'Atticase Administrator' or 'Atticase Admin' and doesn't get used for assigning or working on cases. If you'll be a user of this system as well as an administrator, create a second account for yourself to use for case management purposes after completing the installation process.</p>
                    </div>
                </div>
                <form id='adminUserAccountForm'>
                    <div class='form-group'>
                        <div class='w-75 floatright'><input type='text' class='form-control' name='adminuser' id='adminuser' placeholder='Administrator User Name' value='administrator' /></div>
                        <div class='floatright w-25'>Username</div><div style='clear: both'></div>       
                    </div>
                    <div class='form-group'>
                        <div class='w-75 floatright'><input type='text' class='form-control' name='adminrealname' id='adminrealname' placeholder='Atticase Administrator' value='Atticase Administrator' /></div>
                        <div class='floatright w-25'>Actual name</div><div style='clear: both'></div>       
                    </div>                
                    <div class='form-group'>
                        <div class='w-75 floatright'><input type='text' class='form-control' name='adminemail' id='adminemail' placeholder='Administrator Email' /></div>
                        <div class='floatright w-25'>Email:</div><div style='clear: both'></div>       
                    </div>
                    <div class='form-group'>
                        <div class='w-75 floatright'><input type='password' class='form-control' name='adminpassword' id='adminpassword' placeholder='Administrator Password' /></div>
                        <div class='floatright w-25'>Password:</div><div style='clear: both'></div>       
                    </div>
                    <div class='form-group'>
                        <div class='w-75 floatright'><input type='password' class='form-control' name='adminpasswordconfirmation' id='adminpasswordconfirmation' placeholder='Administrator Password' /></div>
                        <div class='floatright w-25'>Confirm Password:</div><div style='clear: both'></div>       
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#adminpassword, #adminpasswordconfirmation').on('keyup', function() {
                                if ($('#adminpassword').val() != "" && $('#adminpassword').val() === $('#adminpasswordconfirmation').val()) {
                                    $('#createAdmin').prop('disabled', false);
                                } else {
                                    $('#createAdmin').prop('disabled', true);
                                }
                            });
                            $('#adminpasswordconfirmation').on('blur', function() {
                                if ($('#adminpassword').val() !== $('#adminpasswordconfirmation').val()) {
                                    alert("The passwords do not match. Please try again.");
                                    $('#adminpasswordconfirmation').val('');
                                }
                            });
                        });
                    </script>                                
                    <div class="mb-3 center">
                        <input type='button' id='createAdmin' class='btn btn-primary' disabled value='Register administrator' />
                        <!-- script to call the ajax function to create the admin user -->
                        <script>
                            $(document).ready(function() {
                                $('#createAdmin').click(function() {
                                    var username = $('#adminuser').val();
                                    var realname = $('#adminrealname').val();
                                    var email = $('#adminemail').val();
                                    var password = $('#adminpassword').val();
                                    
                                    return $.ajax({
                                        url: 'helpers/ajax/userCreate.php',
                                        method: 'POST',
                                        data: {
                                            method: 'userCreate',
                                            username: username,
                                            realname: realname,
                                            password: password,
                                            email: email,
                                            group: 1,
                                            accountenabled: 1
                                        },
                                        dataType: 'json'
                                    }).done(function(response) {
                                        console.log(response);
                                        if (response.substr(0, 5) == "Error") {
                                            $('#statusMessage3').html("<span style='color: red;'>&#10008;</span> " + response);
                                        } else {
                                            $('#adminUserAccountForm').hide();
                                            $('#statusMessage3').html("<span style='color: green;'>&#10004;</span> Administrator user created.");
                                            $('#completed').show();
                                        }
                                    }).fail(function(jqXHR, textStatus, errorThrown) {
                                        console.error('AJAX request failed:', textStatus, errorThrown);
                                        $('#statusMessage3').html("<span style='color: red;'>&#10008;</span> AJAX request failed: " + textStatus);
                                    });
                                });
                            });
                        </script>
                    </div> 
                </form>               
            </div>
        </div>
        <div class="row justify-content-center align-items-center" style="display: none" id="completed">
            <div class="col-5">
                <div class="mb-3 center">
                    <div id="statusMessage4">
                        <h3>Ready to go</h3>
                        <p><span style='color: green;'>&#10004;</span> Initial setup is now complete.</p>
                        <p>Click the button below to complete the installation process. You will be redirected to the administration settings page.</p>
                    </div>
                </div>
                <div class="mb-3 center">
                    <!-- button to load index.php -->
                    <input type='button' id='complete' class='btn btn-primary' value='Complete Installation' />
                    <!-- script to load index.php when button is clicked -->
                    <script>
                        $('#complete').click(function() {
                            window.location.href = 'index.php?page=options';
                        });
                    </script>
                </div>
            </div>
    </body>
</html>