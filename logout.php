<?php
	session_start();
    
	session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Logout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">

    <style>
        * {
            color: white;
        }
        #nav-home {
            color: white;
        }
        
        #logout-title {
            font-size: 250%;

            padding-left: 5%;
            padding-top: 2%;
        }
        #logout-message {
            font-size: 22px;

            padding-left: 5%;
            padding-bottom: 20px;
        }
        #logout-home, #logout-login {
            font-size: 15px;

            text-align: left;
            padding-left: 5%;
            padding-bottom: 5px;
        }

        @media(min-width: 768px) {
            #logout-title {
                font-size: 300%;
            }
            #logout-message {
                font-size: 25px;
            }
            #logout-home, #logout-login {
                font-size: 17px;
            }
        }
        @media(min-width: 992px) {
            #logout-title {
                font-size: 350%;
            }
            #logout-message {
                font-size: 28px;
                
                padding-bottom: 25px;
            }
            #logout-home, #logout-login {
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

    <div class="container-fluid">
		<div class="row">
			<div id="logout-title" class="col-12 mt-4 mb-4">Logout</div>
			<div id="logout-message" class="col-12">You are now logged out.</div>
            <div id="logout-home" class="col-12 mt-3">Click <a class="text-success" href="home.php">here</a> to go to the home page.</div>
			<div id="logout-login" class="col-12 mt-3">Click <a class="text-primary" href="login.php">here</a> to log-in again.</div>
		</div>
	</div>
</body>
</html>