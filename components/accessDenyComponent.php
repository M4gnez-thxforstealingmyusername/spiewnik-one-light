<?php

function accessDeniedComponent ($reason) {
    ?>
    <div class="details">
        <h1>Brak dostępu</h1>
        <p>Powód: <?php echo $reason ?></p>
    </div>
    <?php
}