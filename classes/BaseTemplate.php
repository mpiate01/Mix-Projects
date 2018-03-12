<?php
/*
*
*	Interface to be used by output's templates
*
*/
interface BaseTemplate
{
	public function magnifier($img_details);
	public function gallery($img_details);
	public static function errMess($message);
	public function upload($action, $title, $descr, $err);
	public function create_page($content,$h1);
	public function header($h1);
	public function footer();		
}