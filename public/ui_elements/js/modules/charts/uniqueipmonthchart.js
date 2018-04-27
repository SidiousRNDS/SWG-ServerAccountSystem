
var statusEndPointUIM = "/uniqueloginscurrentmonth";

function drawUniqueIPMonthChart()
{
    $.ajax({
        url: tokenURL,
        type: 'POST'
    }).done(function(data) {

        if (data.status === "ok")
        {
            $.ajax({
                url: statusURL+statusEndPointUIM,
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
                        return a.loginDate.localeCompare(b.loginDate);
                    });

                    // Build 7 days Graph
                    buildUniqueIPMonth(arrData);
                }
            });
        }
    });
}

// build the Seven Day Graph
function buildUniqueIPMonth(arrData)
{
    var today = new Date();
    var m = today.getMonth(); //January is 0!
    var y = today.getFullYear();
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    var currentMonth = monthNames[m] + ' ' + y;

    if(arrData.length !== 0){
        var graphDataUsersHighPop = [];

        graphDataUsersHighPop.push(['Date', 'Number Of Logins']);

        for (var i = 0; i < arrData.length; i++) {

            var justDate = arrData[i]['loginDate'].split('-')[2];
            var logins = arrData[i]['userdata'].length;
            graphDataUsersHighPop.push([ justDate, parseInt(logins)]);
        }

        var dataUniqueIpMonth = new google.visualization.arrayToDataTable(graphDataUsersHighPop);
        var options = {
            width: 400,
            height: 240,
            title: 'Unique Logins over the last month (' + currentMonth + ')',
            legend: 'none',
            backgroundColor: '#EDE8E6',
            series: {
                0: {color: '#A5D6A7'}
            },
            vAxis: {
                title: 'Logins',
                format: '0'
            }
        };


        var chart = new google.visualization.LineChart(document.getElementById('uniqueipmonth'));
        chart.draw(dataUniqueIpMonth, options);
    }
    else {
        $('#uniqueipmonth').css("width","400");
        $('#uniqueipmonth').css("height","272");

        $('#uniqueipmonth').html("No data to report at this time for the Unique IP per Month graph");
    }
}