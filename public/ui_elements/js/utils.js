
var cdate = new Date();
var day = cdate.getDate();
var month = cdate.getMonth();
var year = cdate.getFullYear();
var monthNames = [
    "JAN", "FEB", "MAR",
    "APR", "MAY", "JUN", "JUL",
    "AUG", "SEP", "OCT",
    "NOV", "DEC"
];

var errorBorderColor  = "#FF0000";
var defaultBorderColor = "#CCCCCC";
var validationParams = {usernameMinLength : 4, usernameMaxLength : 32, passwordMinLength : 5, passwordMaxLength : 20};
var validation = {username : false, email : false, password : false, password2 : false, authcode : false};

$(document).ready(function(){

    // Display the copyright data
    buildCopyRight();

    // Translate the page to galactic basic
    translate();

    adminCaptchaSubmitDisabled();

    $('#authCodeList').DataTable({
        responsive: true,
        columnDefs: [
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1},
        ]
    });

    $('#authCodeListNotUsed').DataTable({
        responsive: true,
        columnDefs: [
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1},
        ],
        "columns": [
            null,
            null,
            null,
            null,
            {"orderable":false}
        ]
    });

    $('#authCodeListUsed').DataTable({
        responsive: true,
        columnDefs: [
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1},
        ]
    });

    $('#userList').DataTable({
        responsive: true,
        columnDefs: [
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1},
        ],
        "columns": [
            null,
            null,
            null,
            {"orderable":false}
        ]
    });


    $('#patchList').DataTable({
        responsive: true,
        columnDefs: [
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1},
        ],
        "columns": [
            null,
            null,
            null,
            null,
            null,
            {"orderable":false}
        ]
    });

    $(".deleteEntry").on('click', function(e){
        deleteEntry(e);
    });
});

// Translate
function translate() {
    $('.translate').on('click',function(){
        if($('.translate').hasClass('active')) {
            $('body').css('font-family',"");
            $('body').css('font-size',"");
            $('.translate').removeClass('active');
            $('.translate').attr("title","Translate to Galactic Basic");
        }
        else {
            $('body').css("font-family","galbasic");
            $('body').css("font-size","1.12em");
            $('.translate').addClass('active');
            $('.translate').attr("title","Translate to English");
        }
    });
}

// Build out the copyright
function buildCopyRight() {

    $('.foot').html("Copyright <i class='fa fa-copyright' aria-hidden='true'></i> "+year+" RNDS");
    $('.foot').append('<span class="translation"><a href="#" class="translate" title="Translate to Galactic Basic"><i class="fab fa-empire" aria-hidden="true"></i> Translate</a></span>');
}

