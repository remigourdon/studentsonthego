// Fixed parameters
width    = 400;
height   = width;
duration = 200;
colors   = ["#393b79", "#5254a3", "#6b6ecf", "#9c9ede", "#637939",
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

countryFile = "content/json/country_" + getUrlValue("id") + ".json";
citiesFile  = "content/json/country_" + getUrlValue("id") + "_cities.json";

d3.json(countryFile, function (error, data) {
    if(error) return console.error(error);

    // Add SVG to the DOM
    var svg = d3.select("#map").append("svg")
                .attr("width", width)
                .attr("height", height);

    var countries = svg.append("g");

    // Create path
    var path = d3.geo.path().projection(projection).pointRadius(1);

    // Compute projection parameters and apply them
    var b = path.bounds(data),
        s = .85 / Math.max((b[1][0] - b[0][0]) / width, (b[1][1] - b[0][1]) / height),
        t = [(width - s * (b[1][0] + b[0][0])) / 2, (height - s * (b[1][1] + b[0][1])) / 2];
    projection.scale(s).translate(t);

    // Create a population scale to have nice representation
    var populationScale = d3.scale.sqrt().range([50, 130]);

    var countryColor = "#FFFFFF";

    countries.selectAll("path")
        .data(data.features)
        .enter().append("path")
        .attr("d", path)
        .style("fill", function (d) {
            countryColor = colors[parseInt(d.properties['ID']) % 40];
            return countryColor;
        })
        .each(function (d) { populationScale.domain([0, d.properties['population']]); });


    // Fetch cities data
    d3.json(citiesFile, function (citiesError, citiesData) {
        if(error) return console.error(citiesError);

        cities = svg.append("g");

        cities.selectAll("path")
            .data(citiesData.features)
            .enter().append("path")
            .attr("d", path)
            .style("stroke", function () { return d3.rgb(countryColor).darker(2.5); })
            .transition()
            .duration(duration * 5)
            .ease('bounce')
            .delay(function (d, i) { return 150 * i; })
            .style("stroke-width", function (d) { return populationScale(d.properties['population']); });

        cities.selectAll("text")
            .data(citiesData.features)
            .enter()
            .append("text")
            .text(function (d) {
                return d.properties['name'];
            })
            .attr("x", function (d) {
                return path.centroid(d)[0];
            })
            .attr("y", function (d) {
                return  path.centroid(d)[1];
            })
            .style({
                "text-anchor"   : "middle",
                "font-size"     : "14pt",
                "font-weight"   : "bold",
                "fill"          : "white",
                "cursor"        : "default",
                "opacity"       : "0"
            })
            .on("mouseover", function (d) {
                d3.select(this).transition()
                    .duration(duration)
                    .style("opacity", "1");
            })
            .on("mouseout", function (d) {
                d3.select(this).transition()
                    .duration(duration)
                    .style("opacity", "0");
            });
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