<?php

/**
 * エスケープ
 *
 * @param string $value
 * @return void
 */
function e(?string $value): void
{
    if ($value === null) {
        echo null;
    }
    echo htmlspecialchars($value, ENT_QUOTES);
}