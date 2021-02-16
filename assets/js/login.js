function tryToggleSubmit(){
  if ($("#password").val().length >= 6 && $("#username").val().length > 0 &&
  $("#usernamelabel").css("display") == "none"){
    $('#submitBtn').removeAttr('disabled');
  } else {
    $('#submitBtn').attr('disabled', true);
  }
}

//Gestore AJAX per il controllo del nome utente
$(document).ready(function (e){
  var typingTimer;
  var doneTypingInterval = 50; //Univoco per tutti i campi di testo che richiedono il controllo

  $('#password').keyup(function (){
    tryToggleSubmit();
  });

  $('#username').keyup(function(){
    clearTimeout(typingTimer);
    if ($('#username').val()) {
      typingTimer = setTimeout(checkUsernameAvailability, doneTypingInterval, $("#username").val());
    }
  });

  function checkUsernameAvailability(usrname) {
    $.post("ajaxhandler/checkusername", {username: usrname}, function(data, status){
      if (data.exists){
        $("#usernamelabel").hide();
        $("#username").css("border-color", "green");

      } else {
        $("#username").css("border-color", "red");
        $("#usernamelabel").css("display", "block");
      }
      tryToggleSubmit();
    }, "json");
  }

});
