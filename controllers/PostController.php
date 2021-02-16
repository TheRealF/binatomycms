<?php

class PostController extends Controller
{
	public function main($params)
	{
		// Creazione delle istanze dei modelli
		$postManager = new PostManager();
		$userManager = new UserManager();
		$blogManager = new BlogManager();
		$commentManager = new CommentManager();
		$user = $userManager->getUser();

		// Viene inserito l'URL del post
		if (!empty($params[0]))
		{
			// Prende un post dall'URL
			$post = $postManager->getPost(urldecode($params[0]));
			$postAuthor = $postManager->getPostAuthor($post['timestamp'], $post['blogName']);
			$bg_author = $blogManager->getBlogAuthor($post['blogName'])['author'];
      //Controlla se l'utente è autore o coautore del blog
			if (isset($user)){
				if ($user['username'] == $bg_author || "$" . $user['username'] == $postAuthor['username']){
					$this->data['nodelete'] = true;
				}
			}
			// Se non viene trovato nessun post redirect alla pagina di errore
			if (!$post){
				$this->redirect('error');
			}

			$_SESSION["timestampPost"] = $post['timestamp'];
			// HTML head
			$this->head = array(
				'title' => $post['title'],
				'description' => $post['title'],
			);

			// Se L'URL per la rimozione del post è inserito
			if (!empty($params[1]) && $params[1] == 'elimina')
			{
				$this->authUser(true);
				$postManager->removePost(urldecode($params[0]));
				$this->addMessage('Il post è stato rimosso con successo');
				$this->redirect('blog/' . $post['blogName']);
			}
			// Solo gli utenti registrati possono pubblicare commenti
			if($_POST){
				$this->authUser(true);
				$time = time();
				// Commento vuoto
				$comment = array(
					'text' => '',
					'timestamp' => '',
				);
				//Elimina commenti
				if (isset($_POST["comment_username"]) && isset($_POST["comment_timestamp"]))
				{
					$commentManager->removeComment($_POST["comment_username"], $_POST["comment_timestamp"]);
					$this->addMessage('Il commento è stato rimosso con successo');
					$this->redirect('post/' . urlencode($post['title'] . $post['timestamp'] ));
				}
				else{
					$keys = array('text', 'timestamp');
					$_POST['timestamp'] = $time;
					$_POST['text'] = InputChecker::purifyHTML($_POST['text']);
					$comment = array_intersect_key($_POST, array_flip($keys));
					$comment['blogNamePost'] = $post['blogName'];
					$comment['timestampPost'] = $post['timestamp'];
					$comment['username'] = $user['username'];
					if(!empty($comment["text"])){
						$commentManager->saveComment($comment);
						$this->addMessage('Il commento è stato pubblicato con successo');
						$this->redirect('post/' . urlencode($post['title'] . $post['timestamp'] ));
					}
					else {
						$this->addMessage('Testo del commento vuoto o non valido');
						$this->redirect('post/' . urlencode($post['title'] . $post['timestamp'] ));
					}
				}
			}

			// Passa i dati alla view
			$this->data['title'] = trim($post['title']);
			$this->data['content'] = $post['content'];
			$this->data['postUrl'] = urlencode($post['title'] . $post['timestamp']);
			$this->data['layout'] = json_decode($blogManager->getLayout($post['blogName']));
			$this->data['backgroundImage'] = $postManager->getBackgroundURL($post['blogName']);
			$this->data['blogName'] = $post['blogName'];
			$this->data['blogURL'] = urlencode($post['blogName']);
			$this->data['data'] = date('j-m-y', $post['timestamp']);
			$this->data['ora'] = date('G:i', $post['timestamp']);
			if($user)
			$this->data['currentUsername'] = $user['username'];
			else
			$this->data['currentUsername'] = "";
			if($postAuthor)
			$this->data['postAuthor'] = $postAuthor['username'];
			else
			$this->data['postAuthor'] = "";

			// Imposta la view post
			$this->view = 'post';
		}
		else
		// Nessun url inserito, viene impostata la vista blog e quindi mostrati tutti i post*/
		{
			$this->view = 'blog';
		}
	}

}
