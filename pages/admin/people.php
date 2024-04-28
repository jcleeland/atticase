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
<script src="js/pages/admin/people.js"></script>
<?php
$users=$oct->userList(array(), null, null, 0, 1000000000);

//Get a list of case types
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($userselect); echo "</pre>";

$recordPage = isset($_GET['recordpage']) ? (int)$_GET['recordpage'] : 1;

$recordsPerPage = isset($_GET['recordsPerPage']) ? (int)$_GET['recordsPerPage'] : 10;
$firstRecord = ($recordPage - 1) * $recordsPerPage;
$lastRecord = $firstRecord + $recordsPerPage - 1; // Calculate the last record for the page

$searchTerm = $_GET['search'] ?? '';
$searchCondition = $searchTerm ? "surname LIKE '%$searchTerm%' OR pref_name LIKE '%$searchTerm%'" : "1=1";

// Get total record count for pagination
$totalRecords = $oct->countMembers($searchCondition);
$totalPages = ceil($totalRecords / $recordsPerPage);
if($recordPage > $totalPages) {
    $recordPage=$totalPages;
    $firstRecord = ($recordPage - 1) * $recordsPerPage;
    $lastRecord = $firstRecord + $recordsPerPage -1;
}

// Fetch members with pagination and search
$sortOrder = $_GET['orderBy'] ?? 'surname, pref_name';
$order=$_GET['order'] ?? "ASC";
$members = $oct->memberList(array(), $searchCondition, $sortOrder, $firstRecord, $lastRecord, $order);

//$members = $oct->memberList(array(), $searchCondition, "surname, pref_name", $firstRecord, $lastRecord);

// Determine total pages (may require additional query to count total records)
// Assuming you have a way to get total record count
$totalPages = ceil($totalRecords / $recordsPerPage);
// Calculate range of pages to display
$startPage = max(1, $recordPage - 5);
$endPage = min($totalPages, $startPage + 9);

// Adjust startPage if less than 10 pages are remaining
if ($endPage - $startPage < 9) {
    $startPage = max(1, $endPage - 9);
}
//echo "<pre>"; print_r($members); echo "</pre>";
//$members=$oct->memberList(array(), "1=1", "surname,  pref_name", 0, 500);


//Get a list of member numbers in the tasks/cases table that aren't matched by an entry in the member_cache table

$query="SELECT distinct member, name from ".$oct->dbprefix."tasks WHERE member NOT IN (SELECT distinct member from ".$oct->dbprefix."member_cache) ORDER BY member";
$results = $oct->fetchMany($query, array(), 0, 1000000000);
$missing = count($results['output']);
$missings = $results['output'];

?>





