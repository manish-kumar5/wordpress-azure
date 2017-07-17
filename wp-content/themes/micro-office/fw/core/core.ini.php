<?php
/**
 * Micro Office Framework: ini-files manipulations
 *
 * @package	micro_office
 * @since	micro_office 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


//  Get value by name from .ini-file
if (!function_exists('micro_office_ini_get_value')) {
	function micro_office_ini_get_value($file, $name, $defa='') {
		if (!is_array($file)) {
			if (file_exists($file)) {
				$file = micro_office_fga($file);
			} else
				return $defa;
		}
		$name = micro_office_strtolower($name);
		$rez = $defa;
		for ($i=0; $i<count($file); $i++) {
			$file[$i] = trim($file[$i]);
			if (($pos = micro_office_strpos($file[$i], ';'))!==false)
				$file[$i] = trim(micro_office_substr($file[$i], 0, $pos));
			$parts = explode('=', $file[$i]);
			if (count($parts)!=2) continue;
			if (micro_office_strtolower(trim(chop($parts[0])))==$name) {
				$rez = trim(chop($parts[1]));
				if (micro_office_substr($rez, 0, 1)=='"')
					$rez = micro_office_substr($rez, 1, micro_office_strlen($rez)-2);
				else
					$rez *= 1;
				break;
			}
		}
		return $rez;
	}
}

//  Retrieve all values from .ini-file as assoc array
if (!function_exists('micro_office_ini_get_values')) {
	function micro_office_ini_get_values($file) {
		$rez = array();
		if (!is_array($file)) {
			if (file_exists($file)) {
				$file = micro_office_fga($file);
			} else
				return $rez;
		}
		for ($i=0; $i<count($file); $i++) {
			$file[$i] = trim(chop($file[$i]));
			if (($pos = micro_office_strpos($file[$i], ';'))!==false)
				$file[$i] = trim(micro_office_substr($file[$i], 0, $pos));
			$parts = explode('=', $file[$i]);
			if (count($parts)!=2) continue;
			$key = trim(chop($parts[0]));
			$rez[$key] = trim($parts[1]);
			if (micro_office_substr($rez[$key], 0, 1)=='"')
				$rez[$key] = micro_office_substr($rez[$key], 1, micro_office_strlen($rez[$key])-2);
			else
				$rez[$key] *= 1;
		}
		return $rez;
	}
}
?>