<?php
    
    if(isset($_POST['login']) && $_POST['login']=="true") {
        
        
        if($configsettings['ldap']['useldap']['value'] == 1 && $_POST['username'] != $configsettings['ldap']['ldaplocaladmin']['value']) {
            //LDAP
            // Set up all options.
            $options = [
                'account_suffix' => $configsettings['ldap']['ldapacsuffix'],
                'base_dn' => $configsettings['ldap']['ldapbasedn'],
                'domain_controllers' => [$configsettings['ldap']['ldaphost']],
                'admin_username' => $configsettings['ldap']['ldapadminuser'],
                'admin_password' => $configsettings['ldap']['ldapadminpassword'],
                'real_primarygroup' => '',
                'use_ssl' => false,
                'use_tls' => false,
                'recursive_groups' => true,
                'ad_port' => $configsettings['ldap']['ldapport'],
                'sso' => '',
            ];            
            
            //Manage prefixes and suffixes
            $user = $_POST['username'];
            $localuser = $_POST['username'];
            if(!empty($configsettings['ldap']['ldapacprefix']['value'])) {
                //check to make sure that the prefix hasn't already been included in the posted usernam
                if(substr($_POST['username'], 0, strlen($configsettings['ldap']['ldapacprefix']['value'])) !=  $configsettings['ldap']['ldapacprefix']['value']) {
                    $user = $configsettings['ldap']['ldapacprefix']['value']."\\".$_POST['username'];
                } else {
                    $localuser=substr($_POST['username'], strlen($configsettings['ldap']['ldapacprefix']['value']));
                }
            } elseif (!empty($configsettings['ldap']['ldapacsuffix']['value'])) {
                if(substr($_POST['username'], strlen($configsettings['ldap']['ldapacsuffix']['value']) * -1) != $configsettings['ldap']['ldapacsuffix']['value']) {
                    $user = $_POST['username'].$configsettings['ldap']['ldapacsuffix']['value'];
                } else {
                    $localuser = substr($_POST['username'], 0, strlen($_POST['username'])-strlen($configsettings['ldap']['ldapacsuffix']['value']));
                }
            } 
            $ad=new \Adldap\Adldap();
            
            $adoptions=[
                'hosts'     =>  [$configsettings['ldap']['ldaphost']['value']],
                'base_dn'   =>  $configsettings['ldap']['ldapbasedn']['value'],
            ];
            
            if($configsettings['ldap']['ldapadminuser']['value'] != "null" && $configsettings['ldap']['ldapadminpassword']['value'] !="null" ) {
                $adoptions['username']  = $configsettings['ldap']['ldapadminuser']['value'];
                $adoptions['password']  = $configsettings['ldap']['ldapadminpassword']['value'];
                
            }

            
            $ad->addProvider($adoptions);
            
            try {
                $provider = $ad->connect();
                if($provider->auth()->attempt($user, $_POST['password'])) {
                    $query="SELECT * FROM ".$oct->dbprefix."users u INNER JOIN ".$oct->dbprefix."groups g ON u.group_in=g.group_id WHERE user_name = :username AND account_enabled=1";
                    $out=$oct->fetch($query, array("username"=>$localuser));
                    if(isset($out['real_name'])) {
                        $_SESSION['authenticated']=1;
                        $_SESSION['administrator']=$out['is_admin'];
                        $_SESSION['user_name']=$out['user_name'];
                        $_SESSION['is_admin']=$out['is_admin'];
                        $_SESSION['user_id']=$out['user_id'];
                        $_SESSION['real_name']=$out['real_name'];
                        $_SESSION['group_in']=$out['group_in'];
                        $_SESSION['email_address']=$out['email_address'];                        
                    } else {
                        echo "<span class='danger'>Your account has been authenticated by your local system, however you do not have permission to access AttiCase<br />\nContact your system administrator and ask them to enable your access.</span>";
                        if($oct->config['ldap']['ldapnewusergroup']['value'] > 0) {
                            //INSERT USER INTO DATABASE
                            $query="INSERT INTO ".$oct->dbprefix."users ('user_name', 'real_name', group_in', 'account_enabled') VALUES ('$localuser', '$localuser', '".$oct->config['ldap']['ldapnewusergroup']['value']."', '0')";
                            $result=$oct->execute($query, array());
                        }
                    }
                } else {
                    echo "<span class='danger'>Username & password combination failed LDAP authentication</span>";
                }
                //$oct->showArray($results);
            } catch (\Adldap\Auth\BindException $e) {
                echo "<span class='danger'>Error message from LDAP ($user)</span>";
                $oct->showArray($e);
                die();
            }
            
        } else {
            //USING LOCAL USER DATABASE
            //Log user in
            if(isset($_POST['username']) && isset($_POST['password'])) {
                //See if user exists
                $query="SELECT * FROM ".$oct->dbprefix."users u INNER JOIN ".$oct->dbprefix."groups g ON u.group_in=g.group_id WHERE user_name = :username AND account_enabled=1";
                $out=$oct->fetch($query, array("username"=>$_POST['username']));
                //$stmt=$oct->db->prepare($query) or die("The prepared statement does not work");
                //$stmt->execute(['username'=>$_POST['username']]);
                //$out=$stmt->fetch();
                //echo crypt($_POST['password'], '4t6dcHiefIkeYcn48B')." || ".$out['user_pass'];
                if(isset($out['user_pass']) && crypt($_POST['password'], '4t6dcHiefIkeYcn48B') == $out['user_pass']) {
                    //$oct->showArray($out);
                    $_SESSION['authenticated']=1;
                    $_SESSION['administrator']=$out['is_admin'];
                    $_SESSION['user_name']=$out['user_name'];
                    $_SESSION['is_admin']=$out['is_admin'];
                    $_SESSION['user_id']=$out['user_id'];
                    $_SESSION['real_name']=$out['real_name'];
                    $_SESSION['group_in']=$out['group_in'];
                    $_SESSION['email_address']=$out['email_address'];
                } else {
                    echo "<div class='text-center w-100 text-danger'><br />Login failed<br /></div>";
                }
            }          
        }
    }

    if(isset($_GET['logout']) && $_GET['logout']=="true") {
        foreach($_SESSION as $key=>$val) {
          unset($_SESSION[$key]);
        }
        //echo "<pre>"; print_r($_SESSION); echo "</pre>";
        //$cookiepath=dirname($_SERVER['PHP_SELF']);
        $cookiepath="/atticase";
        if(isset($_GET['clearcookies']) && $_GET['clearcookies']=="true") {

            if (isset($_SERVER['HTTP_COOKIE'])) {
                $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                //echo "<pre>"; print_r($cookies); echo "</pre>";
                foreach($cookies as $cookie) {
                    $parts = explode('=', $cookie);
                    $name = trim($parts[0]);
                    if($name == $oct->cookiePrefix."System" || $name == $oct->cookiePrefix."Status" || $name == $oct->cookiePrefix."Status1") {
                        //echo "Deleting [$name]<br />";
                        setcookie($name, "", time()-7200);
                        setcookie($name, "", time()-7200, "/");
                        setcookie($name, "", time()-7200, $cookiepath);
                        setcookie($name);
                    }
                }
            }
            
            if(isset($_COOKIE)) {
                foreach($_COOKIE as $name=>$cookie) {
                    //echo $name."<br />";
                    if(isset($_COOKIE[$name]) && ($name == $oct->cookiePrefix."System" || $name == $oct->cookiePrefix."Status" || $name == $oct->cookiePrefix."Status1")) {
                        echo "Deleting [$name] (".(time()-7200)." - ".time().")<br />";
                        unset($_COOKIE[$name]);
                        setcookie($name, "", time()-7200);
                        setcookie($name, "", time()-7200, "/");
                        setcookie($name, "", time()-7200, $cookiepath);
                        setcookie($name);
                    }
                }
            }
          /* $setDomain = ($_SERVER['HTTP_HOST']) != "localhost" ? $_SERVER['HTTP_HOST'] : false;          
          foreach($_COOKIE as $key=>$val) {
              setcookie($key, null, time()-3600, "/; samesite=Strict", $setDomain, false, false);
          } */
           //echo "<pre>"; print_r($_COOKIE); print_r($_SESSION);  print_r($_SERVER['HTTP_COOKIE']); echo "</pre>"; die();
        }
      //Reload the page
      //echo "<pre>PHP Cookie Setting"; print_r($_COOKIE); echo "</pre>"; 
      ?>
      <center>Logging Out</center>
      <script type='text/javascript'>
        window.location.href="index.php";
      </script>
      <?php
    }
?>
