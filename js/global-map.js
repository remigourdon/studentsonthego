// Fixed parameters
width    = 800;
height   = width;
colors   = d3.scale.category20b();
duration = 200;
style    = "position: absolute;\
            text-align: center;\
            width: 220px;\
            height: 35px;\
            padding: 8px;\
            font: 14px sans-serif;\
            font-weight: bold;\
            color: black;\
            background: #ddd;\
            border: solid 1px #aaa;\
            border-radius: 2px;\
            pointer-events: none;";

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

    // Creates tooltip
    tooltip = d3.select("#map").append("div")
                    .style("opacity", 0)
                    .attr("style", style);

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
        .on("mouseover", function (d) {
            // Brighten the color
            d3.select(this).transition()
                .duration(duration)
                .style("fill", function () {
                    return d3.rgb(colors(d.properties['ID'] % 20)).brighter(0.3);
                });

            showTooltip(d.properties['name']);
        })
        .on("mousemove", moveTooltip)
        .on("mouseout", function (d) {
            // Put the color back in its default state
            d3.select(this).transition()
                .duration(duration)
                .style("fill", function () {
                    return colors(d.properties['ID'] % 20);
                });

            hideTooltip();
        })
        .on("click", function (d) {
            // Redirect to the country page
            document.location.href = "country.php?id=" + d.properties['ID'];
        });


    function showTooltip(name) {
        tooltip
            .text(name)
            .transition()
            .duration(duration)
            .style("opacity", "0.9");
    }

    function moveTooltip() {
        tooltip
            .style({
                "left"  : (d3.event.pageX - 220) + "px",
                "top"   : (d3.event.pageY - 35) + "px"});
    }

    function hideTooltip() {
        tooltip
            .transition()
            .duration(duration)
            .style("opacity", "0");
    }

})