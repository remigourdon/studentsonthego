<?php

/**
 * This file implements convenient methods for conversion of JSON.
 */

function json_to_wkt($json) {
    $coordinates = json_decode($json)->{"features"}[0]->{"geometry"}->{"coordinates"};

    $wkt = "MULTIPOLYGON(";

    foreach ($coordinates as $polygon) {
        $wkt .= "((";

        // We take only the first linear ring
        foreach ($polygon[0] as $point) {
            $wkt .= "$point[0] $point[1],";
        }

        $wkt = substr($wkt, 0, -1) . ")),";
    }

    return substr($wkt, 0, -1) . ")";
}

?>