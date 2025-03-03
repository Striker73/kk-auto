<?php
function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

function formatDateForMySQL($date) {
    if (strpos($date, '/') !== false) {
        $parts = explode('/', $date);
        return "{$parts[2]}-{$parts[1]}-{$parts[0]}";
    }
    return $date;
}
?> 