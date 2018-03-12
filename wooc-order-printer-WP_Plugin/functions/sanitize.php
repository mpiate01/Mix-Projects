<?php
function escape($string)
{
	return htmlentities($string, ENT_QUOTES, 'UTF-8');  //ent_quotes aiuta ad aumentare la sicurezza
}