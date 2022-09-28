<?php

if (!isset($_GET['album_id']) || empty($_GET['album_id'])) {
    $error = "Invalid album ID.";
}
else {    
    require "config.php";

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

    // CRUD --> "R" / "Retrieve"
    $sql_ranking = "SELECT tracks.track_number AS track_number, tracks.track_id AS track_id, tracks.track AS track, SUM(ranking) AS ranking_sum
                    FROM rankings
                    LEFT JOIN tracks on rankings.track_id = tracks.track_id
                    LEFT JOIN albums on tracks.album_id = albums.album_id
                    WHERE tracks.album_id = " . $_GET['album_id'] . " GROUP BY track_id
                    ORDER BY ranking_sum;";

    $results_ranking = $mysqli->query($sql_ranking);
    if (!$results_ranking) {
        echo $mysqli->error;
        exit();
    }

    $mysqli->close();
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>View Album</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css?v=<?php echo time();?>">

    <style>
        * {
            color: white;
            margin: 0;
        }
        #nav-search {
            color: white;
        }
        #nav-results {
            color: gray;
        }

        #view-info, #error-info {
            max-width: 1600px;
            
            padding-top: 40px;
        }
        #error-message {
            display: block;
            padding-left: 5%;

            font-size: 25px;
        }
        img {
            max-width: 225px;
            border: 7px solid white;

            display: block;
            margin-left: auto;
            margin-right: auto;

            animation-duration: 4.5s;
            animation-name: slidein;
        }
        #album-title, #artist-title, #release-date {
            width: 100%;
            text-align: center;
        }
        #album-title {
            padding-top: 30px;
        
            font-size: 40px;
        }
        #artist-title {
            font-size: 25px;
        }
        #release-date {
            font-size: 15px;
        }

        #track-rankings {
            max-width: 1600px;

            padding-top: 20px;
        }
        #ranking-title {
            font-size: 40px;

            padding-bottom: 20px;
            text-align: center;
            text-decoration: underline;

            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .rank-number, .track-title {
            color: #324A5F;
            background-color: white;
            text-decoration: underline;
        }
        .view-track {
            width: 65%;
            margin-left: auto;
            margin-right: auto;

            border: 1px solid #324A5F;
	        padding-left: 10px;
            padding-top: 7px;
            padding-bottom: 6px;

            color: #324A5F;
            background-color: white;

            animation-duration: 4.5s;
            animation-name: slidein2;
        }
        @keyframes slidein {
            from {
                margin-top: 100%;
                width: 65%;
            }
            to {
                margin-top: 0%;
                width: 65%;
            }
        }
        @keyframes slidein2 {
            from {
                margin-top: 100%;
                width: 65%;
            }
            to {
                margin-top: 0%;
                width: 65%;
            }
        }
        
        #button {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        #button-signin {
            margin-bottom: 0;
            padding-top: 10px;

            font-size: 15px;
        }
        #fine-print {
            max-width: 1600px;
            text-align: center;

            padding-top: 15px;
            padding-bottom: 40px;
        }
        #fine-print-row {
            width: 75%;
            
            display: block;
            margin-left: auto;
            margin-right: auto;

            padding-top: 5px;
        }

        @media(min-width: 768px) {
            #view-info {
                padding-top: 4%;
            }
            img {
                max-width: 250px;
            }
            hr {
                width: 75%;
            }
            #ranking-title {
                padding-top: 5px;

                font-size: 35px;
            }
            .view-track {
                width: 55%;
            }
            #fine-print-row {
                width: 65%;
            }

            @keyframes slidein {
                from {
                    margin-top: 100%;
                    width: 55%;
                }
                to {
                    margin-top: 0%;
                    width: 55%;
                }
            }
            @keyframes slidein2 {
                from {
                    margin-top: 100%;
                    width: 55%;
                }
                to {
                    margin-top: 0%;
                    width: 55%;
                }
            }
        }
        @media(min-width: 992px) {
            #view-info {
                padding-top: 3%;
            }
            img {
                max-width: 280px;
            }
            hr {
                width: 70%;
            }
            #ranking-title {
                padding-top: 10px;

                font-size: 40px;
            }
            .view-track {
                width: 45%;
            }
            #fine-print-row {
                width: 50%;
            }

            @keyframes slidein {
                from {
                    margin-top: 100%;
                    width: 45%;
                }
                to {
                    margin-top: 0%;
                    width: 45%;
                }
            }
            @keyframes slidein2 {
                from {
                    margin-top: 100%;
                    width: 45%;
                }
                to {
                    margin-top: 0%;
                    width: 45%;
                }
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
                    <a id="menu-home" class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a id="nav-search" class="nav-link" href="search_results.php?album_search=&genre=">Search</a>
                </li>
                <li class="nav-item">
                    <a id="nav-results" class="nav-link" href="#">View Album</a>
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
    <?php if (isset($error) && !empty($error)): ?>
        <div class="container" id="error-info">
            <div class="row">
                <div class="text-danger" id="error-message">
                    <?php echo $error;?>
                </div>
            </div>
        </div>
    <?php else:?>
        <div class="container" id="view-info">
            <div class="row">
                <img src="images/<?php echo $row_info['album'];?>.jpg" alt="album cover for <?php echo $row_info['album'];?>">
            </div>

            <div class="row">
                <h3 id="album-title"><?php echo $row_info['album'];?></h3>
            </div>

            <div class="row">
                <h5 id="artist-title"><?php echo $row_info['artist'];?></h5>
            </div>

            <div class="row">
                <h6 id="release-date"><?php echo $row_info['release_date'];?></h6>
            </div>
        </div>

        <div class="container" id="track-rankings">
            <div class="row">
                <div id="ranking-title">Live Ranking</div>
            </div>
        
            <?php while ($row_ranking = $results_ranking->fetch_assoc()): ?>
                <div class="row">
                    <div class="view-track">
                        <span class="track-title">Track </span><span class="rank-number"><?php echo $row_ranking['track_number'];?></span>: <?php echo $row_ranking['track'];?></div>
                </div>
            <?php endwhile; ?>

        </div>

        <div class="container" id="fine-print"> 
            <div class="row mt-4 mb-4">
                
                <?php if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]): ?>
                    <div id="button" class="col-sm-6 col-md-8">
                        <div role="button" class="btn btn-warning">Create or update your ranking!</div>
                        <p id="button-signin" class="text-danger">You must be signed in to create a ranking.</p>
                    </div>
                <?php else: ?>
                    <div id="button" class="col-sm-6 col-md-8">
                        <a href="addmod_ranking.php?album_id=<?php echo $row_info['album_id'];?>" role="button" class="btn btn-warning">Create or update your ranking!</a>
                    </div>
                <?php endif; ?>
            
            </div>
        
            <div id="fine-print-row" class="row">
                <div>
                    Our live rankings are always updated to show the most current global hierarchy of this album. Check back in at any time to see how things change over time!
                </div>
            </div>
        </div>
    <?php endif; ?>

    <footer>
        Created by Joshua Yoo -- <a href="mailto:joshuajcyoo@gmail.com">joshuajcyoo@gmail.com</a>
    </footer> 
</body>
</html>