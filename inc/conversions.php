<?php

/**
 * This file implements convenient methods for conversion of JSON.
 */

function json_to_wkt($json) {
    $geometry = json_decode($json)->{"features"}[0]->{"geometry"};
    $coordinates = $geometry->{"coordinates"};
    $wkt = "";

    if($geometry->{"type"} == "Polygon") {
        $wkt = "POLYGON(";
        foreach ($coordinates as $linearRing) {
            $wkt .= "(";
            foreach($linearRing as $point) {
                $wkt .= $point[0] . " " . $point[1] . ",";
            }
            $wkt = substr($wkt, 0, -1) . "),";
        }
    } else if($geometry->{"type"} == "MultiPolygon") {
        $wkt = "MULTIPOLYGON(";
        foreach ($coordinates as $polygon) {
            $wkt .= "(";
            foreach ($polygon as $linearRing) {
                $wkt .= "(";
                foreach ($linearRing as $point) {
                    $wkt .= $point[0] . " " . $point[1] . ",";
                }
                $wkt = substr($wkt, 0, -1) . "),";
            }
            $wkt = substr($wkt, 0, -1) . "),";
        }
    }

    return substr($wkt, 0, -1) . ")";
}

?>