<?php
/**
 * Parse UserAgent to get browser/os/device
 */

$regexes = require __DIR__ . "/ua_regexes.php";

function ua_parse($ua)
{
    global $regexes;
    if (empty($ua)) {
        return false;
    }

    $result = [];
    foreach (['ua', 'os', 'device'] as $key) {
        foreach ($regexes[$key] as $regex => $replacement) {
            if (preg_match($regex, $ua, $match)) {
                array_shift($match);
                $result[$key] = trim(str_replace(['$1', '$2', '$3', '$4'], $match, $replacement), ',');
                continue 2;
            }
        }
        $result[$key] = 'Unknown';
    }
    return $result;
}

