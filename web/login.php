<?php
$page = 'login';
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

        .sucess-message {
            color: green;
            font-size: 20px;
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
        }

        .methode-con {
            /* display: none; */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }

        .methode-con-small {
            display: flex;
            flex-direction: row;
            align-items: baseline;
            justify-content: space-around;
            margin-top: 20px;
            flex-wrap: nowrap;
        }

        .methode-con-btn {
            margin-left: 10px;
            margin-right: 10px;
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
        $AfficherFormulaire = 1;
        if (isset($_SESSION['pseudo'])) {
            echo "You are already login <a href='index.php'>click here</a> !";
            $AfficherFormulaire = 0;
            exit();
        }

        if (isset($_POST['post'])) {
            if (!isset($_POST['pseudo'], $_POST['pwd'])) {
                echo "Please complete all fields.";
            } else {
                $Pseudo = htmlentities($_POST['pseudo'], ENT_QUOTES, "UTF-8");
                $Pwd = hash('sha256', $_POST['pwd']);
                $query = "SELECT * FROM utilisateurs WHERE pseudo='" . $Pseudo . "' AND pwd='" . $Pwd . "'";
                $result = mysqli_query($conn, $query) or die(mysql_error());
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_assoc($result);
                    $_SESSION['pseudo'] = $row['pseudo'];
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['auth'] = $row['auth'];
                    echo "<p class='sucess-message'>You are well login <a href='index.php'>click here</a> !</p>";
                    $AfficherFormulaire = 0;
                    exit();
                } else {
                    echo "The username or password is incorrect !";
                }
            }
        }
        if ($AfficherFormulaire == 1) {
            ?>
        </center>
        <center>
            <div class="container">
                <h1>Login</h1>
                <br />
                <form method="post" action="connexion.php">
                    Username :
                    <input type="text" name="pseudo">
                    <br />
                    Password : <input type="password" name="pwd">
                    <br />
                    <input type="submit" name="post" value="Login">
                </form>
                <div class="methode-con">
                    Login as :
                    <div class="methode-con-small">
                        <form class="methode-con-btn" method="post" action="connexion.php">
                            <input type="text" name="pseudo" value="user" style="display: none">
                            <input type="password" name="code" value="user" style="display: none">
                            <input type="submit" name="post" value="user">
                        </form>
                        or
                        <form class="methode-con-btn" method="post" action="connexion.php">
                            <input type="text" name="pseudo" value="admin" style="display: none">
                            <input type="password" name="code" value="admin" style="display: none">
                            <input type="submit" name="post" value="admin">
                        </form>
                    </div>
                </div>
                <a href="register.php">Register</a>
                <br>
            </div>
        </center>
        <?php
        }
        ?>
</body>

</html>