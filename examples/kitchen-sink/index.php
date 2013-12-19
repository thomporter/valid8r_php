<?php
require_once '../vendor/Valid8r/Valid8r.php';
require_once './CustomValidator.php';

class CustomValidatorClass {
	static public function static_validator($field, $value) {
		if ($value != 'custom') return 'Please enter the correct value.';
		return '';
	}
	public function instance_validator($field, $value) {
		if ($value != 'custom') return 'Please enter the correct value.';
		return '';
	}
}

use Valid8r\Valid8r;

$validatorFile = dirname(__FILE__).'/kitchen-sink.json';

function customValidatorFunction($field, $value) {
	if ($value != 'custom') return 'Please enter the correct value.';
	return '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$v = new Valid8r();
	$v->setRulesFromFile($validatorFile);
	$errors = $v->validateFields($_POST);
	$success = empty($errors);
}
?>
	<!DOCTYPE html>
	<head>
		<title>Valid8 - Example 1</title>
		<script src="../vendor/jquery.js"></script>
		<script src="../valid8r.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="../examples.css">
	</head>
	<body>
	<?php if (@$success) { ?>
		<div class="alert alert-success"><strong>Success!</strong><br />All fields validated successfully!</div>
	<?php } ?>
	<form id="example-form" role="form" novalidate method="POST" class="form-horizontal container">
	<h1>Valid8r Kitchen Sink Examples</h1>
	<input type="hidden" name="valid8_scheme" value="example1" class="form-control">
	<ul id="validTabs" class="nav nav-tabs">
		<li class="active"><a href="#tab-strings" data-toggle="tab">Strings</a></li>
		<li><a href="#tab-num" data-toggle="tab">Numbers</a></li>
		<li><a href="#tab-cboxes" data-toggle="tab">Checkboxes & Radios</a></li>
		<li><a href="#tab-special" data-toggle="tab">Special Fields</a></li>
		<li><a href="#tab-custom" data-toggle="tab">Custom Validators</a></li>
		<li><a href="#tab-conditionals" data-toggle="tab">Conditional Validation</a></li>
		<li><a href="#tab-code" data-toggle="tab">KitchenSink JSON</a></li>
	</ul>
	<div class="tab-content">
	<div id="tab-strings" class="tab-pane active fade in">
		<div class="validator-examples"><h3>Validate Strings</h3>
			<div class="form-group row<?php if (@$errors['min_len']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="min_len" class="col-sm-3 control-label">Min Length</label>
					<div class="col-sm-9">
						<input id="min_len" type="text" name="min_len" class="form-control" value="<?php echo @$_POST['min_len']?>" />
						<?php if (@$errors['min_len']) { ?><span class="help-block"><?php echo $errors['min_len']?></span><?php } ?>
						<div class="rule">{"rule":"len", "min":5}</div>
						
					</div>
				</div>
				<div class="col-md-6">Validate the length of a value.</div>
			</div>
			<div class="form-group row<?php if (@$errors['max_len']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="max_len" class="col-sm-3 control-label">Max Length</label>
					<div class="col-sm-9">
						<input type="text" id="max_len" name="max_len" class="form-control" value="<?php echo @$_POST['max_len']?>" />
						<?php if (@$errors['max_len']) { ?><span class="help-block"><?php echo $errors['max_len']?></span><?php } ?>
						<div class="rule">{"rule":"len", "max":20}</div>
					</div>
				</div>
			</div>
			<div class="form-group row<?php if (@$errors['min_max_len']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="min_max_len" class="col-sm-3 control-label">Min/Max Length</label>
					<div class="col-sm-9">
						<input type="text" id="min_max_len" name="min_max_len" class="form-control" value="<?php echo @$_POST['min_max_len']?>" />
						<?php if (@$errors['min_max_len']) { ?><span class="help-block"><?php echo $errors['min_max_len']?></span><?php } ?>
						<div class="rule">{"rule":"len", "min":5, "max":20}</div>
					</div>
				</div>
			</div>
			<div class="form-group row<?php if (@$errors['alpha']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="alpha" class="col-sm-3 control-label">Alhpa</label>
					<div class="col-sm-9">
						<input type="text" id="alpha" name="alpha" class="form-control" value="<?php echo @$_POST['alpha']?>" />
						<?php if (@$errors['min_alphalen']) { ?><span class="help-block"><?php echo $errors['alpha']?></span><?php } ?>
						<div class="rule">{"rule":"isAlpha"}</div>
					</div>
				</div>
				<div class="col-md-6">Validate that a value contains all alphabetic characters (a-z, A-Z)</div>
			</div>
			<div class="form-group row<?php if (@$errors['num']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="num" class="col-sm-3 control-label">Numeric</label>
					<div class="col-sm-9">
						<input type="text" id="num" name="num" class="form-control" value="<?php echo @$_POST['num']?>" />
						<?php if (@$errors['num']) { ?><span class="help-block"><?php echo $errors['num']?></span><?php } ?>
						<div class="rule">{"rule":"isNum"}</div>
					</div>
				</div>
				<div class="col-md-6">Validate that a value contains all numeric characters (0-9)</div>
			</div>
			<div class="form-group row<?php if (@$errors['num_pos']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="num_pos" class="col-sm-3 control-label">Positive Numeric</label>
					<div class="col-sm-9">
						<input type="text" id="num_pos" name="num_pos" class="form-control" value="<?php echo @$_POST['num_pos']?>" />
						<?php if (@$errors['num_pos']) { ?><span class="help-block"><?php echo $errors['num_pos']?></span><?php } ?>
						<div class="rule">{"rule":"isNum", "nonNeg":true}</div>
					</div>
				</div>
				<div class="col-md-6">Validate that a value contains a non-negative numeric number (+0-9)</div>
			</div>
			<div class="form-group row<?php if (@$errors['alnum']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="alnum" class="col-sm-3 control-label">AlphaNumeric</label>
					<div class="col-sm-9">
						<input type="text" id="alnum" name="alnum" class="form-control" value="<?php echo @$_POST['alnum']?>" />
						<?php if (@$errors['alnum']) { ?><span class="help-block"><?php echo $errors['alnum']?></span><?php } ?>
						<div class="rule">{"rule":"isAlnum"}</div>
					</div>
				</div>
				<div class="col-md-6">Validate that a value is all Alpha-numeric characters</div>
			</div>
			<div class="form-group row<?php if (@$errors['formatted_as']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="formatted_as" class="col-sm-3 control-label">Formatted</label>
					<div class="col-sm-9">
						<input type="text" id="formatted_as" name="formatted_as" class="form-control" value="<?php echo @$_POST['formatted_as']?>" />
						<?php if (@$errors['formatted_as']) { ?><span class="help-block"><?php echo $errors['formatted_as']?></span><?php } ?>
						<div class="rule">{"rule":"formatted_as", "format":"DD/DD/DDDD"}</div>
					</div>
				</div>
				<div class="col-md-6">Validate that a value matches a format of letters, numbers and other symbols.</div>
			</div>
			<div class="form-group row<?php if (@$errors['formatted_as2']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="formatted_as2" class="col-sm-3 control-label">Formatted 2</label>
					<div class="col-sm-9">
						<input type="text" id="formatted_as2" name="formatted_as2" class="form-control" value="<?php echo @$_POST['formatted_as2']?>" />
						<?php if (@$errors['formatted_as2']) { ?><span class="help-block"><?php echo $errors['formatted_as2']?></span><?php } ?>
						<div class="rule">{"rule":"formatted_as", "format":"(DDD) DDD-DDDD"}</div>
					</div>
				</div>
			</div>
			<div class="form-group row<?php if (@$errors['formatted_as3']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="formatted_as2" class="col-sm-3 control-label">Formatted 3</label>
					<div class="col-sm-9">
						<input type="text" id="formatted_as3" name="formatted_as3" class="form-control" value="<?php echo @$_POST['formatted_as3']?>" />
						<?php if (@$errors['formatted_as3']) { ?><span class="help-block"><?php echo $errors['formatted_as3']?></span><?php } ?>
						<div class="rule">{"rule":"formatted_as", "format":"AADDAADDDDDD"}</div>
					</div>
				</div>
			</div>
			<div class="form-group row<?php if (@$errors['regex']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="regex" class="col-sm-3 control-label">RegEx</label>
					<div class="col-sm-9">
						<input type="text" id="regex" name="regex" class="form-control" value="<?php echo @$_POST['regex']?>" />
						<?php if (@$errors['regex']) { ?><span class="help-block"><?php echo $errors['regex']?></span><?php } ?>
						<div class="rule">{"rule":"regex", pattern:"[a-Z0-9.-]{2,7}", modifiers:"i"}</div>
					</div>
				</div>
				<div class="col-md-6">Validate that a value using a regular expression.</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<input type="submit" value="Submit" class="pull-right btn btn-primary">
				</div>
			</div>
		</div>
	</div>
	<div id="tab-num" class="tab-pane fade">
		<div class="validator-examples"><h3>Validate Numbers</h3>
			<div class="form-group row<?php if (@$errors['min_val']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="min_val" class="col-sm-3 control-label">Min Value</label>
					<div class="col-sm-9">
						<input type="text" id="min_val" name="min_val" class="form-control" value="<?php echo @$_POST['min_val']?>" />
						<?php if (@$errors['min_val']) { ?><span class="help-block"><?php echo $errors['min_val']?></span><?php } ?>
						<div class="rule">{"rule":"val","min":5}</div>
					</div>
				</div>
				<div class="col-md-6">Validate a numeric value.</div>
			</div>
			<div class="form-group row<?php if (@$errors['max_val']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="max_val" class="col-sm-3 control-label">Max Value</label>
					<div class="col-sm-9">
						<input type="text" id="max_val" name="max_val" class="form-control" value="<?php echo @$_POST['max_val']?>" />
						<?php if (@$errors['max_val']) { ?><span class="help-block"><?php echo $errors['max_val']?></span><?php } ?>
						<div class="rule">{"rule":"val","max":10}</div>
					</div>
				</div>
			</div>
			<div class="form-group row<?php if (@$errors['min_max_val']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="min_max_val" class="col-sm-3 control-label">Min/Max Value</label>
					<div class="col-sm-9">
						<input type="text" id="min_max_val" name="min_max_val" class="form-control" value="<?php echo @$_POST['min_max_val']?>" />
						<?php if (@$errors['min_max_val']) { ?><span class="help-block"><?php echo $errors['min_max_val']?></span><?php } ?>
						<div class="rule">{"rule":"val","min":5,"max":10}</div>
					</div>
				</div>
			</div>
			<div class="form-group row<?php if (@$errors['val_outside']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="val_outside" class="col-sm-3 control-label">Value Outside</label>
					<div class="col-sm-9">
						<input type="text" id="val_outside" name="val_outside" class="form-control" value="<?php echo @$_POST['val_outside']?>" />
						<?php if (@$errors['val_outside']) { ?><span class="help-block"><?php echo $errors['val_outside']?></span><?php } ?>
						<div class="rule">{"rule":"val", "outside":[5,10]}</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<input type="submit" value="Submit" class="pull-right btn btn-primary">
				</div>
			</div>
		</div>
	</div>
	<div id="tab-special" class="tab-pane fade">
		<div class="validator-examples"><h3>Special Validators</h3>
			<div class="form-group row<?php if (@$errors['email']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="email" class="col-sm-3 control-label">Email</label>
					<div class="col-sm-9">
						<input type="text" id="email" name="email" class="form-control" value="<?php echo @$_POST['email']?>" />
						<?php if (@$errors['email']) { ?><span class="help-block"><?php echo $errors['email']?></span><?php } ?>
						<div class="rule">{"rule":"email"}</div>
					</div>
				</div>
				<div class="col-md-6">Validate email addresses. (several options available on this.)</div>
			</div>
			<div class="form-group row<?php if (@$errors['url']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="url" class="col-sm-3 control-label">URL</label>
					<div class="col-sm-9">
						<input type="text" id="url" name="url" class="form-control" value="<?php echo @$_POST['url']?>" />
						<?php if (@$errors['url']) { ?><span class="help-block"><?php echo $errors['url']?></span><?php } ?>
						<div class="rule">{"rule":"url"}</div>
					</div>
				</div>
				<div class="col-md-6">Validate a URL</div>
			</div>
			<div class="form-group row<?php if (@$errors['url_protocols']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="url_protocols" class="col-sm-3 control-label">URL w/ Protocols</label>
					<div class="col-sm-9">
						<input type="text" id="url_protocols" name="url_protocols" class="form-control" value="<?php echo @$_POST['url_protocols']?>" />
						<?php if (@$errors['url_protocols']) { ?><span class="help-block"><?php echo $errors['url_protocols']?></span><?php } ?>
						<div class="rule">{"rule":"url", "protocols":["http","https","ftp","git"]}</div>
					</div>
				</div>
			</div>
			<div class="form-group row<?php if (@$errors['url_withoutProtocols']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="url_withoutProtocols" class="col-sm-3 control-label">URL noProtocols</label>
					<div class="col-sm-9">
						<div class="input-group">
							<div class="input-group-addon">http://</div>
							<input type="text" id="url_withoutProtocols" name="url_withoutProtocols" class="form-control" value="<?php echo @$_POST['url_withoutProtocols']?>" />
						</div>
						<?php if (@$errors['url_withoutProtocols']) { ?><span class="help-block"><?php echo $errors['url_withoutProtocols']?></span><?php } ?>
						<div class="rule">{"rule":"url", "noProtocols":true}</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<input type="submit" value="Submit" class="pull-right btn btn-primary">
				</div>
			</div>
		</div>
	</div>
	<div id="tab-cboxes" class="tab-pane fade">
		<div class="validator-examples"><h3>Validate Checkoxes &amp; Radio Buttons</h3>
			<div class="form-group row<?php if (@$errors['checkboxes_min_3']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<input type="hidden" id="checkboxes_min_3">
					<div class="row">
						<label class="control-label col-sm-4">Check at least 3</label>
						<div class="col-sm-8">
							<div class="row">
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_min_3[]" value="1"<?php if (@in_array(1, $_POST['checkboxes_min_3'])) { ?> checked<? } ?>> 1
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_min_3[]" value="2"<?php if (@in_array(2, $_POST['checkboxes_min_3'])) { ?> checked<? } ?>> 2
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_min_3[]" value="3"<?php if (@in_array(3, $_POST['checkboxes_min_3'])) { ?> checked<? } ?>> 3
								</label>
							</div>
							<div class="row">
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_min_3[]" value="4"<?php if (@in_array(4, $_POST['checkboxes_min_3'])) { ?> checked<? } ?>> 4
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_min_3[]" value="5"<?php if (@in_array(5, $_POST['checkboxes_min_3'])) { ?> checked<? } ?>> 5
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_min_3[]" value="6"<?php if (@in_array(6, $_POST['checkboxes_min_3'])) { ?> checked<? } ?>> 6
								</label>
							</div>
							<?php if (@$errors['checkboxes_min_3']) { ?><span class="help-block"><?php echo $errors['checkboxes_min_3']?></span><?php } ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-offset-3 col-md-8">
							<div class="rule">{"rule":"checks", "min":3}</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">Validate Checkboxes</div>
			</div>
			<div class="form-group row<?php if (@$errors['checkboxes_max_3']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<input type="hidden" id="checkboxes_max_3">
					<div class="row">
						<label class="control-label col-sm-3">Check no more than 3</label>
						<div class="col-sm-9">
							<div class="row">
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_max_3[]" value="1"<?php if (@in_array(1, $_POST['checkboxes_min_3_max4'])) { ?> checked<? } ?>> 1
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_max_3[]" value="2"<?php if (@in_array(2, $_POST['checkboxes_min_3_max4'])) { ?> checked<? } ?>> 2
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_max_3[]" value="3"<?php if (@in_array(3, $_POST['checkboxes_min_3_max4'])) { ?> checked<? } ?>> 3
								</label>
							</div>
							<div class="row">
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_max_3[]" value="4"<?php if (@in_array(4, $_POST['checkboxes_min_3_max4'])) { ?> checked<? } ?>> 4
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_max_3[]" value="5"<?php if (@in_array(5, $_POST['checkboxes_min_3_max4'])) { ?> checked<? } ?>> 5
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_max_3[]" value="6"<?php if (@in_array(6, $_POST['checkboxes_min_3_max4'])) { ?> checked<? } ?>> 6
								</label>
							</div>
							<?php if (@$errors['checkboxes_max_3']) { ?><span class="help-block"><?php echo $errors['checkboxes_max_3']?></span><?php } ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-offset-3 col-md-8">
							<div class="rule">{"rule":"checks", "max":3}</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group row<?php if (@$errors['checkboxes_min_3_max4']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<input type="hidden" id="checkboxes_min_3_max4">
					<div class="row">
						<label class="control-label col-sm-3">Choose 3 - 4 options:</label>
						<div class="col-sm-9">
							<div class="row">
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_min_3_max4[]" value="1"<?php if (@in_array(1, @$_POST['checkboxes_min_3_max4'])) { ?> checked<? } ?>> 1
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_min_3_max4[]" value="2"<?php if (@in_array(2, $_POST['checkboxes_min_3_max4'])) { ?> checked<? } ?>> 2
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_min_3_max4[]" value="3"<?php if (@in_array(3, $_POST['checkboxes_min_3_max4'])) { ?> checked<? } ?>> 3
								</label>
							</div>
							<div class="row">
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_min_3_max4[]" value="4"<?php if (@in_array(4, $_POST['checkboxes_min_3_max4'])) { ?> checked<? } ?>> 4
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_min_3_max4[]" value="5"<?php if (@in_array(5, $_POST['checkboxes_min_3_max4'])) { ?> checked<? } ?>> 5
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="checkbox" name="checkboxes_min_3_max4[]" value="6"<?php if (@in_array(6, $_POST['checkboxes_min_3_max4'])) { ?> checked<? } ?>> 6
								</label>
							</div>
							<?php if (@$errors['checkboxes_min_3_max4']) { ?><span class="help-block"><?php echo $errors['checkboxes_min_3_max4']?></span><?php } ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-offset-3 col-md-8">
							<div class="rule">{"rule":"checks", "min":3,"max":6}</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group row<?php if (@$errors['radios']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<input type="hidden" id="radios">
					<div class="row">
						<label class="control-label col-sm-3">Radios (required):</label>
						<div class="col-sm-9">
							<div class="row">
								<label class="col-sm-3 checkbox-inline">
									<input type="radio" name="radios" value="1"<?php if (@$_POST['radios'] == 1) { ?> checked<? } ?>> 1
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="radio" name="radios" value="2"<?php if (@$_POST['radios'] == 2) { ?> checked<? } ?>> 2
								</label>
								<label class="col-sm-3 checkbox-inline">
									<input type="radio" name="radios" value="3"<?php if (@$_POST['radios'] == 3) { ?> checked<? } ?>> 3
								</label>
							</div>
							<?php if (@$errors['radios']) { ?><span class="help-block"><?php echo $errors['radios']?></span><?php } ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-offset-3 col-md-8">
							<div class="rule">{"rule":"radios"}</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">Validate Radio Buttons</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<input type="submit" value="Submit" class="pull-right btn btn-primary">
			</div>
		</div>
	</div>
	<div id="tab-conditionals" class="tab-pane fade">
		<div class="validator-examples"><h3>Conditional Validation</h3>
			<div class="form-group row">
				<div class="col-md-6">
					<label class="col-sm-5 control-label">Validate Answer?</label>
					<div class="col-sm-5 checkbox">
						<label>
							<input type="radio" value="1" name="validate_answer" id="validate_answer"<?php if (@$_POST['validate_answer'] == '1') { ?> checked<? } ?>>Yes
						</label>&nbsp;
						<label>
							<input type="radio" value="0" name="validate_answer" id="validate_answer"<?php if (@$_POST['validate_answer'] === '0') { ?> checked<? } ?>>No
						</label>
					</div>
				</div>
				<div class="col-md-6">Validate only when other conditions are met.	</div>
			</div>
			<div class="form-group row<?php if (@$errors['answer']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="answer" class="col-sm-5 control-label">What is 3 + 4 ?</label>
					<div class="col-sm-2">
						<input name="answer" id="answer" class="form-control" value="<?php echo @$_POST['answer']?>" />
					</div>
					<?php if (@$errors['answer']) { ?><span class="help-block"><?php echo $errors['answer']?></span><?php } ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="rule">
                <pre>{
"rule": "val", "is":7, 
"when": {
"selector": "#validate_answer:checked", // do we need this? 
"field": "validate_next_field",
"eq": "1"
}
}</pre>
					</div>
				</div>
				<div class="col-md-6"></div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<input type="submit" value="Submit" class="pull-right btn btn-primary">
				</div>
			</div>
		</div>
	</div>
	<div id="tab-custom" class="tab-pane fade">
		<div class="validator-examples"><h3>Custom Validator Functions</h3>
			<div class="form-group row<?php if (@$errors['custom']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="custom" class="col-md-3 control-label">Custom Validator</label>
					<div class="col-sm-9">
						<input id="custom" type="text" name="custom" class="form-control" value="<?php echo @$_POST['custom']?>" />
						<?php if (@$errors['custom']) { ?><span class="help-block"><?php echo $errors['custom']?></span><?php } ?>
						<div class="rule">{"rule":"custom", "func":"customValidatorFunction"}</div>
					</div>
				</div>
				<div class="col-md-6">
					Validates a value against a custom validation function of your own.  The one used here requires you type the word
					custom into the text field.
				</div>
			</div>
			
			<div class="form-group row<?php if (@$errors['custom_instance_method']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="custom_instance_method" class="col-md-3 control-label">Custom Instance Method Validator</label>
					<div class="col-sm-9">
						<input id="custom_instance_method" type="text" name="custom_instance_method" class="form-control" value="<?php echo @$_POST['custom_instance_method']?>" />
						<?php if (@$errors['custom_instance_method']) { ?><span class="help-block"><?php echo $errors['custom_instance_method']?></span><?php } ?>
						<div class="rule">{"rule":"custom", "func":"instance_validator", "php_class":"CustomValidatorClass"}</div>
					</div>
				</div>
				<div class="col-md-6">
					Add the <code>php_class</code> property to your rule to tell Valid8r to instantiate an object of that class, and then call the
					method you've designated by the <code>func</code> property.
				</div>
			</div>


			<div class="form-group row<?php if (@$errors['custom_static']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="custom_static" class="col-md-3 control-label">Custom Static Validator</label>
					<div class="col-sm-9">
						<input id="custom_static" type="text" name="custom_static" class="form-control" value="<?php echo @$_POST['custom_static']?>" />
						<?php if (@$errors['custom_static']) { ?><span class="help-block"><?php echo $errors['custom_static']?></span><?php } ?>
						<div class="rule">{"rule":"custom", "func":"static_validator", "php_static_class":"CustomValidatorClass"}</div>
					</div>
				</div>
				<div class="col-md-6">
					You can use the <code>php_static_class</code> method to tell Valid8r to look for <code>func</code> as a static method of that class.
				</div>
			</div>

			<div class="form-group row<?php if (@$errors['custom_instance_namespaced']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="custom_instance_namespaced" class="col-md-3 control-label">Custom Namespaced Validator</label>
					<div class="col-sm-9">
						<input id="custom_instance_namespaced" type="text" name="custom_instance_namespaced" class="form-control" value="<?php echo @$_POST['custom_instance_namespaced']?>" />
						<?php if (@$errors['custom_instance_namespaced']) { ?><span class="help-block"><?php echo $errors['custom_instance_namespaced']?></span><?php } ?>
						<div class="rule">{"rule":"custom", "func":"instance_validator", "php_class":"CustomNamespacedValidatorClass", "php_namespace":"CustomValidatorNamespace"}</div>
					</div>
				</div>
				<div class="col-md-6">
					Add a <code>php_namespace</code> property to your rule to tell Valid8r to look for <code>php_class</code> in that namespace.
				</div>
			</div>

			<div class="form-group row<?php if (@$errors['custom_static_namespaced']) { ?> has-error<? } ?>">
				<div class="col-md-6">
					<label for="custom_static_namespaced" class="col-md-3 control-label">Custom Namespaced Validator</label>
					<div class="col-sm-9">
						<input id="custom_static_namespaced" type="text" name="custom_static_namespaced" class="form-control" value="<?php echo @$_POST['custom_static_namespaced']?>" />
						<?php if (@$errors['custom_static_namespaced']) { ?><span class="help-block"><?php echo $errors['custom_static_namespaced']?></span><?php } ?>
						<div class="rule">{"rule":"custom", "func":"static_validator", "php_static_class":"CustomNamespacedValidatorClass", "php_namespace":"CustomValidatorNamespace"}</div>
					</div>
				</div>
				<div class="col-md-6">
					You can also namespace static class methods...
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<input type="submit" value="Submit" class="pull-right btn btn-primary">
				</div>
			</div>
		</div>
	</div>
	<div id="tab-code" class="tab-pane fade">
	<div class="validator-examples">
	<h3>JSON used for the Kitchen Sink</h3>
	<pre><?php echo str_replace("\t", "  ", htmlspecialchars(file_get_contents(dirname(__FILE__).'/kitchen-sink.json'))); ?></pre>
	</div>
	</div>
	</div>
	</form>
	<script>
		$(function() {
			$('#validTabs a[href="#tag-strings"]').tab('show')
			$('#validTabs a').click(function (e) {
				e.preventDefault()
				$(this).tab('show')
			})
		})
	</script>
	</body>
</html>
