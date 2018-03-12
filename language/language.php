<?php
/*****

$GLOBALS['errors'] array used to display errors based on the application language selected in config.inc
 
******/


$GLOBALS['errors'] = array(
	'en' => array(
		'404' => 'Oops, something went wrong! ERROR 404',
		'connession' => 'An error has occured while connecting with the Database!',
		'query' => 'Error while querying the database!',
		'file' => 'Error, a template file is missing!',
		'double' => 'File already exists - file not uploaded!',
		'typeimg' => 'Error. Wrong format uploaded!',
		'img404' => 'Image not found!'
		),
	'it' => array(
		'404' => 'Oops, pagina non trovata! ERRORE 404',
		'connession' => 'Errore nel connettersi al database!',
		'query' => 'Errore durante la richiesta di dati dal database!',
		'file' => 'Errore, template file mancante!',
		'double' => 'Una file simile e gia presente - file non caricato!',
		'typeimg' => 'Errore. Estensione non permessa!',
		'img404' => 'Immagine non trovata!'
		)
);