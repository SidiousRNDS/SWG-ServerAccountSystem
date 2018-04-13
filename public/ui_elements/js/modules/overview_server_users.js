
//var tokenURL = "http://swgusers.rnds.io/api/token";
//var statusURL = "http://swgusers.rnds.io/api/v1/status/lastseven";
var statusEndPointLS = "/lastsevenlive";
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart()
{
    $.ajax({
        url: tokenURL,
        type: 'POST'
    }).done(function(data) {

        if (data.status === "ok")
        {
            $.ajax({
                url: statusURL+statusEndPointLS,
                type: 'POST',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + data.token);
                },
                success: function (data) {
                    var arrData = Object.values(data);

                    // Sort the Array cause the Object was did not come back in the correct sort
                    arrData.sort(function (a, b) {
                        return a.date.localeCompare(b.date);
                    });

                    // Build 7 days Graph
                    build7Days(arrData);
                }
            });
        }
    });
}

// build the Seven Day Graph
function build7Days(arrData)
{
    if(arrData.length !== 0){
        var graphDataUsersHighPop = [];

        graphDataUsersHighPop.push(['Datetime', 'Population']);

        for (var i = 0; i < arrData.length; i++) {
            graphDataUsersHighPop.push([new Date(arrData[i]['date']), parseInt(arrData[i]['population_high'])]);
        }

        console.dir(graphDataUsersHighPop);

        var dataSevenDays = new google.visualization.arrayToDataTable(graphDataUsersHighPop);
        var options = {
            width: 500,
            height: 240,
            title: 'Users Online over the last 7 days',
            vAxis: {'title': 'Server Population'},
            legend: 'none',
            backgroundColor: '#EDE8E6',
            series: {
                0: {color: '#A5D6A7'}
            }
        };


        var chart = new google.visualization.LineChart(document.getElementById('last7days'));
        chart.draw(dataSevenDays, options);
    }
    else {
        $('#last7days').css("width","500");
        $('#last7days').css("height","240");

        $('#last7days').html("No data to report at this time.");
    }
}