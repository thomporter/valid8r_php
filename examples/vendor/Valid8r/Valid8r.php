<?php
/******************************************************************************
The MIT License (MIT)

Copyright (c) 2013 Thom Porter (www.thomporter.com)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
******************************************************************************/

namespace Valid8r;

class Valid8r {

	/**
	 * @var array $res Regular Expressions used by the validators...
	 */
	static $res = Array(
		'alpha' => '/^[A-z]+$/',
		'num' => '/^-?[0-9]+$/',
		'numNonNeg' => '/^[0-9]+$/',
		'alnum' => '/^[A-z0-9]+$/',
		'email_simple' => '#^[^@]+@[a-z0-9_-]+\.[a-z0-9_.-]{2,}$#',
		'email_default' => '#^[a-z0-9!\\#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!\\#$%&\'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)$#i',
		'email_rfc5322' => '#(?:[a-z0-9!\\#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!\\#$%&\'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])#',
	);

	/**
	 * @var Array of rules
	 */
	private $rules = null;
	
	/**
	 * @var Array (associative) of data being validated - must include data for conditions.
	 */
	private $fields = null;

	/**
	 * @param $options Array of options to pass to the constructor
	 */
	public function __construct($options = Array()) {

		$default_options = Array(
		);

		if (!empty($options['rules']))
		{
			$this->rules = $options['rules'];
			$options['rules'] = null;
		}
		
		if (!empty($options['fields']))
		{
			$this->rules = $options['fields'];
			$options['fields'] = null;
		}
		
		$this->options = $options + $default_options;

	}

	/**
	 * Set the rules for the validator to use.
	 * @param Array $rules
	 */
	public function setRules($rules) {
		$this->rules = $rules;
	}

	/**
	 * Set the rules for the validator to use directly from the JSON config
	 * @param String $file
	 */
	public function setRulesFromFile($file) {
		$this->rules = json_decode(file_get_contents($file));
		
	}
	/**
	 * Get the rules...
	 * @param String $file
	 */
	public function getRules() {
		return $this->rules;
		
	}

	/**
	 * Set & Validate Data
	 * @param Array $fields Associative array of the fields
	 * @return Array Array of errors
	 */
	public function validateFields($fields) {
		$results = Array();
		$this->fields = $fields;
		foreach($this->rules as $field=>$options) {
			if ($err = $this->validate($field, @$fields[$field])) {
				$results[$field] = $err;
			}
		}
		return $results;
	}

	/**
	 * Validate Data already passed to Valid8r (like via constructor options.)
	 * @return Array Array of errors
	 */
	public function validateAll() {
		$results = Array();
		foreach($this->rules as $field=>$options) {
			if ($err = $this->validate($field, @$this->fields[$field])) {
				$results[$field] = $err;
			}
		}
		return $results;
	}

	/**
	 * Generates an error string
	 * @param $field
	 * @param $rule
	 * @param string $defaultString
	 * @param array $args
	 * @return string
	 */
	private function errStr($field, $rule, $defaultString = 'Field Error', $args = array()) {
		if (@$rule->errStr) {
			$defaultString = $rule->errStr;
		}
		if (!empty($args)) return vsprintf($defaultString, $args);
		return $defaultString;
	}

	/**
	 * Validate a single field/value
	 * @param String $field
	 * @param String $value
	 * @return string
	 */
	public function validate($field, $value) {
		
		if (!empty($this->rules->$field->rules)) {
			foreach((array)$this->rules->$field->rules as $sel=>$rule) {
				if (@$rule->when && !$this->satisfiesCondition($this->rules->$field->conditions->{$rule->when}, $rule)) continue;
				switch($rule->rule) {
					case 'required': $err = $this->validRequired($field, $value, $rule); break;
					case 'len': $err = $this->validLen($field, $value, $rule); break;
					case 'isAlpha': $err = $this->validIsAlpha($field, $value, $rule); break;
					case "isNum" : $err = $this->validIsNum($field, $value, $rule); break;
					case "isAlnum" : $err = $this->validIsAlnum($field, $value, $rule); break;
					case "formattedAs": $err = $this->validFormat($field, $value, $rule); break;
					case "regex": $err = $this->validRegex($field, $value, $rule); break;
					case "val": $err = $this->validVal($field, $value, $rule); break;
					case "email": $err = $this->validEmail($field, $value, $rule); break;
					case "url": $err = $this->validUrl($field, $value, $rule); break;
					case "checks": $err = $this->validChecks($field, $value, $rule); break;
					case "radios": $err = $this->validRadios($field, $value, $rule); break;
					case "custom": $err = $this->validCustom($field, $value, $rule); break;
					default: $err = "Invalid rule: $rule->rule"; break;
				}
				if ($err) return $err;
			}
		}
		return '';
	}
	
	public function satisfiesCondition($condition, $rule) {
		$value = @$this->fields[$condition->field];
		return ($condition->is == $value); 
		
	}

	/**
	 * Validate the length of a string.
	 * @param String $field
	 * @param String $value
	 * @param Object $rule
	 * @return string
	 */
	public function validRequired($field, $value, $rule) {
		
		if ($value === null || $value === '') {
			return $this->errStr($field, $rule, 'This field is required.');
		}
		return '';
	}
	public function validCustom($field, $value, $rule) {
		$callable = $rule->func;
		if (@$rule->php_static_class)
		{
			$callable = $rule->php_static_class . '::' .$callable;
			if (@$rule->php_namespace)
			{
				$callable = $rule->php_namespace . '\\' . $callable;
			}
			$err = call_user_func($callable, $field, $value);
		} elseif (@$rule->php_class) {
			$class = $rule->php_class;
			if (@$rule->php_namespace)
			{
				$class = $rule->php_namespace . '\\' . $class;
			}
			$o = new $class;
			$err = call_user_func(array($o, $callable), $field, $value);
		} else {
			$err = call_user_func($callable, $field, $value);
		}
		return $err;
	}
	
