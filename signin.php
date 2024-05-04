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

      $isEmailValid=false;
      $isPasswordValid=false;
      $errorMessage="";
      $stmt;
      $result;
    ?>
    <div class="container">
      <div class="background row">
        <form method="post" class="form-container column">
          <div class="form-title center">Sign in</div>
          <div class="column">
            <?php 
              $errorMessage="";
              if(!isset($_POST['email'])) $errorMessage="";
              else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errorMessage="*Invalid email";
              else {
                $stmt=$conn->prepare("SELECT * FROM users WHERE Email = ?");
                $stmt->bind_param("s",$_POST['email']);
                $stmt->execute();
                if(mysqli_num_rows($result=$stmt->get_result())==1) $errorMessage="*Email already registrated";
                else $isEmailValid=true;
              }
            ?>
            <input
              class="custom-input ease-in-out center  <?= $errorMessage==""?"":"error" ?> <?php if(isset($_POST["email"])&& $_POST["email"]!="") echo "active"; else echo "" ?>"
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
              else if(strlen($_POST["password"])<8) $errorMessage="*Password too short";
              else $isPasswordValid=true;
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
          <div class="column">
            <?php
              $errorMessage="";
              if(!isset($_POST["confirm-password"])) $errorMessage="";
              else if($_POST["confirm-password"]=="") $errorMessage="*Password cannot be empty";
              else if(strlen($_POST["confirm-password"])<8) $errorMessage="*Password too short";
              else if($_POST["password"]!=$_POST["confirm-password"]) $errorMessage="*Passwords don't match";
              else {
                if($isEmailValid && $isPasswordValid) {
                  $stmt=$conn->prepare("INSERT INTO users (Email, Password) VALUES (?,?)");
                  $stmt->bind_param("sss",$_POST['email'],md5($_POST["password"]));
                  $stmt->execute();
                  $row=$conn->query("SELECT * FROM users WHERE email='".$_POST["email"]."'")->fetch_assoc();
                  $_SESSION["userId"]=$row["Id"];
                  $_SESSION["cash"]=$row["Cash"];
                  header("Location: ./poker.php");
                  exit;
                }
              }
            ?>
            <input
              class="custom-input ease-in-out center <?= $errorMessage==""?"":"error" ?>"
              type="password"
              name="confirm-password"
              id=""
              value=""
              required
            />
            <div class="custom-input-label ease-in-out">Confirm password:</div>
            <div class="input-error"><?= $errorMessage ?></div>
          </div>
          <div class="submit-container center">
            <button class="submit-button center button ease-in-out">
              SIGN IN
            </button>
          </div>
          <div class="signin-link-container center">
            <a class="signin-link" href="./login.php">already have an account?</a>
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