// Client Form validation
function validateClientForm() {
    // Set form for New Accounts
    if ($('#nAccount') !== undefined) {
        // Form input cells
        var username = "";
        var email    = "";
        var pass     = "";
        var pass2    = "";
        var authcode = "";

        // Username Validation
        $('#usernameInput').blur(function() {
            $('#usernameInput').css("border-color",defaultBorderColor);
            $('#usernamelabel span').remove();
            username = $.trim($('#usernameInput').val());
            userlen = username.length;

            if (username === "") {
                $('#usernamelabel').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Username can not be empty!</span>');
                $('#usernameInput').css("border-color",errorBorderColor);
                validation.username = false;
            }
            else if (/^[a-zA-Z0-9@.\-]*$/g.test(username) == false)
            {
                $('#usernamelabel').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> One or more illegal characters were found.</span>');
                $('#usernameInput').css("border-color",errorBorderColor);
                validation.username = false;
            }
            else if (userlen < validationParams.usernameMinLength || userlen > validationParams.usernameMaxLength) {
                $('#usernamelabel').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Your Username must be between '+validationParams.usernameMinLength+' and '+validationParams.usernameMaxLength+' characters!</span>');
                $('#usernameInput').css("border-color",errorBorderColor);
                validation.username = false;
            }
            else {
                // Set username Pass to true
                validation.username = true;
            }

            // Unlock the captcha so we can check that and then if that is valid then we can unlock the submit button
            unlockCaptcha(this.form);
        });

        // Email Address Validation
        $('#emailInput').blur(function() {
            $('#emailInput').css("border-color",defaultBorderColor);
            $('#emaillabel span').remove();

            email = $.trim($('#emailInput').val());

            if (email === "") {
                $('#emaillabel').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Email can not be empty!</span>');
                $('#emailInput').css("border-color",errorBorderColor);
                validation.email = false;
            }
            else if (!isValidEmailAddress(email)) {
                $('#emaillabel').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Please enter in a valid email address.</span>');
                $('#emailInput').css("border-color",errorBorderColor);
                validation.email = false;
            }
            else {
                // Set emailPass to true
                validation.email = true;
            }
            // Unlock the captcha so we can check that and then if that is valid then we can unlock the submit button
            unlockCaptcha(this.form);
        });

        // Password 1 Validation
        $('#passwordInput').blur(function() {
            $('#passwordInput').css("border-color",defaultBorderColor);
            $('#passwordlabel span').remove();

            pass = $.trim($('#passwordInput').val());
            passlen = pass.length;

            if (pass === "") {
                $('#passwordlabel').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Password can not be empty!</span>');
                $('#passwordInput').css("border-color",errorBorderColor);
                validation.password = false;
            }
            // Check for Valid Characters
            else if (/^[a-zA-Z0-9!@$*\-:_]*$/g.test(pass) === false)
            {
                $('#passwordlabel').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> One or more illegal characters were found.</span>');
                $('#passwordInput').css("border-color",errorBorderColor);
                validation.password = false;
            }
            else if (passlen < validationParams.passwordMinLength || passlen > validationParams.passwordMaxLength) {
                $('#passwordlabel').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Your Pass must be between '+validationParams.passwordMinLength+' and '+validationParams.passwordMaxLength+' characters!</span>');
                $('#passwordnameInput').css("border-color",errorBorderColor);
                validation.password = false;
            }
            else {
                // Set Pass Pass to true
                validation.password = true;
            }

            // Unlock the captcha so we can check that and then if that is valid then we can unlock the submit button
            unlockCaptcha(this.form);
        });

        // Password 2 Validation
        $('#password2Input').blur(function() {
            $('#password2Input').css("border-color",defaultBorderColor);
            $('#password2label span').remove();

            pass2 = $.trim($('#password2Input').val());
            pass2len = pass2.length;

            if (pass2 === "") {
                $('#password2label').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Password can not be empty!</span>');
                $('#password2Input').css("border-color",errorBorderColor);
                validation.password2 = false;
            }
            else if (pass2len < validationParams.passwordMinLength || passlen > validationParams.passwordMaxLength) {
                $('#password2label').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Your Pass must be between '+validationParams.usernameMinLength+' and '+validationParams.usernameMaxLength+' characters!</span>');
                $('#password2Input').css("border-color",errorBorderColor);
                validation.password2 = false;
            }
            else if (pass != pass2) {
                $('#password2label').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Passwords do not match!</span>');
                $('#password2Input').css("border-color",errorBorderColor);
                validation.password2 = false;
            }
            else {
                // Set Pass Pass to true
                validation.password2 = true;
            }

            // Unlock the captcha so we can check that and then if that is valid then we can unlock the submit button
            unlockCaptcha(this.form);
        });

        // Authcode check
        $('#authcodeInput').blur(function() {
            $('#authcodeInput').css("border-color",defaultBorderColor);
            $('#authcodelabel span').remove();

            authcode = $.trim($('#authcodeInput').val());

            authcodelen = authcode.length;

            if (authcode === "") {
                $('#authcodelabel').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Authorization code is required.</span>');
                $('#authcodeInput').css("border-color",errorBorderColor);
                validation.authcode = false;
            }
            else {
                // Set authcode to true
                validation.authcode = true;
            }
            // Unlock the captcha so we can check that and then if that is valid then we can unlock the submit button
            unlockCaptcha(this.form);
        });
    }
}

