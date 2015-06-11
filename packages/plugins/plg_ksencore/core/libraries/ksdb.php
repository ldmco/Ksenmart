<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class KSDb{
	
	protected static $nameQuote = '`';

	public static function quoteName($name, $as = null) {
		if (is_string($name)) {
			$quotedName = self::quoteNameStr(explode('.', $name));
			
			$quotedAs = '';
			
			if (!is_null($as)) {
				settype($as, 'array');
				$quotedAs.= ' AS ' . self::quoteNameStr($as);
			}

			return $quotedName . $quotedAs;
		} else {
			$fin = array();
			
			if (is_null($as)) {
				
				foreach ($name as $str) {
					$fin[] = self::quoteName($str);
				}
			} elseif (is_array($name) && (count($name) == count($as))) {
				$count = count($name);
				
				
				for ($i = 0;$i < $count;$i++) {
					$fin[] = self::quoteName($name[$i], $as[$i]);
				}
			}
			
			
			return $fin;
		}
	}


    public static function escape($db, $text){
        return $db->escape($text);
    }

	/**
	 * Quote strings coming from quoteName call.
	 *
	 * @param   array  $strArr  Array of strings coming from quoteName dot-explosion.
	 *
	 * @return  string  Dot-imploded string of quoted parts.
	 *
	 * @since 11.3
	 */
	protected static function quoteNameStr($strArr) {
		$parts = array();
		$q = self::$nameQuote;
		
		
		foreach ($strArr as $part) {
			if (is_null($part)) {
				
				continue;
			}
			
			if (strlen($q) == 1) {
				$parts[] = $q . $part . $q;
			} else {
				$parts[] = $q{0} . $part . $q{1};
			}
		}
		
		return implode('.', $parts);
	}
}