<?php
    $page = 'register';
    require('config.php');
    require('nav.php');
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primaire: #F9DBBB;
            --secondaire: #4E6E81;
            --bordure: #2E3840;
        }

        body {
            background-color: var(--primaire);
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
        }

        h1 {
            text-align: center;
            font-size: 50px;
            color: var(--bordure);
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
            margin-top: 50px;
        }

        input {
            margin-top: 10px;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid var(--bordure);
            border-radius: 5px;
            font-size: 20px;
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
        }

        input[type="submit"] {
            background-color: var(--bordure);
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: var(--secondaire);
            color: #fff;
            border: 1px solid #000;
        }

        a {
            text-decoration: none;
            color: var(--bordure);
            font-size: 20px;
        }

        a:hover {
            color: var(--secondaire);
        }

        form {
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            align-content: space-around;
            justify-content: space-around;
            align-items: stretch;
        }

        .container {
            background-color: var(--primaire);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 50%;
            border: 5px solid var(--bordure);
        }

        .error-message {
            color: red;
            font-size: 20px;
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
        }

        .success-message {
            color: green;
            font-size: 20px;
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
        }

        @media screen and (max-width: 600px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <center class="error-message">

        <?php
        // si l'utilisateur est déjà connecté, on le redirige vers la page d'accueil
        $AfficherFormulaire = 1;
        if (isset($_SESSION['pseudo'])) {
            echo "You are already login <a href='index.php'>click here</a> !";
            $AfficherFormulaire = 0;
            exit();
        }

        // si le formulaire a été envoyé, on vérifie que tous les champs sont remplis correctement et on insert l'utilisateur dans la base de données
        if (isset($_POST['pseudo'], $_POST['pwd'], $_POST['email'])) {
            if (empty($_POST['pseudo']) || empty($_POST['pwd']) || empty($_POST['email'])) {
                echo "Please complete all fields.";
            } else {
                $Pseudo = htmlentities($_POST['pseudo'], ENT_QUOTES, "UTF-8");
                $Email = htmlentities($_POST['email'], ENT_QUOTES, "UTF-8");
                $Pwd = hash('sha256', $_POST['pwd']);
                if (!preg_match("#^[a-z0-9]+$#", $Pseudo)) {
                    echo "The username must be entered in lowercase letters without accents or special characters.";
                } elseif (strlen($Pseudo) < 3) {
                    echo "The username is too short, it must be at least 3 characters long.";
                } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) { // The email is that https://www.youtube.com/watch?v=xxX81WmXjPg !!!
                    echo "The email address is invalid.";
                } elseif (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='" . $Email . "'")) == 1) {
                    echo "This email is already in use.";
                } elseif (strlen($Pseudo) > 25) {
                    echo "The username is too long, it exceeds 25 characters.";
                } elseif (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE pseudo='" . $Pseudo . "'")) == 1) {
                    echo "This username is already used.";
                } else {
                    if (!mysqli_query($conn, "INSERT INTO users SET pseudo='" . $Pseudo . "', pwd='" . $Pwd . "', email='" . $Email . "'")) {
                        echo "An error has occurred: " . mysqli_error($conn);
                    } else {
                        echo "<p class='success-message'>You are successfully registered ! </p>";
                        echo "Click here to return to the home page <a href='login.php'>login</a>";
                        $AfficherFormulaire = 0;
                    }
                }
            }
        }
        if ($AfficherFormulaire == 1) {
            ?>
        </center>
        <center>
            <br>
            <div class="container">
                <h1>Register</h1>
                <form method="post" action="inscription.php">
                    Email : <input type="text" name="email">
                    <br />
                    Username : <input type="text" name="pseudo">
                    <br />
                    Password : <input type="password" name="pwd">
                    <br />
                    <input type="submit" value="Register">
                </form>
                <a href="login.php">Login</a>
                <br>
            </div>
        </center>
        <?php
        }
        ?>
</body>

</html>