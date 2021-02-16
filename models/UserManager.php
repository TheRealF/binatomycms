<?php

class UserManager
{

	//Utilizzo dell'algoritmo bcrypt per il salting e lo hashing della password
	public function computeHash($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}

	public function register($username, $password, $document, $phone, $email)
	{
		$usr = array(
			'username' => $username,
			'password' => $this->computeHash($password),
			'document' => $document,
			'phone' => $phone,
			'email' => $email
		);
		try
		{
			DbManager::insert('users', $usr);
		}
		catch (PDOException $ex)
		{
			throw new Exception('Errore, (l\'utente potrebbe essere già stato registrato)' . $ex->getMessage());
		}
	}

	public function login($username, $password)
	{
		$usr = DbManager::queryOne('
		SELECT username, password
		FROM users
		WHERE username = ?
		', array($username));
		if (!$usr || !password_verify($password, $usr['password'])){ //password_verify è la funzione speculare (quindi di controllo) di password_hash
			throw new Exception('Nome utente o password non validi.');
		}
		$_SESSION['username'] = $usr;
	}

	public function logout()
	{
		unset($_SESSION['username']);
	}

	public function getUser()
	{
		if (isset($_SESSION['username'])){
			return $_SESSION['username'];
		}
		return null;
	}

	public function usernameExists($name)
 	{
		return DbManager::queryOne('
		SELECT `username`
		FROM `users`
		WHERE `username` = ?
		', array($name));
 	}

	public function mailExists($email)
	{
		return DbManager::queryOne('
		SELECT `email`
		FROM `users`
		WHERE `email` = ?
		', array($email));
	}

	public function documentExists($document)
	{
		return DbManager::queryOne('
		SELECT `document`
		FROM `users`
		WHERE `document` = ?
		', array($document));
	}

	public function phoneExists($phone)
	{
		return DbManager::queryOne('
		SELECT `phone`
		FROM `users`
		WHERE `phone` = ?
		', array($phone));
	}
}
