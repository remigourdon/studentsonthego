<?php

/**
 * Implements convenient methods for conversion of JSON and WKT.
 */

/**
 * Converts geojson into wkt (polygon or multipolygon).
 *
 * We consider only the externar linear rings.
 *
 * @param  string $json the geojson data
 * @return string       the wkt data
 */
function json_to_wkt($json) {

    $geometry = json_decode($json)->{"features"}[0]->{"geometry"};
    $coordinates = $geometry->{"coordinates"};
    $wkt = "";

    if($geometry->{"type"} == "Polygon") {

        $wkt = "POLYGON((";

        $extRing = $coordinates[0]; // Consider only the external ring

        foreach ($extRing as $point) {

            $wkt .= $point[0] . " " . $point[1] . ",";

        }

        $wkt = substr($wkt, 0, -1) . "),";

    } else if($geometry->{"type"} == "MultiPolygon") {

        $wkt = "MULTIPOLYGON(";
        foreach ($coordinates as $polygon) {

            $extRing = $polygon[0]; // Consider only the external ring

            $wkt .= "((";

            foreach ($extRing as $point) {

                $wkt .= $point[0] . " " . $point[1] . ",";

            }

            $wkt = substr($wkt, 0, -1) . ")),";

        }

    }

    return substr($wkt, 0, -1) . ")";
}

/**
 * Converts wkt into geojson with properties.
 * @param  array  $data the data as an array (wkt => properties[])
 * @return string       the geojson data
 */
function wkt_to_json($data) {

    $features = array();

    // Go through each country
    foreach ($data as $wkt => $properties) {

        if(strpos($wkt, "POINT") !== FALSE) { // If it is a point

            $type           = "Point";
            $coordinates    = point_to_coord($wkt);

            // Convert the coordinates to floats
            $coordinates[0] = (float) $coordinates[0];
            $coordinates[1] = (float) $coordinates[1];


        } else {

                // Get polygons from wkt
            preg_match_all("/\(\(([0-9\.\s,-]*)\)\)/", $wkt, $matches);
            $mPolygons = $matches[1];

            // Prepare coordinates array for JSON
            $coordinates = array();

            // Go through each polygon
            foreach ($mPolygons as $mPolygon) {

                // Get all points from the polygon
                preg_match_all("/([0-9\.-]*\s[0-9\.-]*)/", $mPolygon, $matches);
                $mPoints = $matches[1];

                // Prepare linear ring array for JSON (we consider only one)
                $linearRing = array();

                // Go through each point
                foreach ($mPoints as $mPoint) {

                    // Get the coordinates in an array [latitude, longitude]
                    $coord = explode(" ", $mPoint);

                    // Convert the coordinates to floats
                    $coord[0] = (float) $coord[0];
                    $coord[1] = (float) $coord[1];

                    // Push new coordinates into the linear ring
                    $linearRing = array_merge($linearRing, array($coord));

                }

                // Prepare polygon array for JSON (we consider one polygon == one linear ring)
                $polygon = array($linearRing);

                // Push new polygon into the coordinates
                if(count($mPolygons) > 1)
                    $coordinates = array_merge($coordinates, array($polygon));
                else
                    $coordinates = $polygon;

            }

            $type = (count($mPolygons) > 1) ? "MultiPolygon" : "Polygon";

        }


        // Prepare the geometry associative array for JSON
        $geometry = array("type" => $type, "coordinates" => $coordinates);

        // Prepare the feature associative array for JSON
        $feature = array(
            "type"          => "Feature",
            "properties"    => $properties,
            "geometry"      => $geometry);

        // Push new feature into the features array
        $features = array_merge($features, array($feature));

    }

    $result = array(
                    "type"      => "FeatureCollection",
                    "features"  => $features);

    return json_encode($result);

}

/**
 * Converts a wkt POINT into latitude and longitude.
 * @param  string $wkt the wkt data
 * @return array       the array containing latitude and longitude
 */
function point_to_coord($wkt) {

    $coord = explode("(", $wkt)[1];
    $coord = explode(")", $coord)[0];

    return explode(" ", $coord);

}

?>