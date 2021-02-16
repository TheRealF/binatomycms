//Gestione dell'immissione degli argomenti del blog (qua chiamati "tags" e "hashtags")
$(document).ready(function() {
  $(document).on("keyup", "#hashtags", function() {
    var input = document.querySelector('#hashtags');
    var container = document.querySelector('.tag-container');

    if (event.which == 13 && input.value.length > 0 && input.value.length < 17) {
      if (container.childElementCount < 5) { //Max numero argomenti: 5

        var userinput = input.value.trim();

        if (container.childElementCount > 0) {
          for (let i = 0; i < container.childElementCount; i++) {
            if (container.children.item(i).textContent == userinput) {
              $('#hastagslabel').show();
              return false;
            }
          }
        }

        $('#hastagslabel').hide();
        var text = document.createTextNode(userinput);
        var p = document.createElement('p');
        container.appendChild(p);
        p.appendChild(text);
        tryToggleSubmit();
        p.classList.add('tag');
        input.value = '';


        p.addEventListener("click", () => {
          container.removeChild(p);
          tryToggleSubmit();
        });
      }
    }
  });

  //Restrizione sui caratteri immettibili dall'utente come nomi di argomenti
  $(document).on('keypress', '#hashtags', function(event) {
    var regex = new RegExp("^[a-zàèéìóòùA-ZÀÈÉÌÓÒÙ0-9 ]$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
      event.preventDefault();
      return false;
    }
  });
});
