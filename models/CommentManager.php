<?php

class CommentManager
{
	
	public function getComments($blogName, $timestamp, $oc, $nb)
	{
		return DbManager::queryAll('
		SELECT `text`, `username`, `timestamp`
		FROM `comment`
		WHERE `blogNamePost` = ? AND `timestampPost` = ?
		LIMIT ?, ?
		', array($blogName, $timestamp, $oc, $nb));
	}
	public function saveComment($c)
	{
		DbManager::insert('comment', $c);
	}

	public function removeComment($username, $timestamp)
	{
		DbManager::query('
		DELETE FROM `comment`
		WHERE `username` = ? AND `timestamp` = ?
		', array($username, $timestamp));
	}

}
