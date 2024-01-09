<?php
$page = 'home';
include_once('config.php');
include_once('nav.php');

?>

<html lang="en">

<head>
    <style>
        :root {
            --primaire: #F9DBBB;
            --secondaire: #4E6E81;
            --bordure: #2E3840;
        }

        body {
            background-color: var(--primaire);
            color: black;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-gap: 10px;
            grid-auto-rows: minmax(100px, auto);
        }

        .grid-item {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: var(--secondaire);
            border: 2px solid var(--bordure);
            padding: 15px;
            font-size: 30px;
            border-radius: 10px;
            width: 90%;
            margin-left: 5%;
        }

        .title {
            background-color: var(--secondaire);
            border: 5px solid var(--bordure);
            border-radius: 10px;
            padding: 10px;
            width: 50%;
            font-size: 50px;
            font-family: 'Courier New', Courier, monospace;
        }

        a {
            filter: grayscale(1);
        }

        .a:hover {
            filter: grayscale(0);
        }

        /* css de la bare de recherche */
        input[type=text] {
            width: 50%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 2px solid var(--bordure);
            border-radius: 4px;
        }


        input[type=submit] {
            width: 10%;
            background-color: var(--secondaire);
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: var(--bordure);
        }

        button {
            width: 10%;
            background-color: var(--secondaire);
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: var(--bordure);
        }

        /* pour le redimentionnement de la page */

        @media (max-width: 1375px) {
            .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1000px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .title {
                width: 90%;
            }
        }

        @media (max-width: 500px) {
            .grid {
                grid-template-columns: repeat(1, 1fr);
            }
        }
    </style>
</head>

<body>
    <center>
        <h1 class="title">
            Chrome Carousel Page
        </h1>

    </center>
    <div class="grid">
        <div class="grid-item">
            <h3>Go from page to page like a pro</h3>
            <p>The Google Chrome Carousel Page extension allows you to easily create a list of pages that will be automatically displayed</p>
        </div>
        <div class="grid-item">
            <h3>Import your images</h3>
            <p>Using our management panel you can import your own images and even choose to post YouTube videos</p>
        </div>
        <div class="grid-item">
            <h3>It's Easy</h3>
            <p>Our extension is very easy to use, you can create your carousel in just a few clicks</p>
        </div>
        <div class="grid-item">
            <h3>It's Free</h3>
            <p>Our extension is completely free and will remain so</p>
        </div>
        <div class="grid-item">
            <h3>It's Open Source</h3>
            <p>Our extension is open source, you can find the source code on our GitHub</p>
        </div>
    </div>

</body>

</html>