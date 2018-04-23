/**
 * JS Utilities file
 **/

var errorBorderColor  = "#FF0000";
var defaultBorderColor = "#CCCCCC";
var validationParams = {usernameMinLength : 4, usernameMaxLength : 32, passwordMinLength : 5, passwordMaxLength : 20};
var validation = {username : false, email : false, password : false, password2 : false, authcode : false};

// TODO Have to rewrite this part to use tokens and point to the correct location
var tokenURL = "http://swgusers.rnds.io/api/token";
var newaccountURL = "http://swgusers.rnds.io/api/v1/account/addaccount";

if (checkMobile.any()) {
    $('#main').html("<div class='moduleHeader' style='text-align:center;'><p><strong>Required to play here, a pc is</strong></p></div><div class='module'>");
    $('.module').append("<div class='moduleMSG'><p><i class='fas fa-minus-circle moduleError' aria-hidden='true'></i> You can only sign up for a new account with a desktop or laptop seeing you can not play SWG on a mobile device.</p></div>");
    $('.module').append("</div></div><div id='yoda'><img src='ui_elements/img/yoda-error3.png' class='yimg'/></div>");
    $('#newAccount').remove();
}

$(document).ready(function(){

    // Disable submit
    captchaSubmitDisabled();

    // Validate Form
    validateClientForm();

    $('#accCreate').on('click',function(e) {

        e.preventDefault();

        // Get Auth Token from the API
        $.ajax({
            url: tokenURL,
            type: 'POST'
        }).done(function(data) {
            if (data.status === "ok") {
                $.ajax({
                    url: newaccountURL,
                    type: 'POST',
                    data: $('#nAccount').serialize(),
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Authorization', 'Bearer ' + data.token);
                    },
                    success: function(data) {
                        // Error
                        if (data !== "Account for " + username + " has been created.") {
                            $('.msg').html("<span class='error-msg'><i class='fas fa-exclamation-triangle' aria-hidden='true'></i> " + data + "</span>");
                            $('#nAccount')[0].reset();
                            grecaptcha.reset();
                            captchaSubmitDisabled();
                        }
                        else {
                            // success
                            $('.msg').html("<span class='success'><i class='fas fa-info-circle' aria-hidden='true'></i> Your Account has been created.</span>");
                            $('#nAccount')[0].reset();
                            grecaptcha.reset();
                            captchaSubmitDisabled();
                        }
                    }
                });
            }
        });
    });
});


/* Misc Methods */




// Encode special Chars
function specialCharsEncode(special)
{
    var encodeSpecial = encodeURI(special);

    return encodeSpecial;
}