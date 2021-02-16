<?php

class PostManager
{

	public function getPost($url)
	{
		return DbManager::queryOne('
		SELECT `blogName`, `title`, `content`,  CONCAT(`title`, `timestamp`) as url, `timestamp`
		FROM `post`
		WHERE CONCAT(`title`, `timestamp`) = ?
		', array($url));
	}


	public function getPosts($blogName, $ot, $np)
	{
		return DbManager::queryAll('
		SELECT `blogName`, `title`,  CONCAT(`title`, `timestamp`) as url, `content`, `timestamp`
		FROM `post`
		WHERE `blogName` = ?
		LIMIT ?, ?
		', array($blogName, $ot, $np));
	}

	public function savePost($p)
	{
		DbManager::insert('post', $p);
	}


	public function removePost($url)
	{
		DbManager::query('
		DELETE FROM post
		WHERE CONCAT(`title`, `timestamp`) = ?
		', array($url));
	}

	public function saveAuthor($username, $timestamp, $blogName){
		$arr = array(
			"username" => $username,
			"timestampPost" => $timestamp,
			"blogNamePost" => $blogName
		);
		$userAsCoauthor = "$" . $username;
		$isCoauthor = DbManager::queryOne('
		SELECT author
		FROM blog_author
		WHERE blogName = ? AND author = ?
		', array($blogName, $userAsCoauthor));
		if ($isCoauthor) {
			$arr["username"] = $userAsCoauthor;
		}
		DbManager::insert('post_author', $arr);
	}

	public function getPostTitle(){

	}
	public function getPostAuthor($postTimestamp, $blogName) {
		return DbManager::queryOne("
		SELECT username
		FROM post_author
		WHERE timestampPost = ? AND blogNamePost = ?
		", array($postTimestamp, $blogName));
	}

	public function getBackgroundURL($blogName){

		return PicUtils::getBgPicPath("post", $blogName);
	}
}
