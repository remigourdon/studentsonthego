/**
 * Dynamic form to calculate an estimation of the price of the stay.
 *
 * Display the result both as text and pie chart.
 */

$(function () {

    // Fixed parameters
    var width = $("#map").width(),
        aspect = 800 / 800,
        height = width * aspect
        radius = Math.min(width, height) / 2;

    //
    // Prepare the graph
    //

    // Add SVG to the DOM
    var svg = d3.select("#resultGraph").append("svg")
                .attr("width", width)
                .attr("height", height)
                .attr("viewBox", "0 0 " + width + " " + height)
                .attr("preserveAspectRatio", "xMidYMid");

    // Dynamic resizingt of the svg
    $(window).resize(function() {
      var width = $("#map").width();
      svg.attr("width", width);
      svg.attr("height", width * aspect);
    });

    var color = d3.scale.ordinal()
        .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

    var arc = d3.svg.arc()
        .outerRadius(radius - 10)
        .innerRadius(20);

    var pie = d3.layout.pie()
        .sort(null)
        .value(function(d) { return d.value; });



    // Get json data
    var file = "content/json/country_" + getUrlValue("id") + ".json";

    $.getJSON(file, function (json) {

        prices = json['features'][0]['properties']['prices'];

        $('#nbCinema').change(compute);
        $('#nbFastfood').change(compute);
        $('#nbBeer').change(compute);
        $('#gymYes').change(compute);
        $('#gymNo').change(compute);
        $('#transportYes').change(compute);
        $('#transportNo').change(compute);
        $('#internetYes').change(compute);
        $('#internetNo').change(compute);
        $('#durationStay').change(compute);

        function compute() {
            var inputs = {
                "nbCinema"  : parseInt($('#nbCinema').val()),
                "nbFastfood": parseInt($('#nbFastfood').val()),
                "nbBeer"    : parseInt($('#nbBeer').val()),
                "fitness"   : $('#gymYes').prop("checked") ? 1 : 0,
                "transport" : $('#transportYes').prop("checked") ? 1 : 0,
                "internet"  : $('#internetYes').prop("checked") ? 1 : 0,
                "duration"  : parseFloat($('#durationStay').val())
            };

            var categories = [
                {
                    "label":    "Food",
                    "value":    inputs["nbFastfood"]  * parseFloat(prices['fastfood']) +
                                inputs["nbBeer"]      * parseFloat(prices['beer'])
                },
                {
                    "label":    "Entertainment",
                    "value":    inputs["nbCinema"]    * parseFloat(prices['cinema']) +
                                inputs["fitness"]     * parseFloat(prices['fitness'])
                },
                {
                    "label":    "Housing",
                    "value":    parseFloat(prices['rent'])
                },
                {
                    "label":    "Various",
                    "value":    inputs["internet"]    * parseFloat(prices['internet'])
                }
            ];

            var result = 0;

            categories.forEach(function(d) { result += d.value; });

            result = !isNaN(result * inputs['duration']) ? result * inputs['duration'] : 0;

            result = Math.round(result).toFixed(2);

            // console.log(prices);
            // console.log(categories);
            // console.log(result);

            $('#result').text("Total: " + result + "$");

            plot(categories);
        }

        function plot(categories) {
            var g = svg.selectAll(".arc")
                .data(pie(categories))
                .enter().append("g")
                .attr("class", "arc");

            g.append("path")
              .attr("d", arc)
              .style("fill", function(d, i) { return color(i); });
        }

    });
});

function getUrlValue(s){
    var searchString = window.location.search.substring(1);
    var array = searchString.split('&');
    for(var i = 0; i < array.length; i++){
        var keyValue = array[i].split('=');
        if(keyValue[0] == s){
            return keyValue[1];
        }
    }
}