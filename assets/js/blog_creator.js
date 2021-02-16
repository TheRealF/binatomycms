//Dichiarazioni funzioni ausiliare
//Controlla se i campi sono stati riempiti correttamente
function tryToggleSubmit() {
  if (($("#blognamelabel").css("display") == "none") && $('#blognamefield').val() !== "" &&
  $('.tag-container').children().length !== 0 &&
  $("#blognamefield").val().length > 0 && $("#blognamefield").val().length < 33) {
    $('#mainSubmit').removeAttr('disabled');  //Il bottone di submit si può abilitare
  } else {
    $('#mainSubmit').attr('disabled', true); //Il bottone di submit si mantiene disabilitato/disabilita
  }
}
//Controlla se esiste già un blog con lo stesso nome
function checkBlognameAvailability(blgname) {
  $.post("ajaxhandler/checkblogname", {
    blogName: blgname
  }, function(data) {
    if (data.exists) {
      $("#blognamefield").css("border-color", "red");
      $("#blognamelabel").show();
    } else {
      $("#blognamelabel").hide();
      $("#blognamefield").css("border-color", "green");
    }
    tryToggleSubmit();
  }, "json");
}

//Restrizione sui caratteri dei campi
function addCharCheckEvent(id, regex1, regex2) {
    $(document).on('keypress', id, function(event) {
        var regex = new RegExp(regex1);
        var regexField = new RegExp(regex2);
        var textContent = $(id).val();
        var isTextFinished = (regexField.test(textContent));
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key) || !isTextFinished) {
            event.preventDefault();
            showCheckerLabel(id);
            return false;
        } else {
            hideCheckerLabel(id);
        }
    });
}

function showCheckerLabel(id) {
    switch (id) {
        case "#blognamefield":
            $(id + "label").show().css("color", "red");
            break;
        case "#hashtags":
            $(id + "label").show().css("color", "black");
            break;
        default:
    }
}

function hideCheckerLabel(id) {
    $(id + "label").hide();
}

//Inizio gestione eventi
$(document).ready(function() {
addCharCheckEvent("#blognamefield", "^[a-zàèéìóòùA-ZÀÈÉÌÓÒÙ0-9 ]$", "^[a-zàèéìóòùA-ZÀÈÉÌÓÒÙ0-9 ]{0,31}$");
addCharCheckEvent("#hashtags", "^[a-zàèéìóòùA-ZÀÈÉÌÓÒÙ0-9]$", "^[a-zàèéìóòùA-ZÀÈÉÌÓÒÙ0-9]{0,16}$");

  //POST form "mainForm"
  $("#mainSubmit").on("click", function() {
    var form = document.getElementById("mainForm");
    var _args;

    //Aggiunge gli argomenti divisi da separatore nella stringa _args
    _args = "";
    $(".tag-container").children().each(function() {
      _args += $(this).text() + "|";
    });
    _args = _args.slice(0, -1);

    $("#hiddenArgs").val(_args);
    $('#hiddenLayout').val($('#search_drop').text());
    form.submit();
  });

  var typingTimer;
  var doneTypingInterval = 50; //Univoco per tutti i campi di testo che richiedono il controllo

  $('#blognamefield').keyup(function() {
    tryToggleSubmit();
    clearTimeout(typingTimer);
    if ($('#blognamefield').val()) {
      typingTimer = setTimeout(checkBlognameAvailability, doneTypingInterval, $("#blognamefield").val());
    }
  });

  });
