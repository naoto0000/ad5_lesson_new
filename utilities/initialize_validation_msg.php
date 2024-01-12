<?php

function initializeValidationErrors($key) {
    $_SESSION['validation_errors'][$key] = "";
}
