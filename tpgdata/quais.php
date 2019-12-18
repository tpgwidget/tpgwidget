<?php
function qprint($quai) {
    echo '<div class="quai">'.htmlentities($quai).'</div>';
}

/**
 * @deprecated
 */
function quai($stopCode, $departureLine, $departureDestination) {}
