// Fixed parameters
width    = 800;
height   = width;
colors   = d3.scale.category20b();

// Creates projection
var projection = d3.geo.mercator()
        .scale(1)
        .translate([0, 0]);

d3.json("content/data.json", function (error, data) {
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
        .style("fill", function (d) { return colors(d.properties['ID'] % 20); })
});