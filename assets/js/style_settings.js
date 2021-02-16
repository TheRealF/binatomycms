//Funzioni Ausiliarie
function ajaxPostPic (formData){
  formData.get("mode");
  const check = formData.get("mode"); //blogBg o postBg
  $.ajax({
    type: "POST",
    url: (window.location.href).replace(/\/$/, "") + '/uploadpic',
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function(data){
      if(check == "blog"){
        $('#errorlabel').empty();
        switch (data.result) {
          case 0: $('#errorlabel').prepend("<span class='span2'>Upload eseguito con successo</span>");  break; //Upload ok;
          case 1: $('#errorlabel').prepend("<span class='span1'>Il file non è un'immagine</span>");  break; //File non è un'immagine;
          case 2: $('#errorlabel').prepend("<span class='span1'>Il file è troppo grande</span>"); break; //File troppo grande;
          case 3: $('#errorlabel').prepend("<span class='span1'>Il file non è un'immagine</span>");  break;//Formato errato;
          case 4: location.reload; break;
          default: location.reload;
        }
      }
      if(check == "post"){
        $('#errorlabel1').empty();
        switch (data.result) {
          case 0: $('#errorlabel1').prepend("<span class='span2'>Upload eseguito con successo</span>");  break; //Upload ok;
          case 1: $('#errorlabel1').prepend("<span class='span1'>Il file non è un'immagine</span>");  break; //File non è un'immagine;
          case 2: $('#errorlabel1').prepend("<span class='span1'>Il file è troppo grande</span>"); break; //File troppo grande;
          case 3: $('#errorlabel1').prepend("<span class='span1'>Il file non è un'immagine</span>");  break;//Formato errato;
          case 4: location.reload; break;
          default: location.reload;
        }
      }
    },
    error: function(){
      location.reload;
    }
  });
}

