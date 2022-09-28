<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">

    <style>
        * {
            color: white;
        }
        #register-title {
            font-size: 350%;

            padding-left: 5%;
        }
        #nav-home {
            color: white;
        }

        .container {
            max-width: 1600px;

            padding-top: 30px;
        }
        #username-group, #email-group {
            padding-bottom: 10px;
        }
        #username-label, #email-label, #password-label {
            font-size: 20px;
        }
        #login-account-message {
            padding-top: 5px;
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
			<div id="register-title" class="col-12 mt-4 mb-4">Register</div>
        </div>
    </div>

    <div class="container">
        <form action="register_confirmation.php" method="POST">
            <div id="username-group" class="form-group row">
                <label id="username-label" for="register-username" class="col-sm-3 col-form-label text-sm-right">Username:<span class="text-danger">*</span></label>
                <div class="col-sm-8 col-md-7 col-lg-6">
                    <input type="text" class="form-control" id="register-username" name="username">
                    <small id="username-error" class="invalid-feedback">Username is required.</small>
                </div>
            </div>

            <div id="email-group" class="form-group row">
                <label id="email-label" for="register-email" class="col-sm-3 col-form-label text-sm-right">Email:<span class="text-danger">*</span></label>
                <div class="col-sm-8 col-md-7 col-lg-6">
                    <input type="email" class="form-control" id="register-email" name="email">
                    <small id="email-error" class="invalid-feedback">Email is required.</small>
                </div>
            </div>

            <div class="form-group row">
                <label id="password-label" for="register-password" class="col-sm-3 col-form-label text-sm-right">Password:<span class="text-danger">*</span></label>
                <div class="col-sm-8 col-md-7 col-lg-6">
                    <input type="text" class="form-control" id="register-password" name="password">
                    <small id="password-error" class="invalid-feedback">Password is required.</small>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"></div>
                <div class="col-sm-9 mt-2">
                    <button id="register-button" type="submit" class="btn btn-primary">Register</button>
                </div>
            </div>
        </form>

        <div class="row">
            <div id="login-account-message" class="col-sm-9 ml-sm-auto">
                If you already have an account, click <a href="login.php">here</a> to sign in.
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').onsubmit = function() {
			if (document.querySelector('#register-username').value.trim().length == 0) {
                document.querySelector('#register-username').classList.add('is-invalid');
			}
            else {
				document.querySelector('#register-username').classList.remove('is-invalid');
			}

            if (document.querySelector('#register-email').value.trim().length == 0) {
                document.querySelector('#register-email').classList.add('is-invalid');
			}
            else {
				document.querySelector('#register-email').classList.remove('is-invalid');
			}

            if (document.querySelector('#register-password').value.trim().length == 0) {
                document.querySelector('#register-password').classList.add('is-invalid');
			}
            else {
				document.querySelector('#register-password').classList.remove('is-invalid');
			}

            return (!document.querySelectorAll('.is-invalid').length > 0);
        }
    </script>
</body>
</html>