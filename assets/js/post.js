//Mostra e nasconde i commenti
$(document).ready(function () {
  $(".hid").hide();
  $(".pop_comment_container").hide();
  $("button[name='show_comments']").click(function() {
    $(".show").hide();
    $(".hid").show();
  });
  $("button[name='hide_comments']").click(function() {
    $(".hid").hide();
    $(".show").show();
  });
  $("button[name='btn_comment']").click(function() {
    $(".show").hide();
    $(".hid").hide();
    $(".pop_comment_container").show();
  });
  $(".close").click(function() {
    $(".pop_comment_container").hide();
    $(".show").show();
  });
});
