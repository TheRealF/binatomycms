<?php

class StyleeditorController extends Controller {

  public function main($params){
    //Controllo che l'accesso alla pagina provenga da un utente registrato
    $this->authUser(true);

    $blogManager = new BlogManager();
    $userManager = new UserManager();
    $currentUser = $userManager->getUser()['username'];
    $blogName = "";

    //Controllo che l'utente che modifica lo stile sia effettivamente autore del blog
    if (!empty($params[0])){
      $blogName = urldecode($params[0]);
      $author = $blogManager->getBlogAuthor($blogName)['author'];
      if ($author != $currentUser) {
        $this->addMessage('Errore: azione non permessa');
        $this->redirect('homepage');
      }
    }

    try {
      //Controlla se l'utente sta salvando uno stile
      if (!empty($params[1]) && $params[1] == 'save'){
        if(isset($_POST)){
          $this->checkPOSTRequest($_POST, $currentUser);
        }
        $blogName = urldecode($params[0]); //Il nome dello stile è uguale al nome del blog!
        $layoutData = array($blogName, $_POST['layout']);
        $blogManager->updateLayout($layoutData);
        $this->addMessage("Stile aggiornato con successo");
        $this->redirect("blog/" . $params[0]);
      }

      //Controlla se l'utente sta caricando un'immagine di sfondo (del blog o dei post)
      if (!empty($params[1]) && $params[1] == 'uploadpic'){
        if ($_POST['mode'] && $_FILES['pic']){
          if(!in_array($_POST['mode'], array("blog", "post"))){
            $this->badRequest();
          } else {
            $layoutObj = json_decode($blogManager->getLayout($blogName));
            if ($_POST['mode'] == "blog"){
              $layoutObj->bg_blog_mode = "img";
            }
            if ($_POST['mode'] == "post"){
              $layoutObj->bg_post_mode = "img";
            }
            $blogManager->updateLayout(array($blogName, json_encode($layoutObj)));

            $result = PicUtils::uploadPic($_FILES['pic'], $_POST['mode'], $params[0]);
            $this->data['noLayout'] = true;
            $this->data['ajaxData'] = array("result" => $result);
            $this->view = 'print_ajax';
          }
        }
      } else {
        //Visualizza la view
        $this->head['title'] = 'Gestione dello stile';
        $this->data['blogURL'] = urlencode($blogName);
        $this->data['blogName'] = $blogName;
        $this->data['layout'] = json_decode($blogManager->getLayout($blogName));
        $this->view = 'styleeditor';
      }
    } catch (\Exception $e) {
      $this->badRequest();
    }
  }

  //Funzione di controllo specifica per tutta la richiesta post a StyleeditorController
  private function checkPOSTRequest($postrq, $currentUser){
    $safeKeys = array("bg_blog_mode", "blog_bgColor", "bg_post_mode", "post_bgColor", "blog_SelectFontFamily", "blog_FontSize", "blog_FontColor", "post_SelectFontFamily", "post_FontSize", "post_FontColor", "header_color");

    //Controllo che i campi della richiesta post non siano vuoti
    if (empty($postrq['layout'])) {
      throw new \Exception();
    }

    //Controlla le proprietà json del campo "layout" della richiesta POST
    $layoutArr = json_decode($postrq['layout'], true);
    $layout_keys = array_keys($layoutArr);
    foreach ($layout_keys as $key) {
      if (!in_array($key, $safeKeys)){
        throw new \Exception();
      }
    }

    //Controllo dei colori, dei nomi dei font e della dimensione dei fontimmessi dall'utente
    foreach ($layoutArr as $key => $value) {
      if(stripos($key, "color") !== false){  //Controllo i colori
        if (!InputChecker::isHexColor($value)){
          throw new \Exception();
        }
      }
      if(stripos($key, "fontfamily") !== false){  //Controllo i nomi dei font
        if (!InputChecker::isStringWithSpaces($value)){
          throw new \Exception();
        }
      }
      if(stripos($key, "size") !== false){  //Controllo le dimensioni dei font
        if (!InputChecker::isNumber($value)){
          throw new \Exception();
        }
      }
    }
  }

}
?>
