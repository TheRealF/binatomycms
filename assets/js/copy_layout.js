//Gestione del comportamento del bottone di copia degli stili
$(document).ready(function() {
  $('#copyButton').hide();
  $('#myblogs_drop').text("📋");
  $.post("ajaxhandler/getmyblogs", function(data){
    $.each(data, function (key, value){
      $("#dropdown_myblogs").append("<p>" + value.name + "</p>");
    });

    $("#dropdown_myblogs").children("p").each(function (){
      $(this).click(function() {
        $('#myblogs_drop').empty(this);
        $('#myblogs_drop').text($(this).text());
        $('input[name=bgToUpdateLayout]').val($(this).text());
        $('#copyButton').show();
      });
    });
  }, "json");

  $(document).click(function() {
    $('.dropbtn').trigger("hover");
  });
});
