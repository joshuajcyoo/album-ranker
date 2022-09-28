<?php

require "config.php";

if (!isset($_GET['album_id']) || empty($_GET['album_id'])) {
    $error = "Invalid album ID.";
}
else {    
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) {
        echo $mysqli->connect_error;
        exit();
    }
    
    $mysqli->set_charset('utf8');

    // CRUD --> "R" / "Retrieve"
    $sql_info = "SELECT album_id, album, artist, release_date
                    FROM albums
                    WHERE album_id = " . $_GET['album_id'] . ";";

    $results_info = $mysqli->query($sql_info);
    if (!$results_info) {
        echo $mysqli->error;
        exit();
    }
    $row_info = $results_info->fetch_assoc();

    $sql_user = "SELECT user_id, username
                    FROM users
                    WHERE username = '" . $_SESSION['username'] . "';";
    
    $results_user = $mysqli->query($sql_user);
    if (!$results_user) {
        echo $mysqli->error;
        exit();
    }
    $row_user = $results_user->fetch_assoc();

    // CRUD --> "R" / "Retrieve"
    $sql_previous = "SELECT ranking, tracks.track_number, tracks.track, tracks.album_id, users.user_id
                        FROM rankings
                        LEFT JOIN tracks on rankings.track_id = tracks.track_id
                        LEFT JOIN albums on tracks.album_id = albums.album_id
                        LEFT JOIN users on rankings.user_id = users.user_id
                        WHERE users.user_id = " . $row_user['user_id'] .
                        " AND tracks.album_id = " . $_GET['album_id'] . 
                        " ORDER BY ranking;";
    
    $results_previous = $mysqli->query($sql_previous);
    if (!$results_previous) {
        echo $mysqli->error;
        exit();
    }
    
    // CRUD --> "R" / "Retrieve"
    $sql_tracks = "SELECT track_id, track_number, track, album_id
                    FROM tracks
                    WHERE album_id =" . $_GET['album_id'] . ";";

    $results_tracks = $mysqli->query($sql_tracks);
    if (!$results_tracks) {
        echo $mysqli->error;
        exit();
    }
}

