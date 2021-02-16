<?php

class BlogcreatorController extends Controller
{

	public function main($params)
	{
		$blogManager = new BlogManager();
		$userManager = new UserManager();
		$this->authUser(true);  //Solo gli utenti registrati possono creare un blog
		$this->head['title'] = 'Creazione Blog';  //HTML head
		$blog = array();
		$user = $userManager->getUser()['username'];  //Ricava lo username dell'utente corrente
    //controlla il numero di blogs
		if (count($blogManager->getBlogbyAuthor($user)) >= 5) {
		 $this->badRequest();
		}
		//Elaborazione della richiesta POST

		if (isset($_POST['name'])){
			$_POST['name'] = trim($_POST['name']);
			if (!InputChecker::isItalianStringWithSpaces($_POST['name']) || !(strlen($_POST['name']) <= 32)){  //Controllo validità: nome del blog
				$this->badRequest();
			}

			if (isset($_POST['args'])){  //Controllo validità: nome degli argomenti
				$argsArray = explode("|", $_POST['args']);
				foreach ($argsArray as $arg) {
					if (!InputChecker::isItalianString($arg) || !(strlen($arg) <= 16)){
						$this->badRequest();
					}
					if ($argsArray !== array_unique($argsArray)){ //Controlla argomenti duplicati
						$this->badRequest();
					}
				}
			} else {
				$this->badRequest();
			}

			if (isset($_POST['layout'])){
				$presetsArr = json_decode(file_get_contents("assets/config/layout_presets.json"), true);  //Recupera i nomi degli stili preset di default
				$arrok = array();  //Array dei soli nomi accettabili per stili di default, presi dal relativo file
				foreach ($presetsArr as $key => $value) {
					array_push($arrok, $key);
				}
				if (!in_array($_POST['layout'], $arrok)){ //Controlla che nel campo 'layout' ci sia un nome che appartiene ai nomi degli stili di default
					$this->badRequest();
				}
			}

			try {
				$presetsObj = json_decode(file_get_contents("assets/config/layout_presets.json"));
				$layoutName = $_POST['layout'];
				$blog = array(
					"name" => trim($_POST['name']),
					"layout" => json_encode($presetsObj->$layoutName)
				);
				$blogManager->createBlog($blog, $user, $argsArray);
				$this->addMessage('Il blog è stato creato con successo.');
				$this->redirect('blog/' . urlencode($blog['name']));
			} catch (\Exception $e) {
				$this->badRequest();
			}
		}
		$this->data['blog'] = $blog;
		$this->view = 'blogcreator';
	}
}
