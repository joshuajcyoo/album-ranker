<?php

require 'config.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    echo $mysqli->connect_error;
    exit();
}

$mysqli->set_charset('utf8');

$sql_user = "SELECT user_id, username
                FROM users
                WHERE username = '" . $_SESSION['username'] . "';";
        
$results_user = $mysqli->query($sql_user);
if (!$results_user) {
    echo $mysqli->error;
    exit();
}
$row_user = $results_user->fetch_assoc();

$sql_trackid = "SELECT track_id
                FROM tracks
                WHERE tracks.album_id = " . $_GET['album_id'] . ";";

$results_trackid = $mysqli->query($sql_trackid);
if (!$results_trackid) {
    echo $mysqli->error;
    exit();
}
$num_tracks = mysqli_num_rows($results_trackid);
$first_track_row = $results_trackid->fetch_assoc();

$first_track_id = $first_track_row['track_id'];
$last_track_id = $first_track_id + ($num_tracks - 1);

// CRUD --> "D" / "Delete"
$sql_delete = "DELETE FROM rankings
                WHERE user_id = " . $row_user['user_id'] . 
                " AND track_id BETWEEN " . $first_track_id . 
                " AND " . $last_track_id . ";";

$results_delete = $mysqli->query($sql_delete);
if (!$results_delete) {
    $error = "There was an error with deleting your ranking.";
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
	<title>Delete</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css?v=<?php echo time();?>">

    <style>
        * {
            color: white;
        }
        #nav-home {
            color: white;
        }
        #delete-title {
            font-size: 250%;

            padding-left: 5%;
            padding-top: 2%;
        }

        .container {
            max-width: 1600px;
        }
        #delete-message {
            font-size: 22px;

            padding-left: 5%;
            padding-bottom: 20px;
        }
        #home-message {
            font-size: 15px;

            text-align: left;
            padding-left: 5%;
            padding-bottom: 5px;
        }

        @media(min-width: 768px) {
            #delete-title {
                font-size: 300%;
            }
            #delete-message {
                font-size: 25px;
            }
            #home-message {
                font-size: 17px;
            }
        }
        @media(min-width: 992px) {
            #delete-title {
                font-size: 300%;
            }
            #delete-message {
                font-size: 28px;
                
                padding-bottom: 25px;
            }
            #home-message {
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
			<div id="delete-title" class="col-12 mt-4 mb-2">Delete Confirmation</div>
        </div>

        <div class="row mt-4">
            <div id="delete-message" class="col-12">

                <?php if (isset($error) && !empty($error)): ?>
					<div class="text-danger">
                        <?php echo $error; ?>
                    </div>
				<?php else: ?>
					<div class="text-danger">Your ranking was successfully deleted.</div>
				<?php endif; ?>

            </div>
        </div>
        
        <div class="row">
            <div id="home-message" class="col-12">
                Click <a class="text-primary" href="view_ranking.php?album_id=<?php echo $_GET['album_id'];?>">here</a> to view the album again.
            </div>
        </div>
    </div>
</body>
</html>