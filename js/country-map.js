// Fixed parameters
width    = 400;
height   = width;
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

file = "content/json/country_" + getUrlValue("id") + ".json"

d3.json(file, function (error, data) {
    if(error) return console.error(error);

    // Add SVG to the DOM
    var svg = d3.select("#map").append("svg")
                .attr("width", width)
                .attr("height", height);

    // Create path
    var path = d3.geo.path().projection(projection);

    // Compute projection parameters and apply them
    var b = path.bounds(data),
        s = .95 / Math.max((b[1][0] - b[0][0]) / width, (b[1][1] - b[0][1]) / height),
        t = [(width - s * (b[1][0] + b[0][0])) / 2, (height - s * (b[1][1] + b[0][1])) / 2];
    projection.scale(s).translate(t);

    svg.selectAll("path")
        .data(data.features)
        .enter().append("path")
        .attr("d", path)
        .style("fill", function (d) { return colors[parseInt(d.properties['ID']) % 40]; });
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