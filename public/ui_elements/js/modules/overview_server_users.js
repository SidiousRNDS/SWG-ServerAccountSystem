
var tokenURL = "http://swgusers.rnds.io/api/token";
var statusURL = "https://swgusers.rnds.io/api/status/lastseven";

$(document).ready(function(){

    $.ajax({
        url: tokenURL,
        type: 'POST'
    }).done(function(data) {

        if (data.status === "ok")
        {
            $.ajax({
                url: statusURL,
                type: 'POST',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + data.token);
                },
                success: function (data) {

                }
            });
        }
    });
});