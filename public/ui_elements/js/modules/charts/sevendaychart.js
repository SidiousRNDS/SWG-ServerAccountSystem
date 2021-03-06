
var statusEndPointLS = "/lastsevenlive";

function draw7DayChart()
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

                    var i = 0;
                    var arrData = [];
                    for(prop in data){
                        arrData[i] = data[prop];
                        i++;
                    }

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

        graphDataUsersHighPop.push(['Date', 'Population']);

        for (var i = 0; i < arrData.length; i++) {

            var justDate = arrData[i]['date'].split(' ')[0];
            graphDataUsersHighPop.push([ new Date(justDate), parseInt(arrData[i]['population_high'])]);
        }

        var dataSevenDays = new google.visualization.arrayToDataTable(graphDataUsersHighPop);
        var options = {
            width: 400,
            height: 240,
            title: 'Users online over the last 7 days',
            legend: 'none',
            backgroundColor: '#EDE8E6',
            series: {
                0: {color: '#A5D6A7'}
            },
            vAxis: {
                title: 'Server Population',
                format: '0'
            }
        };


        var chart = new google.visualization.LineChart(document.getElementById('last7days'));
        chart.draw(dataSevenDays, options);
    }
    else {
        $('#last7days').css("width","400");
        $('#last7days').css("height","272");

        $('#last7days').html("No data to report at this time for the population over the last 7 days graph");
    }
}