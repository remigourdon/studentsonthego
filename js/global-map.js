// Fixed parameters
width    = 800;
height   = width;
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

d3.json("content/json/country_global.json", function (error, data) {
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
        .style("fill", function (d) { return colors[parseInt(d.properties['ID']) % 40]; })
        .on("mouseover", function (d) {
            // Brighten the color
            d3.select(this).transition()
                .duration(duration)
                .style("fill", function () {
                    return d3.rgb(colors[parseInt(d.properties['ID']) % 40]).brighter(0.3);
                });

            showTooltip(d.properties['name']);
        })
        .on("mousemove", moveTooltip)
        .on("mouseout", function (d) {
            // Put the color back in its default state
            d3.select(this).transition()
                .duration(duration)
                .style("fill", function () {
                    return colors[parseInt(d.properties['ID']) % 40];
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