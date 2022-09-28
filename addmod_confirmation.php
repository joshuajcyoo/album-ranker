<?php

require 'config.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    echo $mysqli->connect_error;
    exit();
}

$mysqli->set_charset('utf8');

$tracks = $_POST['track'];
$rankings = $_POST['ranking'];

$sql_info = "SELECT albums.album_id AS album_id, track
                FROM tracks
                LEFT JOIN albums ON tracks.album_id = albums.album_id
                WHERE track = ?";
$statement_info = $mysqli->prepare($sql_info);
$statement_info->bind_param("s", $tracks[0]);
$statement_info->execute();
$result_info = $statement_info->get_result();
$test_track = $result_info->fetch_assoc();

$album_id = $test_track['album_id'];

$sql_user = "SELECT user_id, username
                FROM users
                WHERE username = '" . $_SESSION['username'] . "';";
        
$results_user = $mysqli->query($sql_user);
if (!$results_user) {
    echo $mysqli->error;
    exit();
}
$row_user = $results_user->fetch_assoc();

$sql_previous = "SELECT tracks.track_number, tracks.track, tracks.album_id, users.user_id
                    FROM rankings
                    LEFT JOIN tracks on rankings.track_id = tracks.track_id
                    LEFT JOIN albums on tracks.album_id = albums.album_id
                    LEFT JOIN users on rankings.user_id = users.user_id
                    WHERE users.user_id = " . $row_user['user_id'] .
                    " AND tracks.album_id = " . $album_id . ";";

$results_previous = $mysqli->query($sql_previous);
if (!$results_previous) {
    echo $mysqli->error;
    exit();
}

// CRUD --> "C" / "Create"
if (mysqli_num_rows($results_previous) == 0) {
    for ($i = 1; $i <= count($tracks); $i++) {
        $sql_current = "SELECT track_id, track FROM tracks WHERE track = ?";
        $statement_current = $mysqli->prepare($sql_current);
        $statement_current->bind_param("s", $tracks[$i - 1]);
        $statement_current->execute();
        $result_current = $statement_current->get_result();
        
        $current_track = $result_current->fetch_assoc();
        
        $sql_create = "INSERT INTO rankings (ranking, track_id, user_id) VALUES (" . $i . ", " . $current_track['track_id'] . ", " . $row_user['user_id'] . ");";

        $results_create = $mysqli->query($sql_create);
        
        if (!$results_user) {
            $error = "There was an error with submitting your ranking.";
            echo $mysqli->error;
            exit();
        }
    }
}
// CRUD --> "U" / "Update"
else {
    for ($i = 1; $i <= count($tracks); $i++) {
        $sql_current = "SELECT track_id, track FROM tracks WHERE track = ?";
        $statement_current = $mysqli->prepare($sql_current);
        $statement_current->bind_param("s", $tracks[$i - 1]);
        $statement_current->execute();
        $result_current = $statement_current->get_result();
        
        $current_track = $result_current->fetch_assoc();

        $sql_update = "UPDATE rankings
                        SET ranking = " . $i . 
                        " WHERE track_id = " . $current_track['track_id'] . "
                        AND user_id = " . $row_user['user_id'] . ";";
        
        $results_update = $mysqli->query($sql_update);
        
        if (!$results_update) {
            $error = "There was an error with submitting your ranking.";
            echo $mysqli->error;
            exit();
        }
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
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

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
        #confirmation-message {
            font-size: 22px;

            padding-left: 5%;
            padding-bottom: 20px;
        }
        #back-to-album {
            font-size: 15px;

            text-align: left;
            padding-left: 5%;
            padding-bottom: 5px;
        }

        @media(min-width: 768px) {
            #confirmation-title {
                font-size: 300%;
            }
            #confirmation-message {
                font-size: 25px;
            }
            #back-to-album {
                font-size: 17px;
            }
        }
        @media(min-width: 992px) {
            #confirmation-title {
                font-size: 350%;
            }
            #confirmation-message {
                font-size: 28px;
                
                padding-bottom: 25px;
            }
            #back-to-album {
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
			<div id="confirmation-title" class="col-12 mt-4 mb-2">Add/Update Confirmation</div>
        </div>

        <div class="row mt-4">
            <div id="confirmation-message" class="col-12">

                <?php if (isset($error) && !empty($error)):?>
                    <div class="text-danger">
                        <?php echo $error; ?>
                    </div>
                <?php elseif (mysqli_num_rows($results_previous) == 0):?>
                    <div class="text-success">Your ranking was successfully created.</div>
                <?php else: ?>
                    <div class="text-success">Your ranking was successfully updated.</div>
                <?php endif; ?>

            </div>
        </div>

        <div class="row">
            <div id="back-to-album" class="col-12">
                Click <a class="text-primary" href="view_ranking.php?album_id=<?php echo $album_id;?>">here</a> to view the album again.
            </div>
        </div>
    </div>
</body>
</html>