
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

$(document).ready(function(){

    // Display the copyright data
    buildCopyRight();

    // Translate the page to galactic basic
    translate();

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

    $('.foot').html("Copyright <i class='fa fa-copyright' aria-hidden='true'></i> "+year+" SWG Rogue One");
    $('.foot').append('<span class="translation"><a href="#" class="translate" title="Translate to Galactic Basic"><i class="fab fa-empire" aria-hidden="true"></i> Translate</a></span>');
}
