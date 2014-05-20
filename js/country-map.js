// Fixed parameters
var width           = 400;
var height          = width;
var duration        = 200;
var delay           = 100;
var circlesRadius   = 5;
var colors   = ["#393b79", "#5254a3", "#6b6ecf", "#9c9ede", "#637939",
            "#8ca252", "#b5cf6b", "#cedb9c", "#8c6d31", "#bd9e39",
            "#e7ba52", "#e7cb94", "#843c39", "#ad494a", "#d6616b",
            "#e7969c", "#7b4173", "#a55194", "#ce6dbd", "#de9ed6",
            "#3182bd", "#6baed6", "#9ecae1", "#c6dbef", "#e6550d",
            "#fd8d3c", "#fdae6b", "#fdd0a2", "#31a354", "#74c476",
            "#a1d99b", "#c7e9c0", "#756bb1", "#9e9ac8", "#bcbddc",
            "#dadaeb", "#636363", "#969696", "#bdbdbd", "#d9d9d9"];

// Creates projection
var projection = d3.geo.mercator()
        .scale(1)
        .translate([0, 0]);

var countryFile = "content/json/country_" + getUrlValue("id") + ".json";
var citiesFile  = "content/json/country_" + getUrlValue("id") + "_cities.json";

d3.json(countryFile, function (error, data) {
    if(error) return console.error(error);

    // Add SVG to the DOM
    var svg = d3.select("#map").append("svg")
                .attr("width", width)
                .attr("height", height);

    var country = svg.append("g");

    // Create path
    var path = d3.geo.path().projection(projection).pointRadius(1);

    // Compute projection parameters and apply them
    var b = path.bounds(data),
        s = .75 / Math.max((b[1][0] - b[0][0]) / width, (b[1][1] - b[0][1]) / height),
        t = [(width - s * (b[1][0] + b[0][0])) / 2, (height - s * (b[1][1] + b[0][1])) / 2];
    projection.scale(s).translate(t);

    // Create a population scale to have nice representation
    var populationScale = d3.scale.pow().range([20, 300]);

    var countryColor = "#FFFFFF";

    var capitalID = 0;

    country.selectAll("path")
        .data(data.features)
        .enter().append("path")
        .attr("d", path)
        .style("fill", function (d) {
            countryColor = colors[parseInt(d.properties['ID']) % 40];
            return countryColor;
        })
        .each(function (d) {
            capitalID = d.properties['capitalID'];
            populationScale.domain([1, d.properties['population']]);
        });


    // Fetch cities data
    d3.json(citiesFile, function (citiesError, citiesData) {
        if(error) return console.error(citiesError);

        cities = svg.append("g");

        // Draw cities
        circles = cities.selectAll("circle")
                .data(citiesData.features)
                .enter().append("circle")
                .attr("cx", function (d) { return projection(d['geometry']['coordinates'])[0]; })
                .attr("cy", function (d) { return projection(d['geometry']['coordinates'])[1]; })
                .attr("r", 0)
                .style({
                    "fill": function (d) {
                        if(d.properties['ID'] == capitalID)
                            return d3.rgb(countryColor).brighter(1);
                        return d3.rgb(countryColor).darker(2);
                    },
                    "stroke": function () { return d3.rgb(countryColor).darker(4);},
                    "stroke-width": "2px",
                    "opacity": "0.9"});

        // Animation
        circles.transition()
            .delay(function (d, i) { return delay * 3 * i })
            .duration(duration)
            .attr("r", circlesRadius);

        circles
                .on("mouseover", function (d) {
                    circle = d3.select(this);

                    // Circle widen
                    circle
                        .transition()
                        .duration(duration * 5)
                        .ease('bounce')
                        .attr("r", function (d) { return populationScale(d.properties['population']); });;

                    // Tooltip appears
                    tooltip = svg.append("text")
                        .attr("x", function () { return circle.attr("cx"); })
                        .attr("y", function () {
                            var cY = parseInt(circle.attr("cy"));
                            var cR = populationScale(d.properties['population']);

                            if((cY + cR + 25) > height)
                                return cY - cR - 5;
                            else
                                return cY + cR + 25;
                        })
                        .style({
                            "cursor"        : "default",
                            "text-anchor"   : "middle",
                            "font-size"     : "25px",
                            "font-weight"   : "bold",
                            "stroke"        : function () { return d3.rgb(countryColor).darker(3); },
                            "stroke-width"  : "0.5px",
                            "fill"          : function () { return d3.rgb(countryColor).brighter(2.5); },
                        })
                        .text(function () { return d.properties['name']; })

                })
                .on("mouseout", function () {
                    circle = d3.select(this);

                    // Circle shrunken
                    circle
                        .transition()
                        .duration(duration * 5)
                        .delay(delay)
                        .ease('bounce')
                        .attr("r", circlesRadius);

                    // Tooltip disappears
                    svg.selectAll("text").transition()
                        .delay(delay)
                        .remove();
                });


        // cities.selectAll("text")
        //     .data(citiesData.features)
        //     .enter()
        //     .append("text")
        //     .text(function (d) {
        //         return d.properties['name'];
        //     })
        //     .attr("x", function (d) {
        //         return path.centroid(d)[0];
        //     })
        //     .attr("y", function (d) {
        //         return  path.centroid(d)[1];
        //     })
        //     .style({
        //         "text-anchor"   : "middle",
        //         "font-size"     : "25px",
        //         "font-weight"   : "bold",
        //         "fill"          : function () { return d3.rgb(countryColor).brighter(2.5); },
        //         "stroke"        : function () { return d3.rgb(countryColor).darker(3); },
        //         "stroke-width"  : "1px",
        //         "cursor"        : "none",
        //         "opacity"       : "0"
        //     })
        //     .on("mouseover", function (d) {
        //         d3.select(this).transition()
        //             .duration(duration)
        //             .style("opacity", "1");
        //     })
        //     .on("mouseout", function (d) {
        //         d3.select(this).transition()
        //             .duration(duration)
        //             .style("opacity", "0");
        //     });
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