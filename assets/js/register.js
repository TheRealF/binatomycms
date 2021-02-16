//Funzione di callback di ReCAPTCHA
function recaptchaCallback() {
    tryToggleSubmit();
}

//Controlla se i campi AJAX sono stati riempiti correttamente, quindi attiva il bottone submit
function tryToggleSubmit() {
    if (($("#usernamelabel").css("display") == "none") &&
        ($("#username").val().length > 0) &&
        ($("#documentlabel").css("display") == "none") &&
        ($("#document").val().length > 0) &&
        ($("#emaillabel").css("display") == "none") &&
        ($("#email").val().length > 0) &&
        ($("#phonelabel").css("display") == "none") &&
        ($("#phone").val().length > 0) &&
        ($("#password").val().length > 0)) {
        $('#register').removeAttr('disabled');
    } else {
        $('#register').attr('disabled', true);
    }
}

function showCheckerLabel(id) {
    $(id + "label").css("color", "black");
    switch (id) {
        case "#username":
            $(id + "label").show().text("Per il nome utente sono consentiti solo lettere e numeri");
            break;
        case "#document":
            $(id + "label").show().text("Per il documento sono consentiti solo lettere e numeri");
            break;
        case "#email":
            $(id + "label").show().text("Carattere non consentito per l'indirizzo mail");
            break;
        case "#phone":
            $(id + "label").show().text("Non hai inserito un numero");
            break;
        default:
    }
}

function hideCheckerLabel(id) {
    $(id + "label").hide();
}
//Restrizione sui caratteri del nome utente
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

function checkFieldDuplicate(field, input) {
    var dataObj = {};
    dataObj[field] = input;
    $.post("ajaxhandler/check" + field, dataObj, function(data) {
        if (data.exists) {
            $("#" + field).css("border-color", "red");
            $("#" + field + "label").css("display", "block").css("color", "red").text($("#" + field).val() + " è già stato utilizzato");
        } else {
            $("#" + field + "label").hide();
            $("#" + field).css("border-color", "green");
        }
        tryToggleSubmit();
    }, "json");
}
$(document).ready(function() {
    addCharCheckEvent("#username", "^[a-zA-Z0-9]$", "^[a-zA-Z0-9]{0,32}$");
    addCharCheckEvent("#document", "^[a-zA-Z0-9]$", "^[a-zA-Z0-9]{0,10}$");
    addCharCheckEvent("#email", "^[a-z0-9._@-]$", "^[a-z0-9._@-]{0,50}$");
    addCharCheckEvent("#phone", "^[0-9+]$", "^[0-9+]{0,14}$");

    //Gestore AJAX per il controllo dei campi
    var typingTimer;
    var doneTypingInterval = 50; //Univoco per tutti i campi di testo che richiedono il controllo

    $('#username').keyup(function() {
        clearTimeout(typingTimer);
        if ($('#username').val()) {
            typingTimer = setTimeout(checkFieldDuplicate, doneTypingInterval, "username", $("#username").val());
        }
    });

    $('#email').keyup(function() {
        clearTimeout(typingTimer);
        if ($('#email').val()) {
            typingTimer = setTimeout(checkFieldDuplicate, doneTypingInterval, "email", $("#email").val());
        }
    });

    $('#document').keyup(function() {
        clearTimeout(typingTimer);
        if ($('#document').val()) {
            typingTimer = setTimeout(checkFieldDuplicate, doneTypingInterval, "document", $("#document").val());
        }
    });

    $('#phone').keyup(function() {
        clearTimeout(typingTimer);
        if ($('#phone').val()) {
            typingTimer = setTimeout(checkFieldDuplicate, doneTypingInterval, "phone", $("#phone").val());
        }
    });

    $('#password').keyup(function() {
        tryToggleSubmit();
    });


});
