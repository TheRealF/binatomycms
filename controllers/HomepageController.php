<?php
class HomepageController extends Controller
{
	public function main($params)
	{
		$searchManager = new SearchManager();
		$this->head = array(
			'title' => 'Binatomy Blog CMS',
			'description' => 'Binatomy CMS'
		);
/*Gestisce la ricerca nell'homepage, controlla, per alcuni inidici, se sono settati i valori POST corrispondenti e
chiama la query corrispondente. Passa i dati alla view e imposta la view search*/
		try {
			if (isset($_POST['search_blog'])){

				$search = $_POST['search_blog'];
				$searchq = $searchManager->searchBlog($search);

				$count = count($searchq);
				if($count == 0){
					$this->addMessage('Nessun risultato');
					$this->redirect('homepage');
				} else if($count == 1){
					$this->addMessage($count . ' risultato di ricerca trovato');
					for ($i=0; $i < count($searchq); $i++) {
            $searchq[$i]['url'] = urlencode($searchq[$i]["name"]);
          }
					$this->data['search'] = $searchq;
					$this->view = 'search';
				}
				else {

					$this->addMessage($count . ' risultati di ricerca trovati');
					for ($i=0; $i < count($searchq); $i++) {
						$searchq[$i]['url'] = urlencode($searchq[$i]["name"]);
					}
					$this->data['search'] = $searchq;
					$this->view = 'search';
				}
			}	else if (isset($_POST['search_utenti']))
			{

				$search = $_POST['search_utenti'];
				$searchq = $searchManager->searchUsers($search);

				$count = count($searchq);
				if($count == 0){
					$this->addMessage('Nessun risultato');
					$this->redirect('homepage');
				}else if($count == 1){
					$this->addMessage($count . ' risultato di ricerca trovato');
					for ($i=0; $i < count($searchq); $i++) {
						$searchq[$i]['url'] = urlencode($searchq[$i]["name"]);
					}
					$this->data['search'] = $searchq;
					$this->view = 'search';
				}else{
					$this->addMessage($count . ' risultati di ricerca trovati');
					for ($i=0; $i < count($searchq); $i++) {
						$searchq[$i]['url'] = urlencode($searchq[$i]["name"]);
					}
					$this->data['search'] = $searchq;
					$this->view = 'search';
				}

			}
			else if (isset($_POST['search_temi']))
			{

				$search = $_POST['search_temi'];
				$searchq = $searchManager->searchThemes($search);
				$count = count($searchq);
				if($count == 0){
					$this->addMessage('Nessun risultato');
					$this->redirect('homepage');
				}else if($count == 1){
					$this->addMessage($count . ' risultato di ricerca trovato');
					for ($i=0; $i < count($searchq); $i++) {
						$searchq[$i]['url'] = urlencode($searchq[$i]["name"]);
					}
					$this->data['search'] = $searchq;
					$this->view = 'search';
				}

			}
			else if (isset($_POST['search_tutto']))
			{

				$search = $_POST['search_tutto'];
				$searchq = $searchManager->searchGlobal($search, $search);

				$count = count($searchq);
				if($count == 0){
					$this->addMessage('Nessun risultato');
					$this->redirect('homepage');
				}else if($count == 1){
					$this->addMessage($count . ' risultato di ricerca trovato');
					for ($i=0; $i < count($searchq); $i++) {
						$searchq[$i]['url'] = urlencode($searchq[$i]["name"]);
					}
					$this->data['search'] = $searchq;
					$this->view = 'search';
				}

			} else {
				$this->view = 'homepage';
			}
		} 			catch (Exception $ex)
		{
			$this->addMessage($ex->getMessage());
		}
	}
}
