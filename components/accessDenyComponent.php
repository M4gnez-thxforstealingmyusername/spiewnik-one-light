<?php

function accessDeniedComponent ($reason) {
    ?>
        <h1>Brak dostępu</h1>
        <p>Powód: <?php echo $reason ?></p>
    <?php
}