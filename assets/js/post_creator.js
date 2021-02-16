// Controlla la lunghezza del post
function getPostLength() {
  var body = tinymce.get('content').getBody(), text = tinymce.trim(body.innerText || body.textContent);
  return text.length;
}

function showCheckerLabel(id, message) {
  $(id + "label").css("color", "black");
  switch (id) {
    case "#title":
    $(id + "label").show().text(message);
    break;
    case "#content":
    $(id + "label").show().text(message);
    break;
    default:
  }
}
function hideCheckerLabel(id) {
  $(id + "label").hide();
}
function checkFields(){
  if($("#title").val().length < 1){
    showCheckerLabel("#title", "Il titolo è vuoto");
    return false;
  }
  if($("#title").val().length > 64){
    showCheckerLabel("#title", "Il titolo non può superare i 64 caratteri");
    return false;
  }
  if(getPostLength() < 1){
    showCheckerLabel("#content", "Il contenuto del post è vuoto!");
    return false;
  }
  if(getPostLength() > 30000){
    showCheckerLabel("#content", "Il post non può superare i 30000 caratteri");
    return false;
  }
  return true;
}

$(document).ready(function() {
  tinymce.init({
    language : 'it',
    selector: "textarea[name=content]",
    plugins: [
      "advlist autolink lists link image charmap print preview anchor",
      "searchreplace visualblocks code fullscreen",
      "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    entities: "160,nbsp",
    entity_encoding: "raw",
    setup: function(editor) {
      editor.on('keyup', function() {
        hideCheckerLabel("#content");
      });
    }
  });

  //Restrizione sui caratteri del titolo del post
  $(document).on('keypress', "#title", function() {
    if($("#title").val().length == 64){
      showCheckerLabel("#title", "Il titolo non può superare i 60 caratteri");
      return false;
    } else {
      hideCheckerLabel("#title");
    }

    var regex1 = new RegExp( "^[a-zàèéìóòùA-ZÀÈÉÌÓÒÙ0-9 ]$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex1.test(key)) {
      event.preventDefault();
      showCheckerLabel("#title", "Il titolo può contenere solo lettere, numeri e spazi");
      return false;
    }
    else{
      hideCheckerLabel("#title");
    }
  });
  $(document).on("click", "#submitPost", function(e){
    e.preventDefault();
    if(checkFields()){
      $("form").submit();
    }
  });

});
