<?php

class AdministrationController extends Controller
{
	public function main($params)
	{
		// Solo gli utenti registrati possono accedere al pannello di amministrazione
		$this->authUser();
		// HTML head
		$this->head['title'] = 'Area privata';
		$userManager = new UserManager();
		$blogManager = new BlogManager();

		//È stato cliccato logout?
		if (!empty($params[0]) && $params[0] == 'logout')
		{
			$userManager->logout();
			$this->redirect('login');
		}
		$user = $userManager->getUser();
		$blogs = 	$blogManager->getBlogbyAuthor($user['username']);
		for ($i=0; $i < count($blogs); $i++) {
			$blogs[$i]['url'] = urlencode($blogs[$i]["name"]);
			$blogs[$i]['imageurl'] = PicUtils::getBgPicPath("blog", $blogs[$i]["name"]);
		}
		//Passaggio dei dati alla view
		$this->data['blogs'] = $blogs;
		if (count($blogs) > 0 && count($blogs) < 5) {
			$still = (5 - count($blogs));
			$this->data['stillBlogs'] = $still;
		}
		else{
			$still = 6;
			$this->data['stillBlogs'] = $still;
		}
		$this->view = 'administration';
	}
}
