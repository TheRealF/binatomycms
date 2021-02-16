<?php

class BlogController extends Controller
{

  public function main($params)
  {
    // Creazione delle istanze dei model necessarie
    $blogManager = new BlogManager();
    $postManager = new PostManager();
    $userManager = new UserManager();
    $searchManager = new SearchManager();

    //Gestine della ricerca attraverso il parametro "search" della richiesta POST
    if (isset($_POST['search']))
    {
      try
      {
        $search = $_POST['search'];
        $searchq = $searchManager->searchPost($search);

        $count = count($searchq);
        if($count == 0){
          $this->addMessage('Nessun risultato');
          $this->redirect('blog/'.$params[0]);
        } else if($count == 1){
          $this->addMessage($count . ' risultato di ricerca trovato');
          for ($i=0; $i < count($searchq); $i++) {
            $searchq[$i]['url'] = urlencode($searchq[$i]["title"] . $searchq[$i]["timestamp"]);
          }
          $this->data['search'] = $searchq;
          $this->head = array(
            'title' => 'Ricerca',
            'description' => '1 Risultato',
          );
          $this->view = 'search';
        }
        else {
          $this->addMessage($count . ' risultati di ricerca trovati');
          $this->data['search'] = $searchq;
          $this->head = array(
            'title' => 'Ricerca',
            'description' => $count . 'Risultati',
          );
          $this->view = 'search';
        }
      }
      catch (Exception $ex)
      {
        $this->addMessage($ex->getMessage());
      }
    }
    else{
      // Controlla se l'utente ha cliccato su elimina blog
      if ((!empty($params[1]) && $params[1] == 'elimina'))
      {
        if (isset($_POST['confirm'])){
          $this->authUser(true);
          $user = $userManager->getUser();
          $blogName = urldecode($params[0]);
          $blog_author = $blogManager->getBlogAuthor($blogName);
          if ($user['username'] == $blog_author['author']){
            $blogManager->removeBlog(urldecode($params[0]));

            //Rimozione delle immagini di sfondo caricate in precedenza
            PicUtils::deleteOldPics("blog", $blogName);
            PicUtils::deleteOldPics("post", $blogName);

            $this->addMessage('Il Blog è stato eliminato');
            $this->redirect('homepage');
          } else {
            $this->addMessage('Errore: azione non permessa');
            $this->redirect('homepage');
          }
        } else {
          $this->addMessage('Errore: azione non permessa');
          $this->redirect('homepage');
        }
      }
      //Controllo attraverso l'URL se l'utente intende copiare il layout
      else if ((!empty($params[1]) && $params[1] == 'copylayout')) {
        if ($_POST['bgToUpdateLayout']){
          if ($blogManager->getBlogAuthor($_POST['bgToUpdateLayout'])['author'] == $userManager->getUser()['username']){
            $layoutToCopy = json_decode($blogManager->getLayout(urldecode($params[0])), true);
            $layoutToUpdate = json_decode($blogManager->getLayout($_POST['bgToUpdateLayout']), true);
            if($layoutToCopy['bg_blog_mode'] == "img"){
              $layoutToCopy['bg_blog_mode'] = $layoutToUpdate['bg_blog_mode'];
              $layoutToCopy['blog_bgColor'] = $layoutToUpdate['blog_bgColor'];
            }
            if($layoutToCopy['bg_post_mode'] == "img"){
              $layoutToCopy['bg_post_mode'] = $layoutToUpdate['bg_post_mode'];
              $layoutToCopy['post_bgColor'] = $layoutToUpdate['post_bgColor'];
            }
            $updatedLayout = json_encode(array_intersect_key($layoutToCopy,$layoutToUpdate));
            echo json_encode($layoutToCopy);
            echo json_encode($layoutToUpdate);
            echo json_encode($updatedLayout);
            $blogManager->updateLayout(array($_POST['bgToUpdateLayout'], $updatedLayout));
            $this->addMessage("Stile aggiornato con successo");
            $this->redirect("blog/" . urlencode($_POST['bgToUpdateLayout']));
          }
        }
      }
      //Nessuna richiesta particolare è stata inoltrata: si procede a visualizzare il blog
      else if (!empty($params[0]))
      {
        //Recupera lo username dell'utente corrente
        $user = $userManager->getUser();

        // Recupera il blog dall'URL
        $blog = $blogManager->getBlog(urldecode($params[0]));
        // Se non c'è alcun blog, si rimanda alla pagina di errore
        if (!$blog){
          $this->redirect('error');
        }

        //Recupera l'autore del blog dalla tabella blog_author
        $blog_author = $blogManager->getBlogAuthor(urldecode($params[0]));

        //Recupera tutti gli autori del blog
        $allAuthors = $blogManager->getAllBlogAuthors(urldecode($params[0]));

        // HTML head
        $this->head = array(
          'title' => $blog['name'],
          'description' => $blog['name'],
        );

        // Passaggio dei dati alla view
        $this->data['blogURL'] = urlencode($blog['name']);
        $this->data['title'] = $blog['name'];
        $this->data['backgroundImage'] = $blogManager->getBackgroundURL($blog['name']);
        $this->data['layout'] = json_decode($blogManager->getLayout($blog['name'])); //TODO
        $this->data['blog'] = $blog;
        $this->data['blogAuthor'] = $blog_author['author'];
        $this->data['allAuthors'] = $allAuthors;
        if (!empty($user)){
          $this->data['currentUser'] = $user['username'];
        }

        $_SESSION["blogName"] = $blog['name'];
        // Setting the template
        $this->view = 'blog';

      }
      else
      {
        $this->view = 'homepage';
      }
    }
  }

}
