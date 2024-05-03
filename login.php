<html>
  <head>
    <link rel="stylesheet" href="./login.css" />
  </head>
  <body>
    <?php
      $dbHost = "localhost";
      $dbUser = "root";
      $dbPass = "";
      $dbName = "poker";
      $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

      $isValid=false;
      $errorMessage="";
      $stmt;
      $result;

      session_start();
    ?>
    <div class="container">
      <div class="background row">
        <form method="post" class="form-container column">
          <div class="form-title center">Login</div>
          <div class="column">
            <?php 
              if(!isset($_POST['email'])) $errorMessage="";
              else if($_POST["email"]=="") $errorMessage="*Email cannot be empty";
              else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errorMessage="*Invalid email";
              else {
                $stmt=$conn->prepare("SELECT * FROM users WHERE Email = ?");
                $stmt->bind_param("s",$_POST['email']);
                $stmt->execute();
                if(mysqli_num_rows($result=$stmt->get_result())==0) $errorMessage="*Email not registrated";
                else 
                {
                  $isValid=true; 
                }
              }
            ?>
            <input
              class="custom-input ease-in-out center <?= $errorMessage==""?"":"error" ?> <?= isset($_POST["email"])?"active":"" ?>"
              type="email"
              name="email"
              id=""
              value="<?php if(isset($_POST["email"])) echo $_POST["email"] ?>"
              required
            />
            <div class="custom-input-label ease-in-out">Email:</div>
            <div class="input-error"><?= $errorMessage ?></div>
          </div>
          <div class="column">
            <?php
              $errorMessage="";
              if(!isset($_POST["password"])) $errorMessage="";
              else if($_POST["password"]=="") $errorMessage="*Password cannot be empty";
              else if($isValid){
                if((($row = $result->fetch_assoc())['Password'] != md5($_POST['password']))) $errorMessage="*Wrong email or password";
                else{
                  $_SESSION["userId"]=$row["Id"];
                  $_SESSION["cash"]=$row["Cash"];
                  header("Location: ./Poker.php");
                  exit;
                }
              } 
            ?>
            <input
              class="custom-input ease-in-out center <?= $errorMessage==""?"":"error" ?>"
              type="password"
              name="password"
              id=""
              value=""
              required
            />
            <div class="custom-input-label ease-in-out">Password:</div>
            <div class="input-error"><?= $errorMessage ?></div>
          </div>
          <div class="submit-container center">
            <button class="submit-button center button ease-in-out">
              LOGIN
            </button>
          </div>
          <div class="signin-link-container center">
            <a class="signin-link" href="./signin.php">new user?</a>
          </div>
        </form>
      </div>
    </div>
    <script>
      let inputs = document.getElementsByClassName("custom-input");
      //console.log(inputs);
      for (let i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("change", function () {
          console.log(this);
          if (this.value == "") this.classList.remove("active");
          else this.classList.add("active");
        });
      }
    </script>
  </body>
</html>
