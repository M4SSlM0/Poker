<html>
  <head>
    <link rel="stylesheet" href="./login.css" />
  </head>
  <body>
    <div class="container">
      <div class="background row">
        <form method="post" class="form-container column">
          <div class="form-title center">Sign in</div>
          <div class="column">
            <input
              class="custom-input ease-in-out center"
              type="text"
              name="username"
              id=""
              value=""
              required
            />
            <div class="custom-input-label ease-in-out">Username:</div>
            <div class="input-error"></div>
          </div>
          <div class="column">
            <input
              class="custom-input ease-in-out center"
              type="email"
              name="email"
              id=""
              value=""
              required
            />
            <div class="custom-input-label ease-in-out">Email:</div>
            <div class="input-error"></div>
          </div>
          <div class="column">
            <input
              class="custom-input ease-in-out center"
              type="password"
              name="password"
              id=""
              value=""
              required
            />
            <div class="custom-input-label ease-in-out">Password:</div>
            <div class="input-error"></div>
          </div>
          <div class="column">
            <input
              class="custom-input ease-in-out center"
              type="password"
              name="confirm-password"
              id=""
              value=""
              required
            />
            <div class="custom-input-label ease-in-out">Confirm password:</div>
            <div class="input-error"></div>
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
