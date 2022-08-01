var options = {
    series: [],
    // color:["#727cf5","#0acf97","#fa5c7c","#ffbc00"],
    colors:  ["#727cf5", "#0acf97"],
    chart: {
        id :'mychart',
        height: 329,
        type: 'radialBar',
    },
    plotOptions: {
        radialBar: {
            dataLabels: {
                name: {
                    fontSize: '22px',
                },
                value: {
                    fontSize: '16px',
                },
                total: {
                    show: true,
                    label: 'Perfomance',
                    formatter: function (w) {
                        return 44
                    }
                }
            }
        }
    },
    labels: [],
};
var chart = new ApexCharts(document.querySelector("#sales-performance-chart"), options);
chart.render();

var options = {
    series: [{
        name: 'Net Profit',
        data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
    }, {
        name: 'Revenue',
        data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
    }],
    colors:["#727cf5","#e3eaef"],
    chart: {
        id:'bar_chart',
        type: 'bar',
        height: 419
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '70%',
            // endingShape: ''
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    },
    yaxis: {
        title: {
            // text: '₹ (thousands)'
            text: '₹'
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                // return "₹ " + val + " thousands"
                return "₹ " + val
            }
        }
    }
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();

