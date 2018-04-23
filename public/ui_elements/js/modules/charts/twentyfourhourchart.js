
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
                    arrData.sort(function (a, b) {
                        return a.recordedDate.localeCompare(b.recordedDate);
                    });

                    // Build 24 hour Graph
                    build24Hour(arrData);
                }
            });
        }
    });
}

// build the Seven Day Graph
function build24Hour(arrData)
{
    var today = new Date();
    var d = today.getDate();
    var m = today.getMonth()+1; //January is 0!
    var y = today.getFullYear();

    if(d < 10){
        d ='0'+d;
    }
    if(m < 10){
        m ='0'+ m;
    }
    var today = m+'/'+d+'/'+y;

    if(arrData.length !== 0){
        var graphDataUsersHighPop = [];

        graphDataUsersHighPop.push(['Hour', 'Population']);

        for (var i = 0; i < arrData.length; i++) {
            graphDataUsersHighPop.push([arrData[i]['hourreported'], parseInt(arrData[i]['population_high'])]);
        }

        var dataSevenDays = new google.visualization.arrayToDataTable(graphDataUsersHighPop);
        var options = {
            width: 500,
            height: 240,
            title: 'Users online over the last 24 hours (' + today + ')',
            legend: 'none',
            backgroundColor: '#EDE8E6',
            series: {
                0: {color: '#A5D6A7'}
            },
            hAxis: {
                title: 'Time (24h)',
                viewWindow:{
                    max:24,
                    min:0
                }
            },
            vAxis: {
                title: 'Server Population',
                format: '0'
            }
        };


        var chart = new google.visualization.LineChart(document.getElementById('last24hours'));
        chart.draw(dataSevenDays,options);
    }
    else {
        $('#last24hours').css("width","500");
        $('#last24hours').css("height","240");

        $('#last24hours').html("No data to report at this time.");
    }
}