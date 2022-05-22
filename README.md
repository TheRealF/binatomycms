# Binatomy CMS

## PROGETTO DI BASI DI DATI, UNIPI, ANNO ACCADEMICO 2020/2021

Per la realizzazione del progetto di Basi di Dati, di seguito denominato “Binatomy CMS”, è stato utilizzato il pattern architetturale MVC (Model View Controller).
La struttura del progetto è suddivisa in tre tipologie di componenti che insieme costituiscono l’applicazione: i model, che si occupano di interrogare il database con apposite query, le view, pagine web che contengono l’interfaccia grafica e presentano gli output, e i controller, il cui compito è far interagire model e view.

Come sistema di accesso al database è stato scelto PDO (PHP Data Object).
La scelta di usare PDO è stata dettata dal fatto che è dotato di una sola interfaccia capace di comunicare con diversi DBMS. Nell’ottica di un futuro riutilizzo e ampliamento di Binatomy CMS, questo rende agevole il passaggio a sistemi diversi da MySQL.

Tutte le query utilizzate vengono trattate sotto forma di prepared statements. Questa scelta è dovuta in primo luogo al fatto che i prepared statements riducono il tempo di parsing della query da parte del server, poiché la preparazione della query avviene una volta sola; inoltre i prepared statements sono efficaci nella protezione contro attacchi di SQL Injection: essendo le query pre-compilate, l’input utente non può interferire con il codice della query.

Sempre per la gestione della sicurezza dell’applicazione è stato adottato l’utilizzo della libreria HTMLPurifier che, sanificando il contenuto dei post, protegge il sistema da eventuali attacchi XSS. Inoltre gli input vengono controllati e validati da funzioni implementate appositamente per questo compito.

Per ottimizzare le performance e la resa visiva dell’applicazione è stato implementato un sistema di rendering delle views, salvate in formato .phtml, che vengono così generate separatamente e dinamicamente attraverso un sistema di templating. Sono state adottate diverse soluzioni per l’ottimizzazione delle performance come l’adozione del formato .webp per le immagini e l’uso di pagination.

Per quanto riguarda i nomi dei file e la struttura del progetto sono state utilizzate delle convenzioni:

1. I nomi delle tabelle mySQL sono tutti minuscoli e  in inglese;
2. I controller e i model utilizzano la scrittura camel case: BlogController (controller relativo alla pagina blog), UserManager (model che gestisce le interrogazioni del database relative alla tabella utenti), ecc.
3. I nomi delle viste (views) sono tutti minuscoli (ad es. homepage.phtml)

Tale sistema di nomenclatura è alla base del funzionamento del metodo autoloadFunction() contenuto in index.php: tale metodo è registrato come funzione autoload di PHP (spl_autoload_register()): si occupa di caricare automaticamente le classi istanziate nel programma, senza dover scrivere all’inizio di ogni file una lunga lista di “include” o “require”.
I controller sono tutti derivati dal controller astratto (Controller.php) dal quale ereditano proprietà e metodi fondamentali al funzionamento dell’applicazione, quali ad esempio le funzioni di redirect, di rendering delle viste e di gestione delle eccezioni. Inoltre per la gestione dei controller è stato implementato un RouterController che riceve un indirizzo URL, lo processa e chiama il controller appropriato in base all’informazione fornitagli. Questo consente in seguito al controller di processare la richiesta e mostrare la vista relativa.
Tale procedimento ha consentito, attraverso le giuste impostazioni del file .htaccess, di implementare un sistema di pretty urls: durante l’utilizzo del sito non è mai visibile la struttura delle directory e dei file nel web server. Gli URL sono formati con una logica più user-friendly: 
Esempio: 
www.dominio.com/blog/IlMioBlogPreferito

La relazione che documenta la realizzazione del progetto è allegata alla presente repo con il nome Relazione.pdf

