<?php
namespace Ibw\JobeetBundle\Utils;

class Jobeet 
{
	public static function slugify($text)
	{	
		// \pL for matching Unicode letters
		// u for turning utf-8 features on
		// Replace anything other than '\', Unicode letters or digits with '-'
		$text = preg_replace('#[^\\pL\d]+#u', '-', $text);

		$text = trim($text, '-');

		if(function_exists('iconv'))
		{	
			// Converts any utf-8 character to its ascii replacement.
			// TRANSLIT : To translate 
			//    IGNORE: To ignore
			$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		}

		$text = strtolower($text);

		//Replaces anything other than - or an alphanumeric character with ''
		$text = preg_replace('#[^-\w]+#', '', $text);

		if(empty($text)) $text = "n-a";

		return $text;
	}
}
?>