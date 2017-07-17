<?php
/**
 * Micro Office Framework: theme variables storage
 *
 * @package	micro_office
 * @since	micro_office 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('micro_office_storage_get')) {
	function micro_office_storage_get($var_name, $default='') {
		global $MICRO_OFFICE_STORAGE;
		return isset($MICRO_OFFICE_STORAGE[$var_name]) ? $MICRO_OFFICE_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('micro_office_storage_set')) {
	function micro_office_storage_set($var_name, $value) {
		global $MICRO_OFFICE_STORAGE;
		$MICRO_OFFICE_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('micro_office_storage_empty')) {
	function micro_office_storage_empty($var_name, $key='', $key2='') {
		global $MICRO_OFFICE_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($MICRO_OFFICE_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($MICRO_OFFICE_STORAGE[$var_name][$key]);
		else
			return empty($MICRO_OFFICE_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('micro_office_storage_isset')) {
	function micro_office_storage_isset($var_name, $key='', $key2='') {
		global $MICRO_OFFICE_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($MICRO_OFFICE_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($MICRO_OFFICE_STORAGE[$var_name][$key]);
		else
			return isset($MICRO_OFFICE_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('micro_office_storage_inc')) {
	function micro_office_storage_inc($var_name, $value=1) {
		global $MICRO_OFFICE_STORAGE;
		if (empty($MICRO_OFFICE_STORAGE[$var_name])) $MICRO_OFFICE_STORAGE[$var_name] = 0;
		$MICRO_OFFICE_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('micro_office_storage_concat')) {
	function micro_office_storage_concat($var_name, $value) {
		global $MICRO_OFFICE_STORAGE;
		if (empty($MICRO_OFFICE_STORAGE[$var_name])) $MICRO_OFFICE_STORAGE[$var_name] = '';
		$MICRO_OFFICE_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('micro_office_storage_get_array')) {
	function micro_office_storage_get_array($var_name, $key, $key2='', $default='') {
		global $MICRO_OFFICE_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($MICRO_OFFICE_STORAGE[$var_name][$key]) ? $MICRO_OFFICE_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($MICRO_OFFICE_STORAGE[$var_name][$key][$key2]) ? $MICRO_OFFICE_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('micro_office_storage_set_array')) {
	function micro_office_storage_set_array($var_name, $key, $value) {
		global $MICRO_OFFICE_STORAGE;
		if (!isset($MICRO_OFFICE_STORAGE[$var_name])) $MICRO_OFFICE_STORAGE[$var_name] = array();
		if ($key==='')
			$MICRO_OFFICE_STORAGE[$var_name][] = $value;
		else
			$MICRO_OFFICE_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('micro_office_storage_set_array2')) {
	function micro_office_storage_set_array2($var_name, $key, $key2, $value) {
		global $MICRO_OFFICE_STORAGE;
		if (!isset($MICRO_OFFICE_STORAGE[$var_name])) $MICRO_OFFICE_STORAGE[$var_name] = array();
		if (!isset($MICRO_OFFICE_STORAGE[$var_name][$key])) $MICRO_OFFICE_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$MICRO_OFFICE_STORAGE[$var_name][$key][] = $value;
		else
			$MICRO_OFFICE_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('micro_office_storage_set_array_after')) {
	function micro_office_storage_set_array_after($var_name, $after, $key, $value='') {
		global $MICRO_OFFICE_STORAGE;
		if (!isset($MICRO_OFFICE_STORAGE[$var_name])) $MICRO_OFFICE_STORAGE[$var_name] = array();
		if (is_array($key))
			micro_office_array_insert_after($MICRO_OFFICE_STORAGE[$var_name], $after, $key);
		else
			micro_office_array_insert_after($MICRO_OFFICE_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('micro_office_storage_set_array_before')) {
	function micro_office_storage_set_array_before($var_name, $before, $key, $value='') {
		global $MICRO_OFFICE_STORAGE;
		if (!isset($MICRO_OFFICE_STORAGE[$var_name])) $MICRO_OFFICE_STORAGE[$var_name] = array();
		if (is_array($key))
			micro_office_array_insert_before($MICRO_OFFICE_STORAGE[$var_name], $before, $key);
		else
			micro_office_array_insert_before($MICRO_OFFICE_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('micro_office_storage_push_array')) {
	function micro_office_storage_push_array($var_name, $key, $value) {
		global $MICRO_OFFICE_STORAGE;
		if (!isset($MICRO_OFFICE_STORAGE[$var_name])) $MICRO_OFFICE_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($MICRO_OFFICE_STORAGE[$var_name], $value);
		else {
			if (!isset($MICRO_OFFICE_STORAGE[$var_name][$key])) $MICRO_OFFICE_STORAGE[$var_name][$key] = array();
			array_push($MICRO_OFFICE_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('micro_office_storage_pop_array')) {
	function micro_office_storage_pop_array($var_name, $key='', $defa='') {
		global $MICRO_OFFICE_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($MICRO_OFFICE_STORAGE[$var_name]) && is_array($MICRO_OFFICE_STORAGE[$var_name]) && count($MICRO_OFFICE_STORAGE[$var_name]) > 0) 
				$rez = array_pop($MICRO_OFFICE_STORAGE[$var_name]);
		} else {
			if (isset($MICRO_OFFICE_STORAGE[$var_name][$key]) && is_array($MICRO_OFFICE_STORAGE[$var_name][$key]) && count($MICRO_OFFICE_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($MICRO_OFFICE_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('micro_office_storage_inc_array')) {
	function micro_office_storage_inc_array($var_name, $key, $value=1) {
		global $MICRO_OFFICE_STORAGE;
		if (!isset($MICRO_OFFICE_STORAGE[$var_name])) $MICRO_OFFICE_STORAGE[$var_name] = array();
		if (empty($MICRO_OFFICE_STORAGE[$var_name][$key])) $MICRO_OFFICE_STORAGE[$var_name][$key] = 0;
		$MICRO_OFFICE_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('micro_office_storage_concat_array')) {
	function micro_office_storage_concat_array($var_name, $key, $value) {
		global $MICRO_OFFICE_STORAGE;
		if (!isset($MICRO_OFFICE_STORAGE[$var_name])) $MICRO_OFFICE_STORAGE[$var_name] = array();
		if (empty($MICRO_OFFICE_STORAGE[$var_name][$key])) $MICRO_OFFICE_STORAGE[$var_name][$key] = '';
		$MICRO_OFFICE_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('micro_office_storage_call_obj_method')) {
	function micro_office_storage_call_obj_method($var_name, $method, $param=null) {
		global $MICRO_OFFICE_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($MICRO_OFFICE_STORAGE[$var_name]) ? $MICRO_OFFICE_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($MICRO_OFFICE_STORAGE[$var_name]) ? $MICRO_OFFICE_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('micro_office_storage_get_obj_property')) {
	function micro_office_storage_get_obj_property($var_name, $prop, $default='') {
		global $MICRO_OFFICE_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($MICRO_OFFICE_STORAGE[$var_name]->$prop) ? $MICRO_OFFICE_STORAGE[$var_name]->$prop : $default;
	}
}
?>