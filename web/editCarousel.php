<?php
$page = 'EditCarousel';
require('config.php');
require('nav.php');
mustBeLogin();

if (isset($_GET['action']) && $_GET['action'] === 'add')
{
    $userId = $_SESSION['id'];
    if ($_SESSION['perms'] === 'admin' && isset($_GET['userId']))
    {
        $userId = $_GET['userId'];
    }
    $user = getUser($userId);
    if ($user['permitCarousels'] <= $user['nbCarousels'] and $_SESSION['perms'] !== 'admin') //and $_SESSION['perms'] !== 'admin'
    {
        echo "<script type='text/javascript'>alert('You have reached the maximum number of carousels allowed'); window.location.href='profile.php';</script>";
    }
    else
    {
        $carouselId = getFreeCarouselId($userId);
        $responseCreation = addCarousel($carouselId, $userId);
        if (strpos($responseCreation, "Ok!") !== false)
        {
            echo "<script type='text/javascript'>alert('" . $responseCreation . "'); window.location.href='editCarousel.php?carouselId=" . $carouselId . "';</script>";
        }
        else
        {
            echo "<script type='text/javascript'>alert('" . $responseCreation . "'); window.location.href='profile.php';</script>";
        }
    }

}
elseif (isset($_GET['carouselId']))
{
    $carouselId = $_GET['carouselId'];
    $carousel = getCarousel($carouselId, $_SESSION['id'], $_SESSION['perms']);
    if ($carousel['userId'] !== $_SESSION['id'] and $_SESSION['perms'] !== 'admin')
    {
        echo "<script type='text/javascript'>alert('You do not have permission to access this carousel'); window.location.href='profile.php';</script>";
    }
}
else
{
    echo "<script type='text/javascript'>alert('Error: No carousel Id provided!'); window.location.href='profile.php';</script>";
}
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <style>
        body {
            background-color: #F9DBBB;
        }
    </style>

</head>

