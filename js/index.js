$("#login_err").hide();

$(document).ready(function () {
    $.ajax({
        url:'/tce/php/app.php',
        type:'POST',
        async:false,

        success: function (data) {
            var res = JSON.parse(data);

            console.log(res);

            if(res["loggedin"]==true) {
                showLoader();
                loadInfo(res);
            }
            else
                loadLoginForm();
        },

        error: function(){
            showError("server unreachable");
        }
    });
});

function showIndexImage() {
    var wrapper = $("#wrapper");
    $(wrapper).animateCss("fadeOutUp", function () {
        $(wrapper).html("<img src='img/Final%20Render%20.jpg'>").animateCss('fadeIn');
    });
}

function loadLoginForm(toggle){

    hideLoader();

   if(toggle) {
       toggleTopBackground();
        showIndexImage();
   }

    var info = $("#info-top");

    if(toggle)
        $(info).delay(500).fadeOut("slow").hide();


    var i = new EJS({url:'/tce/ejs/login.ejs'}).render();

    $(info).html(i).fadeIn("slow");

    $("#login").on("click",function(){

        var email = $("#email").val();
        var pass = $("#password").val();

        $.ajax({
            type:'post',
            url:"/tce/php/login.php",
            dataType:'json',
            data:jQuery.param({email:email,password:pass}),
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            success:function(res){
                console.log(res);
                if(res["status"] == 0){
                    showError(res["error"])
                }
                else if( res["status"] == 1){
                    $("#login_err").hide();
                    loadInfo(res);
                }
            },
            error:function(){
                showError("server unreachable");
            }
        });
    });

}

function showLoader(){
    var loader= $("#loader");
    $(loader).show();
}


function showError(error){

    var err = $("#err");
    var loginerr = $("#login_err");

    $(err).text(error);

    if($(loginerr).is(':hidden')) {
        $(loginerr).show().animateCss("slideInRight");
    }

}

function hideLoader(){
    var loader= $("#loader");
    $(loader).hide();
}

function loadInfo(data){

    toggleTopBackground();

    var info = $("#info-top");
    var wrapper = $("#wrapper");
    var i = new EJS({url:'/tce/ejs/info.ejs'}).render(data);

    $(wrapper).animateCss("slideOutDown", function () {

        $(wrapper).load("home.html", function () {
            loadHome();
            $(wrapper).show();
            hideLoader();
            $(wrapper).animateCss('fadeInDown');
        }).hide();

    });

    showLoader();

    $(info).animateCss('fadeOutRight', function () {
        $(info).animateCss("fadeInRight").html(i);
        $("#logout").on('click',function(){
            $.ajax({
                url:'/tce/php/logout.php',
                type:'post',
                success: function(){
                    loadLoginForm(true);
                }
            });
        });
    });

}

function toggleTopBackground(){
    var top = $("#top");
    $(top).toggleClass("blue-top black-top");
}

function scroll(elem){
    $("html, body").delay(400).animate({
        scrollTop: elem.offset().top
    }, 800);
}

$.fn.extend({
    animateCss: function (animationName,callback) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        this.addClass('animated ' + animationName).one(animationEnd, function() {
            $(this).removeClass('animated ' + animationName);
            if(callback)
                callback();
        });
        return this;
    }
});
