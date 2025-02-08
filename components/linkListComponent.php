<?php

function linkListComponent($href, $element) {
    ?>
        <a href="<?php echo SERVER_ROOT . $href ?>"><li><?php echo $element ?></li></a>
    <?php
}