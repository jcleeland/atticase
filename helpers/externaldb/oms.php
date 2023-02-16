<?php
/**
* Class CaseTracker extends the base class fucntionality with EXTERNAL database
* specific functions
* 
*/
//error_reporting(E_ALL);

class CaseTracker extends oct {

    var $password;
    var $username;
    var $returntransfer=TRUE;
    var $useragent='CaseTracker';
    var $httpheader=array('Content-type: application/json');
    var $baseurl="https://oms.economicoutlook.net/cpsuvic/apis/rest/admin";

    /**
    * Open connection to the database, in this case a standard ODBC connection.
    * On linux servers this requires openodbc or similar, and a dsn connection
    * 
    * @param mixed $mustodbcdsn The name DSN connection (from casetracker.conf.php)
    * @param mixed $mustdbuser The database username
    * @param mixed $mustdbpass The database password
    */
    function odbcOpen($mustodbcdsn = '', $mustdbuser='', $mustdbpass='') {
        $this->username=$mustdbuser;
        $this->password=$mustdbpass;
        $this->baseurl=$mustodbcdsn;
        return true;
    }

    /**
    * Close the odbc database link
    * 
    */
    function odbcClose() {
      return true;
    }
    
    
    
    //OMS SPECIFIC FUNCTIONALITY
    function curl_put($username=null, $password=null, $url=null, $httpheader=array('Content-type: application/json'), $useragent='', $jsondata=null, $debug=false) {
        $ch=curl_init();
        
        if($username) $this->username=$username;
        if($password) $this->password=$password;
        $userpass=$this->username.":".$this->password;
        
        if($useragent) $this->useragent=$useragent;
        
        curl_setopt($ch, CURLOPT_USERPWD, $userpass);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); //From David
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS); //From David
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); //From David
        curl_setopt($ch, CURLOPT_POST, true); //From David
        curl_setopt($ch, CURLOPT_HEADER, true); //From David
        curl_setopt($ch, CURLOPT_ENCODING, ""); //From David
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        
        if(!$output=curl_exec($ch)) {
            die("Error: ".curl_error($ch)."\r\n<br />Code: ".curl_errno($ch)."<br />URL Query: $url");
        }

        if($debug) {
            $info=curl_getinfo($ch);
            echo "<pre>"; print_r($httpheader); 
            echo "<hr />"; print_r($jsondata); echo "\n\n<hr />\n\n";
            echo "<hr />"; 
            print_r($info);
            echo "</pre>";
        }

        curl_close($ch);
        
        $output=json_decode($output);
                
        return $output;        
    }

    function curl_post($username=null, $password=null, $url=null, $httpheader=array('Content-type: application/json'), $useragent='', $jsondata=null, $debug=false) {
        $ch=curl_init();
        
        if($username) $this->username=$username;
        if($password) $this->password=$password;
        $userpass=$this->username.":".$this->password;
        
        if($useragent) $this->useragent=$useragent;
        
        curl_setopt($ch, CURLOPT_USERPWD, $userpass);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); //From David
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS); //From David
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); //From David
        curl_setopt($ch, CURLOPT_POST, true); //From David
        curl_setopt($ch, CURLOPT_HEADER, true); //From David
        curl_setopt($ch, CURLOPT_ENCODING, ""); //From David
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
        curl_setopt($ch, CURLOPT_URL, $url);
        
        if(!$output=curl_exec($ch)) {
            die("Error: ".curl_error($ch)."\r\n<br />Code: ".curl_errno($ch)."<br />URL Query: $url");
        }

        if($debug) {
            $info=curl_getinfo($ch);
            echo "<pre>HTTP HEADERS:\n"; print_r($httpheader); 
            echo "<hr />"; print_r($jsondata); echo "\n\n<hr />\n\n"; 
            print_r($info);
            echo "</pre>";
        }

        curl_close($ch);
        
        $output=json_decode($output);
                
        return $output;        
    }
    
    function curl_call($username=null, $password=null, $url=null, $httpheader=array('Content-type: application/json'), $useragent='', $debug=null) {
        $ch=curl_init();
        
        if($username) $this->username=$username;
        if($password) $this->password=$password;
        $userpass=$this->username.":".$this->password;
        $backtrace=debug_backtrace();
        //echo "<pre>"; print_r($backtrace); echo "</pre>";
        if($useragent) $this->useragent=$useragent;
        
        curl_setopt($ch, CURLOPT_USERPWD, $userpass);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);

        $output=curl_exec($ch);
        
        if(curl_errno($ch) > 0) {
            die("Error: ".curl_error($ch)."\r\n<br />Code: ".curl_errno($ch)."<br />URL Query: $url");
        }

        if($debug) {
            $info=curl_getinfo($ch);
            echo "<pre>"; print_r($httpheader); 
            echo "<hr />"; print_r($jsondata); echo "\n\n<hr />\n\n";
            echo "Using ".$username." / ".$password."\n\n<hr />\n\n"; 
            print_r($info);
            echo "</pre>";
        }
        
        curl_close($ch);
        $output=json_decode($output);
        return $output;
    }

    function curl_delete($username=null, $password=null, $url=null, $httpheader=array('Content-type: application/json'), $useragent='', $debug=null) {
        $ch=curl_init();
        
        if($username) $this->username=$username;
        if($password) $this->password=$password;
        $userpass=$this->username.":".$this->password;
        
        if($useragent) $this->useragent=$useragent;
        
        curl_setopt($ch, CURLOPT_USERPWD, $userpass);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        $output=curl_exec($ch);
        
        if(curl_errno($ch) > 0) {
            die("Error: ".curl_error($ch)."\r\n<br />Code: ".curl_errno($ch)."<br />URL Query: $url");
        }

        if($debug) {
            $info=curl_getinfo($ch);
            echo "<pre>"; print_r($httpheader); 
            echo "<hr />"; print_r($jsondata); echo "\n\n<hr />\n\n";
            echo "Using ".$username." / ".$password."\n\n<hr />\n\n"; 
            print_r($info);
            echo "</pre>";
        }
        
        curl_close($ch);
        $output=json_decode($output);
        return $output;
    }
   
    /**
    * Return member information
    * 
    * @param mixed $member
    */
    function getOmsMemberSummary($member, $take=1) {
        $url=$this->baseurl."/associations/memberships/";
        $vals="?reference=".$member;
        $vals.="&take=$take";
        $httpheader=array('Accept: application/vnd.eo.membershipassociationsummary+json');
        $output=$this->curl_call($this->username, $this->password, $url.$vals, $httpheader, $this->useragent, false);
        
        return $output;
    }
    
    function getSummaryFromCache($member) {
        $get_details=$this->dbQuery(
            "SELECT * FROM ".$this->returnDBPrefix()."member_cache WHERE member=?",
            array(intval($member))
        );
        $get_details=$this->dbFetchRow($get_details);

        return $get_details;        
    }
    
    function updateOmsCache($member, $data, $method="update") {
        $storedata2=json_encode($data);
        
        $storedata=openssl_encrypt($storedata2, 'aes-128-cbc', 'ct2016');
        
        $now=time();
        
        //echo "<pre>"; print_r($data); die();
        //$retrdata=openssl_decrypt($storedata, 'aes-128-ctr', 'ct2016' );
        //$datadata=json_decode($retrdata);
        //Save this data to cache
        
        $prefname=!empty($data->person->alias) ? $data->person->alias : $data->person->firstNames;
        if($method=="insert") {
            $query = "INSERT INTO ".$this->returnDBPrefix()."member_cache 
                      (member, surname, pref_name, modified, primary_key, `data`) 
                      VALUES ('$member', '"
                      .addslashes($data->person->lastName)
                      ."', '"
                      .addslashes($prefname)
                      ."', '"
                      .$now."', '"
                      .$data->person->id
                      ."', '"
                      .$storedata
                      ."')";
        } else {
            $query = "UPDATE ".$this->returnDBPrefix()."member_cache
                      SET surname='".addslashes($data->person->lastName)."',
                      pref_name='".addslashes($prefname)."',
                      modified='".$now."',
                      primary_key='".$data->person->id."',
                      `data`='".$storedata."'
                      WHERE member=".$member;
        }
        $this->dbQuery($query);        
    }
   
    function decryptData($data) {
        $retrdata=openssl_decrypt($data, 'aes-128-cbc', 'ct2016' );
        $datadata=json_decode($retrdata);
        return $datadata;
    }
    
    function getMembershipStatus($member) {
        $casetracker_prefs = $this->GetGlobalPrefs();
      
        $get_details=$this->getSummaryFromCache($member);
        
        $data=$this->decryptData($get_details['data']);
        
        $now=time();
        $to=!empty($data->membershipAssociation->association->to) ? date("U", strtotime($data->membershipAssociation->association->to)) : null;
        if($to && $to < $now) {return false;}
        else {return true;}
                
    }
    
    function findMemberByQuery($query, $groupId=null, $dateFrom=null, $dateTo=null, $timespanIntersection=null) {
        $url=$this->baseurl."/associations/memberships/";
        $vals="?take=100&query=$query";
        
        //Use this to narrow the search to a specific membershipgroup
        if($groupId) {
            $vals.="&groupId=".$groupId;
        }
        
        //use this to narrow the search to specific membership periods
        if($dateFrom && $dateTo) {
            $vals.="&from=$dateFrom&to=$dateTo&timespanIntersection=$timespanIntersection";
        }
        
        $httpheader=array('Accept: application/json;schema=simple');
        
        $output=$this->curl_call($this->username, $this->password, $url.$vals, $httpheader, $this->useragent);
        
        return $output;        
    }
    
    function findPersonByQuery($query, $groupId=null) {
        $url=$this->baseurl."/people/";
        $vals="?query=".$query;
        if(!empty($groupId)) {
            $vals.="&groupId=".$groupId;
        }
        $vals.="&take=300";
        $httpheader=array('Accept: application/json;schema=person');
        
        $output=$this->curl_call($this->username, $this->password, $url.$vals, $httpheader, $this->useragent);
        
        return $output;        
    }
    
    /**
    * Sorting function for uSort keyed array by "surname"
    * 
    * @param mixed $a
    * @param mixed $b
    * @return mixed
    */
    function sortByFullname($a, $b) {
        return strcmp($a["fullname"], $b["fullname"]);
    }
    function sortByName($a, $b) {
        return strcmp($a["name"], $b["name"]);
    }
    
    function sortByFirstnames($a, $b) {
        return strcmp($a['firstNames'], $b['firstNames']);
    }
    function sortByLastThenFirstnames($a, $b) {
        return strcmp($a['surname'].$a['given_names'], $b['surname'].$b['given_names']);
    }
    
    
    //TRADITIONAL CASETRACKER SHIM/FUNCTIONALITY
    
    /**
    * Perform a SQL command on the odbc connection
    * 
    * @param mixed $sql The SQL command to perform
    * @param mixed $inputarr Any input values in an array format
    * @param mixed $numrows The number of rows to return (-1 = unlimited)
    * @param mixed $offset Which record to start at
    */
    function odbcExec($sql, $inputarr=false, $numrows=-1, $offset=-1) {

      return true;
      
    }

    /**
    * Perform a query on the odbc connection (this is the function to use)
    * 
    * @param mixed $sql
    * @param mixed $inputarr
    * @param mixed $numrows
    * @param mixed $offset
    */
    function odbcQuery($sql, $inputarr=false, $numrows=-1, $offset=-1) {
      return true;
    }

    function odbcFetchArray(&$result) {
      $row = $this->dbFetchRow($result);
      return $row;
    }

    /**
    * ReturnSmiley returns a smiley face based on the financiality of the member
    * 
    * @param mixed $member - the unique id of the member
    * @param mixed $tags - HTML tags to include in the output
    * @param mixed $popup - Extra text to include in the html popup message
    * @return mixed
    */
    function returnSmiley($member, $tags="", $popup="") {
       $casetracker_prefs = $this->GetGlobalPrefs();
       if ($member == 0) {
           return "";
       }
       $uuid=$this->GetLegitMemberNumber($member);
       
       $spd=$this->getPaidTo($member);
       list($pfr, $pmt)=$this->GetPaymentInfo($member);
       $status=$this->getMembershipStatus($member);
   
   
       $time=time();
       $offset=16; //How many days leeway to give before the smiley indicates they are behind.
                   //A value of 16, for example, will mean that the embarrassed face only starts showing up when the member is over 16 days in arrears
       $subs_paid_to=$spd ? intval(((((($spd)-time())/60/60/24)-(26-$offset))/30)+0) : 0;
       $spd_date=date("d M Y", $spd);
       $explanation = "This member is paying on level $pmt, and payments are made $pfr. Subscriptions are currently paid up until $spd_date";
       
       
       
       if ($uuid=$this->GetLegitMemberNumber($member) && $status===true) {
          switch($subs_paid_to) {
             case -3:
               $output = "<img $tags src='themes/".$casetracker_prefs['theme_style']."/mortified.png' border='0' title='This member is financial but between two and three months in arrears! ($explanation)'";
               break;
             case -2:
               $output = "<img $tags src='themes/".$casetracker_prefs['theme_style']."/ashamed.png' border='0' title='This member is financial but between one and two months in arrears! ($explanation)'";
               break;
             case -1:
               $output = "<img $tags src='themes/".$casetracker_prefs['theme_style']."/embarrassed.png' border='0' title='This member is financial but up to one month in arrears! ($explanation)'";
               break;
             default:
               $output = "<img $tags src='themes/".$casetracker_prefs['theme_style']."/happy.png' border='0' title='This member is Fully financial $popup ($explanation)'";
               break;
          }
          if ($popup != "") {
              $output .=" onClick='alert(\"This member is FINANCIAL $popup\")'";
          }
          $output .= ">";
          return $output;
       } else {
             $output ="<img $tags src='themes/".$casetracker_prefs['theme_style']."/sad.png' border='0' title='This member is not a current financial member!'";
          if ($popup != "") {
              $output .= "return  onClick='alert(\"This member is not a current financial member $popup\")'";
          }
          $output .= ">";
          return $output;
       }
   }

    /**
    * Checks if a membership number is legitimate or not
    * For OMS, returns the UUID if exists, or FALSE if it doesn't
    * 
    * @param mixed $member
    */
    function GetLegitMemberNumber($member) {
        //Gets Legitimate Financial Member Numbers
        global $conf_array;
        $now=time();        
        
        //First check the cache
        $get_details = $this->dbQuery("SELECT modified, primary_key FROM ".$this->returnDBPrefix()."member_cache WHERE member=?", array(intval($member)));
        $get_details = $this->dbFetchRow($get_details);
        
        if(empty($get_details)) {
            //Search OMS
            $output=$this->getOmsMemberSummary($member);
            //echo "<pre style='text-align: left'>"; print_r($output); die("Done in getLegitMemberNumber");
            if(count($output->response->membershipAssociationSummaries) > 0) {
                $this->updateOmsCache($member, $output->response->membershipAssociationSummaries[0], "insert");
                return $output->response->membershipAssociationSummaries[0]->person->id;
            } else {
                return FALSE;
            }
        } else {
            //If the default time limit has passed since "modified", collect updated information
            if($now-$get_details['modified'] > 24*60*60) {
                $output=$this->getOmsMemberSummary($member);
                $this->updateOmsCache($member, $output->response->membershipAssociationSummaries[0], "update");
            }
            return $get_details['primary_key'];
        }
    }

    /**
    * Returns a "paid-to" date
    * 
    * @param mixed $uuid
    */
    function GetPaidTo($member) {
        
        
      //Gets "paid_to" date for member
      $casetracker_prefs = $this->GetGlobalPrefs();
      $month='12'; $year='1900'; $day='25';
      
      $get_details=$this->dbQuery("SELECT data, modified FROM ".$this->returnDBPrefix()."member_cache WHERE member=?", array(intval($member)));
      $get_details=$this->dbFetchRow($get_details);
      $datadata=$this->decryptData($get_details['data']);
      $modified=$get_details['modified'];

      //Calculate the $month, $day and $year of the last fully paid subs / subs-paid-to date, then return that
      // in php date format
      
      
      return mktime(0, 0, 0, $month, $day, $year);
    }

    function GetPayMethod($member) {
      $casetracker_prefs = $this->GetGlobalPrefs();
      
      $get_details=$this->dbQuery("SELECT * FROM ".$this->returnDBPrefix()."member_cache WHERE member=?", array(intval($member)));
      $get_details = $this->dbFetchRow($get_details);
      
      $data=$this->decryptData($get_details['data']);
      //echo "<pre style='text-align: left'>"; print_r($data);die("Done");
      $subs=$data->currentSubscriptionPeriod;
      
      return "subscriptions";
      //return $get_details['pay_method'];
    }

    function GetPaymentInfo($member) {
      $casetracker_prefs = $this->GetGlobalPrefs();
      
      $get_details=$this->getSummaryFromCache($member);
      
      $data=$this->decryptData($get_details['data']);
      
      //echo "<pre style='text-align: left'>"; print_r($data);die("Done");
      $subs=$data->currentSubscriptionPeriod;
      return array($subs->subscriptionFrequencyName, $subs->subscriptionMethodName);
    }

    function highlightMemberNumberByEmpType($member, $emp_type) {
        if ($emp_type == "C" || $emp_type == "X") {
            return "<font color='green'>$member</font>";
        } else {
            return "<font color='red'>$member</font>";
        }
    }

    function returnMemberNumber($member, $searchbutton=TRUE) {
        global $project_prefs, $casetracker_prefs;
        if ($member == 0) {
            $number = "N/A";
        } else {
            $number = $member;
        }
        if ($this->GetLegitMemberNumber($member)) {
            $output="<font color='green'>$number</font>";
        } else {
            $output="<font color='red'>$number</font>";
        }
        if ($search) {
            $output.= "&nbsp;<a href='' onClick='window.open(\"index.php?do=member_search&member=$number\", \"_blank\", \"toolbar=no, scrollbars=yes, width=610, height=450\")'><img src='themes/" . $project_prefs['theme_style'] . "/find.png' border='0' hspace=0 vspace=0 title='Search' /></a>";
        }
        return $output;
    }

    function returnMemberName($member=0) {
        if (empty($member)) {
            $member=0;
        }
        global $conf_array;
        $now=time();        
        
        //First check the cache
        $get_details=$this->getSummaryFromCache($member);
        
        //echo "<pre style='text-align: left'>"; print_r($get_details); die("from returnMemberName");
        if(empty($get_details)) {
            //Search OMS
            $output=$this->getOmsMemberSummary($member);
            //echo "<pre style='text-align: left'>"; print_r($output); die("Done in returnMemberName");
            if(count($output->response->membershipAssociationSummaries) > 0) {
                $data=$output->response->membershipAssociationSummaries[0];
                $this->updateOmsCache($member, $data, "insert");
                $firstname=!empty($data->person->alias) ? $data->person->alias : $data->person->firstNames;
                $name=$firstname." ".$data->person->lastName;
            }
        } else {
            //If the default time limit has passed since "modified", collect updated information
            if($now-$get_details['modified'] > 24*60*60) {
                $output=$this->getOmsMemberSummary($member);
                $this->updateOmsCache($member, $output->response->membershipAssociationSummaries[0], "update");
            }
            $name=$get_details['pref_name']." ".$get_details['surname'];
        }
        
        
        
        if(!empty($name)) {
            return $name;
        } else {
            return "NA";
        }
    
        /*
        $sql = "SELECT given_names, pref_name, surname
                FROM members
                WHERE member = ".$member;
        $return = $this->odbcQuery($sql);
        $return = $this->dbFetchArray($return);
        //print_r($return)."<br />";
        $output="";
        if (!empty($return)) {
                if(empty($return['pref_name'])) {$output = $return['given_names']." ".$return['surname'];}
                else {$output = $return['pref_name']." ".$return['surname'];}
        }
        return $output;
        */
   }

    function returnMemberUUID($member=0) {
        $url=$this->baseurl."/associations/memberships/";
        $vals="?reference=".$member;
        $vals.="&take=1";
        $httpheader=array('Accept: application/vnd.eo.person+json');
        $output=$this->curl_call($this->username, $this->password, $url.$vals, $httpheader, $this->useragent, false);
        $uuid=$output->response->people[0]->id;
        return $uuid;
    }
   
    function returnDaysMember($member) {
        if ($member==0) {
          return "";
        }
        $casetracker_prefs=$this->getGlobalPrefs();
        
        $get_details=$this->dbQuery(
            "SELECT * FROM ".$this->returnDBPrefix()."member_cache WHERE member=?",
            array(intval($member))
        );
        $get_details=$this->dbFetchRow($get_details);
        $data=$this->decryptData($get_details['data']);
        
        $from=date("U", strtotime($data->membershipAssociation->association->from));
        $to=!empty($data->membershipAssociation->association->to) ? date("U", strtotime($data->membershipAssociation->association->to)) : time();
        $daysmember=intval(($to-$from)/60/60/24);
        //echo "<pre style='text-align: left'>"; print_r($data); die("From returnDaysMember()");
        return $daysmember;
    }

    function returnSubsLevel($member) {
        if ($member==0) {
            return "";
        }
        
        $get_details=$this->getSummaryFromCache($member);
        $data=$this->decryptData($get_details['data']);
        
        return $data->currentSubscriptionPeriod->subscriptionMethodName;        

   }

    function returnContactInfo($member) {
        if ($member==0) {
          return array();
        }
        $results=array();
        
        $get_details=$this->getSummaryFromCache($member);
        $data=$this->decryptData($get_details['data']);
        
        //echo "<pre style='text-align: left'>"; print_r($data); die("from returnContactInfo");
        $results=array("work_phone"=>"stub", "home_phone"=>$data->person->homePhone, "email"=>$data->person->email, "attrib_6"=>$data->person->mobilePhone, "mail_addr_1"=>$data->person->address->formatted);

        return $results;
   }

    function returnMemberNameWithPE($member) {
        $today=date("Y-m-d");
        
        $get_details=$this->getSummaryFromCache($member);
        $data=$this->decryptData($get_details['data']);
        
        $firstname=!empty($data->person->alias) ? $data->person->alias : $data->person->firstNames;
        $return=$firstname . " " . $data->person->lastName. "(".$data->currentEmployment->groupName.")";
        
        return $return;
        
   }

    function getMemberPayingEmp($member) {
        $today=date("Y-m-d");
        
        $get_details=$this->getSummaryFromCache($member);
        $data=$this->decryptData($get_details['data']);
        return $data->currentEmployment->groupName;
        
        /*$get_details = $this->odbcQuery("SELECT paying_emp FROM members WHERE member = $member");
        $get_details = $this->odbcFetchArray($get_details); */
   }

    function getPayingEmpCatCode($paying_emp) {
        return($paying_emp);
        
        $get_details = $this->dbQuery("SELECT category_id
                                       FROM ".$this->returnDBPrefix()."list_category
                                       WHERE category_name = ?",
                                       array($paying_emp));
        $get_details = $this->dbFetchArray($get_details);
        return $get_details['category_id'];
   }

    function getPayingEmpCatCodeTas($paying_emp) {
        $get_details = $this->dbQuery("SELECT category_id
                                       FROM ".$this->returnDBPrefix()."list_category
                                       WHERE category_descrip = ?",
                                       array($paying_emp));
        $get_details = $this->dbFetchArray($get_details);
        //print "\n\n<!-- paying_emp: $paying_emp -->\n";
        return $get_details['category_descrip'];
   }

    function GetMemberDetails($member=0) {
        global $must;
        if(empty($member)) {$member=0;}
        $casetracker_prefs = $this->GetGlobalPrefs;
        $lang = $casetracker_prefs['lang_code'];
        if(!$must) {
            $get_details=$this->dbQuery("SELECT name FROM ".$this->returnDBPrefix()."tasks
                                         WHERE member = ?",
                                         array($member));
            $get_details = $this->dbFetchArray($get_details);
            if(empty($get_details)) {
                $get_details=array();
            } else {
                list($given_names, $surname)=split(" ", $get_details[0]['name']);
                $get_details[0]['surname']=$surname;
                $get_details[0]['given_names']=$given_names;
                $get_details[0]['email']="";
            }
            return $get_details;
        }
        
        $get_details=$this->getSummaryFromCache($member);
        $data=$this->decryptData($get_details['data']);
        
        /* members.surname, members.given_names, members.pref_name, members.title, members.email, members.birth,
           members.gender, members.home_addr_1, members.home_addr_2, members.home_addr_3,
           members.home_phone, members.work_phone, members.fax, members.actual_emp, members.paying_emp,
           employers.emp_descrip, workplaces.work_addr_1, workplaces.work_addr_2, workplaces.work_addr_3,
           workplaces.work_addr_4, members.member, mem_attributes.attrib_6, mem_subs_control.subs_paid_to,
           awards.award, awards.award_descrip, award_details.url, members.mail_addr_1
        */
        
        $return=array(
            "surname"=>$data->person->lastName,
            "given_names"=>$data->person->firstNames,
            "pref_name"=>$data->person->alias,
            "title"=>"",
            "email"=>$data->person->email,
            "birth"=>date("Y-m-d", strtotime($data->person->dob)),
            "gender"=>$data->person->gender,
            "home_addr_1"=>$data->person->address->subStreet,
            "home_addr_2"=>$data->person->address->street,
            "home_addr_3"=>$data->person->address->locality.", ".$data->person->address->administrativeArea." ".$data->person->address->postalCode,
            "home_phone"=>$data->person->homePhone,
            "work_phone"=>"STUB",
            "fax"=>"",
            "actual_emp"=>"",
            "paying_emp"=>$data->currentEmployment->groupName,
            "emp_descrip"=>$data->currentEmployment->groupName,
            "work_addr_1"=>"",
            "work_addr_2"=>"",
            "work_addr_3"=>"",
            "work_addr_4"=>"",
            "member"=>$data->membershipAssociation->reference,
            "attrib_6"=>$data->person->mobilePhone,
            "subs_paid_to"=>"",
            "award"=>"",
            "award_descrip"=>"",
            "url"=>"",
            "mail_addr_1"=>""
        );

        return $return;
    }

    function getCommitteeInfo($member) {
        $uuid=$this->returnMemberUUID($member);
        
        $url=$this->baseurl."/associations/";
        $vals="?personId=".$uuid;
        $httpheader=array('Accept: application/vnd.eo.associationplus+json');
        $output=$this->curl_call($this->username, $this->password, $url.vals, $httpheader, $this->useragent, false);
        
      /* $details=$this->odbcQuery("SELECT * FROM committees, committee_mems
                                                           WHERE committees.committee=committee_mems.committee
                                                           AND member = $member
                                                             ORDER BY committee_descrip",
                                                           array());
      $output=array();
      while ($row = $this->odbcFetchArray($details)) {
          $output[]=$row;
        } */
        return $output;
    }

    function getCorroDetails($member) {
        /* $get_details = $this->odbcQuery("SELECT *
                                       FROM corro
                                       WHERE file_number = ?
                                       ORDER BY date_received DESC", array($member)); */
        $output=array();
        /* while ($row = $this->odbcFetchArray($get_details)) {
            $output[]=$row;
        } */
        return $output;
    }

    /**
    * Search Members Returns an array containing detailed member
    * information from the ODBC database as per the conditions in the $where variable
    * 
    * Note that because the $where expects database specific select statements this
    * is usually called from within the external DB script
    * 
    * @param mixed $wheres - an array containing additional "WHERE" statements such as:
    *     "surname LIKE '%CLEELAND%'"
    */
    function SearchMembers($wheres) {
        //This function is used by member_search.php
        // - if multiple responses are returned, only the member number, name, employer and worksite info is needed
        // - if a single result is returned, then the full data is required.
        
        // So initially this function searches by the findMemberByQuery() function, which
        //  only returns a limited amount of information
        // If only one person is returned, then a subsequent search is done using the getOmsMemberSummary function
        //  which is a far more comprehensive object and which can then fill out all the other fields
        list($action,$method,$query)=explode(" ", $wheres);
        
        //echo $action."->".$method."->".$query;
        if($action=="findPeopleByQuery") {
            if (preg_match ("/^\d+$/", $query)) {
                //Search by member reference (member number)
                
                //First - search the cache, that's the best place to find this kind of thing
                //  note that this particular search will only ever return a single response
                $get_details=$this->dbQuery(
                    "SELECT * FROM ".$this->returnDBPrefix()."member_cache WHERE member=?",
                    array(intval($query))
                );
                $get_details=$this->dbFetchRow($get_details);
                if(!empty($get_details)) {
                    //Since this is only ever a single response, we can return the full dataset
                    $obj=$this->decryptData($get_details['data']);
                    $resignation=!empty($obj->membershipAssociation->association->to) ? date("Y-m-d h:i:s", strtotime($obj->membershipAssociation->association->to)) : null;
                    //echo "<pre style='text-align: left'>"; print_r($obj); //die("from SearchMembers- memberNumber-Cache");
                    $emptype=date("U", strtotime($obj->membershipAssociation->association->to)) < time() ? "X" : "C";
                    $return[]=array(
                            "surname"=>$obj->person->lastName,
                            "given_names"=>$obj->person->firstNames,
                            "pref_name"=>$obj->person->alias,
                            "email"=>$obj->person->email,
                            "birth"=>date("Y-m-d h:i:s", strtotime($obj->person->dob)),
                            "gender"=>$obj->person->gender,
                            "home_addr_1"=>$obj->person->address->street,
                            "home_addr_2"=>$obj->person->address->locality,
                            "home_addr_3"=>$obj->person->address->administrativeArea." ".$obj->person->address->postalCode,
                            "home_phone"=>$obj->person->homePhone,
                            "work_phone"=>$obj->person->workPhone,
                            "fax"=>"",
                            "actual_emp"=>$obj->currentEmployment->groupName,
                            "paying_emp"=>$obj->currentEmployment->groupName,
                            "joined"=>date("Y-m-d h:i:s", strtotime($obj->membershipAssociation->association->from)),
                            "resignation"=>$resignation,
                            "workplace"=>$obj->currentEmploymentWorksite->worksite->name,
                            "employed"=>date("Y-m-d h:i:s", strtotime($obj->currentEmployment->from)),
                            "emp_descrip"=>$obj->currentEmployment->groupName,
                            "work_addr_1"=>$obj->currentEmploymentWorksite->worksite->subStreet,
                            "work_addr_2"=>$obj->currentEmploymentWorksite->worksite->street,
                            "work_addr_3"=>$obj->currentEmploymentWorksite->worksite->locality,
                            "work_addr_4"=>$obj->currentEmploymentWorksite->worksite->administrativeArea." ".$obj->currentEmploymentWorksite->worksite->postalCode,
                            "member"=>$obj->membershipAssociation->reference,
                            "attrib_3"=>"",
                            "attrib_4"=>"",
                            "attrib_6"=>$obj->person->mobilePhone,
                            "subs_paid_to"=>"STUB",
                            "award"=>"STUB",
                            "award_descrip"=>"STUB",
                            "actual_emp_descrip"=>$obj->currentEmployment->groupName,
                            "class_descrip"=>$obj->currentEmployment->position,
                            "emp_type"=>$emptype,
                            "award_details"=>"",
                            "mem_type"=>"",
                            "mail_addr_1"=>"",
                            "fullname"=>$obj->lastName.$obj->firstNames
                        );
                } 
                else {
                    
                    $output=$this->getOmsMemberSummary($query, 100);
                    //echo "<pre style='text-align: left'>"; print_r($output); die("from SearchMembers-getOmsMemberSummary-memberNumber");
                    
                    if(count($output->response->membershipAssociationSummaries) < 2) {
                        //Get the personId and do a summarysearch
                        foreach($output->response->membershipAssociationSummaries as $newobj) {
                            //echo "<pre style='text-align: left'>"; print_r($newobj); die("memberSearch - from OMS - only found one");
                            $member=$newobj->membershipAssociation->reference;
                            $resignation=!empty($newobj->membershipAssociation->association->to) ? date("Y-m-d h:i:s", strtotime($newobj->membershipAssociation->association->to)) : null;
                            if(!empty($obj->membershipAssociation->association->to) && date("U", strtotime($obj->membershipAssociation->association->to)) < time()) {
                                $emptype="Z";
                            } else {
                                $emptype="C";
                            }
                            $return[]=array(
                                "surname"=>$newobj->person->lastName,
                                "given_names"=>$newobj->person->firstNames,
                                "pref_name"=>$newobj->person->alias,
                                "email"=>$newobj->person->email,
                                "birth"=>date("Y-m-d h:i:s", strtotime($newobj->person->dob)),
                                "gender"=>$newobj->person->gender,
                                "home_addr_1"=>$newobj->person->address->street,
                                "home_addr_2"=>$newobj->person->address->locality,
                                "home_addr_3"=>$newobj->person->address->administrativeArea." ".$newobj->person->address->postalCode,
                                "home_phone"=>$newobj->person->homePhone,
                                "work_phone"=>$newobj->person->workPhone,
                                "fax"=>"STUB",
                                "actual_emp"=>"",
                                "paying_emp"=>$newobj->currentEmployment->groupName,
                                "joined"=>date("Y-m-d h:i:s", strtotime($newobj->membershipAssociation->from)),
                                "resignation"=>$resignation,
                                "workplace"=>$newobj->currentEmploymentWorksite->worksite->name,
                                "employed"=>date("Y-m-d h:i:s", strtotime($newobj->currentEmployment->from)),
                                "emp_descrip"=>$newobj->currentEmployment->groupName,
                                "work_addr_1"=>$newobj->currentEmploymentWorksite->worksite->address->substreet,
                                "work_addr_2"=>$newobj->currentEmploymentWorksite->worksite->address->street,
                                "work_addr_3"=>$newobj->currentEmploymentWorksite->worksite->address->locality,
                                "work_addr_4"=>$newobj->currentEmploymentWorksite->worksite->address->administrativeArea.", ".$newobj->currentEmploymentWorksite->worksite->address->postalCode,
                                "member"=>$member,
                                "attrib_3"=>"STUB",
                                "attrib_4"=>"STUB",
                                "attrib_6"=>$newobj->person->mobilePhone,
                                "subs_paid_to"=>"STUB",
                                "award"=>"STUB",
                                "award_descrip"=>"STUB",
                                "actual_emp_descrip"=>"STUB",
                                "class_descrip"=>"STUB",
                                "emp_type"=>$emptype,
                                "award_details"=>"STUB",
                                "mem_type"=>"STUB",
                                "mail_addr_1"=>"STUB"
                            );                            
                        }
                    } else {
                        foreach($output->response->membershipAssociationSummaries as $obj) {
                            $firstname=!empty($obj->person->alias) ? $obj->person->alias : $obj->person->firstNames;
                            //echo "<pre><br />"; print_r($obj); die("searchMembers - Found One");
                            if(!empty($obj->membershipAssociation->association->to) && date("U", strtotime($obj->membershipAssociation->association->to)) < time()) {
                                $emptype="Z";
                            } else {
                                $emptype="C";
                            }
                            
                            $return[]=array(
                                "member"=>$obj->membershipAssociation->reference,
                                "surname"=>$obj->person->lastName,
                                "given_names"=>$obj->person->firstNames,
                                "name"=>$firstname." ".$obj->person->lastName,
                                "paying_emp"=>$obj->currentEmployment->groupName,
                                "workplace"=>$obj->currentEmploymentWorksite->address->formatted,
                                "work_phone"=>"9876 5432",
                                "emp_type"=>$emptype,
                                "fullname"=>$obj->person->lastName.", ".$obj->person->firstNames
                            );
                        }                        
                    }                    
                }
                usort($return, array("CaseTracker", "sortByFullname"));
            } 
            else {
                //strip the apostrophe from the beginning and the apostrophe & % from end
                $query=ltrim($query, "'");
                $query=rtrim($query, "AND");
                $query=rtrim($query);
                $query=rtrim($query, "'");
                $query=rtrim($query, "%");
                $output=$this->getOmsMemberSummary($query, 100);
                //echo "<pre style='text-align: left'>"; print_r($output); die("searchMembers - OmsSummary");
                if(count($output->response->membershipAssociationSummaries) < 2) {
                    foreach($output->response->membershipAssociationSummaries as $newobj) {
                        $member=$newobj->membershipAssociation->reference;
                        $resignation=!empty($newobj->membershipAssociation->to) ? date("Y-m-d h:i:s", strtotime($newobj->membershipAssociation->to)) : null;
                        $return[]=array(
                            "surname"=>$newobj->person->lastName,
                            "given_names"=>$newobj->person->firstNames,
                            "pref_name"=>$newobj->person->alias,
                            "email"=>$newobj->person->email,
                            "birth"=>date("Y-m-d h:i:s", strtotime($newobj->person->dob)),
                            "gender"=>$newobj->person->gender,
                            "home_addr_1"=>$newobj->person->address->street,
                            "home_addr_2"=>$newobj->person->address->locality,
                            "home_addr_3"=>$newobj->person->address->administrativeArea." ".$newobj->person->address->postalCode,
                            "home_phone"=>$newobj->person->homePhone,
                            "work_phone"=>$newobj->person->workPhone,
                            "fax"=>"STUB",
                            "actual_emp"=>"",
                            "paying_emp"=>$newobj->currentEmployment->groupName,
                            "joined"=>date("Y-m-d h:i:s", strtotime($newobj->membershipAssociation->from)),
                            "resignation"=>$resignation,
                            "workplace"=>$newobj->currentEmploymentWorksite->worksite->name,
                            "employed"=>date("Y-m-d h:i:s", strtotime($newobj->currentEmployment->from)),
                            "emp_descrip"=>$newobj->currentEmployment->groupName,
                            "work_addr_1"=>$newobj->currentEmploymentWorksite->worksite->address->substreet,
                            "work_addr_2"=>$newobj->currentEmploymentWorksite->worksite->address->street,
                            "work_addr_3"=>$newobj->currentEmploymentWorksite->worksite->address->locality,
                            "work_addr_4"=>$newobj->currentEmploymentWorksite->worksite->address->administrativeArea.", ".$newobj->currentEmploymentWorksite->worksite->address->postalCode,
                            "member"=>$member,
                            "attrib_3"=>"STUB",
                            "attrib_4"=>"STUB",
                            "attrib_6"=>$newobj->person->mobilePhone,
                            "subs_paid_to"=>"STUB",
                            "award"=>"STUB",
                            "award_descrip"=>"STUB",
                            "actual_emp_descrip"=>"STUB",
                            "class_descrip"=>"STUB",
                            "emp_type"=>"STUB",
                            "award_details"=>"STUB",
                            "mem_type"=>"STUB",
                            "mail_addr_1"=>"STUB"
                        );
                    }
                } else {
                    foreach($output->response->membershipAssociationSummaries as $obj) {
                        //print_r($obj);
                        $firstname=!empty($obj->person->alias) ? $obj->person->alias : $obj->person->firstNames;
                        if(!empty($obj->membershipAssociation->association->to) && date("U", strtotime($obj->membershipAssociation->association->to)) < time()) {
                            $emptype="Z";
                        } else {
                            $emptype="C";
                        }
                        $return[]=array(
                            "member"=>$obj->membershipAssociation->reference,
                            "surname"=>$obj->person->lastName,
                            "given_names"=>$obj->person->firstNames,
                            "name"=>$firstnames." ".$obj->person->lastName,
                            "paying_emp"=>$obj->currentEmployment->groupName,
                            "workplace"=>$obj->currentEmploymentWorksite->address->formatted,
                            "work_phone"=>"9876 5432",
                            "emp_type"=>$emptype,
                            "fullname"=>$obj->person->lastName.", ".$firstname
                        );
                    }                    
                }

                //print_r($return);
                usort($return, array("CaseTracker", "sortByFullname"));
                //usort($return, 'sortByFirstname');
                //echo "<pre style='text-align: left'>"; print_r($return); die("from SearchMembers-byQuery");
                //Search by people reference
            }
        }
        
        
        return $return;
    }

    /**
    * This function returns a list of member numbers to be used searching the internal
    * database. It matches to a name or member number search from the main filter
    * 
    * @param mixed $search - string
    */
    function findMemberNumbers($search) {
        if (is_numeric($search)) {
            //Search is by member number only
            $output[]=$search;
        } else {
            //Search by name
            $search=strtoupper($search);
            $search=str_replace("\'", "%", $search);
            if(strpos(' ', $search)) {$nospaces=str_replace(" ", "%", $search);} else {$nospaces='';}

            
            $return=$this->findMemberByQuery($search);
            foreach($return->simpleMembershipAssociations->simpleMembershipAssociation as $retitem) {
                $output[]=$retitem->reference;
            }
            //echo "<pre>"; print_r($return); die();
            
            
            /*$sql = "SELECT member FROM members WHERE surname LIKE '%".$search."%' OR given_names LIKE '%".$search."%' OR pref_name LIKE '%".$search."%' OR given_names+' '+surname LIKE '$nospaces'";
            $numbers=$this->odbcQuery($sql);
            while ($row = $this->dbFetchArray($numbers)) {
                $output[]=$row['member'];
            }*/
        }
        if (empty($output)) {
            $output=array("99999999999");
        }
        return $output;
    }

    /**
    * listMembers returns a brief summary list of names and member 
    * number using a search value. Specifically used with the ajax
    * search feature when entering a member number or name in new case
    * fields. Only searches among financial members.
    * 
    * @param mixed $value search term (part of name or member number)
    * 
    * @returns array containing member, name, department NAME, workplace (unit)
    */
    function listMembers($value) {
        if(is_numeric($value)) {
            //First - search the cache, that's the best place to find this kind of thing
            $get_details=$this->dbQuery(
                "SELECT * FROM ".$this->returnDBPrefix()."member_cache WHERE member=?",
                array(intval($value))
            );
            $get_details=$this->dbFetchRow($get_details);
            if(!empty($get_details)) {
                $obj=$this->decryptData($get_details['data']);
                $firstname=!empty($obj->person->alias) ? $obj->person->alias : $obj->person->firstNames;
                $return[]=array(
                        "member"=>$obj->membershipAssociation->reference,
                        "name"=>$firstname." ".$obj->person->lastName,
                        "emp_descrip"=>$obj->currentEmployment->groupName,
                        "unit"=>$obj->currentEmploymentWorksite->worksite->address->formatted
                );                
            } else {
                //Go to OMS
                $output=$this->getOmsMemberSummary($value, 50);
                if($output->response->records == 0) {
                    //Didn't return an exact member match. Let's try a more general search
                    $output=$this->findMemberByQuery($value);
                    foreach($output->simpleMembershipAssociations->simpleMembershipAssociation as $item) {
                        if(substr($item->reference, 0, strlen($value)) == $value) {
                            $details=$this->getOmsMemberSummary($item->reference, 1);
                            //print_r($details);
                            $return[]=array(
                                "member"=>$item->reference, 
                                "name"=>$item->personFirstNames." ".$item->personLastName,
                                "emp_descrip"=>$details->response->membershipAssociationSummaries[0]->currentEmployment->groupName,
                                "unit"=>$details->response->membershipAssociationSummaries[0]->currentEmploymentWorksite->worksite->address->formatted
                                );
                        }
                    }
                } else {
                    
                    //echo "<pre style='text-align: left'>$value<br />"; print_r($output); die("from listMembers-memberNumber");
                    foreach($output->response->membershipAssociationSummaries as $obj) {
                        $firstname=!empty($obj->person->alias) ? $obj->person->alias : $obj->person->firstNames;
                        $return[]=array(
                            "member"=>$obj->membershipAssociation->reference,
                            "name"=>$firstname." ".$obj->person->lastName,
                            "emp_descrip"=>$obj->currentEmployment->groupName,
                            "unit"=>$obj->currentEmploymentWorksite->worksite->address->formatted
                        );
                    }
                }

                //Update the cache
                $now=time();
            }
        } else {
            //Search is by name
            $search=str_replace(" ", "%20", $value);
            if(strpos(' ', $search)) {$search=str_replace(" ", "%", $search);}
            /* $sql="SELECT member, name=pref_name+' '+surname, emp_descrip, unit=work_addr_1+' '+work_addr_2+' '+work_addr_3+' '+work_addr_4 
                  FROM members, workplaces, employers 
                  WHERE members.workplace=workplaces.workplace
                  AND members.paying_emp=employers.employer
                  AND emp_type='C'
                  AND (pref_name+' '+surname LIKE '%$search%' 
                  OR given_names+' '+surname LIKE '%$search%')"; */
            
            $output=$this->getOmsMemberSummary($search, 50);
            if($output->response->records == 0) {
                //Didn't return an exact member match. Let's try a more general search
                $output=$this->findMemberByQuery($search, null, date("Y-m-d"), date("Y-m-d"));
                //print_r($output);
                //print_r($output);
                foreach($output->simpleMembershipAssociations->simpleMembershipAssociation as $item) {
                    //if(substr($item->reference, 0, strlen($value)) == $value) {
                        $details=$this->getOmsMemberSummary($item->reference, 1);
                        //print_r($details);
                        $return[]=array(
                            "member"=>$item->reference, 
                            "name"=>$item->personFirstNames." ".$item->personLastName,
                            "emp_descrip"=>$details->response->membershipAssociationSummaries[0]->currentEmployment->groupName,
                            "unit"=>$details->response->membershipAssociationSummaries[0]->currentEmploymentWorksite->worksite->address->formatted
                            );
                    //}
                }
            } else {
                //echo "<pre style='text-align: left'>$value<br />"; print_r($output); die("from listMembers-memberNumber");
                foreach($output->response->membershipAssociationSummaries as $obj) {
                    $firstname=!empty($obj->person->alias) ? $obj->person->alias : $obj->person->firstNames;
                    $return[]=array(
                        "member"=>$obj->membershipAssociation->reference,
                        "name"=>$firstname." ".$obj->person->lastName,
                        "emp_descrip"=>$obj->currentEmployment->groupName,
                        "unit"=>$obj->currentEmploymentWorksite->address->formatted
                    );
                }
            }
        }
        //echo "<pre style='text-align: left'>$value<br />"; print_r($return); //die("from listMembers-memberNumber");
        
        usort($return, array("CaseTracker", "sortByName"));
        //echo "<pre style='text-align: left'>$value<br />"; print_r($return); die("from listMembers-memberNumber");
        
        return $return;
    }

    function getAllPayingEmps() {

        $url=$this->baseurl."/groups/employers/";
        $vals="?take=3000";
        $httpheader=array('Accept: application/json;schema=employer');
        $output=$this->curl_call($this->username, $this->password, $url.$vals, $httpheader, $this->useragent, false);
        foreach($output->employers->employer as $obj) {
            if(empty($obj->parentGroupId)) {
                $return[$obj->group->name]=$obj->group->name;
            }
        }
        //echo "<pre style='text-align: left'>"; print_r($return); die("from getPayingEmps");
        return $return; 
        /*   $query = $this->odbcQuery("SELECT employer, emp_descrip
                                     FROM employers
                                     WHERE can_be_paying_emp='Y'
                                     AND emp_type='C'
                                     ORDER BY employer", array());
            $aligns=array();
            while($row=$this->dbFetchArray($query)) {
                $aligns[$row['employer']]=$row['emp_descrip'];
            }
            return $aligns; */
    }    
    
    function getPayingEmps($query="") {
        $url=$this->baseurl."/groups/employers/";
        $vals="?query=$query&take=3000";
        $httpheader=array('Accept: application/json;schema=employer');
        $output=$this->curl_call($this->username, $this->password, $url.$vals, $httpheader, $this->useragent, false);
        foreach($output->employers->employer as $obj) {
            if(empty($obj->parentGroupId)) {
                $return[]=array("employer"=>$obj->group->name, "emp_descrip"=>$obj->group->name);
            }
        }
        //echo "<pre style='text-align: left'>"; print_r($return); die("from getPayingEmps");
        return $return;        
    }

    function getWorkplaces($array=array()) {
      $inlist = count($array)>0 ? "AND workplaces.workplace IN ('".implode("', '", $array)."')" : null;
      $get_details = $this->odbcQuery("SELECT total=count(member), workplaces.workplace, work_addr_1, work_addr_2, work_addr_3, work_addr_4
                                                                       FROM members, workplaces, employers
                                                                       WHERE members.workplace=workplaces.workplace
                                                                       AND members.paying_emp=employers.employer
                                                                       AND emp_type='C'
                                                                         $inlist
                                                                       GROUP BY workplaces.workplace");
      $output=array();
      while($row=$this->dbFetchArray($get_details)) {
          $output[]=array('workplace'=>$row['workplace'], 'name'=>$row['work_addr_1']." ".$row['work_addr_2'], 'count'=>$row['total']);
        }
        return $output;
    }

    function getActualEmps($query="") {
        $url=$this->baseurl."/groups/employers/";
        $vals="?query=$query&take=3000";
        $httpheader=array('Accept: application/json;schema=employer');
        $output=$this->curl_call($this->username, $this->password, $url.$vals, $httpheader, $this->useragent, false);
        foreach($output->employers->employer as $obj) {
            if(!empty($obj->parentGroupId)) {
                $return[]=array("employer"=>$obj->group->name, "emp_descrip"=>$obj->group->name);
            }
        }
        //echo "<pre style='text-align: left'>"; print_r($return); die("from getActualEmps");
        return $return;
    }
    
    function getReps($workplace="%", $exclude=array()) {
        //TODO: Complete the getReps function    
            //returns a number being the total number of reps for the given workplace
            $repcount=0; 
                        
            return $repcount;
    }

    function getContacts($workplace, $excludes=array()) {
        //returns full workplace_reps table, plus work_phone, fax, email, surname, pref_name, rep_type_descrip  
        
        $contacts=array();
        return $contacts;
    }
    
    function getFileDetails($member) {
        /*global $conf_array;
        $casetracker_prefs = $this->GetGlobalPrefs;
        $lang = $casetracker_prefs['lang_code'];
        $get_details = $this->odbcQuery("SELECT *
                                           FROM ".$conf_array['must']['mustfilestable']."
                                           WHERE file_number = '$member'");
        $get_details = $this->odbcFetchArray($get_details);
        if (empty($get_details)) {
            $get_details = array();
        } */
        $get_details=array();
        return $get_details;

    }
    
    /* Name conversions for this database */
    function member_search_query_converter($query) {
        $searchfields=array(); //No need to convert anything, this is the original

        $output=array();
        
        foreach($query as $key=>$value) {
            if($searchfields[$key]) {
                $output[$searchfields[$key]]=$value;
            } else {
                $output[$key]=$value;
            }
        }
        
        return ($output);
    }
    
    function getMustTranslations() {
        return array("member"=>"findPeopleByQuery", 
                            "surname"=>"findPeopleByQuery", 
                            "given_names"=>"findPeopleByQuery", 
                            "paying_emp"=>"findPeopleByTopEmployer", 
                            "actual_emp"=>"findPeopleByBottomEmployer", 
                            "workplace"=>"findPeopleByWorksite", 
                            "classification"=>"findPeopleByOccupation",
                            "class"=>"findPeopleByOccupation", 
                            "email"=>"findPeopleByQuery");        
    }

    function paying_emp_search($text) {
        $searchterm="%".strtoupper($text)."%";
        $result = $this->odbcQuery("SELECT code=employer, value=emp_descrip FROM employers
                                  WHERE (employer LIKE ?
                                  OR emp_descrip LIKE ?)
                                  AND can_be_paying_emp='Y'
                                  ORDER BY emp_descrip",
                                  array($searchterm, $searchterm));
        while($row=$this->odbcFetchArray($result)) {
            $results[]=$row;
        }
        return $results;        
    }
    function actual_emp_search($text) {
        $searchterm="%".strtoupper($text)."%";
        $result = $this->odbcQuery("SELECT code=employer, value=emp_descrip FROM employers
                                  WHERE (employer LIKE ?
                                  OR emp_descrip LIKE ?)
                                  AND can_be_paying_emp='N'
                                  ORDER BY emp_descrip",
                                  array($searchterm, $searchterm));
        while($row=$this->odbcFetchArray($result)) {
            $results[]=$row;
        }
        return $results;        
    }
    function workplace_search($text) {
        $searchterm="%".strtoupper($text)."%";
        $result = $this->odbcQuery("SELECT code=workplace, value=work_addr_1+'<br />'+work_addr_2+'<br />'+work_addr_3+'<br />'+work_addr_4
                                  FROM workplaces
                                  WHERE (workplace LIKE ?
                                  OR work_addr_1+work_addr_2+work_addr_3+work_addr_4 LIKE ?)
                                  ORDER BY work_addr_1",
                                  array($searchterm, $searchterm));
        while($row=$this->odbcFetchArray($result)) {
            $results[]=$row;
        }
        return $results;
    }
    function classification_search($text) {
        $searchterm="%".strtoupper($text)."%";
        $result = $this->odbcQuery("SELECT code=class, value=class_descrip
                                  FROM classes
                                  WHERE (class LIKE ?
                                  OR class_descrip LIKE ?)
                                  ORDER BY class_descrip",
                                  array($searchterm, $searchterm));
        while($row=$this->odbcFetchArray($result)) {
            $results[]=$row;
        }
        return $results;
    }
    
    /**
    * Function getUFMembers returns an array of members who are unfinancial
    * 
    * @param mixed $members A list of members (as an array) to check
    */
    function getUFMembers($members) {
    
        //First, split the array into a series of arrays less than 150 members in size
        
        
        //TODO: Iterate through each member and see if they are financial (using OMS)
        //Rest of this is kind of irrelevent. Return an array of member numbers found to be not current
        // or unfinancial
        
        $ufmembers=array();        
        
        return $ufmembers; 
    }
}  
?>