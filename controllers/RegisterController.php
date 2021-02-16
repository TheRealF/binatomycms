<?php

class RegisterController extends Controller
{

	private function checkFields($data){
		//Implementazione di reCAPTCHA
		$responseKey = $data['g-recaptcha-response'];
		$secretKey = "6LcQvuMZAAAAAEhKaIOz_6UEwDU33A0H4wmSAcmu";
		$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey";
		$response = json_decode(file_get_contents($url));
		if (
			strlen($data['username']) >= 1 && strlen($data['username']) <= 32 &&
			strlen($data['password']) >= 6 && strlen($data['password']) <= 60 &&
			strlen($data['document']) >= 5 && strlen($data['document']) <= 10 &&
			strlen($data['phone']) >= 6 && strlen($data['phone']) <= 14 &&
			strlen($data['email']) >= 3 && strlen($data['email']) <= 50 &&
			$response->success
		){
			return true;
		}
		return false;
	}

	public function main($params)
	{
		// HTML head
		$this->head['title'] = 'Register';
		if ($_POST)
		{
			if ($this->checkFields($_POST)){  //Controllo dei campi inviati
				try  //Registrazione dell'utente
				{
					$userManager = new UserManager();
					if (!$userManager->usernameExists($_POST['username']) && !$userManager->mailExists($_POST['email'])) {
						$userManager->register($_POST['username'], $_POST['password'], $_POST['document'], $_POST['phone'], $_POST['email']);
						$userManager->login($_POST['username'], $_POST['password']);
						$this->addMessage('La registrazione è stata effettuata con successo.');
						$this->redirect('administration');
					} else {
						$this->addMessage('La registrazione non è andata a buon fine: username o mail sono già utilizzate');
						$this->redirect('register');
					}

				}
				catch (Exception $ex)
				{
					$this->addMessage($ex->getMessage());
				}
			} else {
				$this->badRequest();
			}
		}
		$this->view = 'register';
	}
}