<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">Clients</h4>

            <nav aria-label="Page navigation" style="display: flex; align-items: center; justify-content: space-between;" class="smaller w-100 p-1">
                <ul class="pagination">
                <!-- First Page Link -->
                <li class="page-item <?= $recordPage <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=options&option=people&recordpage=1&search=<?= htmlspecialchars($_GET['search'] ?? '') ?>&recordsPerPage=<?= $_GET['recordsPerPage'] ?? '10' ?>">First</a>
                </li>
                <!-- Previous Page Link -->
                <li class="page-item <?= $recordPage <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=options&option=people&recordpage=<?= $recordPage - 1; ?>&search=<?= htmlspecialchars($_GET['search'] ?? '') ?>&recordsPerPage=<?= $_GET['recordsPerPage'] ?? '10' ?>">Previous</a>
                </li>

                <!-- Page Number Links -->
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <li class="page-item <?= $i == $recordPage ? 'active' : '' ?>">
                    <a class="page-link" href="?page=options&option=people&recordpage=<?= $i; ?>&search=<?= htmlspecialchars($_GET['search'] ?? '') ?>&recordsPerPage=<?= $_GET['recordsPerPage'] ?? '10' ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next Page Link -->
                <li class="page-item <?= $recordPage >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=options&option=people&recordpage=<?= $recordPage + 1; ?>&search=<?= htmlspecialchars($_GET['search'] ?? '') ?>&recordsPerPage=<?= $_GET['recordsPerPage'] ?? '10' ?>">Next</a>
                </li>
                <!-- Last Page Link -->
                <li class="page-item <?= $recordPage >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=options&option=people&recordpage=<?= $totalPages; ?>&search=<?= htmlspecialchars($_GET['search'] ?? '') ?>&recordsPerPage=<?= $_GET['recordsPerPage'] ?? '10' ?>">Last</a>
                </li>    
                </ul>

                <ul class="pagination" style="margin-bottom: 0">
                <!-- Search Box and Direct Page Number Input -->
                <form action="" method="get" style="display: flex; align-items: center;">
                    <input type="hidden" name="page" value="options">
                    <input type="hidden" name="option" value="people">

                    <!-- Search Input -->
                    <label style="margin-right: 5px;" class="smaller">Search:</label>
                    <input type="text" name="search" class="form-control p-1 smaller" placeholder="Search..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="margin-right: 10px;">

                    <!-- Records Per Page Dropdown -->
                    <label style="margin-right: 5px;" class="smaller">Records:</label>
                    <select name="recordsPerPage" class="form-control p-1 smaller" onchange="this.form.submit()" style="margin-right: 10px; max-width: 40px">
                        <option value="10" <?= (isset($_GET['recordsPerPage']) && $_GET['recordsPerPage'] == '10') ? 'selected' : '' ?>>10</option>
                        <option value="20" <?= (isset($_GET['recordsPerPage']) && $_GET['recordsPerPage'] == '20') ? 'selected' : '' ?>>20</option>
                        <option value="50" <?= (isset($_GET['recordsPerPage']) && $_GET['recordsPerPage'] == '50') ? 'selected' : '' ?>>50</option>
                    </select>

                    <!-- Page Number Input -->
                    <label style="margin-right: 5px;" class="smaller">Page:</label>
                    <input type="number" name="recordpage" class="form-control p-1 smaller" min="1" max="<?= $totalPages; ?>" placeholder="Page Number" value="<?= $recordPage ?>" style="width: 60px; margin-right: 10px;">

                    <button type="submit" class="btn btn-sm btn-primary" >Go</button>&nbsp;
                    <button type="button" class="btn btn-sm btn-secondary" onclick="window.location.href='?page=options&option=people';">Clear</button>
                </form>
                </ul>  
            </nav>

            <div class="row border rounded centered">
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-2">
                        <div class="col-sm-1">
                            <a class='btn' onclick="sortResults('member')">Identifier</a>
                        </div>
                        <div class="col-sm">
                            <a class='btn' onclick="sortResults('surname')">Surname</a>
                        </div>
                        <div class="col-sm-2">
                            <a class='btn' onclick="sortResults('pref_name')">Preferred Name</a>
                        </div>
                        <div class="col-sm-1">
                            <a class='btn' onclick="sortResults('joined')">Started</a>
                        </div>
                        <div class="col-sm-2">
                            <a class='btn'>More</a>
                        </div>
                        <div class="col-sm-1">
                            <a class='btn'>Action</a>
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2" style="min-height: 40vh; max-height: 50vh" id="listParent" >

