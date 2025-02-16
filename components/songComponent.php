<?php
function songComponent($id, $title, $userId, $uploadDate) {
    ?>
        <a href="<?php echo SERVER_ROOT . "/song?id=" . $id ?>">
            <div class="basicComponent">
                <h3>#<?php echo $id ?>: <?php echo $title ?></h3>
                <p>Dodał(a): <?php echo User::getName($userId) ?> <br>
                Data dodania: <?php echo $uploadDate ?></p>
            </div>
        </a>
    <?php
}