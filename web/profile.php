<?php
    $page = 'profile';
    require('config.php');
    require('nav.php');
    mustBeLogin();
    $userId = $_SESSION['id'];
    if (isset($_GET['userId']) && $_SESSION['perms'] === 'admin') {
        $userId = $_GET['userId'];
    }
    $user = getUser($userId);
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <style>
        :root {
            --primaire: #F9DBBB;
            --secondaire: #4E6E81;
            --bordure: #2E3840;
        }

        body {
            background-color: var(--primaire);
            font-family: 'Roboto', sans-serif;
        }

        .main-box {
            width: 70%;
            background-color: var(--secondaire);
            color: white;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-content: center;
            justify-content: space-evenly;
            align-items: center;
            border: 3px solid var(--bordure);
            border-radius: 10px;
        }

        .achats {
            width: 70%;
            background-color: var(--secondaire);
            border: 3px solid var(--bordure);
            border-radius: 10px;
            color: white;
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            justify-content: flex-start;
            align-items: center;
        }

        /* de la  */

        .titre {
            width: 100%;
            background-color: var(--bordure);
            border-radius: 10px 10px 0px 0px;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: center;
            align-items: center;
        }

        .titre h1 {
            font-size: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: var(--bordure);
            color: white;
            font-size: 20px;
            padding: 10px;
        }

        .achat-infos {
            background-color: var(--bordure);
            color: white;
            font-size: 20px;
        }

        .infos {
            width: 50%;
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            justify-content: center;
            align-items: center;
        }

        tr {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: space-around;
            align-items: center;
        }

        td,
        th {
            width: 33%;
            padding: 10px;
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            justify-content: center;
            align-items: center;
        }

        td>a {
            text-decoration: none;
            color: white;
        }

        .pp {
            font-size: 200px;
        }

        .mdp {
            background-color: var(--primaire);
            border: 2px solid var(--bordure);
            border-radius: 5px;
            font-size: 20px;
        }

        .mdp:hover {
            background-color: red;
            color: white;
        }

        @media screen and (max-width: 500px) {
            .pp {
                font-size: 40px;
            }

            .main-box {
                width: 95%;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .achats {
                width: 95%;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
        }
    </style>
</head>

<script>
    function changePwd(id) {
        alertify.prompt('Password change', 'Enter your new password', 'Password', function (evt, value) {
            if (value.length < 5) {
                alertify.error("Password must contain at least 5 characters");
                return;
            }
            $.ajax({
                url: 'controller.php',
                type: 'POST',
                data: 'action=changePwd&userId=' + id + '&pwd=' + value,
                dataType: 'html',
                success: function (code_html, status) {
                    nb = code_html.search(/Ok/i);
                    if (nb !== -1) {
                        alertify.success(code_html);
                    } else {
                        alertify.message(code_html);
                    }
                }
            });
            alertify.confirm().close();
        }, function () {
            alertify.error("The operation was canceled");
        });
    }
</script>

<body>
    <center>
        <div class="main-box">
            <i class="fa-solid fa-user fa-2xl pp"></i>
            <div class="infos">
                <h1>
                    <?php echo $user['pseudo'] ?>
                </h1>
                <h2>
                    <?php echo $user['email']; ?>
                    <br>
                    <br>
                    <i class="fa-solid fa-tv"></i>
                    <b><?= $user['nbCarousels']?></b> / <?= $user['permitCarousels']?>
                    <br>
                    <br>
                    <i class="fa-solid fa-image"></i>
                    <b><?= $user['permitMedias']?></b> / <?= $user['permitMedias']?>
                </h2>
                <?php
                if ($_SESSION['perms'] === 'admin') {
                    echo "<h2>Permissions: " . $user['perms'] . "</h2>";
                    echo "<h2>ID: " . $user['id'] . "</h2>";
                }
                ?>
                <button class="mdp" onclick="changePwd(<?php echo $user['id']; ?>)">
                    Modifier mon mot de passe
                </button>
            </div>
        </div>
        <br>
        <br>
        <div class="achats">
            <div class="titre">
                <h1>My Carousels
                    <?php if ($user['nbCarousels'] < $user['permitCarousels']) { ?>
                        <a href="editCarousel.php?action=add&userId=<?= $user['id'] ?>"><i class="fa-solid fa-square-plus fa-xl" style="color: grey;"></i></a>
                    <?php } ?>
                </h1>
            </div>
            <table>
                <tr class="achat-infos">
                    <th>Id</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                <?php
                $carousels = getCarousels($user['id']);
                foreach ($carousels as $carousel) {
                    echo "<tr>";
                    echo "<td>" . $carousel['id'] . "</td>";
                    echo "<td>" . $carousel['title'] . "</td>";
                    echo "<td>" . $carousel['description'] . "</td>";
                    echo "<td><a href='editCarousel.php?carouselId=".$carousel['id']."'><i class='fa-solid fa-pen'></i></a><i class='fa-solid fa-trash' onlick='deleteCarousel(\'".$carousel['id']."\')'></i></td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </center>

</body>