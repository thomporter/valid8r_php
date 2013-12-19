<?php
namespace CustomValidatorNamespace;

class CustomNamespacedValidatorClass {
	static public function static_validator($field, $value) {
		if ($value != 'custom') return 'Please enter the correct value.';
		return '';
	}
	public function instance_validator($field, $value) {
		if ($value != 'custom') return 'Please enter the correct value.';
		return '';
	}
}
