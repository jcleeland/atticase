<?php
  if(isset($_POST['login']) && $_POST['login']=="true") {
      //Log user in
      if(isset($_POST['username']) && isset($_POST['password'])) {
          //See if user exists
          $query="SELECT * FROM ".$oct->dbprefix."users WHERE user_name = :username";
          $out=$oct->fetch($query, array("username"=>$_POST['username']));
          //$stmt=$oct->db->prepare($query) or die("The prepared statement does not work");
          //$stmt->execute(['username'=>$_POST['username']]);
          //$out=$stmt->fetch();
          if(crypt($_POST['password'], '4t6dcHiefIkeYcn48B') == $out['user_pass']) {
              $_SESSION['authenticated']=1;
              $_SESSION['administrator']=1;
              $_SESSION['user_name']=$out['user_name'];
              $_SESSION['is_admin']=1;
              $_SESSION['user_id']=$out['user_id'];
              $_SESSION['real_name']=$out['real_name'];
          } else {
              echo "Login failed";
          }
      }
      
      
      
  }
  
  if(isset($_GET['logout']) && $_GET['logout']=="true") {
      foreach($_SESSION as $key=>$val) {
          unset($_SESSION[$key]);
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
