<?php
    require_once 'config/database.php';
    include("head.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Registration Page (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Register a new membership</p>

      <form action="users/register_action.php" method="post">
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="u_fullname" name="u_fullname" placeholder="Full Name" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-pen"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="u_email" name="u_email" placeholder="E-mail" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-pen"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="u_username" name="u_username" placeholder="Username" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="u_phone_number" name="u_phone_number" placeholder="phone number" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-phone"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
    <textarea class="form-control" id="u_address" name="u_address" placeholder="Address" rows="2" required></textarea>
    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-home"></span>
        </div>
    </div>
</div>


    <div class="input-group mb-3">
    <input type="password" class="form-control" id="u_password" name="u_password" placeholder="Password" required>
    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-lock"></span>
        </div>
    </div>
</div>
<span id="password-validation-msg"></span>

<script>
    var passwordInput = document.getElementById("u_password");
    var confirmPasswordInput = document.getElementById("u_confirm_password");
    var registerButton = document.getElementById("registerButton");
    var validationMsg = document.getElementById("password-validation-msg");

    passwordInput.addEventListener("input", validatePassword);
    confirmPasswordInput.addEventListener("input", validatePassword);

    function validatePassword() {
        var password = passwordInput.value;
        var confirmPassword = confirmPasswordInput.value;
        var isValid = true;

        // Define your password rules here
        var passwordRules = [
            { rule: /[A-Z]/, message: "At least one uppercase letter", isValid: false },
            { rule: /[a-z]/, message: "At least one lowercase letter", isValid: false },
            { rule: /[0-9]/, message: "At least one digit", isValid: false },
            { rule: /[^A-Za-z0-9]/, message: "At least one special character", isValid: false },
            { rule: /.{8,}/, message: "Minimum length of 8 characters", isValid: false }
        ];

        var validationMessages = [];

        for (var i = 0; i < passwordRules.length; i++) {
            var rule = passwordRules[i].rule;
            var message = passwordRules[i].message;
            var isValidRule = rule.test(password);

            passwordRules[i].isValid = isValidRule;

            if (!isValidRule) {
                isValid = false;
            }

            validationMessages.push({ message: message, isValid: isValidRule });
        }

        if (isValid && password === confirmPassword) {
            validationMessages.push({ message: "Password rules met", isValid: true });
            registerButton.disabled = false;
        } else {
            registerButton.disabled = true;
        }

        var messagesHtml = validationMessages.map(function (validation) {
            var color = validation.isValid ? "green" : "red";
            var icon = validation.isValid ? '<i class="fas fa-check-circle"></i> ' : '';
            return '<span style="color: ' + color + '">' + icon + validation.message + '</span>';
        }).join("<br>");

        validationMsg.innerHTML = messagesHtml;
    }
</script>
    
    <!-- <div class="mb-3">
        <label for="u_level" class="form-label">Level</label>
        <select id="u_level" name="u_level" class="form-select">
            <option value="user">user</option>
            <option value="administrator">administrator</option>
        </select>
    </div> -->
    <div class="input-group mb-3">
        <input type="password" class="form-control" id="u_confirm_password" name="u_confirm_password" placeholder="Confirm Password" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" id="agreeTerms" name="u_level" value="user" required>
                <label for="agreeTerms">
                    I agree to the <a href="#">terms</a>
                </label>
            </div>
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" id="registerButton" disabled>Register</button>
        </div>
    </div>
</form>

<script>
    var passwordInput = document.getElementById("u_password");
    var confirmPasswordInput = document.getElementById("u_confirm_password");
    var registerButton = document.getElementById("registerButton");

    passwordInput.addEventListener("input", validatePassword);
    confirmPasswordInput.addEventListener("input", validatePassword);

    function validatePassword() {
        var password = passwordInput.value;
        var confirmPassword = confirmPasswordInput.value;
        var isValid = true;

        // Define your password rules here
        var passwordRules = [
            { rule: /[A-Z]/, message: "At least one uppercase letter" },
            { rule: /[a-z]/, message: "At least one lowercase letter" },
            { rule: /[0-9]/, message: "At least one digit" },
            { rule: /[^A-Za-z0-9]/, message: "At least one special character" },
            { rule: /.{8,}/, message: "Minimum length of 8 characters" }
        ];

        for (var i = 0; i < passwordRules.length; i++) {
            var rule = passwordRules[i].rule;
            var message = passwordRules[i].message;

            if (!rule.test(password)) {
                isValid = false;
                break;
            }
        }

        if (isValid && password === confirmPassword) {
            registerButton.disabled = false;
        } else {
            registerButton.disabled = true;
        }
    }
</script>


      

      <a href="login.php" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>
