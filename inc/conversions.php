<?php

/**
 * Implements convenient methods for conversion of JSON and WKT.
 */

/**
 * Converts geojson into wkt (polygon or multipolygon).
 * @param  string $json the geojson data
 * @return string       the wkt data
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

/**
 * Converts wkt into geojson with properties.
 * @param  array  $data the data as an array (wkt => properties[])
 * @return string       the geojson data
 */
function wkt_to_json($data) {

    $result = array("type" => "FeatureCollection");
    $features = array();

    foreach ($data as $wkt => $properties) {

        // Split wkt in polygons
        preg_match_all("/\(\(([0-9\.\s,-]*)\)\)/", $wkt, $polygons);
        $polygons = $polygons[1];

        foreach ($polygons as $polygon) {

            $coordinates = array();

            // Split wkt in points
            preg_match_all("/([0-9\.-]*\s[0-9\.-]*)/", $polygon, $points);
            $points = $points[1];

            $ringJSON = array(); // We consider each polygon made of one linear string

            foreach ($points as $point) {

                // Split wkt in latitude and longitude
                preg_match_all("/([0-9\.-]+)/", $point, $coord);
                $coord = $coord[1];

                // Convert to integer
                $coord[0] = (float) $coord[0];
                $coord[1] = (float) $coord[1];

                $ringJSON = array_merge($ringJSON, array($coord));

            }

            $polyJSON = [$ringJSON];

            if(count($polygons) > 1) {

                $coordinates = array_merge($coordinates, array($polyJSON));

            } else {

                $coordinates = array_merge($coordinates, $polyJSON);

            }


        }

        if(count($polygons) > 1) {

            $geometry = array(
                "type"          => "MultiPolygon",
                "coordinates"   => $coordinates);

        } else {

            $geometry = array(
                "type"          => "Polygon",
                "coordinates"   => $coordinates);

        }

        $feature = array(
            "type"          => "Feature",
            "properties"    => $properties,
            "geometry"      => $geometry);

        $features = array_merge($features, array($feature));

    }

    $result = array_merge($result, array("features" => $features));

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