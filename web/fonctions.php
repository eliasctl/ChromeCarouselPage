<?php

function mustBeLogin()
{
    if (!isset($_SESSION["pseudo"])) {
        echo "<script type='text/javascript'>alert('You must be logged in to access this page'); window.location.href='login.php';</script>";
    }
}

function mustBeAdmin()
{
    if ($_SESSION["perms"] !== 'admin') {
        echo "<script type='text/javascript'>alert('This page is reserved for administrators'); window.location.href='index.php';</script>";
    }
}

function getUsers()
{
    global $conn;
    $query = "SELECT id, pseudo, email, perms, permitCarousels, permitMedias FROM users";
    $res = mysqli_query($conn, $query);
    $usersList = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $usersList[$row['id']]['id'] = $row['id'];
        $usersList[$row['id']]['pseudo'] = $row['pseudo'];
        $usersList[$row['id']]['email'] = $row['email'];
        $usersList[$row['id']]['perms'] = $row['perms'];
        $usersList[$row['id']]['permitCarousels'] = $row['permitCarousels'];
        $usersList[$row['id']]['permitMedias'] = $row['permitMedias'];
    }
    return $usersList;
}

function getUser($id)
{
    global $conn;
    $query = "SELECT 
                    u.id,
                    u.pseudo,
                    u.email,
                    u.perms,
                    u.permitCarousels,
                    u.permitMedias,
                    COUNT(c.id) AS nbCarousels 
                FROM 
                    users u
                LEFT JOIN 
                    carousels c ON u.id = c.userId
                WHERE 
                    u.id = $id
                GROUP BY 
                    u.id,
                    u.pseudo,
                    u.email,
                    u.perms,
                    u.permitCarousels,
                    u.permitMedias;";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($res);
    return $row;
}

function deleteUser($userId)
{
    global $conn;
    $query = "DELETE FROM `users` WHERE `id` = '" . $id_utilisateur . "'";
    $res = mysqli_query($conn, $query);
    if ($res) {
        return "Ok! User has been deleted";
    } else {
        return "An error has occurred";
    }
}

function updatePermitCarousels($userId, $nbValue)
{
    global $conn;
    $query = "UPDATE `users` SET `permitCarousels` = '" . $nbValue . "' WHERE `id` = '" . $userId . "'";
    $res = mysqli_query($conn, $query);
    if ($res) {
        return "Ok! The value has been updated";
    } else {
        return "An error has occurred";
    }
}

function updatePermitMedias($userId, $nbValue)
{
    global $conn;
    $query = "UPDATE `users` SET `permitMedias` = '" . $nbValue . "' WHERE `id` = '" . $userId . "'";
    $res = mysqli_query($conn, $query);
    if ($res) {
        return "Ok! The value has been updated";
    } else {
        return "An error has occurred";
    }
}

function changePwd($userId, $pwd)
{
    global $conn;
    $query = "UPDATE `users` SET `pwd` = '" . $pwd . "' WHERE `id` = '" . $userId . "'";
    $res = mysqli_query($conn, $query);
    if ($res) {
        return "Ok! The password has been updated";
    } else {
        return "An error has occurred";
    }
}

function getFreeCarouselId($userId)
{
    $userPseudo = getUser($userId)['pseudo'];
    global $conn;
    $query = "SELECT id FROM carousels WHERE userId = $userId";
    $res = mysqli_query($conn, $query);
    $id = 1;
    while ($row = mysqli_fetch_assoc($res)) {
        if ($row['id'] !== $userPseudo . $id) {
            break;
        }
        $id++;
    }
    $return = $userPseudo . $id;
    return $return;
}

function addCarousel($carouselId, $userId)
{
    global $conn;
    $query = "INSERT INTO `carousels` (`id`, `userId`, `title`, `description`, `list`) VALUES ('" . $carouselId . "', '" . $userId . "', 'New Carousel', 'Description of the new display', '{}');";
    $res = mysqli_query($conn, $query);
    if ($res) {
        return "Ok! The carousel has been added";
    } else {
        return "An error has occurred";
    }
}

function getCarousels($userId)
{
    global $conn;
    $query = "SELECT id, title, description FROM carousels WHERE userId = $userId";
    $res = mysqli_query($conn, $query);
    $carouselsList = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $carouselsList[$row['id']]['id'] = $row['id'];
        $carouselsList[$row['id']]['title'] = $row['title'];
        $carouselsList[$row['id']]['description'] = $row['description'];
    }
    return $carouselsList;
}

function getCarousel($carouselId, $userId, $perms)
{
    global $conn;
    $query = "SELECT * FROM carousels WHERE id = '$carouselId'";
    if ($perms !== 'admin') {
        $query .= " AND userId = $userId";
    }
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($res);
    $return = array();
    $return['id'] = $row['id'];
    $return['userId'] = $row['userId'];
    $return['title'] = $row['title'];
    $return['description'] = $row['description'];
    $return['list'] = json_decode($row['list'], true);
    return $return;
}

function updateTitle($carouselId, $title)
{
    global $conn;
    $query = "UPDATE `carousels` SET `title` = '" . $title . "' WHERE `id` = '" . $carouselId . "'";
    $res = mysqli_query($conn, $query);
    if ($res) {
        return "Ok! The title has been updated";
    } else {
        return "An error has occurred";
    }
}

function updateDescription($carouselId, $description)
{
    global $conn;
    $query = "UPDATE `carousels` SET `description` = '" . $description . "' WHERE `id` = '" . $carouselId . "'";
    $res = mysqli_query($conn, $query);
    if ($res) {
        return "Ok! The description has been updated";
    } else {
        return "An error has occurred";
    }
}

function addLink($carouselId, $link, $time)
{
    global $conn;
    $query = "SELECT list FROM carousels WHERE id = '$carouselId'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($res);
    $list = json_decode($row['list'], true);
    foreach ($list as $key => $value) {
        if ($value['link'] === $link) {
            return "This link already exists";
        }
    }
    $query = "SELECT list FROM carousels WHERE id = '$carouselId'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($res);
    $list = json_decode($row['list'], true);
    array_push($list, array('link' => $link, 'time' => $time));
    $list = json_encode($list);
    $query = "UPDATE `carousels` SET `list` = '" . $list . "' WHERE `id` = '" . $carouselId . "'";
    $res = mysqli_query($conn, $query);
    if ($res) {
        return "Ok! The link has been added";
    } else {
        return "An error has occurred";
    }
}

function deleteLink($carouselId, $link)
{
    global $conn;
    $query = "SELECT list FROM carousels WHERE id = '$carouselId'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($res);
    $list = json_decode($row['list'], true);
    $newList = array();
    foreach ($list as $key => $value) {
        if ($value['link'] !== $link) {
            array_push($newList, $value);
        }
    }
    $list = json_encode($newList);
    $query = "UPDATE `carousels` SET `list` = '" . $list . "' WHERE `id` = '" . $carouselId . "'";
    $res = mysqli_query($conn, $query);
    if ($res) {
        return "Ok! The link has been deleted";
    } else {
        return "An error has occurred";
    }
}

?>