/**
 * Dynamic form to calculate an estimation of the price of the stay.
 *
 * Display the result both as text and pie chart.
 */

$(function () {

    // Fixed parameters
    var width   = $("#resultGraph").width(),
        aspect  = 800 / 800,
        height  = width * aspect,
        duration        = 400,
        delay           = 10,
        radius  = Math.min(width, height) / 2 - 20,
        color   = d3.scale.ordinal()
                    .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b",
                            "#a05d56", "#d0743c", "#ff8c00"]);

    //
    // Prepare the graph
    //

    // Add SVG to the DOM
    var svg = d3.select("#resultGraph").append("svg")
                .attr("width", width)
                .attr("height", height)
                .attr("viewBox", "0 0 " + width + " " + height)
                .attr("preserveAspectRatio", "xMidYMid")
                .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    // Dynamic resizingt of the svg
    $(window).resize(function() {
      var width = $("#resultGraph").width();
      svg.attr("width", width);
      svg.attr("height", width * aspect);
    });

    var arc = d3.svg.arc()
        .outerRadius(radius)
        .innerRadius(radius * 0.55);

    var pie = d3.layout.pie()
        .sort(null)
        .value(function(d) { return d.value; });


    // Get json data
    var file = "content/json/country_" + getUrlValue("id") + ".json";

    $.getJSON(file, function (json) {

        prices = json['features'][0]['properties']['prices'];

        d3.selectAll("input").on("change", compute);

        compute();

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
                                inputs["nbBeer"]      * parseFloat(prices['beer']) +
                                300 // Food for the month (until finding good data source)
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
                    "value":    inputs["internet"]    * parseFloat(prices['internet']) +
                                inputs["transport"]   * parseFloat(prices['transports'])
                }
            ];

            var result = 0;

            categories.forEach(function(d) { result += d.value; });

            result = !isNaN(result * inputs['duration']) ? result * inputs['duration'] : 0;

            result = Math.round(result).toFixed(2);

            $('#result').text("Total: " + result + "$");

            plot(categories);
        }

        function plot(categories) {
            // Remove previous graph
            svg.selectAll(".arc").remove();

            g = svg.selectAll(".arc").data(pie(categories))
                .enter().append("g")
                .attr("class", "arc");

            g.append("path")
                .attr("d", arc)
                .style("fill", function (d, i) { return color(i); })
                .on("mouseover", function (d, i) {
                    d3.select(this).transition()
                        .duration(duration)
                        .style("fill", d3.rgb(color(i)).brighter(0.3));

                    svg.append("text")
                        .attr("y", 15)
                        .style({
                            "text-anchor"   : "middle",
                            "font-size"     : "30px",
                            "font-weight"   : "bold",
                            "fill"          : "white"
                            })
                        .text(function () { return categories[i].label +
                                            "\n" + categories[i].value; });
                })
                .on("mouseout", function(d, i) {
                    d3.select(this).transition()
                        .delay(delay)
                        .duration(duration)
                        .style("fill", color(i));

                    svg.selectAll("text").remove();
                });
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