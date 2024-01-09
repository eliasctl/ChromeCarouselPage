<?php
require('config.php');

$return = "Processing error!";

if(empty($_POST['action'])) {
    echo $return;
    exit();
}
$action = filter_var($_POST['action'], FILTER_SANITIZE_STRING);

if (isset($_POST['userId'])) $userId = filter_var($_POST['userId'], FILTER_SANITIZE_NUMBER_INT);

if (isset($_POST['nbValue'])) $nbValue = filter_var($_POST['nbValue'], FILTER_SANITIZE_NUMBER_INT);

if (isset($_POST['pwd'])) $pwd = filter_var($_POST['pwd'], FILTER_SANITIZE_STRING);

if (isset($_POST['title'])) $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);

if (isset($_POST['carouselId'])) $carouselId = filter_var($_POST['carouselId'], FILTER_SANITIZE_STRING);

if (isset($_POST['description'])) $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

if (isset($_POST['link']))
{
    $link = filter_var($_POST['link'], FILTER_SANITIZE_STRING);
    $link = urldecode($link);
}

if (isset($_POST['time'])) $time = filter_var($_POST['time'], FILTER_SANITIZE_NUMBER_INT);

switch ($action) {
    case 'deleteUser':
        if (!empty($userId) && $_SESSION["perms"] === 'admin') {
            $return = deleteUser($userId);
        }
        echo $return;
        break;
    case 'updatePermitCarousels':
        if (!empty($userId) && $_SESSION["perms"] === 'admin') {
            $return = updatePermitCarousels($userId, $nbValue);
        }
        echo $return;
        break;
    case 'updatePermitMedias':
        if (!empty($userId) && $_SESSION["perms"] === 'admin') {
            $return = updatePermitMedias($userId, $nbValue);
        }
        echo $return;
        break;
    case 'changePwd':
        if (!empty($userId) && $_SESSION["perms"] === 'admin') {
            $return = changePwd($userId, $nbValue);
        }
        echo $return;
        break;
    case 'updateTitle':
        if (!empty($userId) && !empty($title) && !empty($carouselId) && ($_SESSION["perms"] === 'admin' || $_SESSION["id"] === $userId)) {
            $return = updateTitle($carouselId, $title);
        }
        echo $return;
        break;
    case 'updateDescription':
        if (!empty($userId) && !empty($description) && !empty($carouselId) && ($_SESSION["perms"] === 'admin' || $_SESSION["id"] === $userId)) {
            $return = updateDescription($carouselId, $description);
        }
        echo $return;
        break;
    case 'addLink':
        if (!empty($userId) && !empty($link) && !empty($time) && !empty($carouselId) && ($_SESSION["perms"] === 'admin' || $_SESSION["id"] === $userId)) {
            $return = addLink($carouselId, $link, $time);
        }

        echo $return;
        break;
    case 'deleteLink':
        if (!empty($userId) && !empty($carouselId) && ($_SESSION["perms"] === 'admin' || $_SESSION["id"] === $userId)) {
            $return = deleteLink($carouselId, $link);
        }
        echo $return;
        break;
}
?>