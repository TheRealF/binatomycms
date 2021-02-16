<?php


class BlogsettingsController extends Controller
{

  public function main($params)
  {
    $blogManager = new BlogManager();
    $userManager = new UserManager();

    //Accesso ristretto solo a un utente loggato...
    $this->authUser(true);

    //...e che sia autore del blog
    $user = $userManager->getUser();
    $blog_author = $blogManager->getBlogAuthor(urldecode($params[0]));

    if ($user['username'] != $blog_author['author']){
      $this->addMessage('Errore: azione non permessa');
      $this->redirect('homepage');
    } else {
      // Recupera la lista dei coautori
      $coauthors = $blogManager->getBlogCoauthors(urldecode($params[0]));
      //Recupera i dati del blogs
      $blog = $blogManager->getBlog(urldecode($params[0]));

      // HTML head
      $this->head = array(
        'title' => $blog['name'] . " - Impostazioni",
        'description' => $blog['name'],
      );

      //Gestisce la rimozione di un coautore
      if (isset($_POST['coauthor_del'])) {
        $result = $blogManager->removeCoauthor($blog['name'], $_POST['coauthor_del']);
        if($result == 1){
          $this->addMessage("Il coautore " . trim($_POST['coauthor_del'], '$') . " è stato rimosso");
          $this->redirect('blogsettings/' . $blog['name']);
        }
      }
      //Gestisce l'aggiunta di un coautore
      if (isset($_POST['coauthor_add'])) {
        if (empty($userManager->usernameExists($_POST['coauthor_add'])))
        $this->addMessage("Lo username inserito non esiste");
        else if ($_POST['coauthor_add'] == $user['username'])
        $this->addMessage("Sei già autore del blog!");
        else {
          try {
            $result = $blogManager->addCoauthor($blog['name'], $_POST['coauthor_add']);
            if($result == 1){
              $this->addMessage("Il coautore " . $_POST['coauthor_add'] . " è stato aggiunto con successo");
              $this->redirect('blogsettings/' . $blog['name']);
            }
          } catch (\Exception $e) {
            $errorMsg = "Errore.";
            if (strpos($e->getMessage(), "Duplicate")){
              $errorMsg .= " L'utente " . $_POST['coauthor_add'] . " è già coautore del blog";
            }
            $this->addMessage($errorMsg);
          }
        }
      }
      // Passaggio dei dati alla view
      $this->data['title'] = "Modifica il blog: " . $blog['name'];
      $this->data['blog'] = $blog;
      $this->data['layout'] = json_decode($blogManager->getLayout($blog['name']));
      $this->data['blogAuthor'] = $blog_author['author'];
      $this->data['blogCoauthors'] = $coauthors;

      // Scelta della view
      $this->view = 'blogsettings';
    }
  }
}
