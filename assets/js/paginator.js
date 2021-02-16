//Fonte: https://www.myprogrammingtutorials.com/infinite-scroll-pagination-with-php-and-ajax.html
$(document).ready(function (){
  var splittedURL = $(location).attr('href').split("/");
  var page = splittedURL[3];
  var blogname = splittedURL[4];
  var blogname_post = $("#blogname_post").text();

  $('#loader').on('inview', function(event, isInView) {
    if (isInView) {
      var nextPage = parseInt($('#pageno').val()) + 1;
      $.get("pagination/" + page, {
        pageno: nextPage,
        blogname: blogname,
        blogname_post: blogname_post
      }, function(data) {
        if (data != '') {
          $('#paginate').append(data);
          $('#pageno').val(nextPage);
        } else {
          $("#loader").hide();
        }
      });
    }
  });
});
