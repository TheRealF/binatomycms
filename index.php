<?php

session_start();

//Impostazione dell'encoding interno per il corretto funzionamento delle funzioni di manipolazione delle stringhe
mb_internal_encoding("UTF-8");

//Funzione di callback di autoload
function autoloadFunction($class)
{
  // Controlla se la stringa termina con "Controller"
  if (preg_match('/Controller$/', $class)){
    require("controllers/" . $class . ".php");
  } else if (preg_match('/Manager$/', $class)){
    require("models/" . $class . ".php");
  } else {
    require("utils/" . $class . ".php");
  }
}

//Registrazione della funzione callback di autoload
spl_autoload_register("autoloadFunction");

//Connessione al database
DbManager::connect("127.0.0.1", "root", "", "cms");

//Creazione del router ed elaborazione dell'URL inserito dall'utente
$router = new RouterController();
$router->main(array($_SERVER['REQUEST_URI']));

$router->renderView();
