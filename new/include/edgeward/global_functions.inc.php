<?php
require_once("include/edgeward/sessions.inc.php");
require_once('include/edgeward/database.inc.php');
//IL addition
require_once('include/commonfunctions.php');
require_once('include/siteconstants.php');
//$sess = new SessionManager();
//session_start();
//End of IL addition

//formInput function outputs a form field with related attributes, wrapper div etc
function formInput($inputType,$name,$id,$label,$value=null,$options=array()){
	/* Required:
	$inputType = text, select, radio, checkbox
	$name = element name
	$id = element id
	$label = label for text
	Optional:
	$value = starting value for the element
	$options = hash array of options, see below
	Valid options for the options array:
	$options['attr'] = array - hash of html attributes for the input element. eg array('class'=>'someClass')
	$options['options'] = array - hash of options for select elements eg array('optionDisplay'=>'optionValue')
	$options['help'] = Text - Display ? help icon and text.
	$options['checked'] = boolean - for radio or checkbox, whether it starts checked
	$options['disabled'] = boolean - disable the input, prefix name and id with "d_"
	$options['hidden'] = render a hidden field with name and value as above
	$options['div'] = render the wrapper div
	$options['required'] = Appends " <strong>*</strong>" to the label	
	$options['error'] = output a validation error if one is supplied
	*/
	
	//set the defaults
	$bolDisabled = isset($options['disabled']) ? $options['disabled'] : false;
	$attribs = $options['attr'];
	$bolChecked = isset($options['checked']) ? $options['checked'] : false;
	$help = isset($options['help']) ? $options['help'] : '';
	$div = isset($options['div']) ? $options['div'] : true;
	$hidden = isset($options['hidden']) ? $options['hidden'] : true;
	$req = isset($options['required']) ? $options['required'] : false;
	$error = isset($options['error']) ? $options['error'] : false;
	if($req) $label = $label.' <strong>*</strong>';
	//if the field is disabled, prefix it
	$fieldPrefix = $bolDisabled ? 'd_' : '';
	$field = '';
	if($label) $field .= "<label for=\"$fieldPrefix$name\">$label</label>\n";
	//if its a select field
	if($inputType == 'select'){		
		$field .= "<select name=\"$fieldPrefix$name\" id=\"$id\"";
		foreach($attribs as $k=>$v){
			$field .= " $k=\"$v\"";
		}
		if($bolDisabled)  $field .= " disabled=\"disabled\"";
		$field .= ">\n";
		foreach($options['options'] as $k=>$v){
			$selected = '';
			if($value == $v) $selected = "selected=\"selected\" ";
			$field .= "<option {$selected}value=\"$v\">$k</option>\n";
		}
		$field .= "</select>\n";
	} else {
		$field .= "<input name=\"$fieldPrefix$name\" type=\"$inputType\" id=\"$fieldPrefix$id\"";
		if($value){
			$field .= " value=\"$value\"";
		}
		foreach($attribs as $k=>$v){
			$field .= " $k=\"$v\"";
		}
		if($bolChecked) $field .= " checked=\"checked\"";
		if($bolDisabled)  $field .= " disabled=\"disabled\"";
		$field .= "/>\n";		
	}
	$output = '';
	if($div){ //if div wrapper require, output it
		$output .= "<div class=\"input $inputType";
		if($error) $output .= " error";
		$output .= "\">\n";
	}
	$output .= $field;	
	if($error) $output .= "<label for=\"$name\" class=\"error\">$error</label>\n";
	//if($help) $output .= "<div class=\"help\" id=\"help_$id\" rev=\"$help\">?</div>\n";
	if($help) {
		$helpArray = split('\|',$help);
		$helpUrl = $helpArray[0];
		$helpLabel = isset($helpArray[1]) ? "{$helpArray[1]} Help" : "$label Help";
		$output .= "<a class=\"help\" id=\"help_$id\" href=\"{$helpArray[0]}\" title=\"$helpLabel\">?</a>\n";
	}
	if($div) $output .= "</div>\n";
	//if disabled, output a hidden field
	if($bolDisabled && $hidden){		
		if($inputType == 'checkbox'){
			if($bolChecked){
				$output .= "<input type=\"hidden\" name=\"$name\" value=\"$value\" class=\"noval\"/>\n";
			} else {
				$output .= "<input type=\"hidden\" name=\"$name\" value=\"\" class=\"noval\"/>\n";
			}
			
		} else {		
			$output .= "<input type=\"hidden\" name=\"$name\" value=\"$value\" class=\"noval\"/>\n";
		}
	}	
	return $output;	
}

