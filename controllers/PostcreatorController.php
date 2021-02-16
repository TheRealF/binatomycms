<?php


class PostcreatorController extends Controller
{

	public function main($params)
	{
		// Solo gli utenti loggati possono accede a questa pagina
		$this->authUser(true);

		// HTML head
		$this->head['title'] = 'Post editor';
		$time = time();
		$postManager = new PostManager();
		$userManager = new UserManager();
		$blogManager = new BlogManager();
		$user = $userManager->getUser();
		// Prepares an empty post
		$post = array(
			'title' => '',
			'content' => '',
			'timestamp' => '',
		);

		//Controlla se viene specificato nell'URL il blog per cui creare il post
		if (empty($params[0])){
			$this->badRequest();
		}

		$authors = array_values($blogManager->getAllBlogAuthors(urldecode($params[0])));
		$currentUser = $userManager->getUser()['username'];
		if (!(in_array($currentUser, $authors) || in_array("$" . $currentUser, $authors))){
			$this->redirect('homepage');
		}


		//È stato inviato un post da salvare?
		if (isset($_POST['title']) && $_POST['content'])
		{  //Controlli sull'input del post
			if (!InputChecker::isItalianStringWithSpaces($_POST['title']) || empty($_POST['title']) ||
			empty($_POST['content'])){
				$this->badRequest();
			}
			// Recupera i dati del post da POST
			$keys = array('title', 'content', 'timestamp');
			$_POST['content'] = InputChecker::purifyHTML($_POST['content']);  //Purificazione ulteriore del contenuto del post, nel caso la richiesta POST sia stata inviata da programmi esterni (Postman, ecc)
			$_POST['timestamp'] = $time;
			$post = array_intersect_key($_POST, array_flip($keys));

			// Salvataggio post nel database con controlli
			$post['blogName'] = urldecode($params[0]);
			$postManager->savePost($post);
			$postManager->saveAuthor($user['username'], $time, urldecode($params[0]));
			$this->addMessage('Il post è stato salvato con successo');
			$this->redirect('post/' . urlencode($post['title'] . $post['timestamp'] ));
		}

		$this->data['layout'] = json_decode($blogManager->getLayout($_SESSION["blogName"]));
		$this->data['post'] = $post;
		$this->view = 'postcreator';
	}
}
