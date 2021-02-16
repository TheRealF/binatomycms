<?php

class RouterController extends Controller
{

	// Istanza del controller annidato
	protected $controller;

	 // Ricava l'indirizzo URL e restituisce i parametri dell'URL in un array
	private function parseUrl($url)
	{
		// Effettua il parsing dell'url in un array associativo
		$parsedUrl = parse_url($url);
		// Rimuove slash aggiuntivo
		$parsedUrl["path"] = ltrim($parsedUrl["path"], "/");
		// Rimouovi eventuali spazi bianchi intorno all'indirizzo URL
		$parsedUrl["path"] = trim($parsedUrl["path"]);
		// Divide in parametri utilizzando gli slash per filtrare questi ultimi
		$explodedUrl = explode("/", $parsedUrl["path"]);
		return $explodedUrl;
	}

	 //Converte il nome del controller, preso da un URL, in un nome di una classe scritto secondo la convenzione CamelCase
	private function dashesToCamel($text)
	{
		$text = str_replace('-', ' ', $text);
		$text = ucwords($text);
		$text = str_replace(' ', '', $text);
		return $text;
	}

	 //Ricava l'indirzzo URL e crea il controller appropiato
	public function main($params)
	{
		$parsedUrl = $this->parseUrl($params[0]);

		if (empty($parsedUrl[0]))
			$this->redirect('homepage');
		// Il nome del controller è il primo parametro dell'URL
		$controllerClass = $this->dashesToCamel(array_shift($parsedUrl)) . 'Controller';

		if (file_exists('controllers/' . $controllerClass . '.php'))
			$this->controller = new $controllerClass;
		else
			$this->redirect('error');

    $this->controller->main($parsedUrl);
		//Recupera lo username dell'utente corrente
		$userManager = new UserManager();
		$username = $userManager->getUser();

		// Passa i dati alla view
		$this->data['title'] = $this->controller->head['title'];
		$this->data['description'] = $this->controller->head['description'];
		$this->data['messages'] = $this->getMessages();

		if ($username){
			$this->data['username'] = $username['username'];
		}
    $this->view = $this->controller->view;
}
}