$mysqli->close();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add/Update</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css?v=<?php echo time();?>">

    <style>
        * {
            color: white;
        }
        #nav-home, #nav-search, #nav-results {
            color: white;
        }

        #create-title {
            font-size: 250%;

            padding-left: 5%;
            padding-top: 2%;
        }
        .container {            
            max-width: 1600px;
            width: 100%;

            padding-top: 6%;
        }
        #info-img {
            margin-left: 10%;
            width: 30%;
            
        }
        img {
            width: 100%;
            display: block; 

            border: 2px solid white;

            animation-duration: 4.5s;
            animation-name: slidein;
        }
        #info-text {
            width: 60%;
            
            padding-left: 5%;

            animation-duration: 4.5s;
            animation-name: slidein2;
        }
        #album-title {
            font-size: 5vw;

            width: 100%;

            text-decoration: underline;
        }
        #artist-title {
            font-size: 4vw;
            
            margin-bottom: 1%;
        }
        #release-date {
            font-size: 3vw;
        }

        #info-message {
            width: 80%;

            display: block;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            
            padding-bottom: 5%;
        }
        
        #ranking-form {
            padding-top: 5%;
            padding-bottom: 40px;
        }
        .number-input {            
            width: 40px;
            
            margin-left: 10%;
        }
        .arrow-up {            
            display: block;
            margin-left: 3%;
        }
        .up {
            content: "\2191";
            display: block;

            font-size: 25px;
            color: #28a745;
            -webkit-text-stroke-width: 0.5px;
            -webkit-text-stroke-color: white;
        }
        .arrow-down {            
            display: block;
            margin-left: 3%;
        }
        .down {
            content: "\2193";
            display: block;

            font-size: 25px;
            color: #dc3545;
            -webkit-text-stroke-width: 0.5px;
            -webkit-text-stroke-color: white;
        }
        .track-input {
            margin-left: 1%;
        }
        #buttons {
            padding-top: 1.5%;
        }
        .button {
            margin-left: 10%;
        }
        #back-to-view {
            margin-left: 15px;
        }
        #delete-message {
            margin-left: 10%;
            
            font-size: 15px;
        }
        #delete-button {
            margin-left: 10%;
        }

        @keyframes slidein {
            from {
                margin-top: 100%;
            }
            to {
                margin-top: 0%;
            }
        }
        @keyframes slidein2 {
            from {
                margin-top: 100%;
            }
            to {
                margin-top: 0%;
            }
        }


        @media(min-width: 768px) {
            #create-title {
                font-size: 300%;
            }
            .container {
                padding-top: 5%;
            }
            #info-img {
                width: 20%;
            }
            #album-title {
                font-size: 4vw;
            }
            #artist-title {
                font-size: 3vw;
            }
            #release-date {
                font-size: 2vw;
            }
            #info-message {
                font-size: 17px;
                
                width: 70%;

                display: block;
                margin-left: 8.5%;
                text-align: left;
            }
            #ranking-form {
                padding-top: 4%;
            }
            #buttons {
                padding-top: 1%;
            }
            .track-input {
                margin-left: 1.5%;
            }
        }
        @media(min-width: 992px) {
            #create-title {
                font-size: 350%;
            }
            .container {
                padding-top: 4%;
            }
            #info-img {
                width: 17%;
            }
            #album-title {
                font-size: 3.5vw;
            }
            #artist-title {
                font-size: 2.5vw;
            }
            #release-date {
                font-size: 2vw;
            }
            #info-message {
                font-size: 18px;
                
                width: 65%;

                display: block;
                margin-left: 9%;
                text-align: left;
                
                padding-bottom: 4%;
            }
            #ranking-form {
                padding-top: 4%;
            }
            #buttons {
                padding-top: 1.5%;
            }
            .track-input {
                margin-left: 2%;
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
                <li class="nav-item">
                    <a id="nav-search" class="nav-link" href="search_results.php?album_search=&genre=">Search</a>
                </li>
                <li class="nav-item">
                    <a id="nav-results" class="nav-link" href="view_ranking.php?album_id=<?php echo $_GET['album_id'];?>">View Album</a>
                </li>
                <li class="nav-item">
                    <a id="nav-create" class="nav-link" href="#">Create</a>
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

    <div class="container-fluid">
		<div class="row">
			<div id="create-title" class="col-12 mt-4">Add/Update Ranking</div>
		</div>
	</div>

    <div class="container" id="view-info">
        <div class="row">
            <div id="info-img">
                <a href="view_ranking.php?album_id=<?php echo $_GET['album_id'];?>">
                    <img src="images/<?php echo $row_info['album'];?>.jpg" alt="album cover for <?php echo $row_info['album'];?>">
                </a>
            </div>

            <div id="info-text">
                <div id="album-title">
                    <a class="text-light" href="view_ranking.php?album_id=<?php echo $_GET['album_id'];?>">
                        <?php echo $row_info['album'];?>
                    </a>
                </div>

                <div id="artist-title"><?php echo $row_info['artist'];?></div>
 
                <div id="release-date"><?php echo $row_info['release_date'];?></div>
            </div>
        </div>
    </div>

    <div class="container" id="ranking-form">
        <div id="info-message">
            If you haven't submitted a ranking yet, you can do so by using the <span class="text-success">green</span> and <span class="text-danger">red</span> arrows to adjust the ranking. Then when you're ready, press <span class="text-primary">submit</span>!

            <br><br>If you've already submitted a ranking before, you can update it by submitting again! You may also delete your previous ranking below.
        </div>
    
        <form action="addmod_confirmation.php" method="POST">
            
            <?php if (mysqli_num_rows($results_previous) == 0): ?>
                
                <?php while ($row_tracks = $results_tracks->fetch_assoc()): ?>

                    <div class="form-group row">
                        <div class="number-input">
                            <input  class="number form-control" name="ranking[]" type="text" value="<?php echo $row_tracks['track_number'];?>" readonly>
                        </div>
                        
                        <div class="arrow-up">
                            <span class="up">&#8593;</span> 
                        </div>
                        <div class="arrow-down">
                            <span class="down">&#8595;</span>
                        </div>

                        <div class="track-input col-7">
                            <input class="track form-control" name="track[]" type="text" value="<?php echo $row_tracks['track'];?>" readonly>
                        </div>
                    </div>

                <?php endwhile; ?>

            <?php else: ?>
                
                <?php while ($row_previous = $results_previous->fetch_assoc()): ?>

                    <div class="form-group row">
                        <div class="number-input">
                            <input name="ranking[]" class="number form-control" type="text" value="<?php echo $row_previous['track_number'];?>" readonly>
                        </div>
                        
                        <div class="arrow-up">
                            <span class="up">&#8593;</span> 
                        </div>
                        <div class="arrow-down">
                            <span class="down">&#8595;</span>
                        </div>

                        <div class="track-input col-7">
                            <input name="track[]" class="track form-control" type="text" value="<?php echo $row_previous['track'];?>" readonly>
                        </div>
                    </div>

                <?php endwhile; ?>
                
            <?php endif; ?>
        
            <div class="form-group row" id="buttons">
				<div class="button mt-2">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
                <a id="back-to-view" href="view_ranking.php?album_id=<?php echo $row_info['album_id'];?>" role="button" class="btn btn-secondary mt-2">Back to View</a>
			</div>
            
            <?php if (mysqli_num_rows($results_previous) == 0): ?>
                <div class="form-group row">
                    <div id="delete-button" role="button" class="btn btn-danger mt-2">Delete Ranking</div>
                </div>
                <div class="form-group row">
                    <div id="delete-message" class="text-danger">You haven't submitted a ranking to delete yet.</div>
                </div>
            <?php else: ?>
                <div class="form-group row">
                    <a id="delete-button" href="delete_ranking.php?album_id=<?php echo $row_info['album_id'];?>" role="button" class="btn btn-danger mt-2">Delete Ranking</a>
                </div>
            <?php endif; ?>

        </form>
    </div>

    <footer>
        Created by Joshua Yoo -- <a href="mailto:joshuajcyoo@gmail.com">joshuajcyoo@gmail.com</a>
    </footer> 

    <script>
        let upArrows = document.querySelectorAll(".arrow-up");
        for (let i = 0; i < upArrows.length; i++) {
            upArrows[i].onclick = function() {
                let parentDivUp = this.parentNode;
                let downArrow = this.nextElementSibling;
                
                // Information for current track.
                let currentTrackNumber = this.previousElementSibling.firstElementChild.value;
                let currentTrack = downArrow.nextElementSibling.firstElementChild.value;

                // Information for previous track.
                if (!(parentDivUp.previousElementSibling == null)) {
                    let previousParentDiv = parentDivUp.previousElementSibling;

                    let previousTrackNumber = previousParentDiv.children[0].children[0].value;
                    let previousTrack = previousParentDiv.children[3].children[0].value;

                    // Swap values up.
                    this.previousElementSibling.firstElementChild.value = previousTrackNumber;
                    downArrow.nextElementSibling.firstElementChild.value = previousTrack;

                    previousParentDiv.children[0].children[0].value = currentTrackNumber;
                    previousParentDiv.children[3].children[0].value = currentTrack;
                }
            }
        }

        let downArrows = document.querySelectorAll(".arrow-down");
        for (let j = 0; j < downArrows.length; j++) {
            downArrows[j].onclick = function() {
                let parentDivDown = this.parentNode;
                let upArrow = this.previousElementSibling;

                // Information for current track.
                let currentTrackNumber = upArrow.previousElementSibling.firstElementChild.value;
                let currentTrack = this.nextElementSibling.firstElementChild.value;

                // Information for next track.
                if (!(parentDivDown.nextElementSibling == null)) {
                    let nextParentDiv = parentDivDown.nextElementSibling;

                    let nextTrackNumber = nextParentDiv.children[0].children[0].value;
                    let nextTrack = nextParentDiv.children[3].children[0].value;

                    // Swap values down.
                    upArrow.previousElementSibling.firstElementChild.value = nextTrackNumber;
                    this.nextElementSibling.firstElementChild.value = nextTrack;

                    nextParentDiv.children[0].children[0].value = currentTrackNumber;
                    nextParentDiv.children[3].children[0].value = currentTrack;
                }
            }
        }
    </script>
</body>
</html>