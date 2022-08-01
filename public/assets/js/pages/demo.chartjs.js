function hexToRGB(a, r) {
    var t = parseInt(a.slice(1, 3), 16), e = parseInt(a.slice(3, 5), 16), o = parseInt(a.slice(5, 7), 16);
    return r ? "rgba(" + t + ", " + e + ", " + o + ", " + r + ")" : "rgb(" + t + ", " + e + ", " + o + ")"
}

!function (d) {
    "use strict";

    function a() {
        this.$body = d("body"), this.charts = []
    }

    a.prototype.respChart = function (r, t, e, o) {
        var n = Chart.controllers.line.prototype.draw;
        Chart.controllers.line.prototype.draw = function () {
            n.apply(this, arguments);
            var a = this.chart.chart.ctx, r = a.stroke;
            a.stroke = function () {
                a.save(), a.shadowColor = "rgba(0,0,0,0.01)", a.shadowBlur = 20, a.shadowOffsetX = 0, a.shadowOffsetY = 5, r.apply(this, arguments), a.restore()
            }
        };
        var s = Chart.controllers.doughnut.prototype.draw;
        Chart.controllers.doughnut = Chart.controllers.doughnut.extend({
            draw: function () {
                s.apply(this, arguments);
                var a = this.chart.chart.ctx, r = a.fill;
                a.fill = function () {
                    a.save(), a.shadowColor = "rgba(0,0,0,0.03)", a.shadowBlur = 4, a.shadowOffsetX = 0, a.shadowOffsetY = 3, r.apply(this, arguments), a.restore()
                }
            }
        });
        var l = Chart.controllers.bar.prototype.draw;
        Chart.controllers.bar = Chart.controllers.bar.extend({
            draw: function () {
                l.apply(this, arguments);
                var a = this.chart.chart.ctx, r = a.fill;
                a.fill = function () {
                    a.save(), a.shadowColor = "rgba(0,0,0,0.01)", a.shadowBlur = 20, a.shadowOffsetX = 4, a.shadowOffsetY = 5, r.apply(this, arguments), a.restore()
                }
            }
        }), Chart.defaults.global.defaultFontColor = "#8391a2", Chart.defaults.scale.gridLines.color = "#8391a2";
        var i = r.get(0).getContext("2d"), c = d(r).parent();
        return function () {
            var a;
            switch (r.attr("width", d(c).width()), t) {
                case"Bar":
                    a = new Chart(i, {type: "bar", data: e, options: o});
                    break;
            }
            return a
        }()
    }, a.prototype.initCharts = function () {
        var a, r, t, e, o, n, s, l = [], i = ["#727cf5", "#0acf97", "#fa5c7c", "#ffbc00"], x,y = null;
        return 0 < d("#bar-chart-example").length && (n = (o = d("#bar-chart-example").data("colors")) ? o.split(",") : i.concat(), (r = document.getElementById("bar-chart-example").getContext("2d").createLinearGradient(0, 500, 0, 150)).addColorStop(0, n[0]), r.addColorStop(1, n[1]),
            y = function () {
                var item = null;
                $.ajax({
                    type: "GET",
                    async: false,
                    url: SITEURL + "/bar-chart",
                    // data: {start: moment(new Date()).format("YYYY-MM-DD"), end: moment(new Date(new Date().setDate(new Date().getDate() + 30))).format("YYYY-MM-DD")},
                    // data: {start: moment(new Date(new Date().setDate(new Date().getDate() - 30))).format("YYYY-MM-DD"), end: moment(new Date(new Date().setDate(new Date().getDate() + 30))).format("YYYY-MM-DD")},
                    dataType: "json",
                    success: function (res) {
                        item = res;
                    }
                });
                return item;
            }(),

            t = {

            labels: y.labels,
            datasets: [{
                label: "Total",
                backgroundColor: r,
                borderColor: r,
                hoverBackgroundColor: r,
                hoverBorderColor: r,
                data: y.sent
            }, {
                label: "Accepted",
                backgroundColor: "#e3eaef",
                borderColor: "#e3eaef",
                hoverBackgroundColor: "#e3eaef",
                hoverBorderColor: "#e3eaef",
                data: y.close
            }]
        }, l.push(this.respChart(d("#bar-chart-example"), "Bar", t, {
            maintainAspectRatio: !1,
            legend: {display: 1},
            scales: {
                yAxes: [{gridLines: {display: 1, color: "rgba(0,0,0,0.05)"}, stacked: !1, ticks: {beginAtZero: true,stepSize: 10000}}],
                xAxes: [{
                    barPercentage: .7,
                    categoryPercentage: .8,
                    stacked: !1,
                    gridLines: {color: "rgba(0,0,0,0.01)"}
                }]
            }
        }))), /*0 < d("#donut-chart-example").length && (
            x = function () {
                var item = null;
                $.ajax({
                    type: "GET",
                    async: false,
                    url: SITEURL + "/donut-chart",
                    // data: {start: moment(new Date()).format("YYYY-MM-DD"), end: moment(new Date(new Date().setDate(new Date().getDate() + 30))).format("YYYY-MM-DD")},
                    // data: {start: moment(new Date(new Date().setDate(new Date().getDate() - 30))).format("YYYY-MM-DD"), end: moment(new Date(new Date().setDate(new Date().getDate() + 30))).format("YYYY-MM-DD")},
                    dataType: "json",
                    success: function (res) {
                        item = res;
                    }
                });
                return item;
            }(),
                e = {
                    labels: x.labels,
                    datasets: [{
                        label: 'My First Dataset',
                        data: x.data,
                        backgroundColor: n = (o = d("#donut-chart-example").data("colors")) ? o.split(",") : i.concat(),
                        borderColor: "transparent",
                        borderWidth: "3"
                    }]
                }, l.push(this.respChart(d("#donut-chart-example"), "Doughnut", e, {
                maintainAspectRatio: !1,
                cutoutPercentage: 60,
                legend: {display: 1}
            }))),*/  l
    }, a.prototype.init = function () {
        var r = this;
        Chart.defaults.global.defaultFontFamily = '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif', r.charts = this.initCharts(), d(window).on("resize", function (a) {
            d.each(r.charts, function (a, r) {
                try {
                    r.destroy()
                } catch (a) {
                }
            }), r.charts = r.initCharts()
        })
    }, d.ChartJs = new a, d.ChartJs.Constructor = a
}(window.jQuery), function () {
    "use strict";
    window.jQuery.ChartJs.init()
}();
