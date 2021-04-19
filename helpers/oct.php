<?php
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
    
    function getCase($caseid) {
        $query="SELECT t.*, p.*, lt.*, lc.*, lv.*, uo.real_name as openedby_real_name, flr.*, mst.*, u.real_name as assigned_real_name, ue.real_name as last_edited_real_name, t.member as clientname";
        if($this->externalDb===true) {
            $query .= ", mem.*, CONCAT(mem.pref_name, ' ', mem.surname) as clientname";
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
                 ";
        
        //External Database Link
        if($this->externalDb===true) {
            $query .= "    LEFT JOIN ".$this->dbprefix."member_cache mem ON mem.member=t.member
                 ";
        }        
        //CONDITIONS         
        $query .= "WHERE t.task_id = :task_id";
        
        $parameters[':task_id']=$caseid;
        
        $results=$this->fetchMany($query, $parameters);
        
        //print_r($results['output'][0]);
        
        $output=array("results"=>$results['output'][0], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        //print_r($output);  
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
        
        $output=array("results"=>$results." rows inserted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
    
        return($output);         
    }

    function attachmentDelete($parameters) {
        //Firstly, delete the actual attachment file from the server
        $query = "SELECT file_name FROM ".$this->dbprefix."attachments";
        $query .= "\r\n WHERE attachment_id=:attachment_id";
        
        $search=$this->fetch($query, $parameters);
        
        //Delete $results['file_name'] from server;
        $attachmentDir="/var/attachments"; //TODO - Get this from configuration settings
        
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
        
        if($conditions === null) {$conditions="is_closed != 1";}
        if($order===null) {$order="date_due ASC";}
        
        $query="SELECT t.*, p.*, lt.*, lc.*, lv.*, lvc.*, uo.*, flr.*, mst.*, u.real_name as assigned_real_name, ue.real_name as last_edited_real_name";
        if($this->externalDb===true) {
            $query .= ", mem.*";
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
        
        //External Database Link
        if($this->externalDb===true) {
            $query .= "    LEFT JOIN ".$this->dbprefix."member_cache mem ON mem.member=t.member
                 ";
        }        
        //CONDITIONS         
        $query .= "WHERE ".$conditions;
        
        //ORDER
        $query .= " \nORDER BY ".$order;       
        //echo $query;
        $results=$this->fetchMany($query, $parameters, $first, $last, false);
        
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
    
    function departmentList($parameters=array(), $conditions="", $order="list_position", $first=0, $last=1000000000) {
        //All future versions to correct the table name
        $tablename="list_category";
        
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="list_position, category_name";}
        
        $query = "SELECT *";
        $query .= "\r\n  FROM ".$this->dbprefix.$tablename;
        $query .= "\r\n LEFT JOIN ".$this->dbprefix."users ON ".$this->dbprefix.$tablename.".category_owner=".$this->dbprefix."users.user_id";
        $query .= "\r\n WHERE $conditions";
        $query .= "\r\n ORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);
        
        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
        
        return($output);
    }
    
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
            60=>"Added person of interest",
            61=>"Deleted person of interest",
            62=>"Changed description for person of interest",
            91=>"Unknown - 91",
            99=>"Unknown - 99", 
            71=>"Changed Note",
            81=>"Changed Attachment Description",           
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
    
    function poiList($parameters=array(), $conditions="", $order="created ASC", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="date_added DESC";}
        
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
    
    function recentList($parameters=array(), $conditions="", $order="created ASC", $first=0, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="date_added DESC";}
        //print_r($parameters);
        //SELECT h.*, t.*, mem.surname, mem.pref_name, u.real_name as assigned_real_name
        //FROM flyspray_history h
        //INNER JOIN flyspray_tasks t ON t.task_id = h.task_id
        //INNER JOIN flyspray_member_cache mem ON mem.member=t.member
        //LEFT JOIN flyspray_users u ON t.assigned_to=u.user_id
        //WHERE h.user_id = :user_id
        //ORDER BY event_date DESC LIMIT 50
        
        /* select h.*, t.* 
        from flyspray_tasks t 
        inner join (select task_id, history_id, user_id, event_type, field_changed, old_value, new_value, max(event_date) as event_date from flyspray_history WHERE user_id=61 GROUP BY task_id ORDER BY event_date desc) AS h ON t.task_id=h.task_id */
        $query = "SELECT h.*, t.*, u.real_name as assigned_real_name ";
        if($this->externalDb===true) {
            $query .= ", mem.*, CONCAT(mem.pref_name, ' ', mem.surname) as clientname";
        } else {
            $query .=", t.name as clientname";
        } 
                
        $query .="\r\n  FROM ".$this->dbprefix."tasks t";
        $query .="\r\n  INNER JOIN ".$this->dbprefix."users u ON t.assigned_to=u.user_id";
        $query .= "\r\n  inner join (select task_id, history_id, user_id, event_type, field_changed, old_value, new_value, max(event_date) as event_date from flyspray_history WHERE user_id=".$parameters[':user_id']." GROUP BY task_id ORDER BY event_date desc) AS h ON t.task_id=h.task_id";
        if($this->externalDb===true) {
            $query .= "\r\n LEFT JOIN ".$this->dbprefix."member_cache mem ON mem.member=t.member
                 ";
        }         
        
        $query .="\r\n";
        $query .="\r\nORDER BY $order";
        //$query .="\r\nLIMIT 500";
        
        //echo $query;
        //die();
        
        $results=$this->fetchMany($query, $parameters, $first, $last);

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

    function tableList($tablename, $joins, $select, $parameters=array(), $conditions="", $order="", $first=1, $last=1000000000) {
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
    
    function userList($parameters=array(), $conditions="", $order="", $first=1, $last=1000000000) {
        if($conditions===null) {$conditions="1=1";}
        if($order===null) {$order="group_name, real_name";}
        
        $query = "SELECT *";
        $query .= "\r\nFROM ".$this->dbprefix."users";
        $query .= "\r\n  INNER JOIN ".$this->dbprefix."groups ON ".$this->dbprefix."groups.group_id=".$this->dbprefix."users.group_in";
        $query .= "\r\nWHERE $conditions";
        $query .= "\r\nORDER BY $order";
        
        $results=$this->fetchMany($query, $parameters, $first, $last);

        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
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
    * @param boolean $pleasechoose - whether or not to include "Please choose..." as the first option with no value (true or false)
    * @param mixed $optgroup - optional key for the $data containing the optgroup
    */
    function buildSelectList($data, $attributes=array(), $value, $text, $selectedvalue=null, $nulloption="Please choose...", $optgroup=null) {
        //Currently we assume that the data is already sorted by optgroup if provided
        
        $select="<select";
        
        foreach($attributes as $key=>$val) {
            $select.=" $key='".$val."'";
        }
        $select.=">\r\n";
        if($nulloption) {
            $select .= "    <option value=''>$nulloption</option>\r\n";
        }
        $currentog="";
        
        foreach($data as $row) {
            if($optgroup) {
                if($currentog != $row[$optgroup]) {
                    if($currentog != "") {
                        $select .= "  </optgroup>\r\n";
                    }
                    $currentog=$row[$optgroup];
                    $select .= "  <optgroup label='".$currentog."'>\r\n";
                }
            }
            $select.="    <option value='".$row[$value]."'";
            if($row[$value]==$selectedvalue) {
                $select .= " selected=selected";
            }
            $select .= ">".$row[$text]."</option>\r\n";    
        }
        if($optgroup) {
            $select .= "  </optgroup>";
        }
        $select .= "</select>";
        return $select;
    }
    
}




?>
