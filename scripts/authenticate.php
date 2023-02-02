<?php
  if(isset($_POST['login']) && $_POST['login']=="true") {
      //Log user in
      if(isset($_POST['username']) && isset($_POST['password'])) {
          //See if user exists
          $query="SELECT * FROM ".$oct->dbprefix."users u INNER JOIN ".$oct->dbprefix."groups g ON u.group_in=g.group_id WHERE user_name = :username";
          $out=$oct->fetch($query, array("username"=>$_POST['username']));
          //$stmt=$oct->db->prepare($query) or die("The prepared statement does not work");
          //$stmt->execute(['username'=>$_POST['username']]);
          //$out=$stmt->fetch();
          //echo crypt($_POST['password'], '4t6dcHiefIkeYcn48B')." || ".$out['user_pass'];
          if(crypt($_POST['password'], '4t6dcHiefIkeYcn48B') == $out['user_pass']) {
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
              echo "Login failed";
          }
      }
      
      
      
  }
  
  if(isset($_GET['logout']) && $_GET['logout']=="true") {
      foreach($_SESSION as $key=>$val) {
          unset($_SESSION[$key]);
      }
      if(isset($_GET['clearcookies']) && $_GET['clearcookies']=="true") {

            if (isset($_SERVER['HTTP_COOKIE'])) {
                $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                foreach($cookies as $cookie) {
                    $parts = explode('=', $cookie);
                    $name = trim($parts[0]);
                    echo "Deleting $name<br />";
                    setcookie($name, '', time()-1000);
                    setcookie($name, '', time()-1000, '/');
                }
            }
          
          /* $setDomain = ($_SERVER['HTTP_HOST']) != "localhost" ? $_SERVER['HTTP_HOST'] : false;          
          foreach($_COOKIE as $key=>$val) {
              setcookie($key, null, time()-3600, "/; samesite=Strict", $setDomain, false, false);
          } */
          
      }
      //Reload the page
      ?>
      <center>Logging Out</center>
      <script type='text/javascript'>
        window.location.href="index.php";
      </script>
      <?php
  }
?>
