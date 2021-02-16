<?php

//Controller che gestisce la pagina di errore 4040
class ErrorController extends Controller
{
    public function main($params)
    {
        // HTTP header
        header("HTTP/1.0 404 Not Found");
        // HTML header
        $this->head['title'] = 'Error 404';
        // Sets the template
        $this->view = 'error';
    }
}
