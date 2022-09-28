<?php

$page_url = preg_replace('/&page=\d*/', '', $_SERVER['REQUEST_URI']);

require "config.php";

$results_per_page = 8;

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
	echo $mysqli->connect_error;
	exit();
}

$mysqli->set_charset('utf8');

$sql = "SELECT album_id, album, artist, genres.genre AS genre, genres.genre_id, release_date
        FROM albums
        LEFT JOIN genres
            ON genres.genre_id = albums.genre_id
        WHERE 1 = 1";
    
if (isset($_GET['album_search']) && !empty($_GET['album_search'])) {
    $sql = $sql . " AND album LIKE '%" . $_GET['album_search'] . "%'";
}

if (isset($_GET['genre']) && !empty($_GET['genre'])) {
    $sql = $sql . " AND genres.genre_id = " . $_GET['genre'];
}

$sql = $sql . ";";

$results = $mysqli->query($sql);

if ($results == false) {
    echo $mysqli->error;
    exit();
}

$num_results = $results->num_rows;
$first_page = 1;
$last_page = ceil($num_results / $results_per_page);

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $current_page = $_GET['page'];
}
else {
    $current_page = $first_page;
}

if ($current_page < $first_page) {
	$current_page = $first_page;
}
elseif ($current_page > $last_page) {
    $current_page = $last_page;
}

$start_index = ($current_page - 1) * $results_per_page;

$sql = str_replace(';', '', $sql);
$sql = $sql . " LIMIT " . $start_index . ", " . $results_per_page . ";";

$results = $mysqli->query($sql);

if ($results == false) {
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
	<title>Search Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css?v=<?php echo time();?>">

    <style>
        * {
            color: white;
        }
        #nav-search {
            color: gray;
        }
        
        #search-title {
            font-size: 250%;

            padding-left: 5%;
            padding-top: 2%;
        }
        #number-results {
            padding-top: 3%;
            padding-left: 5%;
        }
        #results {
            padding-bottom: 30px;
        }
		img {
            width: 100%;
            border: 2px solid white;

            min-width: 80px;
        }
        th, td {
			vertical-align: middle !important;

            width: 20%;
        }
        table {
            font-size: 16px;

            padding-left: 3%;
        }
        .album-name {
            color: #CCC9DC;
            text-decoration: underline;
        }
        .album-name:hover {
            color: white;
        }
        #button {
            margin-left: 3%;
        }

        @media(min-width: 768px) {
            #search-title {
                font-size: 300%;
            }
            #number-results {
                font-size: 17px;
            }
            table {
                font-size: 18px;
            }
            td, th {
                width: 15%;
            }
            img {
                display: block;
                margin-left: 0;
                margin-right: auto;

                width: 75%;
                max-width: 105px;
            }
        }
        @media(min-width: 992px) {
            #search-title {
                font-size: 350%;
            }
            #number-results {
                font-size: 20px;
            }
            table {
                font-size: 19px;
            }
            td, th {
                width: 15%;
            }
            img {
                width: 70%;
                max-width: 110px;
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
                    <a id="nav-search" class="nav-link" href="#">Search</a>
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
			<div id="search-title" class="col-12 mt-4">Search Results</div>
		</div>
	</div>

    <div id="results" class="container-fluid">
		<div class="row">
            <div id="number-results" class="col-12">
                Showing <?php echo ($start_index + 1); ?> - <?php echo ($start_index + $results->num_rows); ?> of <?php echo $num_results; ?> result(s).
            </div>
        </div>
    
        <div class="row">
			<div class="col-12">
			</div>
			<div class="col-12">
				<table class="table table-hover table-responsive mt-4">
					<thead>
						<tr>
							<th></th>
                            <th>Album</th>
							<th>Artist</th>
                            <th>Genre</th>
                            <th>Release Date</th>
						</tr>
					</thead>
					<tbody>

                        <?php while ($row = $results->fetch_assoc()): ?>
                            <tr>
                                <td class="img-value">
                                    <a href="view_ranking.php?album_id=<?php echo $row['album_id'];?>">
                                        <img src="images/<?php echo $row['album']?>.jpg">
                                    </a>
                                </td>
                                <td><a class="album-name" href="view_ranking.php?album_id=<?php echo $row['album_id'];?>">
                                    <?php echo $row['album']; ?>
                                </td>
                                <td><?php echo $row['artist'];?></td>
                                <td><?php echo $row['genre'];?></td>
                                <td><?php echo $row['release_date'];?></td>
                            </tr>
                        <?php endwhile; ?>

					</tbody>
				</table>
			</div>
		</div>

        <div class="row">
            <div class="col-12">
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="bg-secondary text-light page-link" href="<?php echo $page_url . "&page=" . ($current_page - 1); ?>">Prev</a>
                        </li>
                        <li class="page-item">
                            <a class="bg-secondary text-light page-link" href=""><?php echo $current_page; ?></a>
                        </li>
                        <li class="page-item">
                            <a class="bg-secondary text-light page-link" href="<?php echo $page_url . "&page=" . ($current_page + 1); ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a id="button" href="home.php" role="button" class="btn btn-primary">Back to Home</a>
			</div>
		</div>
	</div>
    
    <footer>
        Created by Joshua Yoo -- <a href="mailto:joshuajcyoo@gmail.com">joshuajcyoo@gmail.com</a>
    </footer> 
</body>
</html>