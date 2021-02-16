<?php

class LoginController extends Controller
{

	public function main($params)
	{
		$userManager = new UserManager();

		//Se l'utente è già loggato, si rimanda alla pagina personale di amministrazione
		if ($userManager->getUser()){
			$this->redirect('administration');
		}

		// HTML head
		$this->head['title'] = 'Login';
		if (isset($_POST['username']))
		{
			try  //Prova il login
			{
				$userManager->login($_POST['username'], $_POST['password']);
				$this->addMessage('Ti sei loggato con successo');
				$this->redirect('administration');
			}
			catch (Exception $ex)
			{
				$this->addMessage($ex->getMessage());
			}
		}
		// Sets the template
		$this->view = 'login';
	}
}
