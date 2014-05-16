var width   = 700,
    height  = 480;

var projection = d3.geo.mercator();

var path = d3.geo.path().projection(projection);

d3.json("content/data.json", function (error, data) {
    if (error) return console.error(error);

    var svg = d3.select("#map").append("svg")
                                .attr("width", width)
                                .attr("height", height);

    projection.scale(1).translate([0, 0])

    var b = path.bounds(data),
        s = .95 / Math.max((b[1][0] - b[0][0]) / width, (b[1][1] - b[0][1]) / height),
        t = [(width - s * (b[1][0] + b[0][0])) / 2, (height - s * (b[1][1] + b[0][1])) / 2];

    projection.scale(s).translate(t);

    svg.selectAll("path")
        .data(data.features)
      .enter().append("path")
        .attr("d", path);

});