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
            0=>"Field Changed",
            1=>"Case Opened",
            2=>"Case Closed",
            3=>"Case Edited",
            4=>"Note Added",
            5=>"Note Deleted",
            6=>"Note Deleted",
            7=>"Attachment Added",
            8=>"Attachment Deleted",
            9=>"Notification Added",
            10=>"Notification Deleted",
            11=>"Related Case Added",
            12=>"Related Case Deleted",
            13=>"Case Reopened",
            14=>"Case Assigned",
            15=>"Case Related to Other Case",
            16=>"Case unRelated to Other Case",
            17=>"Reminder Added",
            18=>"Reminder Deleted",
            19=>"Linked Child Case Added",
            20=>"Linked Child Case Deleted",
            21=>"Case made a linked child Case",
            22=>"Case no longer a linked child Case",
            23=>"Linked parent status removed",
            24=>"Planning Note deleted",
            25=>"Planning note marked as read",
            26=>"Companion task added",
            27=>"Companion task removed",
            28=>"Task made companion of other case",
            29=>"Task removed as companion of other case",
            30=>"Acknowledgement of checklist item",            
        );
        

        foreach($results['output'] as $key=>$resultitem) {
            $results['output'][$key]['event_description']=$eventtypes[$resultitem['event_type']];
            
        }

        $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
          
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
    
}




?>