<?php
foreach($members['results'] as $client) {
    $id=$client['member'];   
    $clientData=$client['data']; 
    $clientCases=$client['cases'];
?>                
                    <div class="row mb-2 p-1" id="client_row_<?php echo $id ?>">
                        <input type="hidden" name="id[]" value="<?php echo $id ?>" />
                        <div class="col-sm-1">
                            <input action='member' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatemember' type='text' id='member<?php echo $id ?>' value='<?php echo $client['member'] ?>' />
                        </div>
                        <div class="col-sm">
                            <input action='lastname' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatemember' type='text' id='surname<?php echo $id ?>' value='<?php echo $client['surname'] ?>' />
                        </div>
                        <div class="col-sm-2">
                            <input action='position' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatemember' type='text' id='pref_name<?php echo $id ?>' value='<?php echo $client['pref_name'] ?>' />
                        </div>
                        <div class="col-sm-1">
                            <input action='phone' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatemember' type='text' id='joined<?php echo $id ?>' value='<?php 
                            $updatedDate=false;
                            if(!empty($client['joined'])) {
                                echo date("Y-m-d", $client['joined']);
                            } elseif (!empty($client['data']) && isset($client['data']['membershipAssociation']['association']['from'])) {
                                $updatedDate=true;
                                echo date("Y-m-d", strtotime($clientData['membershipAssociation']['association']['from']));
                                $oct->updateTable("member_cache", array("joined"=>strtotime($clientData['membershipAssociation']['association']['from'])), "member=$id", $oct->userid);
                            }
                            ?>' style='min-width: 75px' />
                            <?php
                            if ($updatedDate) {
                                ?>
                                <script type='text/javascript'>$('#joined<?php echo $id ?>').addClass('fieldUpdated');</script>
                                <?php
                            }
                            ?>
                        </div>
                        <!-- Button to trigger popup -->
                        <div class="col-sm-2 popup-button-container">
                            <button type="button" class="btn btn-info btn-sm" onclick="togglePopup('data_popup_<?php echo $id ?>')">Data</button>
                            <!-- Popup Data Div (initially hidden) -->
                            <div id="data_popup_<?php echo $id ?>" class="popup-container scrollable-div custom-popup-height-30 custom-popup-width-50" style="display:none;">
                                <?php
                                if (!empty($clientData)) {
                                    displayData($clientData, $client['modified']);                                
                                }
                                ?>
                            </div>
                            <button type="button" class="btn btn-info btn-sm" onclick="togglePopup('case_popup_<?php echo $id ?>')">Cases</button>
                            <!-- Popup Case Div (initially hidden) -->
                            <div id="case_popup_<?php echo $id ?>" class="popup-container scrollable-div custom-popup-height-30 custom-popup-width-50" style="display:none;">
                                <?php
                                if (!empty($clientCases)) {
                                    displayCases($clientCases);                                
                                }
                                ?>
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <span class="btn btn-sm btn-warning" title="Delete this client/member from Atticus database" onClick="deleteClient('<?php echo $id ?>');">Del</span>
                        </div>
                    </div>
                    <div class="row m-2 hidden connectionlists smaller" id="connectionlist<?php echo $id ?>">
                        <div id="connections<?php echo $id ?>" class="border rounded">
                        </div>    
                    </div>
<?php
}
?>                
                
                
                
                </div>
                </form>
            </div>
            
            <h4 class="header mt-3">Data options</h4>
            <?php
            if($oct->getSetting("externaldb", "useexternaldb")==1 && $oct->getSetting("externaldb", "externaldb") != "") {
            ?>
            <div class="row border rounded centered">
                <div class="p-2 w-100 mb-1 popup-button-container">
                    <span class='btn btn-info' onclick="togglePopup('missing_popup')">Find <?php echo $missing ?> missing entries</span>
                    <div id="missing_popup" class="popup-container scrollable-div custom-popup-height-30 custom-popup-width-50" style="display: none">
                        <div class="row mb-1">
                            <div class="col-sm-4">Identifier</div>
                            <div class="col-sm-4">Name</div>
                            <div class="col-sm-4">Action</div>

                        </div>
                    <?php
                        foreach($missings as $missed) { 
                    ?>
                        <div class="row mb-1 small-text" id="missing_<?=$missed['member'] ?>">
                            <div class="col-sm-4"><?php echo $missed['member'] ?></div>
                            <div class="col-sm-4"><?php echo $missed['name'] ?></div>
                            <div class="col-sm-4"><span class='btn btn-sm btn-info' data-identifier='<?= $missed['member'] ?>' onClick='updatePersonInfo("<?= $missed['member'] ?>")'>Update</span></div>
                        </div>
                    <?php
                        }
                    ?>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>


            <h4 class="header mt-3">Add New Person</h4>
            <div class="row border rounded centered">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm">
                            Identifier
                        </div>
                        <div class="col-sm">
                            Surname
                        </div>
                        <div class="col-sm">
                            Preferred Name
                        </div>
                        <div class="col-sm-2">
                            Started
                        </div>

                        <div class="col-sm">
                            &nbsp;
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2 w-100" >
                       <div class="row mb-1">
                        <div class="col-sm">
                                <input action='firstname' class='form-control p-1 smaller createclientitem' type='text' id='identifier' />
                            </div>                        
                            <div class="col-sm">
                                <input action='firstname' class='form-control p-1 smaller createclientitem' type='text' id='surname' />
                            </div>
                            <div class="col-sm">
                                <input action='lastname' class='form-control p-1 smaller createclientitem' type='text' id='pref_name'  />
                            </div>
                            <div class="col-sm-2">
                                <input action='position' class='form-control p-1 smaller createclientitem' type='text' id='joined' />
                            </div>
                            <div class="col-sm text-center">
                                <span class='btn btn-sm btn-main createclientbutton'>Add</span>
                            </div>      
                       </div>          
                </div>            
        </div>
    </div>
</div>

<?php
function displayData($data, $modified, $level = 0) {
    if(!empty($modified)) {
        echo "<b class='small-text'>Data last updated ".date("d M Y", $modified)."</b><br />";
    }

    echo "<ul style='padding-left: 1em'>"; // Start an unordered list for this level
    
    foreach ($data as $key => $value) {
        echo "<li>";
        echo "<div class='data-heading small-text' data-level='$level'>$key</div>";
        
        if (is_array($value)) {
            // Recursively call the function for nested arrays
            displayData($value, null, $level + 1);
        } else {
            echo "<div class='data-content small-text' data-level='$level'>";
            if ($value === null) {
                echo "<em>null</em>"; // Display 'null' or some placeholder for null values
            } else {
                echo htmlentities($value);
            }
            echo "</div>";
        }
        
        echo "</li>";
    }
    
    echo "</ul>"; // Close the unordered list for this level
}

function displayCases($cases) {
    global $oct;
    echo "<ul style='padding-left: 1em'>"; //Start an unordered list for these cases
    foreach(explode(", ", $cases) as $case) {
        $details=$oct->getCase($case);
        echo "<li>";
        echo "<div class='data-heading small-text'><a href='?page=case&case=".$details['results']['task_id']."'>#".$details['results']['task_id']."</a> (".date("d M Y", $details['results']['date_opened']).")</div>";
        echo "<div class='data-content small-text'>";
        echo htmlentities($details['results']['item_summary']);
        echo "</div>";
        //print_r($details);
        echo "</li>";
    }
    echo "</ul>";
}


    //$oct->showArray($casetypes);
?>