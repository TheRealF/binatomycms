<?php

//Wrapper CRUD
class DbManager
{

	//Impostazioni di PDO
	private static $settings = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		PDO::ATTR_EMULATE_PREPARES => false,
	);

	//Proprietà per connessione al DB
	private static $connection;

	//Funzione di connessione al database. Prende le credenziali come parametri in ingresso (Vedi index.php)
	public static function connect($host, $user, $password, $database) {
		if (!isset(self::$connection)) {
			self::$connection = @new PDO(
				"mysql:host=$host;dbname=$database",
				$user,
				$password,
				self::$settings
			);
		}
	}

	/**
	* Esegue una query e restituisce la prima riga del risultato.
	* I parametri sono passati in un array
	* Viene restituito un array associativo che rappresenta la riga, oppure false
	*/
	public static function queryOne($query, $params = array())
	{
		$result = self::$connection->prepare($query);
		$result->execute($params);
		return $result->fetch();
	}

	//Esegue una query e restituisce tutte le righe risultanti come un array di array associativi
	public static function queryAll($query, $params = array(), $fetchStyle = PDO::FETCH_BOTH)
	{
		$result = self::$connection->prepare($query);
		$result->execute($params);
		return $result->fetchAll($fetchStyle);
	}


	//Esegue una query e restituisce il numero di righe interessate
	public static function query($query, $params = array())
	{
		$result = self::$connection->prepare($query);
		$result->execute($params);
		return $result->rowCount();
	}

	/**
	* Inserisce dei dati in una nuova riga da un array associativo.
	* Le chiavi dell'array associativo rappresentanno le colonne, i suoi valori i loro valori nel db
	*/
	public static function insert($table, $params = array())
	{
		return self::query("INSERT INTO `$table` (`".
			implode('`, `', array_keys($params)).
			"`) VALUES (".str_repeat('?,', sizeof($params)-1)."?)",
			array_values($params));
		}

		//Come insert(), ma se vi è un record duplicato la riga non viene inserita
		public static function safeInsert($table, $params = array())
		{
			return self::query("INSERT IGNORE INTO `$table` (`".
				implode('`, `', array_keys($params)).
				"`) VALUES (".str_repeat('?,', sizeof($params)-1)."?)",
				array_values($params));
			}

		}