	public function validLen($field, $value, $rule) {
		$len = strlen($value);
		if (@$rule->min && @$rule->max) {
			if ($len < $rule->min || $len > $rule->max) {
				return $this->errStr($field, $rule, 'Between %d and %d characters required.', array($rule->min, $rule->max));
			}
		}

		if (@$rule->min) {
			if ($len < $rule->min) {
				return $this->errStr($field, $rule, 'At least %d characters are required.', Array($rule->min));
			}
		} else if (@$rule->max) {
			if ($len > $rule->max) {
				return $this->errStr($field, $rule, 'At least %d characters are required.', Array($rule->max));
			}
		}
		return '';
	}

	public function validIsAlpha ($field, $value, $rule) {
		if ($value != '' && !preg_match( self::$res['alpha'], $value)) {
			return $this->errStr($field, $rule, 'Please enter alphabetic characters only (a-z).');
		}
		return '';
	}

	public function validIsNum ($field, $value, $rule) {
		if ($value != '') {
			if (@$rule->nonNeg) {
				if (!preg_match(self::$res['numNonNeg'], $value)) {
					return $this->errStr($field, $rule, 'Please enter numeric characters only (0-9).');
				}
			}
			if (!preg_match(self::$res['num'], $value)) {
				return $this->errStr($field, $rule, 'Please enter numeric characters only (0-9).');
			}
		}
		return '';
	}

	public function validIsAlnum($field, $value, $rule) {
		if ($value != '' && !preg_match(self::$res['alnum'],$value)) {
			return $this->errStr($field, $rule, 'Please enter alphanumeric characters only (a-z, 0-9).');
		}
		return '';
	}

	public function validFormat($field, $value, $rule) {
		if ($value != '') {
			$format_re = str_replace('D','\\d', str_replace('A','[A-Z]', preg_quote($rule->format, '#')));
			if (!preg_match("#$format_re#i", $value)) {
				return $this->errStr($field, $rule, 'Does not match required format of: ' . $rule->format);
			}
		}
		return '';
	}

	public function validRegex($field, $value, $rule) {
		if ($value != '') {
			if (!preg_match('#'.$rule->pattern.'#'.$rule->modifiers, $value)) {
				return $this->errStr($field, $rule, 'Does not match required pattern: ' . $rule->pattern);
			}
		}
	}

	public function validVal ($field, $value, $rule) {
		if ($value != '') {
			$v = (int)$value;
			
			if (!is_numeric($value))
				return $this->errStr($field, $rule, 'Please enter a number.');
			
			if (@$rule->is) {
				if ($rule->is != $v) {
					return $this->errStr($field, $rule, 'Please enter ' . $rule->is);
				}
			} elseif (@$rule->min) {
				if ($v < $rule->min) {
					return $this->errStr($field, $rule, 'Please enter a number greater than or equal to ' . $rule->min);
				}
			} elseif (@$rule->max ) {
				if ($v > $rule->max) { 
					return $this->errStr($field, $rule, 'Please enter a number less than or equal to ' . $rule->max); 
				}
			} elseif (@$rule->outside) {
				if ($v >= $rule->outside[0] || $v <= $rule->outside[1]) {
					return $this->errStr($field, $rule, 'Please enter a number outside of %d-%d', Array($rule->outside[0],$rule->outside[1]));
				}
			}
		}
		return '';
	}
	
	public function validEmail($field, $value, $rule) {
		if ($value != '') {
			if (@$rule->validator) $em_re = $rule->validator;
			else $em_re = 'default'; 
			if (empty(self::$res['email_'.$em_re]))
				return 'INVALID EMAIL VALIDATOR: ' . $em_re;
			if (!preg_match(self::$res['email_'.$em_re], $value)) 
				return $this->errStr($field, $rule, 'Please enter a valid email address.');
			
		}
		return '';
	}
	
	public function validUrl($field, $value, $rule) {
		if ($value != '') {
			$parts = parse_url($value);
			if (empty($parts['host'])) {
				return $this->errStr($field, $rule, 'Please enter a valid URL.');
			}
			if (isset($rule->protocols)) {
				if (is_array($rule->protocols)) {
					$protos = $rule->protocols;
				} else {
					$protos = explode(',', $rule->protocols);
				}
				if (!in_array($parts['scheme'], $protos))
					return $this->errStr($field, $rule, 'Please enter a valid URL.');
			} elseif (isset($rule->noProtocols) && !empty($parts['scheme'])) {
				return $this->errStr($field, $rule, 'Please enter a URL without the protocol (eg, http://, https://, etc...)');
			}
			
		}
		return '';
	}

	public function validChecks($field, $value, $rule) {
		$num_checked = is_array($value) ? count($value) : 0;

		if (@$rule->min && @$rule->max) {
			if ($rule->min > $num_checked || $rule->max < $num_checked) {
				return $this->errStr($field, $rule, 'Please check between %d and %d options.', Array($rule->min,$rule->max));
			}
		} elseif (@$rule->min) {
			if ($rule->min > $num_checked) {
				return $this->errStr($field, $rule, 'Please check at least %d options.', Array($rule->min));
			}
		} elseif (@$rule->max) {
			if ($rule->max < $num_checked) {
				return $this->errStr($field, $rule, 'Please check no more than %d options.', Array($rule->max));
			}
		}

		return '';

	}
	public function validRadios($field, $value, $rule) {
		if (empty($value)) return $this->errStr($field, $rule, 'Please choose one.');

		return '';

	}
}