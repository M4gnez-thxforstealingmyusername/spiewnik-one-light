<?php
function presentationComponent($id, $title, $userId, $songs, $uploadDate) {
    ?>
        <a href="<?php echo SERVER_ROOT . "/presentation?id=" . $id ?>">
            <div class="basicComponent presentationComponent">
                <h3>#<?php echo $id ?>: <?php echo $title ?></h3>
                <p>Dodał(a): <?php echo User::getName($userId) ?> <br>
                Data dodania: <?php echo $uploadDate ?></p>
                <div class="songPreview">
                    <h3>Pieśni: </h3>
                    <ol>
                        <?php
                        foreach(Song::getList($songs) as $song) {
                            ?>
                                <li><?php echo $song["title"] ?> (<?php echo $song["id"] ?>)</li>
                            <?php
                        }
                        ?>
                    </ol>
                </div>
            </div>
        </a>
    <?php
}