// Validate Email Address
function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}

// Unlock the captcha if the required fields have validated data
function unlockCaptcha(formname) {

    if (formname.name == 'newAccount') {
        if(useAuth == true) {
            if (validation.username === true && validation.email === true && validation.password === true && validation.password2 === true && validation.authcode === true) {
                $('.g-recaptcha').css('display', 'block');
            }
            else {
                // If something is missing disable captcha and submit button
                captchaSubmitDisabled();
            }
        }
        else {
            if (validation.username === true && validation.email === true && validation.password === true && validation.password2 === true) {
                $('.g-recaptcha').css('display', 'block');
            }
            else {
                // If something is missing disable captcha and submit button
                captchaSubmitDisabled();
            }
        }
    }

}

// Enable Submit button after captcha has been processed
function captchaSubmitEnabled() {
    $('#accCreate').prop('disabled',false);
    $('#loginBtn').prop('disabled',false);
}
// Disable Submit button before catpcha has been processed or if captcha fails
function captchaSubmitDisabled() {
    $('#accCreate').prop('disabled',true);
    // Hide captcha till the require fields are all filled in
    $('.g-recaptcha').css('display','none');
}

function adminCaptchaSubmitDisabled() {
    console.log("Disabling login button");
    $('#loginBtn').prop('disabled',true);
}

/**
 * checkMobile
 *
 */
// Check if they are using a mobile device
var checkMobile = {
    Android: function () {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function () {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function () {
        return navigator.userAgent.match(/iPhone|iPod/i);
    },
    iPAD: function () {
        return navigator.userAgent.match(/iPad/i);
    },
    Opera: function () {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function () {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function () {
        return (checkMobile.Android() || checkMobile.BlackBerry() || checkMobile.iOS() || checkMobile.iPAD()|| checkMobile.Opera() || checkMobile.Windows());
    }
};

// Confirm Delete
function deleteEntry(e)
{
    if( confirm("Are you sure you want to remove this entry? This can not be undone!")) {
        return true;
    }
    else {
        e.preventDefault();
    }
}

$(function() {

    // Disable Form Buttons
    //$('#nUpdate .btn').prop('disabled',true);

    // File Upload Check
    $('#updateTreFileInput').bind('change',function(){
        var maxFileSize = 220200960;    // In Bytes
        var uFile = this.files[0];
        var humanReadableSize = humanReadableFileSize(uFile.size, true);

        if (uFile.size > maxFileSize) {
            $('#updateTreFilelabel').find('span').remove();
            $('#updateTreFilelabel').append('<span class="error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Your file is ' + humanReadableSize + ' which exceeds max file upload size of ' + humanReadableFileSize(maxFileSize, true) + '</span>');
            $('#updateTreFileInput').css("border-color", errorBorderColor);
            this.value = null;
            $('.btn').prop('disabled',true);
        }
        else {
            $('#updateTreFilelabel').find('span').remove();
            $('#updateTreFilelabel').append('<span class="accepted"><i class="fas fa-check-circle" aria-hidden="true"></i> Your file size is ' + humanReadableSize + '</span>');
            $('#updateTreFileInput').css("border-color", defaultBorderColor);
            $('.btn').prop('disabled',false);
        }
    });
});

// Human Readable Filesizes
function humanReadableFileSize(bytes, si) {
    var thresh = si ? 1000 : 1024;
    if(Math.abs(bytes) < thresh) {
        return bytes + ' B';
    }
    var units = si
        ? ['kB','MB','GB','TB','PB','EB','ZB','YB']
        : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
    var u = -1;
    do {
        bytes /= thresh;
        ++u;
    } while(Math.abs(bytes) >= thresh && u < units.length - 1);

    return bytes.toFixed(1)+' '+units[u];
}

