//Gestione della ricerca
function reset(){
  $('#search_drop').empty();
  $('#search_blog').show();
  $('#search_temi').show();
  $('#search_utenti').show();
  $('#search_tutto').show();
}

$(document).ready(function (){
  $(document).click(function() {
    $('.dropbtn').trigger("hover");
  });
  $('#search_blog').click(function() {
    reset();
    $('#search_drop').append("Nome");
    $('.freccia').hide();
    $('#search_blog').hide();
    $('#cerca').attr('name', 'search_blog');
  });
  $('#search_temi').click(function() {
    reset();
    $('#search_drop').append("Temi");
    $('.freccia').hide();
    $('#search_temi').hide();
    $('#cerca').attr('name', 'search_temi');
  });
  $('#search_utenti').click(function() {
    reset();
    $('#search_drop').append("Autore");
    $('.freccia').hide();
    $('#search_utenti').hide();
    $('#cerca').attr('name', 'search_utenti');
  });
  $('#search_tutto').click(function() {
    reset();
    $('#search_drop').append("Nome & Autore");
    $('.freccia').hide();
    $('#search_tutto').hide();
    $('#cerca').attr('name', 'search_tutto');
  });
});
