
var statusEndPoint24 = "/lasttwentyfourlive";

function draw24HourChart()
{
    $.ajax({
        url: tokenURL,
        type: 'POST'
    }).done(function(data) {

        if (data.status === "ok")
        {
            $.ajax({
                url: statusURL+statusEndPoint24,
                type: 'POST',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + data.token);
                },
                success: function (data) {

                    var i = 0;
                    var arrData = [];
                    for(prop in data){
                        arrData[i] = data[prop];
                        i++;
                    }

                    // Sort the Array cause the Object was did not come back in the correct sort
                    /*arrData.sort(function (a, b) {
                        return a.date.localeCompare(b.date);
                    });*/

                    // Build 7 days Graph
                    build24Hour(arrData);
                }
            });
        }
    });
}

// build the Seven Day Graph
function build24Hour(arrData)
{
    if(arrData.length !== 0){
        var graphDataUsersHighPop = [];

        graphDataUsersHighPop.push(['Hour', 'Population']);

        for (var i = 0; i < arrData.length; i++) {

            var hourReported = arrData[i]['hourreported'] + "00:00";


            graphDataUsersHighPop.push([ parseInt(arrData[i]['hourreported']), parseInt(arrData[i]['population_high'])]);
        }

        var dataSevenDays = new google.visualization.arrayToDataTable(graphDataUsersHighPop);
        var options = {
            width: 500,
            height: 240,
            title: 'Users Online for the last 24 hours',
            vAxis: {'title': 'Server Population'},
            legend: 'none',
            backgroundColor: '#EDE8E6',
            series: {
                0: {color: '#A5D6A7'}
            }
        };


        var chart = new google.visualization.LineChart(document.getElementById('last24hours'));
        chart.draw(dataSevenDays, options);
    }
    else {
        $('#last7days').css("width","500");
        $('#last7days').css("height","240");

        $('#last7days').html("No data to report at this time.");
    }
}