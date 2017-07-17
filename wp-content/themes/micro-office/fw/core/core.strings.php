<?php
/**
 * Micro Office Framework: strings manipulations
 *
 * @package	micro_office
 * @since	micro_office 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'MICRO_OFFICE_MULTIBYTE' ) ) define( 'MICRO_OFFICE_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('micro_office_strlen')) {
	function micro_office_strlen($text) {
		return MICRO_OFFICE_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('micro_office_strpos')) {
	function micro_office_strpos($text, $char, $from=0) {
		return MICRO_OFFICE_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('micro_office_strrpos')) {
	function micro_office_strrpos($text, $char, $from=0) {
		return MICRO_OFFICE_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('micro_office_substr')) {
	function micro_office_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = micro_office_strlen($text)-$from;
		}
		return MICRO_OFFICE_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('micro_office_strtolower')) {
	function micro_office_strtolower($text) {
		return MICRO_OFFICE_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('micro_office_strtoupper')) {
	function micro_office_strtoupper($text) {
		return MICRO_OFFICE_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('micro_office_strtoproper')) {
	function micro_office_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<micro_office_strlen($text); $i++) {
			$ch = micro_office_substr($text, $i, 1);
			$rez .= micro_office_strpos(' .,:;?!()[]{}+=', $last)!==false ? micro_office_strtoupper($ch) : micro_office_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('micro_office_strrepeat')) {
	function micro_office_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('micro_office_strshort')) {
	function micro_office_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= micro_office_strlen($str)) 
			return strip_tags($str);
		$str = micro_office_substr(strip_tags($str), 0, $maxlength - micro_office_strlen($add));
		$ch = micro_office_substr($str, $maxlength - micro_office_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = micro_office_strlen($str) - 1; $i > 0; $i--)
				if (micro_office_substr($str, $i, 1) == ' ') break;
			$str = trim(micro_office_substr($str, 0, $i));
		}
		if (!empty($str) && micro_office_strpos(',.:;-', micro_office_substr($str, -1))!==false) $str = micro_office_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('micro_office_strclear')) {
	function micro_office_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (micro_office_substr($text, 0, micro_office_strlen($open))==$open) {
					$pos = micro_office_strpos($text, '>');
					if ($pos!==false) $text = micro_office_substr($text, $pos+1);
				}
				if (micro_office_substr($text, -micro_office_strlen($close))==$close) $text = micro_office_substr($text, 0, micro_office_strlen($text) - micro_office_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('micro_office_get_slug')) {
	function micro_office_get_slug($title) {
		return micro_office_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('micro_office_strmacros')) {
	function micro_office_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('micro_office_unserialize')) {
	function micro_office_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			return $data;
		} else
			return $str;
	}
}
?>