<body>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#carousel').DataTable({
                scrollX: true,
                "paging": false,
                "info": false
            });
        });
        function deleteUser(userId) {
            alertify.confirm('Confirmation', 'Do you want to delete this user? ', function () {
                if (userId == 1) {
                    alertify.error("You cannot remove the administrator");
                    return;
                } else if (userId == 2) {
                    alertify.error("You cannot delete the test user's account, this is a basic test site");
                    return;
                }
                $.ajax({
                    url: 'controller.php',
                    type: 'POST',
                    data: 'action=deleteUser&userId=' + userId,
                    dataType: 'html',
                    success: function (code_html, status) {
                        nb = code_html.search(/Ok/i);
                        if (nb !== -1) {
                            alertify.success(code_html);
                            alertify.success("Page reloads in 3 seconds");
                            setTimeout(function () {
                                location.reload();
                            }, 3000);
                        } else {
                            alertify.message(code_html);
                        }
                    }
                });
                alertify.confirm().close();
            }, function () {
                alertify.error("The operation was canceled");
            });
        };
        function updateTitle() {
            var title = document.getElementById("title").value;
            var carouselId = document.getElementById("carouselId").value;
            var userId = document.getElementById("userId").value;
            alertify.prompt('Change title', 'Enter the new title', title, function (evt, value) {
                $.ajax({
                    url: 'controller.php',
                    type: 'POST',
                    data: 'action=updateTitle&carouselId=' + carouselId + '&title=' + value + '&userId=' + userId,
                    dataType: 'html',
                    success: function (code_html, status) {
                        nb = code_html.search(/Ok/i);
                        if (nb !== -1) {
                            alertify.success(code_html);
                            alertify.success("Page reloads in 3 seconds");
                            setTimeout(function () {
                                location.reload();
                            }, 3000);
                        } else {
                            alertify.message(code_html);
                        }
                    }
                });
                alertify.confirm().close();
            }, function () {
                alertify.error("The operation was canceled");
            });
        };
        function updateDescription() {
            var description = document.getElementById("description").value;
            var carouselId = document.getElementById("carouselId").value;
            var userId = document.getElementById("userId").value;
            alertify.prompt('Change description', 'Enter the new description', description, function (evt, value) {
                $.ajax({
                    url: 'controller.php',
                    type: 'POST',
                    data: 'action=updateDescription&carouselId=' + carouselId + '&description=' + value + '&userId=' + userId,
                    dataType: 'html',
                    success: function (code_html, status) {
                        nb = code_html.search(/Ok/i);
                        if (nb !== -1) {
                            alertify.success(code_html);
                            alertify.success("Page reloads in 3 seconds");
                            setTimeout(function () {
                                location.reload();
                            }, 3000);
                        } else {
                            alertify.message(code_html);
                        }
                    }
                });
                alertify.confirm().close();
            }, function () {
                alertify.error("The operation was canceled");
            });
        };
        function addLink()
        {
            var link = document.getElementsByName("newLink");
            var time = document.getElementsByName("newTime");
            link = link[0].value;
            time = time[0].value;
            var carouselId = document.getElementById("carouselId").value;
            var userId = document.getElementById("userId").value;
            link = encodeURIComponent(link);
            $.ajax({
                url: 'controller.php',
                type: 'POST',
                data: 'action=addLink&carouselId=' + carouselId + '&link=' + link + '&userId=' + userId + '&time=' + time,
                dataType: 'html',
                success: function (code_html, status) {
                    nb = code_html.search(/Ok/i);
                    if (nb !== -1) {
                        alertify.success(code_html);
                        alertify.success("Page reloads in 3 seconds");
                        setTimeout(function () {
                            location.reload();
                        }, 3000);
                    } else {
                        alertify.message(code_html);
                    }
                }
            });
        };
        function deleteLink(link)
        {
            var carouselId = document.getElementById("carouselId").value;
            var userId = document.getElementById("userId").value;
            link = encodeURIComponent(link);
            console.log(link);
            alertify.confirm('Confirmation', 'Do you want to delete this link? ', function () {
                $.ajax({
                    url: 'controller.php',
                    type: 'POST',
                    data: 'action=deleteLink&carouselId=' + carouselId + '&link=' + link + '&userId=' + userId,
                    dataType: 'html',
                    success: function (code_html, status) {
                        nb = code_html.search(/Ok/i);
                        if (nb !== -1) {
                            alertify.success(code_html);
                            alertify.success("Page reloads in 3 seconds");
                            setTimeout(function () {
                                location.reload();
                            }, 3000);
                        } else {
                            alertify.message(code_html);
                        }
                    }
                });
                alertify.confirm().close();
            }, function () {
                alertify.error("The operation was canceled");
            });
        };
    </script>
    <input id="carouselId" value="<?= $carousel['id'] ?> " disabled hidden>
    <input id="userId" value="<?= $carousel['userId'] ?> " disabled hidden>
    <input id="title" value="<?= $carousel['title'] ?> " disabled hidden>
    <input id="description" value="<?= $carousel['description'] ?> " disabled hidden>
    <center><h1>Edit display Id: <?= $carousel['id'] ?></h1></center>
    <br>
    <center>
        <div class="main-box">
            <div class="infos">
                <h1>
                    <?= $carousel['title'] ?> <i class="fa-solid fa-pen" onclick="updateTitle()"></i>
                </h1>
                <h2>
                    <?= $carousel['description'] ?> <i class="fa-solid fa-pen" onclick="updateDescription()"></i>
                    <br>
                    <br>
                </h2>
            </div>
        </div>
    <table id="carousel" class="display" style="width:80%">
        <thead>
            <tr>
                <th>Link</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="text" name="newLink" placeholder="New Link" style="width:80%"></td>
                <td><input type="number" name="newTime" placeholder="New Time in minute"></td>
                <td><i class="fa-solid fa-square-plus fa-xl" onclick="addLink()"></i></td>
            </tr>
            <?php
                foreach ($carousel['list'] as $page)
                {
                    echo "<tr>";
                    echo "<td><a href='" . $page['link'] . "' target='_blank'>" . $page['link'] . "</a></td>";
                    echo "<td>" . $page['time'] . "</td>";
                    $link = urlencode($page['link']);
                    echo "<td><i class='fa-solid fa-trash' onclick='deleteLink(\"". $page['link'] ."\")'></i></td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
    </center>
</body>

</html>