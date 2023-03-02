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
//Connect using MySQLi

//This class deals with all data transactions

class oct {
    var $dbpass;
    var $dbuser;
    var $dbhost;
    var $dbname;
    var $dbtype="mysql";
    var $dbprefix="casetracker_";
    var $db;
    var $externalDb=true;
    var $config;
    var $userid;
    
    var $caseitems=array(
        "date_due"=>array(
            "Title"=>"Review date",
            "Sort"=>array(
                "ASC"=>"Most recent first",
                "DESC"=>"Oldest first"
            )
        ),
        "date_closed"=>array(
            "Title"=>"Date closed",
            "Sort"=>array(
                "ASC"=>"Most recent first",
                "DESC"=>"Oldest first"
            )
        ),
        "item_summary"=>array(
            "Title"=>"Summary",
            "Sort"=>array(
                "ASC"=>"Alphabetically",
                "DESC"=>"Reverse Alphabetically"
            ),
        ),
        "client_name"=>array(
            "Title"=>"Client",
            "Sort"=>array(
                "ASC"=>"Alphabetically",
                "DESC"=>"Reverse alphabetically"
            )
        ),
        "tasktype_name"=>array(
            "Title"=>"Case type",
            "Sort"=>array(
                "ASC"=>"Alphabetically",
                "DESC"=>"Reverse alphabetically",
            )
        ),         
        "category_name"=>array(
            "Title"=>"Department",
            "Sort"=>array(
                "ASC"=>"Alphabetically",
                "DESC"=>"Reverse alphabetically"
            )
        ),       
        "u_dot_real_name"=>array(
            "Title"=>"Officer",
            "Sort"=>array(
                "ASC"=>"Alphabetically",
                "DESC"=>"Reverse alphabetically"
            )
        ),

    );
    
    var $displayitems=array(
    
    );
    
    
    function connect() {
        $dsn=$this->dbtype.":host=".$this->dbhost.";dbname=".$this->dbname.";charset=UTF8";
        $options = [
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES      => true,
        ];
        try {
            $this->db=new PDO($dsn, $this->dbuser, $this->dbpass, $options);
        } catch(\PDOException $e) {
            //Send the error message to the error popup ($e->getMessage())
            //throw new \PDOException($e->getMessage(), (int)$e->getCode());
            echo "Database connection failed: ".$e->getMessage();
        }
    }
    
    function getSetting($section, $name) {
        return $this->config[$section][$name]['value'];
    }
    
    function execute($query, $parameters=array()) {
          $stmt=$this->db->prepare($query) or die("The prepared statement ($query) does not work");
          $stmt->execute($parameters);
          return($stmt->rowCount());
    }
    
    function fetch($query, $parameters=array()) {
          $stmt=$this->db->prepare($query) or die("The prepared statement ($query) does not work");
          $stmt->execute($parameters);
          return($stmt->fetch(PDO::FETCH_ASSOC));
    }
    