function cleanString($string){
	$special = array('/','!','&','*','"'); //array of characters to be stripped
	$s = strip_tags($string); //first strip html and php tags	
	$s = trim(str_replace($special,'',$s)); //remove specials and space at beginning and end
	$s = htmlentities($s); //any other specials are turned into html entities
	return $s;
}

function cleanData($data,$acceptedKeys = array()){
	//used for cleaning POST and GET data
	$cleanArray = array();
	
	foreach($data as $k => $v){
		if(!empty($acceptedKeys)){
			if(in_array($k,$acceptedKeys)) $cleanArray[$k] = cleanString($v);
		} else {
			$cleanArray[$k] = cleanString($v);
		}
	}
	return $cleanArray;
}



function sqlDate($dateString){
	//converts dd/mm/yyyy format dates to yyyy-mm-dd for sql input
	$dateArray = explode('/',$dateString);
	$day = strlen($dateArray[0])<2 ? '0'.$dateArray[0] : $dateArray[0];
	$month = strlen($dateArray[1])<2 ? '0'.$dateArray[1] : $dateArray[1];
	$year = $dateArray[2];
	return "$year-$month-$day";
}
function ukDate($dateString){
	//converts yyyy-mm-dd format dates to  dd/mm/yyyy
	$dateArray = explode('-',$dateString);
	$day = strlen($dateArray[2])<2 ? '0'.$dateArray[2] : $dateArray[2];
	$month = strlen($dateArray[1])<2 ? '0'.$dateArray[1] : $dateArray[1];
	$year = $dateArray[0];
	return "$day/$month/$year";
}
function debug($var,$label=null){
	/*echo "<div style=\"padding:5px;background:yellow;font-size:10px;border:1px solid grey;\">";
	if($label) echo "<b>$label</b><br/>";
	if(is_array($var)){
		print_r($var); 
	} else {
		echo $var;
	}
	echo "</div>";	*/
}


/**** VALIDATION ****/

