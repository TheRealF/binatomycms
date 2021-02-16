//Popola dinamicamente il menuù a tendina per la scelta dello stile di default (preset)
$(document).ready(function (){
  var firstPreset = true;
  $.getJSON("assets/config/layout_presets.json", function (data){  //Punta al file JSON in cui gli webmaster caricano gli stili di default che vogliono
    for(var a in data){
      if (firstPreset){
        $('#search_drop').text(a);
        firstPreset = false;
      }
      $("#dropdown_presets").append("<p>" + a + "</p>");
    }
    $("#dropdown_presets").children("p").each(function (){
      $(this).click(function() {
        $('#search_drop').empty(this);
        $('#search_drop').text($(this).text());
      });
    });

  });
  $(document).click(function() {
    $('.dropbtn').trigger("hover");
  });
});