    function fetchMany($query, $parameters=array(), $first=0, $last=100000000, $debug=false) {
          if($debug) {
              echo "<pre>DEBUGGING INFO\r\n";
              print_r($query);
              echo "\r\nPARAMETERS\r\n";
              print_r($parameters);
          }
          $stmt=$this->db->prepare($query) or die("The prepared statement ($query) does not work");
          if($debug) {
              echo "\r\nPREPARED STATEMENT\r\n";
              print_r($stmt);
              echo "\r\nERROR INFO<br />";
              print_r($this->db->errorInfo());
              echo "\r\n-----";
          }

          $stmt->execute($parameters);
          $count=0;

          $output=array();  //Create an empty array for the results
          while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
              if($debug) {
                  echo "\r\nFETCHING!\r\n";
                  print_r($row);
              }
              if($count >= $first && $count <= $last) {
                $output[]=$row;
              }
              $count++;
          }
          return(array("output"=>$output, "records"=>$count, "query"=>$query));
    }



    ##### GETS ######
    ##### These are usually queries with a single return #####

    
    function getAttachment($attachmentid) {
        $query = "SELECT orig_name, file_name, file_type ";
        $query .= "FROM ".$this->dbprefix."attachments ";
        $query .= "WHERE attachment_id=:attachmentid";
        $parameters[':attachmentid']=$attachmentid;
        
        $results=$this->fetchMany($query, $parameters);
        $output=array("results"=>$results['output'][0], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        //print_r($output);  
        return($output);                            
    }
    
    /**
    * Returns case information
    * 
    * @param mixed $caseid
    */
    function getCase($caseid) {
        /*
            Do some checks to make sure that this person is entitled to see this case
        */
        $cookies=$this->getCookies("OpenCaseTrackerSystem");
        $userId=$cookies->user_id;
        $userAccount=$this->getUserAccount($userId);
        $permissions=$this->getUserPermissions($userId);
        //$this->showArray($permissions);
        
        $query="SELECT t.*, p.*, lt.*, lc.*, lv.*, uc.real_name as closedby_real_name, uo.real_name as openedby_real_name, flr.*, mst.*, u.real_name as assigned_real_name, ue.real_name as last_edited_real_name, t.member as clientname";
        if($this->externalDb===true) {
            $query .= ", mem.data, CONCAT(mem.pref_name, ' ', mem.surname) as clientname, mem.modified as externaldbmodified";
        }
        $query.="\r\n                 FROM ".$this->dbprefix."tasks t

                     LEFT JOIN ".$this->dbprefix."projects p ON t.attached_to_project = p.project_id
                     LEFT JOIN ".$this->dbprefix."list_tasktype lt ON t.task_type = lt.tasktype_id
                     LEFT JOIN ".$this->dbprefix."list_category lc ON t.product_category = lc.category_id
                     LEFT JOIN ".$this->dbprefix."list_version lv ON t.product_version = lv.version_id
                     LEFT JOIN ".$this->dbprefix."users u ON t.assigned_to = u.user_id
                     LEFT JOIN ".$this->dbprefix."users uo ON t.opened_by = uo.user_id
                     LEFT JOIN ".$this->dbprefix."users ue on t.last_edited_by = ue.user_id
                     LEFT JOIN ".$this->dbprefix."list_resolution flr ON t.resolution_reason = flr.resolution_id
                     LEFT JOIN ".$this->dbprefix."master mst ON t.task_id = mst.servant_task
                     LEFT JOIN ".$this->dbprefix."users uc ON t.closed_by = uc.user_id
                 ";
        
        //External Database Link
        if($this->externalDb===true) {
            $query .= "    LEFT JOIN ".$this->dbprefix."member_cache mem ON mem.member=t.member
                 ";
        }        
        //CONDITIONS         
        $query .= "WHERE t.task_id = :task_id";
        
        $parameters[':task_id']=$caseid;
        
        //PERMISSIONS
        if($permissions['restrict_versions']==1) {
            $query .= "\r\nAND product_version IN ";
            $query .= "('".implode("','", explode(",", $permissions['versions']))."')";
        }
        //RESTRICTED
        if($permissions['is_admin'] != 1) {
            //don't show any restricted cases not assigned to this person
            $query .= "\r\nAND NOT (is_restricted=1 AND assigned_to != $userId)";
        }
        
        //SYSTEM SETTING RESTRICTS ACCES TO ONLY THIS USERS CASES
        //$this->showArray($this->config['general']);
        if($this->config['general']['restrict_view']['value'] == 1 && $permissions['is_admin'] !=1) {
            //the system is set to restrict access to all cases except ones assigned to user
            $query .= "\r\nAND assigned_to = $userId";
        }        
        //echo $query;
        $results=$this->fetchMany($query, $parameters);
        
        if($results['records'] > 0) {
            if($this->externalDb === true && ($results['output'][0]['clientname']=="" || $results['output'][0]['clientname']==" ")) {

                $results['output'][0]['clientname']=$results['output'][0]['member'];
                if($results['output'][0]['member']=="0") {
                    $results['output'][0]['clientname']="None";
                }            
            }
            
            //Get Custom Field Information
            
            $query2 = "SELECT * FROM ".$this->dbprefix."custom_fields WHERE task_id = :task_id";
            $parameters[':task_id']=$caseid;
            $results2=$this->fetchMany($query2, $parameters);
            
            if($results2['records'] > 0) {
                foreach($results2['output'] as $key=>$val) {
                    $thisname="custom_field_".$val['custom_field_definition_id'];
                    $thisval=$val['custom_field_value'];
                    //print_r($val);
                    if($val != "") {
                        $customlist[]=$thisname;
                        $results['output'][0][$thisname]=$thisval;
                    }
                }
                if(is_array($customlist)) {
                    $results['output'][0]['customlist']=$customlist;
                }
            }
            //print_r($results2);
            
            
            $output=array("results"=>$results['output'][0], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records'], "message"=>"Retrieved");
            //print_r($output); 
            //$this->showArray($this->config);
            //$this->config['general']['restrict_view']==true then only see own cases             
        } else {
            $output=array("results"=>null, "query"=>$query, "parameters"=>$parameters, "count"=>0, "total"=>0, "message"=>"Refused");
        }
        

        return($output);                
    }
    
    function getCustomFields($caseid) {
        $parameters=array(":task_id"=>$caseid);
        $query = "SELECT * FROM ".$this->dbprefix."custom_fields WHERE task_id = :task_id";
        $results=$this->fetchMany($query, $parameters);
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        return($output);
    }
    
    function getLastComment($caseid) {
        $query = "SELECT *";
        $query .="\r\n  FROM ".$this->dbprefix."comments";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."users ON ".$this->dbprefix."comments.user_id = ".$this->dbprefix."users.user_id";
        $query .="\r\nWHERE task_id=$caseid";
        $query .="\r\nORDER BY date_added DESC\r\nLIMIT 1";
        
        $results=$this->fetchMany($query, array(), 0, 1);
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>null, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output); 
    }
    
    function getCookies($cookiename) {
        $output=$_COOKIE[$cookiename];
        return json_decode($output);
    }
    
    function getUserAccount($userid) {
        $query = "SELECT *";
        $query .= "\r\n FROM ".$this->dbprefix."users";
        //$query .= "\r\n INNER JOIN ".$this->dbprefix."groups ON ".$this->dbprefix."users.group_in = ".$this->dbprefix."groups.group_id";
        $query .= "\r\n WHERE user_id=$userid";
        
        $results=$this->fetchMany($query, array(), 0, 1);
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>null, "count"=>count($results['output']), "total"=>$results['records']);
        
        return($output);    
    }
    
    function getUserPermissions($userid) {
        /**
        * Permissions include
        * - checking whether or not there are version (case_type) permissions, and if so, which case_types are allowed
        * - checking whether or not the user has the right to view cases at all
        * 
        */
        
        $query = "SELECT *";
        $query .= "\r\n FROM ".$this->dbprefix."users";
        $query .= "\r\n INNER JOIN ".$this->dbprefix."groups ON ".$this->dbprefix."users.group_in = ".$this->dbprefix."groups.group_id";

        $query .= "\r\n WHERE ".$this->dbprefix."users.user_id = ".$userid;
        
        $results=$this->fetchMany($query, array(), 0, 1);
        $data=$results['output'][0];
        
        $permissions=array(
            "account_enabled"=>$data['account_enabled'],
            "email_moderator"=>$data['email_moderator'],
            "is_admin"=>$data['is_admin'],
            "can_open_jobs"=>$data['can_open_jobs'],
            "can_modify_jobs"=>$data['can_modify_jobs'],
            "can_add_comments"=>$data['can_add_comments'],
            "can_attach_files"=>$data['can_attach_files'],
            "can_vote"=>$data['can_vote'],
            "restrict_versions"=>$data['restrict_versions']
        );
        if(isset($data['restrict_versions']) && $data['restrict_versions'] > 0) {
            $versions=array();
            $query2="SELECT *";
            $query2.="\r\nFROM ".$this->dbprefix."version_permissions";
            $query2.="\r\nWHERE group_id=".$data['group_id'];
            $query2.="\r\nAND enabled=1";
            
            $results2=$this->fetchMany($query2, array(), 0, 1000);
            foreach($results2['output'] as $row) {
                $versions[]=$row['version_id'];    
            }
            $permissions["versions"]=implode(",", $versions);
        }
        $output=$permissions;
        return($output);    

        
    }
    
    /**
    * returns a list of issue types (id & name) - deprecated database name "list_version"
    * 
    */
    function getIssueTypes() {
        $query = "SELECT version_id, version_name";
        $query .= "\r\nFROM ".$this->dbprefix."list_version";
        $query .= "\r\nWHERE show_in_list = 1";
        $query .= "\r\nORDER BY list_position";
        
        $results=$this->fetchMany($query, array(), 0, 99999);
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>null, "count"=>count($results['output']), "total"=>$results['records']);
        
        return($output);
    }
    ##### LISTS ######
    ##### These are usually queries that return a list #####
    

    function attachmentList($parameters=array(), $conditions="is_closed != 1", $order="date_due ASC", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="date_added DESC";}
        
        $query = "SELECT *";
        $query .="\r\n  FROM ".$this->dbprefix."attachments";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."users ON ".$this->dbprefix."attachments.added_by = ".$this->dbprefix."users.user_id";
        $query .="\r\nWHERE $conditions";
        $query .="\r\nORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);         
    }

    function attachmentCreate($parameters=array(), $conditions=null) {
        $query = "INSERT INTO ".$this->dbprefix."attachments";
        $query .= "\r\n (`attachment_id`, `task_id`, `orig_name`, `file_name`, `file_desc`, `file_type`, `file_size`, `file_date`, `added_by`, `date_added`, `attachments_module`, `url`)";
        $query .= "\r\n VALUES (NULL, :task_id, :orig_name, :file_name, :file_desc, :file_type, :file_size, '', :added_by, :date_added, '', '')";
        
        $results=$this->execute($query, $parameters);
        $newId=$this->db->lastInsertId();
        $output=array("results"=>$results." rows inserted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results, "insertId"=>$newId);
    
        return($output);         
    }

    function attachmentDelete($parameters) {
        //Firstly, delete the actual attachment file from the server
        $query = "SELECT file_name FROM ".$this->dbprefix."attachments";
        $query .= "\r\n WHERE attachment_id=:attachment_id";
        
        $search=$this->fetch($query, $parameters);
        
        //Delete $results['file_name'] from server;
        $attachmentDir=$this->config['installation']['attachmentdir']['value'];
        
        $filename=$attachmentDir."/".$search['file_name'];
        
        if(file_exists($filename)) {
            unlink($filename);
            //When that has been successful, then delete the record from the database
            $query = "DELETE FROM ".$this->dbprefix."attachments";
            $query .= "\r\n WHERE attachment_id = :attachment_id";

            $results=$this->execute($query, $parameters);
            
            $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);

        } else {
            $output=array("results"=>"File does not exist [".$filename."]", "query"=>$query, "parameters"=>$parameters, "count"=>0, "total"=>0);
        }
        
        return($output);
    }
    
    function attachmentUpdate($parameters=array(), $conditions=null ) {
        if($conditions===null) {$conditions="1=1";}
        
        $query = "UPDATE ".$this->dbprefix."attachments";
        $query .= "\r\n SET file_desc = :file_desc";
        $query .= "\r\nWHERE $conditions";
        
        $results=$this->execute($query, $parameters);
        
        $output=array("results"=>$results." rows affected", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
    
        return($output);
    }
    
    function caseList($parameters=array(), $conditions="is_closed != 1", $order="date_due ASC", $first=0, $last=1000000000) {
        $cookies=$this->getCookies("OpenCaseTrackerSystem");
        $userId=$cookies->user_id;
        $userAccount=$this->getUserAccount($userId);
        $permissions=$this->getUserPermissions($userId);       
        
        if($conditions === null) {$conditions="is_closed != 1";}
        if($order===null) {$order="date_due ASC";}
        $order=str_replace("_dot_", ".", $order);
        
        $county="SELECT count(t.task_id) as total";
        $query="SELECT t.*, p.*, lt.*, lc.*, lv.*, lvc.*, uo.*, flr.*, mst.*, u.real_name as assigned_real_name, ue.real_name as last_edited_real_name";
        if($this->externalDb===true) {
            //$query .= ", mem.*";
            $query .= ", mem.data, mem.pref_name, mem.surname,        
            CASE WHEN CONCAT (mem.pref_name, ' ', mem.surname) != ' ' THEN CONCAT (mem.pref_name, ' ', mem.surname)
                WHEN CONCAT (mem.pref_name, ' ', mem.surname) = ' ' AND t.member=0 THEN 'None'
                ELSE t.member
            END as clientname";
            if(strpos($order, "client_name") > -1) {
                $order=str_replace("client_name", "CASE WHEN CONCAT (mem.pref_name, ' ', mem.surname) != ' ' THEN CONCAT (mem.pref_name, ' ', mem.surname) WHEN CONCAT (mem.pref_name, ' ', mem.surname) = ' ' AND t.member=0 THEN 'None' ELSE t.member END", $order );
            }            
        } else {
            if(strpos($order, "client_name") > -1) {
                $order=str_replace('client_name', 'name', $order);
            }
        }
        $querybody ="\r\n                 FROM ".$this->dbprefix."tasks t

                     LEFT JOIN ".$this->dbprefix."projects p ON t.attached_to_project = p.project_id
                     LEFT JOIN ".$this->dbprefix."list_tasktype lt ON t.task_type = lt.tasktype_id
                     LEFT JOIN ".$this->dbprefix."list_category lc ON t.product_category = lc.category_id
                     LEFT JOIN ".$this->dbprefix."list_version lv ON t.product_version = lv.version_id
                     LEFT JOIN ".$this->dbprefix."list_version lvc ON t.closedby_version = lvc.version_id
                     LEFT JOIN ".$this->dbprefix."users u ON t.assigned_to = u.user_id
                     LEFT JOIN ".$this->dbprefix."users uo ON t.opened_by = uo.user_id
                     LEFT JOIN ".$this->dbprefix."users ue on t.last_edited_by = ue.user_id
                     LEFT JOIN ".$this->dbprefix."list_resolution flr ON t.resolution_reason = flr.resolution_id
                     LEFT JOIN ".$this->dbprefix."master mst ON t.task_id = mst.servant_task
                 ";
        
        //External Database Link
        if($this->externalDb===true) {
            $querybody .= "    LEFT JOIN ".$this->dbprefix."member_cache mem ON mem.member=t.member
                 ";
        }        
        //CONDITIONS         
        $querybody .= "WHERE ".$conditions;
        
        //PERMISSIONS
        if($permissions['restrict_versions']==1) {
            $querybody .= "\r\nAND product_version IN ";
            $querybody .= "('".implode("','", explode(",", $permissions['versions']))."')";
        }        
        
        //RESTRICTED CASES
        if($permissions['is_admin'] != 1) {
            //don't show any restricted cases not assigned to this person
            $querybody .= "\r\nAND NOT (is_restricted=1 AND assigned_to != $userId)";
        }
        
        //SYSTEM SETTING RESTRICTS ACCES TO ONLY THIS USERS CASES
        if($this->config['general']['restrict_view']['value'] == 1 && $permissions['is_admin'] !=1) {
            //the system is set to restrict access to all cases except ones assigned to user
            $querybody .= "\r\nAND assigned_to = $userId";
        }
        
        //ORDER
        $querybody .= " \r\nORDER BY ".$order;       
        
        //echo $querybody;
        
        $countresults=$this->fetchMany($county.$querybody, $parameters, 0, 1000000000, false);
        //$this->showArray($countresults); 
        $totalresponses=$countresults['output'][0]['total'];

        
        if($totalresponses > ($last-$first)) {
            $qty=$last-$first+2;
            $last=$first+500; 
            $querybody .= " \r\nLIMIT $qty OFFSET $first";
        }
        //echo "First: $first, Last: $last"; die();
        //$results=$this->fetchMany($query.$querybody, $parameters, $first-1, $last-1, false);
        $results=$this->fetchMany($query.$querybody, $parameters, 0, 10000, false);
        $output=array("results"=>$results['output'], "query"=>str_replace("\r\n", " ", $query.$querybody), "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$totalresponses, "offset"=>$first);
        //$this->showArray($output);
        //die();
        
          
        return($output);        
    }

    function caseTypeList($parameters=array(), $conditions="show_in_list=1", $order="list_position asc", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="list_position asc";}
        
        $query = "SELECT *";
        $query .= "\r\n FROM ".$this->dbprefix."list_tasktype";
        $query .= "\r\nWHERE $conditions";
        if(!$conditions) {
            $query .= "1=1";
        }        
        $query .= "\r\nORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        
        return($output);
    }
    
    function caseTypeGroupList($parameters=array(), $conditions="1=1", $order="list_position asc", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="list_position asc";}
        
        $query = "SELECT *";
        $query .= "\r\n FROM ".$this->dbprefix."list_tasktype_groups";
        $query .= "\r\nWHERE $conditions";
        $query .= "\r\n AND hide_from_list=0";
        $query .= "\r\nORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        
        return($output);
    }    
    
    function caseGroupList($parameters=array(), $conditions="show_in_list=1", $order="list_position asc", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="list_position asc";}
        
        $query = "SELECT *";
        $query .= "\r\n FROM ".$this->dbprefix."list_version";
        $query .= "\r\nWHERE $conditions";
        if(empty($conditions)) {
            $query .= "1=1";
        }
        $query .= "\r\nORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        
        return($output);
    }    

    function commentList($parameters=array(), $conditions="is_closed != 1", $order="date_due ASC", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="date_added DESC";}
        
        $query = "SELECT *";
        $query .="\r\n  FROM ".$this->dbprefix."comments";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."users ON ".$this->dbprefix."comments.user_id = ".$this->dbprefix."users.user_id";
        $query .="\r\nWHERE $conditions";
        $query .="\r\nORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);         
    }
    
    function commentCreate($parameters=array(), $conditions=null) {
        $query = "INSERT INTO ".$this->dbprefix."comments";
        $query .= "\r\n (`comment_id`, `task_id`, `date_added`, `user_id`, `comment_text`)";
        $query .= "\r\n VALUES (NULL, :task_id, :date_added, :user_id, :comment_text)";
        
        $results=$this->execute($query, $parameters);
        
        $output=array("results"=>$results." rows inserted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
    
        return($output);         
    }
    
    function commentDelete($parameters=array(), $conditions=null) {
        $query = "DELETE FROM ".$this->dbprefix."comments";
        $query .= "\r\n WHERE comment_id = :comment_id";

        $results=$this->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
    
        return($output);         
            
    }
    
    function commentUpdate($parameters=array(), $conditions=null ) {
        if($conditions===null) {$conditions="1=1";}
        
        $query = "UPDATE ".$this->dbprefix."comments";
        $query .= "\r\n SET comment_text = :comment_text";
        $query .= "\r\nWHERE $conditions";
        
        $results=$this->execute($query, $parameters);
        
        $output=array("results"=>$results." rows affected", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
    
        return($output);
    }
    
    function closureCheckList() {
        $checklist=array(
            1=>"Person is no longer employed",
            2=>"Person's job salary/location/classification has changed",
            3=>"This case should be publicised",
            4=>"Other (explanation provided in closing comments)"
        );
        return($checklist);    
    }
    
    function customFieldList($parameters=array(), $conditions="1=1", $order="custom_field_name", $first=0, $last=1000000000) {
        $tablename="custom_field_definitions";
        
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="custom_field_name, custom_field_type";}
        
        $query = "SELECT *";
        $query .= "\r\n FROM ".$this->dbprefix.$tablename;
        $query .= "\r\n WHERE $conditions";
        $query .= "\r\n ORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
    
        return($output);
    }
    
    function customTextList($parameters=array(), $conditions="1=1", $order="modify_action", $first=0, $last=1000000000) {
        $tablename="custom_texts";
        
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="modify_action";}
        
        $query = "SELECT *";
        $query .= "\r\n FROM ".$this->dbprefix.$tablename;
        $query .= "\r\n WHERE (custom_text_lang='EN' OR custom_text_lang='') AND $conditions";
        if(empty($conditions)) {
            $query .= $conditions;
        }
        $query .= "\r\n ORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
    
        return($output);
    }    
    
    function departmentList($parameters=array(), $conditions="1=1", $order="display_list_position, parent_id, list_position, category_name", $first=0, $last=1000000000) {
        //All future versions to correct the table name
        $tablename="list_category";
        
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="display_list_position, parent_id, list_position, category_name";}
        
        $query = "SELECT ".$this->dbprefix.$tablename.".*, parent.category_name as parent_name, parent.list_position as parent_list_position, ";
        $query .= " IF (parent.list_position IS NOT NULL, parent.list_position, ".$this->dbprefix.$tablename.".list_position) AS display_list_position";
        $query .= "\r\n  FROM ".$this->dbprefix.$tablename;
        $query .= "\r\n LEFT JOIN ".$this->dbprefix."users ON ".$this->dbprefix.$tablename.".category_owner=".$this->dbprefix."users.user_id";
        $query .= "\r\n LEFT JOIN ".$this->dbprefix."list_category parent ON ".$this->dbprefix."list_category.parent_id=parent.category_id";
        $query .= "\r\n WHERE $conditions";
        $query .= "\r\n ORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        
        return($output);
    }

    /**
    * a list of email templates
    * 
    * @param mixed $parameters
    * @param mixed $conditions
    * @param mixed $order
    * @param mixed $first
    * @param mixed $last
    */
    function emailTemplateList($parameters=array(), $conditions="1=1", $order="name, subject", $first=0, $last=1000000000) {
        $tablename="emailtemplates";
        
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="name, subject";}
        
        $query = "SELECT *";
        $query .= "\r\n FROM ".$this->dbprefix.$tablename;
        $query .= "\r\n where $conditions";
        $query .= "\r\n ORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("restuls"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        
        return($output);    
    }
    
    /**
    * a list of files available in the email attachments directory
    * 
    */
    function emailAttachmentsList() {
        
    }
    
    function groupDelete($parameters=array(), $conditions=null) {
        //First check that there are no users assigned to this group, and if there are, don't delete it
        $query = "SELECT * FROM ".$this->dbprefix."users WHERE group_in = :group_id";
        $results = $this->execute($query, $parameters);
        if($results == 0) {
            $query = "DELETE FROM ".$this->dbprefix."groups";
            $query .= "\r\n WHERE group_id = :group_id";

            $results=$this->execute($query, $parameters);
            
            $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        } else {
            $output=array("results"=>"No rows deleted - this group has users assigned to it", "query"=>$query, "parameters"=>$parameters, "count"=>0, "total"=>0);
        }
        
    
        return($output);              
    }    
    
    /**
    * Outputs history table information for a task.
    * 
    * @param mixed $parameters     - Parameters passed to the query
    * @param mixed $conditions     - Conditions - could include, for example, task_id
    * @param mixed $order
    * @param mixed $first
    * @param mixed $last
    */
    function historyList($parameters=array(), $conditions="", $order="created ASC", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="event_date DESC";}
        
        $query = "SELECT h.*, u.*, t.*";
        $query.=", t.name as clientname";
         
        $query .="\r\n  FROM ".$this->dbprefix."history h";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."tasks t ON t.task_id = h.task_id";
        $query .= "\r\n INNER JOIN ".$this->dbprefix."users u ON u.user_id=h.user_id";         
        
        $query .="\r\nWHERE $conditions";
        $query .="\r\nORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);

        $eventtypes=array(
            0=>"Changed Case Field",
            1=>"Opened Case",
            2=>"Closed Case",
            3=>"Edited Case",
            4=>"Added Note",
            5=>"Deleted Note",
            6=>"Deleted Note",
            7=>"Added Attachment",
            8=>"Deleted Attachment",
            9=>"Added Notification",
            10=>"Deleted Notification",
            11=>"Added a Case Relationship",
            12=>"Deleted a Case Relationship",
            13=>"Reopened Case",
            14=>"Assigned Case",
            15=>"Case Related to Other Case",
            16=>"Case unRelated to Other Case",
            17=>"Added Reminder",
            18=>"Deleted Reminder",
            19=>"Added Linked Child Case",
            20=>"Deleted Linked Child Case",
            21=>"Case made a linked child Case",
            22=>"Case no longer a linked child Case",
            23=>"Removed Linked parent status",
            24=>"Deleted Planning Note",
            25=>"Marked Planning Note as Read",
            26=>"Added Companion Case",
            27=>"Removed Companion Case",
            28=>"Case made companion of other case",
            29=>"Case removed as companion of other case",
            30=>"Acknowledged checklist item",
            40=>"Date due changed",
            60=>"Added person of interest",
            61=>"Deleted person of interest",
            62=>"Changed description for person of interest",
            71=>"Changed Note",
            81=>"Changed Attachment Description",           
            91=>"Unknown - 91",
            99=>"Unknown - 99", 
        );
        

        foreach($results['output'] as $key=>$resultitem) {
            if(isset($eventtypes[$resultitem['event_type']])) {
                $eventtype=$eventtypes[$resultitem['event_type']];
            } else {
                $eventtype="Unknown event - ".$resultitem['event_type'];
            }
            $results['output'][$key]['event_description']=$eventtype;
            
        }

        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);         
        
                
    }    
    
    /**
    * Inserts an entry into the history table
    * 
    * @param mixed $parameters - an array containing field names (key) and values. 
    *                           Fields should include task_id, user_id, event_date, event_type, 
    *                           field_changed (name of field changed), old value and new value
    */
    function historyCreate($parameters=array()) {
        //INSERT INTO `flyspray_history` 
        // (`history_id`, `task_id`, `user_id`, `event_date`, `event_type`, `field_changed`, `old_value`, `new_value`) 
        // VALUES (NULL, '10', '2', '154235685', '61', '', 'asdf', 'asdf');
        $query = "INSERT INTO ".$this->dbprefix."history";
        $query .= "\r\n (`history_id`, `task_id`, `user_id`, `event_date`, `event_type`, `field_changed`, `old_value`, `new_value`)";
        $query .= "\r\n VALUES (NULL, :task_id, :user_id, :event_date, :event_type, :field_changed, :old_value, :new_value)";
        
        $results=$this->execute($query, $parameters);
        
        $output=array("results"=>$results." rows inserted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
    
        return($output); 
    }
    
    function historyDelete($parameters=array(), $conditions=null) {
        $query = "DELETE FROM ".$this->dbprefix."history";
        $query .= "\r\n WHERE history_id = :history_id";

        $results=$this->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
    
        return($output);              
    }
    
    function linkedList($parameters=array(), $conditions="", $order="created ASC", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="date_added DESC";}
        
        $query = "SELECT t.*, mas.link_id";
        if($this->externalDb===true) {
            $query .= ", mem.*, CONCAT(mem.pref_name, ' ', mem.surname) as clientname";
        } else {
            $query .=", t.name as clientname";
        } 
                
        $query .="\r\n  FROM ".$this->dbprefix."master mas";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."tasks t ON t.task_id = mas.servant_task";
        if($this->externalDb===true) {
            $query .= "    LEFT JOIN ".$this->dbprefix."member_cache mem ON mem.member=t.member
                 ";
        }         
        
        $query .="\r\nWHERE $conditions";
        $query .="\r\nORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);

        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);         
        
                
    }
    
    function notificationsList($parameters=array(), $conditions="", $order="created ASC", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="date_added DESC";}
        
        $query = "SELECT u.*, n.*";
        $query .="\r\n  FROM ".$this->dbprefix."users u";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."notifications n ON n.user_id = u.user_id";
        $query .="\r\nWHERE $conditions";
        $query .="\r\nORDER BY $order";
        
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);         
    }

    function poiList($parameters=array(), $conditions="", $order="created ASC", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="created DESC";}
        
        $query = "SELECT poi.id as poi_id, task_id, comment, poi.created, poi.modified, p.id as person_id, firstname, lastname, position, organisation, phone, email";
        $query .="\r\n  FROM ".$this->dbprefix."people_of_interest  poi";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."people p ON poi.person_id = p.id";
        $query .="\r\nWHERE $conditions";
        $query .="\r\nORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);         
    }

    function poiUpdate($parameters=array(), $conditions=null ) {
        if($conditions===null) {$conditions="1=1";}
        
        $query = "UPDATE ".$this->dbprefix."people_of_interest";
        $query .= "\r\n SET comment = :comment";
        $query .= "\r\nWHERE $conditions";
        
        $results=$this->execute($query, $parameters);
        
        $output=array("results"=>$results." rows affected", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
    
        return($output);
    }
    
    function poiConnectionsList($parameters=array(), $conditions="1=1", $order="poi.modified", $first=0, $last=1000000000) {
        $query  = "SELECT t.task_id, item_summary, is_closed, poi.comment, poi.modified, poi.id";
        $query .= "\r\n FROM ".$this->dbprefix."tasks t";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."people_of_interest poi ON poi.task_id=t.task_id";
        $query .= "\r\n WHERE $conditions";
        if(!empty($order)) {
            $query .= "\r\n ORDER BY $order";
        }
        
        //echo $query;
        $results=$this->fetchMany($query, $parameters);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);   
    }
    
    function poiPeopleList($parameters=array(), $conditions="", $order="lastname, firstname", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="lastname, firstname";}
        $query  = "SELECT *";
        $query .= "\r\n FROM ".$this->dbprefix."people";
        $query .= "\r\n WHERE $conditions";
        $query .= "\r\n ORDER BY $order";    
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);           
    }
    
    function recentList($parameters=array(), $conditions="", $order="created ASC", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="date_added DESC";}
        $order=str_replace("_dot_", ".", $order);
        /* select h.*, t.* 
        from flyspray_tasks t 
        inner join (select task_id, history_id, user_id, event_type, field_changed, old_value, new_value, max(event_date) as event_date from flyspray_history WHERE user_id=61 GROUP BY task_id ORDER BY event_date desc) AS h ON t.task_id=h.task_id */
        $query = "SELECT distinct t.task_id as caseno, h.changedby_real_name as event_modified_real_name, h.*, t.*, lt.*, lc.*, u.real_name as assigned_real_name ";
        if($this->externalDb===true) {
            //$query .= ", mem.*";
            $query .= ", mem.data, mem.pref_name, mem.surname,        
            CASE WHEN CONCAT (mem.pref_name, ' ', mem.surname) != ' ' THEN CONCAT (mem.pref_name, ' ', mem.surname)
                WHEN CONCAT (mem.pref_name, ' ', mem.surname) = ' ' AND t.member=0 THEN 'None'
                ELSE t.member
            END as clientname"; 
            if(strpos($order, "client_name") > -1) {
                $order=str_replace("client_name", "CASE WHEN CONCAT (mem.pref_name, ' ', mem.surname) != ' ' THEN CONCAT (mem.pref_name, ' ', mem.surname) WHEN CONCAT (mem.pref_name, ' ', mem.surname) = ' ' AND t.member=0 THEN 'None' ELSE t.member END", $order );
            }        
        } else {
            if(strpos($order, "client_name") > -1) {
                $order=str_replace('client_name', 'name', $order);
            }
        }
                
        $query .="\r\n  FROM ".$this->dbprefix."tasks t";
        $query .="\r\n  INNER JOIN ".$this->dbprefix."users u ON t.assigned_to=u.user_id";
        $query .= "\r\n  INNER JOIN (SELECT h.task_id, h.event_date, h.user_id, h.event_type, h.field_changed, h.old_value, h.new_value, real_name as changedby_real_name FROM ".$this->dbprefix."history h JOIN ".$this->dbprefix."users ON ".$this->dbprefix."users.user_id=h.user_id JOIN ( SELECT task_id, MAX(event_date) as max_event_date FROM ".$this->dbprefix."history GROUP BY task_id ) t ON h.task_id = t.task_id AND h.event_date = t.max_event_date WHERE h.user_id LIKE :user_id ORDER BY `h`.`task_id` ASC) AS h ON t.task_id=h.task_id";
        $query .= "\r\n  LEFT JOIN ".$this->dbprefix."list_tasktype lt ON t.task_type = lt.tasktype_id";
        $query .= "\r\n LEFT JOIN ".$this->dbprefix."list_category lc ON t.product_category = lc.category_id";
 
        if($this->externalDb===true) {
            
            $query .= "\r\n LEFT JOIN ".$this->dbprefix."member_cache mem ON mem.member=t.member
                 ";
        }         
        
        $query .="\r\n";
        $query .="\r\nORDER BY $order";

        
        $results=$this->fetchMany($query, $parameters, $first, $last, 0);

        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);         
        
                
    }
    
    function relatedList($parameters=array(), $conditions="", $order="created ASC", $first=0, $last=1000000000) {
        /** This function performs two queries - firstly to gather any deliberately connected cases
        *   and the second to gather any cases related by client/member number
        */
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="date_added DESC";}
        
        $query = "SELECT t.*, rel.related_id";
        if($this->externalDb===true) {
            $query .= ", mem.*, CONCAT(mem.pref_name, ' ', mem.surname) as clientname";
        } else {
            $query .=", t.name as clientname";
        }       
        
        
        $query .="\r\n  FROM ".$this->dbprefix."related rel";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."tasks t ON t.task_id = rel.related_task";
        if($this->externalDb===true) {
            $query .= "    LEFT JOIN ".$this->dbprefix."member_cache mem ON mem.member=t.member
                 ";
        }        
        
        $query .="\r\nWHERE $conditions";
        $query .="\r\nORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        if(isset($parameters[':taskid'])) {
            
            $query = "SELECT t.*, floor(rand()*(15000-5+1)+5) as related_id";
            if($this->externalDb===true) {
                $query .= ", mem.*, CONCAT(mem.pref_name, ' ', mem.surname) as clientname";
            } else {
                $query .=", t.name as clientname";
            }             
            
            
            $query .= "\r\n FROM ".$this->dbprefix."tasks t";
            if($this->externalDb===true) {
                $query .= "    LEFT JOIN ".$this->dbprefix."member_cache mem ON mem.member=t.member
                     ";
            }             
            
            
            $query .=" \r\nWHERE (t.member = (SELECT member FROM ".$this->dbprefix."tasks WHERE task_id=:taskid AND member IS NOT NULL AND member != '')";
            $query .= "\r\n OR t.name = (SELECT name FROM ".$this->dbprefix."tasks WHERE task_id=:taskid AND name IS NOT NULL AND name != ''))";
            $query .= "\r\n AND t.task_id != :taskid";
            $query .=" \r\nORDER BY $order";
            $results2=$this->fetchMany($query, $parameters, $first, $last, false);
            if(is_array($results2['output'])) {
                foreach($results2['output'] as $newrow) {
                    $results['output'][]=$newrow;
                }
            }
            $results['query'].="|".$results2['query'];
            $results['records']+=$results2['records'];
            
        } 

        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);         
        
    }
    
    function relatedCreate($paramaters=array(), $conditions=null) {
        $query = "INSERT INTO ".$this->dbprefix."related";
        $query .= "\r\n (`related_id`, `this_task`, `related_task`)";
        $query .= "\r\n VALUES(NULL, :this_task, :related_task)";
        
        $results=$this->execute($query, $parameters);
        
        $output=array("results"=>$results." rows inserted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
        return($output);    
    }
    
    function resolutionList($parameters=array(), $conditions="show_in_list = 1", $order="list_position ASC, resolution_name ASC", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="list_position ASC, resolution_name ASC";}
        
        $query = "SELECT *";
        $query .= "\r\nFROM ".$this->dbprefix."list_resolution";
        $query .= "\r\nWHERE $conditions";
        if(!$conditions) {
            $query .= "1=1";
        }        
        $query .= "\r\n ORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        //$this->showArray($output);  
        return($output);             
    }
    
    function restrictVersionList($groupId) {
        $query2="SELECT *";
        $query2.="\r\nFROM ".$this->dbprefix."version_permissions";
        $query2.="\r\nWHERE group_id=$groupId";
        $query2.="\r\nAND enabled=1";
        $results2=$this->fetchMany($query2, array(), 0, 1000);
        $output=array();
        foreach($results2['output'] as $row) {
            $output[]=$row['version_id'];    
        }
        return($output);      
    }
    
    function strategyList($parameters=array(), $conditions="", $order="created ASC", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="comment_date DESC";}
        
        $query = "SELECT t.*, st.*, u.*";
        if($this->externalDb===true) {
            $query .= ", CONCAT(mem.pref_name, ' ', mem.surname) as clientname";
        } else {
            $query .=", t.name as clientname";
        } 
                
        $query .="\r\n  FROM ".$this->dbprefix."strategy st";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."tasks t ON t.task_id = st.task_id";
        if($this->externalDb===true) {
            $query .= "    LEFT JOIN ".$this->dbprefix."member_cache mem ON mem.member=t.member
                 ";
        }
        $query .= "\r\n INNER JOIN ".$this->dbprefix."users u ON u.user_id=st.user_id";         
        
        $query .="\r\nWHERE $conditions";
        $query .="\r\nORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);

        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);         
        
                
    }

    function timeList($parameters=array(), $conditions="", $order="created ASC", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="comment_date DESC";}
        
        $query = "SELECT time.*, u.*";
        if($this->externalDb===true) {
            $query .= ", CONCAT(mem.pref_name, ' ', mem.surname) as clientname";
        } else {
            $query .=", t.name as clientname";
        } 
                
        $query .="\r\n  FROM ".$this->dbprefix."times time";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."tasks t ON t.task_id = time.task_id";
        if($this->externalDb===true) {
            $query .= "    LEFT JOIN ".$this->dbprefix."member_cache mem ON mem.member=t.member
                 ";
        }
        $query .= "\r\n INNER JOIN ".$this->dbprefix."users u ON u.user_id=time.user_id";         
        
        $query .="\r\nWHERE $conditions";
        $query .="\r\nORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);

        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);         
        
                
    }

    function tableList($tablename, $joins, $select, $parameters=array(), $conditions="", $order="", $first=0, $last=1000000000) {
        $query="SELECT ".$select."\r\n";
        $query.="FROM ".$this->dbprefix.$tablename."\r\n";
        if($joins) {
            foreach($joins as $key=>$join) {
                //echo $key;
                if($key=="member_cache" && $this->externalDb===true) {
                    $query=str_replace(", mem.*", ", mem.*, CONCAT(mem.pref_name, ' ', mem.surname) as clientname", $query);
                    $query .= str_replace($key." ", $this->dbprefix.$key." ", $join)."\r\n";
                } else if($key=="member_cache" && !$this->externalDb===true) {
                    $query=str_replace(", mem.*", ", t.name as clientname", $query);
                } else {
                    $query .= str_replace($key." ", $this->dbprefix.$key." ", $join)."\r\n";
                }
            }
        }
        $query .= "WHERE ".$conditions;
        //echo $query;
        
        if(!empty($order)) {
            $query .= "\nORDER BY ".$order;
        }
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        return($output);
    }
    
    function unitList($parameters=array(), $conditions="1=1", $order="show_in_list DESC, display_list_position, parent_id, list_position, unit_descrip", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="show_in_list DESC, display_list_position, parent_id, list_position, unit_descrip";}
        
        $query  = "SELECT ".$this->dbprefix."list_unit.*, parent.unit_descrip as parent_descrip, parent.list_position as parent_list_position, ";
        $query .= " IF (parent.list_position IS NOT NULL, parent.list_position, ".$this->dbprefix."list_unit.list_position) AS display_list_position";
        $query .= "\r\nFROM ".$this->dbprefix."list_unit";
        $query .= "\r\n LEFT JOIN ".$this->dbprefix."list_unit parent ON ".$this->dbprefix."list_unit.parent_id=parent.unit_id";
        $query .= "\r\n WHERE $conditions";
        $query .= "\r\n ORDER BY $order";

        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        //$this->showArray($output);  
        return($output);        
    }
    
    function userList($parameters=array(), $conditions="1=1", $order="group_name, real_name", $first=1, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="group_name, real_name";}
        
        $query = "SELECT user_id, user_name, real_name, group_in, email_address, notify_type, notify_rate, account_enabled, default_version, self_notify, default_task_view, last_notice, group_id, group_name, group_desc, is_admin, can_open_jobs, can_attach_files, can_vote, group_open, restrict_versions";
        $query .= "\r\nFROM ".$this->dbprefix."users";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."groups ON ".$this->dbprefix."groups.group_id=".$this->dbprefix."users.group_in";
        $query .= "\r\nWHERE $conditions";
        $query .= "\r\nORDER BY $order";
        //echo $query;
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        //$this->showArray($output);  
        return($output);        
    }
    
    function userGroupList($parameters=array(), $conditions="1=1", $order="group_name, real_name", $first=1, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="group_name, group_desc";}
        
        $query = "SELECT *";
        $query .= "\r\nFROM ".$this->dbprefix."groups";
        $query .= "\r\nWHERE $conditions";
        $query .= "\r\nORDER BY $order";
        //echo $query;
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        //$this->showArray($output);  
        return($output);        
    }
    
    
    
    ##### STATS #####
    
    function statsCases($parameters, $conditions, $order, $first, $last, $select=null) {
        if($conditions === null) {$conditions="is_closed != 1";}
        $first=0;
        $last=1000;
        if(!$select) {
            $query="SELECT count(*) as total";
        } else {
            $query="SELECT ".$select;
        }
        $query.="\r\n                 FROM ".$this->dbprefix."tasks t

                     LEFT JOIN ".$this->dbprefix."projects p ON t.attached_to_project = p.project_id
                     LEFT JOIN ".$this->dbprefix."list_tasktype lt ON t.task_type = lt.tasktype_id
                     LEFT JOIN ".$this->dbprefix."list_category lc ON t.product_category = lc.category_id
                     LEFT JOIN ".$this->dbprefix."list_version lv ON t.product_version = lv.version_id
                     LEFT JOIN ".$this->dbprefix."list_version lvc ON t.closedby_version = lvc.version_id
                     LEFT JOIN ".$this->dbprefix."users u ON t.assigned_to = u.user_id
                     LEFT JOIN ".$this->dbprefix."users uo ON t.opened_by = uo.user_id
                     LEFT JOIN ".$this->dbprefix."users ue on t.last_edited_by = ue.user_id
                     LEFT JOIN ".$this->dbprefix."list_resolution flr ON t.resolution_reason = flr.resolution_id
                     LEFT JOIN ".$this->dbprefix."master mst ON t.task_id = mst.servant_task
                 ";
        
        //CONDITIONS 
        $query .= "WHERE ".$conditions;
        
        if($order) {
            $query .= "\r\n".$order;
        }
        //echo $query;
        //print_r($parameters);
        //ORDER
        //echo $query;
        $results=$this->fetchMany($query, $parameters, $first, $last, false);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
        return($output);         
    }
    
    
    
    
    ##### SAVES #####
    ##### Creates and updates of the database
    
    function insertTable($tablename, $inserts, $debug=0) {
        
        $query  = "INSERT INTO ".$this->dbprefix.$tablename."\r\n";
        
        $fields=array();
        $values=array();
        $parameters=array();
        
        foreach($inserts as $key=>$val) {
            $fields[]=$key;
            $values[]=$val;
            $parameters[":".$key]=$val;
        }
        
        $query .= "(`";
        $query .= implode("`, `", $fields);
        $query .= "`)\r\n";
        $query .= "VALUES (:";
        $query .= implode( ", :", $fields);
        $query .= ")";
        
        //print_r($inserts);
        //print_r($parameters); 
        $this->execute($query, $parameters);
        
        $newId=$this->db->lastInsertId();
        
        if($debug > 0) {
            echo $query;
            echo "<hr />";
            print_r($parameters); 
        }
        
        return $newId;
    }
    
    /**
    * Returns a list of changed fields prior to performing an update
    * 
    * @param mixed $tablename
    * @param mixed $updates
    * @param mixed $wheres
    * @param mixed $userid
    * @param mixed $debug
    */
    function listUpdateChanges($tablename, $updates, $wheres, $userid, $debug=0) {
    
        $changes=array();
        
        $query  = "SELECT * FROM ".$this->dbprefix.$tablename;
        $query .= "\r\nWHERE ".$wheres;
        
        $existing=$this->fetch($query, array());
        
        foreach($updates as $key=>$val) {
            $value=$key;
            if($existing[$value] != $val) {
                $changes[$value]=array("old"=>$existing[$value], "new"=>$val);
            }
        }
        
        return $changes;
            
    }
    
    function createTable($tablename, $values, $userid, $debug=0) {
        $parameters=array();
        $querys=array();
        
        
        foreach($values as $key=>$val) {
            if($val !== "") {
                $labels[]=$key;
                $parameters[":".$key]=$val;
            }
            
        }
        //INSERT INTO `flyspray_tasks` (`task_id`, `attached_to_project`, `task_type`, `date_opened`, `date_due`, `opened_by`, `is_closed`, `date_closed`, `closed_by`, `closure_comment`, `item_summary`, `detailed_desc`, `item_status`, `assigned_to`, `resolution_reason`, `product_category`, `product_version`, `closedby_version`, `operating_system`, `task_severity`, `task_priority`, `last_edited_by`, `last_edited_time`, `percent_complete`, `member`, `name`, `unit`, `line_manager`, `line_manager_ph`, `local_delegate`, `local_delegate_ph`, `resolution_sought`, `is_restricted`, `closure_checklist`, `member_is_delegate`) VALUES (NULL, '0', '0', '', '', '0', '0', '', '0', '', '', '', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '', '', '', '', '', '', '0', '', '0');
        $query = "INSERT INTO ".$this->dbprefix.$tablename."\n";
        $query .= "(`";
        $query .= implode("`, `", $labels);
        $query .= "`)\n";
        $query .= "VALUES (:";
        $query .= implode(", :", $labels);
        $query .= ")";
        
        
        
        if($debug > 0) {
            $this->showArray($values);
            $this->showArray($parameters, "Parameters");
        }
        
        $insert=$this->execute($query, $parameters);
        
        $newId=$this->db->lastInsertId();
        
        $output=array(
            "query"=>$query,
            "values"=>$values,
            "insertId"=>$newId,
            "rowsInserted"=>$insert
        );
        
        return $output;
    }
    
    /**
    * Updates a table
    * 
    * @param mixed $tablename
    * @param mixed $updates - a keyed array containing key=field_name, val=value
    * @param mixed $wheres - the "where" part of the select
    * @param mixed $userid - option userid
    * @param mixed $debug - 0 for no debug info, 1 for debug info
    */
    function updateTable($tablename, $updates, $wheres, $userid, $debug=0) {
        
        $parameters=array();
        $querys=array();
        
        
        $query = "UPDATE ".$this->dbprefix.$tablename."\r\n";
        $query .= "SET \n";
        foreach($updates as $key=>$val) {
            $querys[] = "`$key` = :".$key;
            $parameters[':'.$key]=$val;
        }
        $query .= implode(", ", $querys);
        
        $query .= "\r\nWHERE ".$wheres;
        

        

        
        $this->execute($query, $parameters);
        
        if($debug>0) {
            echo $query;
            echo "<hr />";
            print_r($parameters);
            echo "<hr />";         
        }

        
        return null;
        
        
    }

    
    ##### HELPERS #####
    ##### Various tools that help simplify actions
    
    /**
    * Builds an HTML select list with data presented. IF the optional "optgroup" value is provided, this function
    * assumes that the data is already sorted by optgroup first
    * 
    * @param mixed $data - a keyed array containing the data to be used
    * @param mixed $attributes - any additional attributes for the select element (as an array - $key=>$val = $name=>$value) Must include "name"=>"something", "id"=>"something"
    * @param mixed $value - the key for the $data containing the value for the select options
    * @param mixed $text - the key for the $data containing the text for the select options
    * @param mixed $selectedvalue - the value currently selected
    * @param mixed $nulloption - the entry for no value, optionally an array containing keyed ['value'] and ['text']
    * @param boolean $pleasechoose - whether or not to include "Please choose..." as the first option with no value (true or false)
    * @param mixed $optgroup - optional key for the $data containing the optgroup
    */
    function buildSelectList($data, $attributes, $value, $text, $selectedvalue=null, $nulloption="Please choose...", $optgroup=null) {
        //Currently we assume that the data is already sorted by optgroup if provided
        if(!$attributes) {$attributes=array();}
        $select="<select";
        
        foreach($attributes as $key=>$val) {
            $select.=" $key='".$val."'";
        }
        $select.=">\r\n";
        if($nulloption) {
            if(is_array($nulloption)) {
                $select .= "    <option value='".$nulloption['value']."'>".$nulloption['text']."</option>";
            } else {
                $select .= "    <option value=''>$nulloption</option>\r\n";
            }
        }
        $currentog='';
        
        foreach($data as $row) {
            if($optgroup) {
                if($currentog != $row[$optgroup]) {
                    if($currentog != '') {
                        $select .= "  </optgroup>\r\n";
                    }
                    $currentog=$row[$optgroup];
                    if($currentog != '') {
                        $select .= "  <optgroup label='".$currentog."'>\r\n";
                    }
                }
            }
            if($value) {
                $select.="    <option value='".$row[$value]."'";
                if($row[$value]==$selectedvalue) {
                    $select .= " selected=selected";
                }                
            } else {
                $select.="    <option";
            }
            $select .= ">".$row[$text]."</option>\r\n";    
        }
        if($optgroup) {
            $select .= "  </optgroup>";
        }
        $select .= "</select>";
        return $select;
    }
    
    /**
    * Lists all items in a directory as an keyed array
    * 
    * @param mixed $dir
    */
    function directoryList($dir, $recursive=false) {

        $result = array();

        $cdir = scandir($dir);
        foreach ($cdir as $key => $value)
        {
          if (!in_array($value,array(".","..")))
          {
             if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
                if($recursive) {
                    {
                    $result[$value] = $this->directoryList($dir . DIRECTORY_SEPARATOR . $value);
                    }
                } else {
                    //Don't add the name to the array
                }
             else
             {
                $result[] = $value;
             }
          }
        }

        return $result;        
    }
    
    function getReportList($dir) {
        //Get a list of all the reports
        $reports=$this->directoryList($dir, false);
        $reportGroups=$this->directoryList($dir."/groups", false);
        //Get the descriptions for all the reports
        $reportList=array();
        foreach($reports as $reportName) {
            //Report Name
            $reportList[$reportName]['Name']=ucwords(substr($reportName, 0 , strrpos($reportName, ".")));
            
            //Report Description
            $descfile=$dir."/descriptions/".$reportName.".desc";
            $description='';
            if(file_exists($descfile)) {
                $fh=fopen($descfile, 'r');
                while($line=fgets($fh)) {
                    $description.=$line;
                }
            } else {
                $description='No description provided';
            }
            $reportList[$reportName]['Description']=$description;
            
            //ReportGroup 
            foreach($reportGroups as $reportGroup) {
                if(strchr($reportGroup, $reportName) > -1) {
                    $group=substr($reportGroup, strlen($reportName)+1);
                    
                } else {
                    $group='Default';
                }
                $reportList[$reportName]['Group']=$group;
            }          
        }
        return $reportList;
    }
    
    function fieldNameTranslation() {
        $translation=array(
            "item_summary"=>array(
                "old"=>"item_summary", 
                "description"=>"Item Summary"
                ),
            "task_id"=>array(
                "old"=>"task_id",
                "description"=>"Case ID"
                ),
            "case_type"=>array(
                "old"=>"case_type",
                "description"=>"Case Type"
                ),
            "detailed_desc"=>array(
                "old"=>"detailed_desc",
                "description"=>"Case Outline"
            ),
            "case_group"=>array(
                "old"=>"product_version",
                "description"=>"Case Group"
            ),
            "product_category"=>array(
                "old"=>"product_category",
                "description"=>"Department"
            ),
            
        );
    }    
    
    function showArray($array, $title="Showing array (debug)") {
        echo "<pre><b>$title</b><br />";
        print_r($array);
        echo "</pre>";    
    }
}




?>
