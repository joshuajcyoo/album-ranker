<?php

require 'config.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
	echo $mysqli->connect_error;
	exit();
}

$mysqli->set_charset('utf8');

// CRUD --> "R" / "Retrieve"
$sql_genres = "SELECT * FROM genres;";
$results_genres = $mysqli->query($sql_genres);
if ($results_genres == false) {
    echo $mysqli->error;
    exit();
}

$mysqli->close();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    
    <style> 
        #menu-home {
            color: grey;
        }
        #menu-search {
            color: white;
        }
        
        #welcome {
            font-size: 200%;
            color: white;

            text-align: center;
            padding-top: 40px;
        }
        #title {
            font-size: 450%;
            color: white;

            text-align: center;
            padding-left: 5%;
            padding-right: 5%;
            padding-bottom: 6%;

            margin-bottom: 0px;
        }

        #description1, #description2 {
            color: white;
            background: #324A5F;

            font-size: 18px;
            text-align: center;

            padding-left: 8%;
            padding-right: 8%;
        }
        #description1 {
            padding-bottom: 4%;
        }
        #description2 {
            padding-bottom: 2%;
        }

        .container {
            text-align: center;
            max-width: 1600px;
        }
        form {
            margin-bottom: 0;
            padding-bottom: 30px;
        }
        label {
            color: white;
            text-decoration: underline;
        }
        #search-box, #genre-box {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        option {
            color: white;
        }
        #search-button {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        #search-button2 {
            margin-top: 20px;
        }

        @media(min-width: 768px) {
            #title {
                font-size: 450%;
                padding-bottom: 4%;
            }
            #description1, #description2 {
                padding-left: 14%;
                padding-right: 14%;
            }
        }
        @media(min-width: 992px) {
            #title {
                font-size: 500%;
                padding-bottom: 3%;
            }
            #description1, #description2 {
                font-size: 19px;
                
                padding-left: 18%;
                padding-right: 18%;
            }
            #description1 {
                padding-bottom: 3%;
            }
            #description2 {
                padding-bottom: 1%;

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
            <ul class="navbar-nav p-2">
                <li class="nav-item active">
                    <a id="menu-home" class="nav-link" href="#">Home</a>
                </li>

                <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]): ?>
                    <li class="nav-item active">
                        <div id="hello" class="nav-link">Hi <?php echo $_SESSION['username'];?>!</div>
                    </li>
                <?php endif; ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menu-account" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        
                        <?php if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]): ?>
                            Login
                        <?php else: ?>
                            Logout
                        <?php endif; ?>
                
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        
                        <?php if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]): ?>
				            <a id="menu-login" class="dropdown-item" href="login.php">Login</a>
                            <a class="dropdown-item" href="register_form.php">Register</a>
		                <?php else: ?>
                            <a class="dropdown-item" href="logout.php">Logout</a>
			            <?php endif; ?>
                         
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div id="heading"> 
        <p id="welcome">Welcome to...</p>
        <h1 id="title">Song Hierarchy</h1>
    </div>

    <div id="description1">
        Welcome to Song Hierarchy! This is a place where everyone can rank the songs on their favorite albums, and see what everyone thinks are the best and worst songs on some of the greatest records of all time, old and new.
    </div>
    <div id="description2">
        Our album rankings are always live-updated, so you can check-in at any time to see how the popularity of certain songs shift over time. Type in the name of an album below to get started!
    </div>

    <div id="search-form" class="container">
		<form action="search_results.php?" method="GET">
            <div class="form-group">
                <label for="username-id" class="p-3 col-form-label text-center">Enter the name of an album</label>
                <div id="search-box" class="col-sm-6 col-md-5 col-lg-4">
                    <input type="text" class="mr-auto form-control text-center" id="album-search" name="album_search">
                    <small id="album-error" class="invalid-feedback">Please enter an album.</small>
                </div>
            </div>

            <div class="form-group row" id="enter-search">
				<div class="col-sm-12 mt-2">
					<button type="submit" class="btn btn-primary" id="search-button">Search</button>
				</div>
			</div>

            <div class="form-group">
                <label for="genre-id" class="p-3 col-form-label text-center">OR, search for an album by genre.</label>
                <div id="genre-box" class="col-sm-4 col-md-3 col-lg-2">
                    <select name="genre" id="genre-id" class="form-control" onchange="submit()">
                        <option value="" selected>-------------------------------------</option>

                        <?php while ($row = $results_genres->fetch_assoc()): ?>
                            <option value="<?php echo $row['genre_id']; ?>">
                                <?php echo $row['genre']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- No user input validation by JavaScript for the album search, because a blank search allows users to view all of the albums that are available to view and rank. -->

    <footer>
        Created by Joshua Yoo -- <a href="mailto:joshuajcyoo@gmail.com">joshuajcyoo@gmail.com</a>
    </footer>
</body>
</html>