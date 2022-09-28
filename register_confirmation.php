<?php

require 'config.php';

if (!isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['username']) || empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password'])) {
	$error = "Please fill out all required fields.";
}
else {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

    $statement_registered = $mysqli->prepare("SELECT * FROM users WHERE username = ? OR email = ?");

    $statement_registered->bind_param("ss", $_POST["username"], $_POST["email"]);

    $executed_registered = $statement_registered->execute();
	
    if ($executed_registered) {
		echo $mysqli->error;
	}

    $statement_registered->store_result();
	$numrows = $statement_registered->num_rows;

    $statement_registered->close();

    if ($numrows > 0) {
		$error = "This username or email address is already connected to an existing account.";
	}
    else {
		$password = hash("sha256", $_POST['password']);
		
		// CRUD --> "C" / "Create"
        $statement = $mysqli->prepare("INSERT INTO users(username, email, password) VALUES(?, ?, ?)");
		
		$statement->bind_param("sss", $_POST["username"], $_POST["email"], $password);
		
		$executed = $statement->execute();
		if ($executed) {
			echo $mysqli->error;
		}
		$statement->close();
    }
}

$mysqli->close();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Confirmation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">

    <style>
        * {
            color: white;
        }
        #confirmation-title {
            font-size: 250%;

            padding-left: 5%;
            padding-top: 2%;
        }
        #nav-home {
            color: white;
        }

        .container {
            max-width: 1600px;
        }
        #registration-message {
            font-size: 22px;

            padding-left: 5%;
            padding-bottom: 20px;
        }
        #login-account-message, #register-account-message {
            font-size: 15px;

            text-align: left;
            padding-left: 5%;
            padding-bottom: 5px;
        }

        @media(min-width: 768px) {
            #confirmation-title {
                font-size: 300%;
            }
            #registration-message {
                font-size: 25px;
            }
            #login-account-message, #register-account-message {
                font-size: 17px;
            }
        }
        @media(min-width: 992px) {
            #confirmation-title {
                font-size: 300%;
            }
            #registration-message {
                font-size: 28px;
                
                padding-bottom: 25px;
            }
            #login-account-message, #register-account-message {
                font-size: 20px;
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

    <div class="container">
        <div class="row">
			<div id="confirmation-title" class="col-12 mt-4 mb-4">Register Confirmation</div>
        </div>
    
        <div class="row mt-4">
            <div id="registration-message" class="col-12">

                <?php if (isset($error) && !empty($error)): ?>
					<div class="text-danger">
                        <?php echo $error; ?>
                    </div>
				<?php else: ?>
					<div class="text-success">Your account was successfully registered.</div>
				<?php endif; ?>

            </div>
        </div>
        
        <div class="row">
            <div id="register-account-message" class="col-12">
                Click <a class="text-success" href="register_form.php">here</a> to go back to the register page.
            </div>
            <div id="login-account-message" class="col-12">
                If you already have an account, click <a class="text-primary" href="login.php">here</a> to sign in.
            </div>
        </div>
    </div>
</body>
</html>