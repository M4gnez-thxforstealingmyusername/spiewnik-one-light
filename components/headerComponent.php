<?php

function headerComponent($title) {
    ?>
    <html lang="pl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="theme.css">
            <link rel="stylesheet" href="style.css">
            <title><?php echo $title ?></title>
        </head>
        <body>

        <nav>
            <a href="<?php echo SERVER_ROOT ?>/"><div class="navElement">Strona główna</div></a>
            <a href="<?php echo SERVER_ROOT ?>/songs"><div class="navElement">Pieśni</div></a>
            <a href="<?php echo SERVER_ROOT ?>/presentations"><div class="navElement">Prezentacje</div></a>

            <?php if(User::ping()) { ?>

            <a href="<?php echo SERVER_ROOT ?>/user/me.php"><div class="navElement">Konto</div></a>
            <a href="<?php echo SERVER_ROOT ?>/logout.php"><div class="navElement">Wyloguj</div></a>

            <?php } else { ?>

            <a href="<?php echo SERVER_ROOT ?>/register"><div class="navElement">Utwórz konto</div></a>
            <a href="<?php echo SERVER_ROOT ?>/login"><div class="navElement">Zaloguj się</div></a>

            <?php } ?>

        </nav>
    <?php
}