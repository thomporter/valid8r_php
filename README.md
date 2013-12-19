![Valid8r - Validation for multiple programming languages.](https://raw.github.com/thomporter/valid8r/master/logo.png)

# Valid8r for PHP

Valid8r is a validation library for multiple programming languages using a common
JSON configuration file. Learn more about Valid8r and the configuration file
at:

https://github.com/thomporter/valid8r

## Installation

Valid8r for PHP really only has one file you need.  If you don't care about
testing, examples, etc, you can simply grab the `Valid8r.php` file from the
`lib/Valid8r` folder: 

https://githb.com/thomporter/valid8r_php/lib/Valid8r/Valid8r.php

Alternatively, you can clone this rep and get examples & tests.

You can also install via Composer:

	{
	  "require": {
	    "valid8r/valid8r_php": "v0.0.2"
	  }
	}
	
## Examples

Get the Examples folder running on an PHP enabled web server and you 
can check out the Kitchen Sink examples.  

Here's a quick idea of how it works in PHP:

	<?php
	$validator = new Valid8r();
	$validator->setRules($rules);
	$validator->setData($_POST);
	$errors = $validator->validate();
	// errors is now an associative array, where the keys are fields with 
	// errors, and values are the errors themselves.  
	// Fields with no errors are not in the array.
	
	if (!empty($errors)) {
		// there was at least one error in the form...
	}
	
	if (@$errors['field_name']) {
		// field_name has an error, the error string is in $errors['field_name']
	}
    

## Custom Validators

For general information about Custom Validators, see the main 
[Valid8r docs](https://github.com/thomporter/valid8r)

Valid8r for PHP supports custom validators via any of the following:

* Standard Functions
* Static Methods of Classes
* Instance Methods of Classes

No matter which you use, your function must accept 2 arguments ($field & $value)
and must either return a string to use as the error, or a blank string if no error.

### Custom Validators using Standard Functions

Just use the function name as the value for the `func` property of your custom
rule and Valid8r will access it directly.

### Custom Validators using Static Methods

You can add a `php_static_class` to your custom rule and Valid8r will combine 
that with the `func` property to build the call: 
 
	$php_static_class::$func($field, $value)

You can also add a `php_namespace` which will cause Valid8r to make the call 
like so:

	$php_namespace\$php_static_class::$func($field, $value)

### Custom Validators using Instance Methods

If you need Valid8r to initialize a class, and then call a method on it, 
this is the options for you.  Add a `php_class` property, and Valid8r will
make the call like so:

	$validator = new $php_class
	$validator->$func($field, $value)


## Tests

PHPUnit tests are available if you clone/download the repo.  Once you have 
PHPUnit installed, you can run the tests with:

	`run-tests`
