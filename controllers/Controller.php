<?php

abstract class Controller
{

  /*Un array i cui indici sono accessibili come variabili nelle viste*/
  protected $data = array();
  /*Il nome di una vista*/
  protected $view = "";
  /* Intestazione HTML */
  protected $head = array('title' => '', 'description' => '');

  /*Rendering della vista, chiamata dal routercontroller in index.php*/
  public function renderView()
  {
    if ($this->view)
    {
      extract($this->controller->data);
      extract($this->data);

      if(!isset($this->controller->data['noLayout'])){
        require("views/header.phtml");
      }
      require("views/" . $this->view . ".phtml");
      if(!isset($this->controller->data['noLayout'])){
        require("views/footer.phtml");
      }

    }
  }
  /*Effettua il redirect su un url passato*/
  public function redirect($url)
  {
    header("Location: /$url");
    header("Connection: close");
    exit;
  }

  /*Se una richiesta fallisce redireziona all'homepage e mostra un messaggio*/
  public function badRequest()
  {
    $this->addMessage("Errore. Richiesta non valida.");
    $this->redirect("homepage");
  }

  /*Il controller principale, in input prende un URL*/
  abstract function main($params);

  /*Aggiunge un messaggio per l'utente*/
  public function addMessage($message)
  {
    if (isset($_SESSION['messages']))
    $_SESSION['messages'][] = $message;
    else
    $_SESSION['messages'] = array($message);
  }

  /*Restituisce tutti i messaggi aggiunti*/
  public function getMessages()
  {
    if (isset($_SESSION['messages']))
    {
      $messages = $_SESSION['messages'];
      unset($_SESSION['messages']);
      return $messages;
    }
    else
    return array();
  }

  /*Controlla dove l'utente è loggato, altrimenti reindirizza alla pagina di login*/
  public function authUser()
  {
    $userManager = new UserManager();
    $user = $userManager->getUser();
    if (!$user)
    {
      $this->addMessage('Non è stato effettuato il login');
      $this->redirect('login');
    }
  }

}
