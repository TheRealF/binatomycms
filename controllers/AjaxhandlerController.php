<?php

//Clasee utilizzata per rispondere alle richiesta AJAX
class AjaxhandlerController extends Controller
{

  public function main($params)
  {

    //A seconda della parte finale dell'URL (params[0]) si individua quali dati sono richiesti

    //Controlla se il nome del blog inserito dall'utente esiste già
    if ((!empty($params[0]) && $params[0] == 'checkblogname') && $_POST) {
      $blogManager = new BlogManager();
      $result = array("exists" => false);
      if ($blogManager->blogExists($_POST['blogName'])){
        $result["exists"] = true;
      }
      echo json_encode($result);
      exit();
    }

    //Controlla se il nome utente inserito è già usato da un'altra persona
    if ((!empty($params[0]) && $params[0] == 'checkusername') && $_POST) {
      $userManager = new UserManager();
      $result = array("exists" => false);
      if ($userManager->usernameExists($_POST['username'])){
        $result["exists"] = true;
      }
      echo json_encode($result);
      exit();
    }

    //Controlla se l'indirizzo mail inserito è già usato da un'altra persona
    if ((!empty($params[0]) && $params[0] == 'checkemail') && $_POST) {
      $userManager = new UserManager();
      $result = array("exists" => false);
      if ($userManager->mailExists($_POST['email'])){
        $result["exists"] = true;
      }
      echo json_encode($result);
      exit();
    }

    //Controlla se il documento inserito è già usato da un'altra persona
    if ((!empty($params[0]) && $params[0] == 'checkdocument') && $_POST) {
      $userManager = new UserManager();
      $result = array("exists" => false);
      if ($userManager->documentExists($_POST['document'])){
        $result["exists"] = true;
      }
      echo json_encode($result);
      exit();
    }

    //Controlla se il telefono inserito è già usato da un'altra persona
    if ((!empty($params[0]) && $params[0] == 'checkphone') && $_POST) {
      $userManager = new UserManager();
      $result = array("exists" => false);
      if ($userManager->phoneExists($_POST['phone'])){
        $result["exists"] = true;
      }
      echo json_encode($result);
      exit();
    }

    //Restituisce una lista dei blog creati da un utente
    if ((!empty($params[0]) && $params[0] == 'getmyblogs')) {
      $userManager = new UserManager();
      $blogManager = new BlogManager;
      $currentUser = $userManager->getUser();
      $result = $blogManager->getBlogbyAuthor($currentUser['username']);
      $arrayBlogs = array();
      $object = (object) $arrayBlogs;
      echo (json_encode($result));
      exit();
    }

    //Controlla se è stata caricata un'immagine di sfondo del blog/dei post
    if ((!empty($params[0]) && $params[0] == 'checkpic_location') && $_POST) {
      if ($_POST['mode'] && $_POST['blogname']){

        $path = PicUtils::getBgPicPath($_POST['mode'], $_POST['blogname']);
        $result = array("path" => $path);
        echo json_encode($result, JSON_UNESCAPED_SLASHES);
        exit();
      }
    }
  }
}

?>
