<?php
namespace JarvisPHP\Core;

/**
 * JarvisAil
 *
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class JarvisBehaviourLanguage {

	public static $jbl_set = array();

	public function loadBehaviourLanguage() {
		if(file_exists('language/jbl_'._LANGUAGE.'.jbl')) {
			JarvisBehaviourLanguage::$jbl_set = json_decode(file_get_contents('language/jbl_'._LANGUAGE.'.jbl'));
		}
	}

	public function answer($sentence) {
		foreach(JarvisBehaviourLanguage::$jbl_set->rules as $rule) {
			foreach($rule->matches as $match) {
				if(preg_match($match, $sentence)) {
					return $rule->responses[array_rand($rule->responses)];
				}
			}
		}
		return false;
	}

}