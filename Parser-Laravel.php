<?php  
namespace App\Services;

class Parser{

	/**
	 * Directly modifies the content.
	 * @param $content (Pointer Reference)
	 * @param $arr
	 * @param $prefix string A prefix such as "user" to be added before each $arr fieldname.
	 *      Eg. user.firstname
	 *      The template content would have {user.firstname}
	 * @param $cleanUpAfterParse bool
	 * @return mixed
	 */
	function parseThis(&$content, $source, $prefix = '', $cleanUpAfterParse = false) {
		//debug_pre($arr,'parseThis $arr',0);

		if(is_array($source)){
			foreach($source as $key => $val) {
				if(!is_string($val) && !is_numeric($val)) {
					continue;
				}
	
				if($prefix == '') {
					$content = str_replace('{'.$key.'}',$val,$content);
				}
				else {
					$content = str_replace('{'.$prefix.'.'.$key.'}',$val,$content);
				}
			}
		}

		if(is_object($source)){
			$variables = getContents($content, '{', '}');
			
			foreach($variables as $key) {
				if(!is_string($key) && !is_numeric($key)) {
					continue;
				}
				if($prefix != '') {
					$key = str_replace($prefix, '', $key);
				}

				if(isset($source->{$key})){
					$val = $source->{$key};
					if($prefix == '') {
						$content = str_replace('{'.$key.'}',$val,$content);
					}
					else {
						$content = str_replace('{'.$prefix.'.'.$key.'}',$val,$content);
					}
				}
			}

		}
		

		if($cleanUpAfterParse) {
			$this->cleanUp($content);
		}

		return $content;
	}
	
	/*
		Remove any and all key tags.
		Use this after parsing to be sure there are no tags that have not been replaced yet.
		
		Key tag contents can be A-Z,a-z,0-9 
		and any of these characters-->  .-_
		
		examples:
			{affiliate.firstname}
			{event-details}
	*/
	function cleanUp(&$content) {

		$pattern = '/\{[\w\d\-_\.]+\}/i';

		$replacement = '';

		$content = preg_replace($pattern, $replacement, $content);

		return $content;
	}
	
} // END Class
?>