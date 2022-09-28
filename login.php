<?php

require 'config.php';

if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
	if (isset($_POST['username']) && isset($_POST['password'])) {
		if (empty($_POST['username']) || empty($_POST['password'])) {
			$error = "Please enter a username and password.";
		}
        else {
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

			if($mysqli->connect_errno) {
				echo $mysqli->connect_error;
				exit();
			}

			$inputtedPassword = hash("sha256", $_POST["password"]);

            $sql = "SELECT * FROM users
						WHERE username = '" . $_POST['username'] . "' AND password = '" . $inputtedPassword . "';";
			
			$results = $mysqli->query($sql);

			if(!$results) {
				echo $mysqli->error;
				exit();
			}

            if($results->num_rows == 1) {
				$_SESSION["logged_in"] = true;
				$_SESSION["username"] = $_POST["username"];

				header("Location: home.php");
			}
			else {
				$error = "Invalid username or password.";
			}
        }
    }
}
else {
	header("Location: home.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">

    <style>
        * {
            color: white;
        }
        #login-title {
            font-size: 250%;

            padding-left: 5%;
            padding-top: 2%:
        }
        #nav-home {
            color: white;
        }
        
        .container {
            max-width: 1600px;

            padding-top: 30px;
        }
        #username-group {
            padding-bottom: 10px;
        }
        #username-label, #password-label {
            font-size: 20px;
        }
        #create-account-message {
            padding-top: 5px;
        }

        @media(min-width: 768px) {
            #login-title {
                font-size: 300%;
            }
        }
        @media(min-width: 992px) {
            #login-title {
                font-size: 350%;
            }
        }
    </style>
</head>
<body>
    <nav id="navbar" class="navbar navbar-expand-lg navbar-dark">
        <a id="logo" class="navbar-brand" href="home.php">Song Hierarchy</a>

        <button id="toggle-button" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span id="icon" class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a id="nav-home" class="nav-link" href="home.php">Home</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
		<div class="row">
			<div id="login-title" class="col-12 mt-4 mb-4">Login</div>
        </div>
    </div>

    <div class="container">
		<form action="login.php" method="POST">
            <div class="row mb-3">
				<div class="font-italic text-danger col-sm-9 ml-sm-auto">

					<?php
						if (isset($error) && !empty($error)) {
							echo $error;
						}
					?>

				</div>
			</div>
        
            <div id="username-group" class="form-group row">
                <label id="username-label" for="enter-username" class="col-sm-3 col-form-label text-sm-right">Username:</label>
                <div class="col-sm-8 col-md-7 col-lg-6">
                    <input type="text" class="form-control" id="enter-username" name="username">
                    <small id="username-error" class="invalid-feedback">Username is required.</small>
                </div>
            </div>

            <div class="form-group row">
                <label id="password-label" for="enter-password" class="col-sm-3 col-form-label text-sm-right">Password:</label>
                <div class="col-sm-8 col-md-7 col-lg-6">
                    <input type="password" class="form-control" id="enter-password" name="password">
                    <small id="password-error" class="invalid-feedback">Password is required.</small>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"></div>
                <div class="col-sm-9 mt-2">
                    <button id="login-button" type="submit" class="btn btn-primary">Login</button>
                </div>
            </div>
        </form>

        <div class="row">
            <div id="create-account-message" class="col-sm-9 ml-sm-auto">
                If you don't have an account yet, click <a href="register_form.php">here</a> to create a new account.
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').onsubmit = function() {
			if (document.querySelector('#enter-username').value.trim().length == 0 ) {
                document.querySelector('#enter-username').classList.add('is-invalid');
			}
            else {
				document.querySelector('#enter-username').classList.remove('is-invalid');
			}

            if (document.querySelector('#enter-password').value.trim().length == 0 ) {
                document.querySelector('#enter-password').classList.add('is-invalid');
			}
            else {
				document.querySelector('#enter-password').classList.remove('is-invalid');
			}

            return (!document.querySelectorAll('.is-invalid').length > 0);
        }
    </script>
</body>
</html>