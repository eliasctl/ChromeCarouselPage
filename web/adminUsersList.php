<?php
$page = 'adminUsersList';
require('config.php');
require('nav.php');
mustBeAdmin();
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
            $('#utilisateurs').DataTable({
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
        function updatePermitCarousels(userId, actualNbPermitCarousels) {
            alertify.prompt('Change number of permit carousels', 'Enter the new value', actualNbPermitCarousels, function (evt, value) {
                if (isNaN(value) || value < 0) {
                    alertify.error("The value must be a positive number");
                    return;
                }
                $.ajax({
                    url: 'controller.php',
                    type: 'POST',
                    data: 'action=updatePermitCarousels&userId=' + userId + '&nbValue=' + value,
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
        }
        function updatePermitMedias(userId, actualNbPermitCarousels) {
            alertify.prompt('Change number of permit medias', 'Enter the new value', actualNbPermitCarousels, function (evt, value) {
                if (isNaN(value) || value < 0) {
                    alertify.error("The value must be a positive number");
                    return;
                }
                $.ajax({
                    url: 'controller.php',
                    type: 'POST',
                    data: 'action=updatePermitMedias&userId=' + userId + '&nbValue=' + value,
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
        }
    </script>
    <center><h1>Users List</h1></center>
    <table id="utilisateurs" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Perms</th>
                <th>Pseudo</th>
                <th>Mail</th>
                <th>Permit Carousels</th>
                <th>Permit Medias</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach (getUsers() as $id => $user) {
                echo "<tr>";
                echo "<td>" . $id . "</td>";
                echo "<td>" . $user['perms'] . "</td>";
                echo "<td>" . $user['pseudo'] . "</td>";
                echo "<td>" . $user['email'] . "</td>";
                echo "<td><button onclick='updatePermitCarousels(" . $id . ", " . $user['permitCarousels'] . ")'>" . $user['permitCarousels'] . "</button></td>";
                echo "<td><button onclick='updatePermitMedias(" . $id . ", " . $user['permitMedias'] . ")'>" . $user['permitMedias'] . "</button></td>";
                echo "<td><a href='profile.php?userId=" . $id . "'><i class='fa-solid fa-user'></i></a> <a href='#' onclick='deleteUser(" . $id . ")'><i class='fa-solid fa-trash'></i></a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>