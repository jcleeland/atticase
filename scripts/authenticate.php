<?php
  if(isset($_POST['login']) && $_POST['login']=="true") {
      //Log user in
      
      $_SESSION['authenticated']=1;
      $_SESSION['administrator']=1;
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
