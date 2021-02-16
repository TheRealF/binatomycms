<?php

class BlogManager
{
	public function getBlog($url)
	{
		return DbManager::queryOne('
		SELECT `name`, `layout`
		FROM `blog`
		WHERE `name` = ?
		', array($url));
	}
	public function getBlogbyAuthor($username)
	{
		return DbManager::queryAll('
		SELECT `name`
		FROM `blog`, `blog_author`
		WHERE (`author` = ?) AND (`name` = `blogName`)
		', array($username), PDO::FETCH_ASSOC);
	}

	public function getBlogsLimited($ot, $np)
	{
		return DbManager::queryAll('
		SELECT `name`, `layout`
		FROM `blog`
		LIMIT ?, ?
		', array($ot, $np));
	}
	public function createBlog($blog, $user, $topics)
	{
		DbManager::insert('blog', $blog);

		$blog_author_data = array(
			"blogName" => $blog['name'],
			"author" => $user
		);
		DbManager::insert('blog_author', $blog_author_data);
		$topic_data = array();
		$about_data = array("blogName" => $blog['name']);
		foreach($topics as $topic){
			$topic_data["name"] = $topic;
			$about_data["topicName"] = $topic;
			DbManager::safeInsert('topic', $topic_data);
			DbManager::insert('about', $about_data);
		}
	}

	public function removeBlog($name)
	{
		DbManager::query('
		DELETE FROM blog
		WHERE name = ?
		', array($name));

		DbManager::query('
		DELETE FROM blog_author
		WHERE blogName = ?
		', array($name));
	}

	public function getBlogAuthor($blogName){
		return DbManager::queryOne("
		SELECT `author`
		FROM `blog_author`
		WHERE (`blogName` = ?) AND (`author` NOT LIKE '$%')
		", array($blogName));
	}

	public function getBlogCoauthors($blogName){
		return DbManager::queryAll("
		SELECT `author`
		FROM `blog_author`
		WHERE (`blogName` = ?) AND (`author` LIKE '$%')
		", array($blogName));
	}

	public function getAllBlogAuthors($blogName){
		return DbManager::queryAll("
		SELECT `author`
		FROM `blog_author`
		WHERE `blogName` = ?
		", array($blogName), PDO::FETCH_COLUMN);
	}


	public function removeCoauthor($blogName, $coauthorName){
		return DbManager::query('
		DELETE FROM blog_author
		WHERE blogName = ? AND author = ?
		', array($blogName, $coauthorName));
	}

	public function addCoauthor($blogName, $username){
		$coauthor = array(
			'blogName' => $blogName,
			'author' => '$' . $username
		);

		return DbManager::insert('blog_author',  $coauthor);
	}

	public function blogExists($blogName)
	{
		return DbManager::queryOne('
		SELECT `name`
		FROM `blog`
		WHERE `name` = ?
		', array($blogName));
	}

	public function updateLayout($layout)
	{
		return DbManager::query('
		INSERT INTO blog (name, layout) VALUES (?, ?)
		ON DUPLICATE KEY UPDATE name=VALUES(name), layout=VALUES(layout)', $layout);
	}

	public function getLayout($blogName)
	{
		return DbManager::queryOne('
		SELECT `layout`
		FROM `blog`
		WHERE `name` = ?
		', array($blogName))['layout'];
	}

	public function getBackgroundURL($blogName){
		
		return PicUtils::getBgPicPath("blog", $blogName);
	}
}