$validation = array(
				'forename'=>array('validation'=>array('required'=>true,'maxlength'=>15),'messages'=>array('maxlength'=>'Maximum 15 characters.')),
				'surname'=>array('validation'=>array('required'=>true,'maxlength'=>20),'messages'=>array('maxlength'=>'Maximum 20 characters.')),
				'address_1'=>array('validation'=>array('required'=>true,'maxlength'=>40),'messages'=>array('maxlength'=>'Maximum 40 characters.')),
				'address_2'=>array('validation'=>array('maxlength'=>40),'messages'=>array('maxlength'=>'Maximum 40 characters.')),
				'town'=>array('validation'=>array('required'=>true,'maxlength'=>20),'messages'=>array('maxlength'=>'Maximum 20 characters.')),
				'county'=>array('validation'=>array('required'=>true)),
				'country'=>array('validation'=>array('required'=>true)),
				'postcode'=>array('validation'=>array('required'=>true,'maxlength'=>10),'messages'=>array('maxlength'=>'Maximum 10 characters.')),
				'email'=>array('validation'=>array('required'=>true,'email'=>true,'maxlength'=>50),'messages'=>array('maxlength'=>'Maximum 50 characters.')),
				'tel'=>array('validation'=>array('required'=>true,'phone'=>true,'maxlength'=>14),'messages'=>array('maxlength'=>'Maximum 14 characters.')),
				'terms'=>array('validation'=>array('required'=>true)),
				'b_forename'=>array('validation'=>array('required'=>true,'maxlength'=>15),'messages'=>array('maxlength'=>'Maximum 15 characters.'),'depends'=>array('billing_different'=>'1')),
				'b_surname'=>array('validation'=>array('required'=>true,'maxlength'=>20),'messages'=>array('maxlength'=>'Maximum 20 characters.'),'depends'=>array('billing_different'=>'1')),
				'b_address_1'=>array('validation'=>array('required'=>true,'maxlength'=>40),'messages'=>array('maxlength'=>'Maximum 40 characters.'),'depends'=>array('billing_different'=>'1')),
				'b_town'=>array('validation'=>array('required'=>true,'maxlength'=>20),'messages'=>array('maxlength'=>'Maximum 20 characters.'),'depends'=>array('billing_different'=>'1')),
				'b_county'=>array('validation'=>array('required'=>true),'depends'=>array('billing_different'=>'1')),
				'b_country'=>array('validation'=>array('required'=>true),'depends'=>array('billing_different'=>'1')),
				'b_postcode'=>array('validation'=>array('required'=>true,'maxlength'=>14),'messages'=>array('maxlength'=>'Maximum 14 characters.'),'depends'=>array('billing_different'=>'1')),
					);

$defaultMessages = array(
					'required'=>'This field is required.',
					'email'=>'Invalid email address.',
					'number'=>'Must be a number.',
					'phone'=>'Invalid phone number.',
					'maxlength'=>'Too many characters.',
					);

function checkValidation($postData){
	//returns an array of invalid fields
	global $validation;
	
	$invalidFields = array();
	
	foreach($postData as $k=>$v){
		//if validation exists for this field, test it
		if(array_key_exists($k,$validation)){
			$doValidationCheck = true;
			if(isset($validation[$k]['depends'])){
				//get depends criteria
				$depends = $validation[$k]['depends'];
				foreach($depends as $dk =>$dv){
					$doValidationCheck = ($postData[$dk] == $dv); 
					if(!$doValidationCheck) break;
				}
			}
			if($doValidationCheck){
				//test the validation criteria
				foreach($validation[$k]['validation'] as $critria =>$match){
					//if not valid, add it to the invalid fields array
					if(!validationTest($v,$critria,$match)) $invalidFields[$k][$critria] = 1;
				}
			}
		}
	}
	return $invalidFields;
}

function validationTest($value,$criteria,$match){
	//returns true if value passes validation or false
	switch (strtolower($criteria)) {
		case 'required':
			if(strlen(trim($value)) ==0) return false;
			break;
		case 'email':
			return validateemail($value);
			break;
		case 'number':
			return is_numeric($value);
			break;
		case 'phone':
			return ctype_digit(str_replace(' ','',$value));
			break;
		case 'maxlength':
			return strlen($value) <= $match;
			break;
			
			
	}
	return true;
}

function validationError($fieldName){
	//returns a validation error message or false
	global $validation, $invalidFields, $defaultMessages;
	if(!isset($invalidFields)) return false;
	//if the field exists in invalid fields, get first message
	if(array_key_exists($fieldName,$invalidFields)){
		$keys = array_keys($invalidFields[$fieldName]);
		//if custom message
		if(isset($validation[$fieldName]['messages'][$keys[0]])){
			return $validation[$fieldName]['messages'][$keys[0]];
		} elseif(isset($defaultMessages[$keys[0]])){ //else if default exists
			return $defaultMessages[$keys[0]];
		} else {
		  return false;	
		}
	}
}

//Disabled by IL as name conflicts with existing function
/*function validateEmail($email) {
	// check that there's one @ symbol, and that the lengths are right
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
			return false;
		}
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
				return false;
			}
		}
	}
	return true;
}*/
?>