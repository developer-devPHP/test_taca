<?php
class MyLib_Validators_UrlValidator extends Zend_Validate_Abstract {
	const INVALID_URL = 'invalidUrl';
	protected $_messageTemplates = array (
			self::INVALID_URL => "'%value%' invalid url example http(s)://example.com" 
	);
	public function isValid($value) {
		$valueString = ( string ) $value;
		$this->_setValue ( $valueString );
		
		$regex = new Zend_Validate_Regex ( '/^(http(?:s)?\:\/\/[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*\.[a-zA-Z]{2,6}(?:\/?|(?:\/[\w\-]+)*)(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$/' );
		if(!$regex->isValid($value))
		{
			$this->_error(self::INVALID_URL);
			return false;
		}
		return true;
		/*
		 * if (!Zend_Uri::check($value)) { $this->_error(self::INVALID_URL);
		 * return false; } return true;
		 */
	}
}