//Gestione eventi
//POST form "mainForm"
$(document).ready(function (){
  $("#blogBg_btn").click(function (){
    $("#blogBg_uploader").click()
  });

  $("#blogBg_uploader").change(function (){
    $("#blogBg_preview_label").hide();
    $("#blogBg_preview").attr("src", window.URL.createObjectURL(this.files[0]));
    $("#blogBg_preview").show();
    $("#blogBg_apply").show();
  });

  $("#postBg_btn").click(function (){
    $("#postBg_uploader").click()
  });

  $("#postBg_uploader").change(function (){
    $("#postBg_preview_label").hide();
    $("#postBg_preview").attr("src", window.URL.createObjectURL(this.files[0]));
    $("#postBg_preview").show();
    $("#postBg_apply").show();
  });

  //Gestisce i pannelli di scelta dello sfondo (immagine o colore)
  $("#radio_bg_blog_img").click(function(){
    $("#bg_blog_panel_img").show();
    $("#bg_blog_panel_color").hide();
  })

  $("#radio_bg_blog_solidColor").click(function(){
    $("#bg_blog_panel_img").hide();
    $("#bg_blog_panel_color").show();
  });

  $("#radio_bg_post_img").click(function(){
    $("#bg_post_panel_img").show();
    $("#bg_post_panel_color").hide();
  });

  $("#radio_bg_post_solidColor").click(function(){
    $("#bg_post_panel_img").hide();
    $("#bg_post_panel_color").show();
  });

  $("#mainSubmit").on("click", function (){
    var _form = document.getElementById("mainForm");
    var data = new FormData(_form);

    // //Codifica  dei campi del form relativi al layout in una stringa JSON
    // var layoutJSON = "{";
    // for(var pair of data.entries()) {
    //   layoutJSON += '"' + pair[0] + '": "' + pair[1] + '", ';
    // }
    // layoutJSON = layoutJSON.slice(0, -2);
    // layoutJSON += "}";

    //Codifica  dei campi del form relativi al layout in una stringa JSON
    var layoutJSON = {};
    for(var pair of data.entries()) {
      layoutJSON[pair[0]] = pair[1];
    }

    console.log(JSON.stringify(layoutJSON));
    //Creazione di una form contenente il campo layout da inviare al server
    const form = document.createElement('form');
    form.method = "post";
    form.action = (window.location.href).replace(/\/$/, "") + '/save';
    const hiddenField = document.createElement('input');
    hiddenField.type = 'hidden';
    hiddenField.name = "layout";
    hiddenField.value = JSON.stringify(layoutJSON);
    form.appendChild(hiddenField);
    document.body.appendChild(form);
    form.submit();
  });


  $(document).on('click','#blogBg_apply',function(){
    $('#blogBg_form').submit();
  });

  $(document).on('click','#postBg_apply',function(){
    $('#postBg_form').submit();
  });

  //Gestore AJAX per gli upload delle immagini

  var formData;

  $('#blogBg_form').on('submit', (function(e) {
    e.preventDefault();
    formData = new FormData(this);
    formData.append("mode", "blog");
    ajaxPostPic(formData);
  }));

  $('#postBg_form').on('submit', (function(e) {
    e.preventDefault();
    formData = new FormData(this);
    formData.append("mode", "post");
    ajaxPostPic(formData);
  }));

  //Funzioni di anteprima dei font scelti dall'utente
  var h1Blog = $("#blog_FontPreview");
  var h1Post = $("#post_FontPreview");
  var headerSpan = $("#header_color_preview");
  //Per il titolo del blog
  $("#blog_SelectFontFamily").change(function (){
    h1Blog.css("font-family", $(this).val());
  });
  $("#blog_FontSize").change(function (){
    h1Blog.css("font-size", $(this).val() + "px");
  });
  $("#blog_FontColor").on("input", function (){
    h1Blog.css("color", $(this).val());
  });

  //Per il titolo del post
  $("#post_SelectFontFamily").change(function (){
    h1Post.css("font-family", $(this).val());
  });
  $("#post_FontSize").change(function (){
    h1Post.css("font-size", $(this).val() + "px");
  });
  $("#post_FontColor").on("input", function (){
    h1Post.css("color", $(this).val());
  });

  //Per il colore dello header
  $("#header_color").on("input", function (){
    headerSpan.css("background-color", $(this).val());
  });

  if($("#hidden_bg_blog_mode").val() == "img"){
    $("#radio_bg_blog_img").prop("checked", true).click();

  } else {
    $("#radio_bg_blog_solidColor").prop("checked", true).click();
  }

  if($("#hidden_bg_post_mode").val() == "img"){
    $("#radio_bg_post_img").prop("checked", true).click();
  } else {
    $("#radio_bg_post_solidColor").prop("checked", true).click();
  }

  $("#blog_SelectFontFamily").trigger("change");
  $("#blog_FontSize").trigger("change");
  $("#blog_FontColor").trigger("input");
  $("#post_SelectFontFamily").trigger("change");
  $("#post_FontSize").trigger("change");
  $("#post_FontColor").trigger("input");
  $("#header_color").trigger("input");

  //Gestore degli uploader delle immagini
  //Nascondo preview label
  $("#blogBg_preview_label").hide();
  $("#postBg_preview_label").hide();
  $("#blogBg_preview").hide();
  $("#postBg_preview").hide();

  //Controlli AJAX: verifica se esistono le immagini di sfondo. Se sì, attivano l'anteprima
  var blogName = window.location.pathname.split("/")[2];
  $.post("ajaxhandler/checkpic_location", {mode: "blog", blogname: blogName}, function(data){
    if (data.path !== ""){
      $("#blogBg_preview").attr("src", data.path);
      $("#blogBg_preview").show();
      $("#blogBg_preview_label").show();
    }
  }, "json");
  $.post("ajaxhandler/checkpic_location", {mode: "post", blogname: blogName}, function(data){
    if (data.path != ""){
      $("#postBg_preview").attr("src", data.path);
      $("#postBg_preview").show();
      $("#postBg_preview_label").show();
    }
  }, "json");

});
