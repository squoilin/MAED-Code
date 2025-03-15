$(document).ready(function () {
    Cookies.remove('titlecs');
    Cookies.remove('activelink');
    Cookies.remove('content');
    checkLogin();
    if ($.urlParam('e')=='1' && $.urlParam('e')!==null){
        var hash = $.urlParam('h');
        var message = 'Invalid username or password!';
        if (hash) {
            message += '<br>Generated hash: ' + decodeURIComponent(hash);
        }
        ShowErrorMessage(message);
    }

    $('#login').load('auth/login/login.html');
    var lang=Cookies("langCookie");
    if(lang!==undefined){
        changeLang(lang);
    }
})

$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^]*)').exec(window.location.href);
    if (results==null){
       return null;
    }
    else{
       return results[1] || 0;
    }
}

function ShowErrorMessage(message) {
    $('#msgcontainer').html('');
    d = document.createElement('div');
    $(d).addClass("alert alert-danger alert-dismissable box-shadow--2dp")
        .attr('id', 'msg1')
        .html('<a class="close" data-dismiss="alert" aria-label="close">&times;</a>' + message)
        .appendTo($("#msgcontainer"))
    $('#msg1').delay(5000).fadeOut('slow');
}

function checkLogin(){
    $.ajax({
        url: 'auth/login/checklogin.php',
        type: 'POST',
        success: function(results) {
           if(results==0){
                window.location.replace("app.html");
           }
        }
    })
}