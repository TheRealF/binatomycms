<?php

class SearchManager
{
  public function searchBlog($search)
  {
    try
		{
    return DbManager::queryAll('
      SELECT `name`
      FROM `blog`
      WHERE `name` LIKE ?
    ', array($search));
  }
    catch (PDOException $ex)
		{
			throw new Exception('Attenzione errore!' . $ex);
		}
  }
  public function searchUsers($search)
  {
    try
		{
    return DbManager::queryAll('
      SELECT DISTINCT `blogName` as `name`
      FROM `blog_author`
      WHERE `author` LIKE ?
    ', array($search));
  }
    catch (PDOException $ex)
		{
			throw new Exception('Attenzione errore!' . $ex);
		}
  }
  public function searchThemes($search)
  {
    try
		{
    return DbManager::queryAll('
      SELECT DISTINCT `blogName` as `name`
      FROM `about`
      WHERE `topicName` LIKE ?
    ', array($search));
  }
    catch (PDOException $ex)
		{
			throw new Exception('Attenzione errore!' . $ex);
		}
  }
  public function searchGlobal($search, $search1)
  {
    try
    {
    return DbManager::queryAll('
    SELECT DISTINCT `blogName` as `name`
    FROM `blog_author`
    WHERE (`author` LIKE ?) OR (`blogName` LIKE ?)
    ', array($search, $search1));
  }
    catch (PDOException $ex)
    {
      throw new Exception('Attenzione errore!' . $ex);
    }
  }
  public function searchPost($search)
  {
    try
		{
    return DbManager::queryAll('
      SELECT `title`, `timestamp`, `content`
      FROM `post`
      WHERE `title` LIKE ?
    ', array($search));
  }
    catch (PDOException $ex)
		{
			throw new Exception('Attenzione errore!' . $ex);
		}
  }
  }
