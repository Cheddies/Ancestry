<?php if($order['recordType'] == 'b'):
	$table = 'birth_certificates';
	echo formInput('text','birth_surname','birth_surname',"$lastnameLabel at Birth",$order[$table]['birth_surname'],array('attr'=>array('class'=>'par_field','maxlength'=>45),'disabled'=>$disableParticulars,'required'=>!$disableParticulars,'error'=>validationError('birth_surname')));
	
	echo formInput('text','birth_forename','birth_forename',"$firstnameLabel at Birth",$order[$table]['forenames'],array('attr'=>array('class'=>'par_field','maxlength'=>45),'disabled'=>$disableParticulars,'required'=>!$disableParticulars,'error'=>validationError('birth_forename')));
	
endif;

if($order['recordType'] == 'm'):
	$table = 'marriage_certificates';
	
	echo "<p><strong>Either</strong> man's $firstnameLabel and $lastnameLabel <strong>or</strong> woman's $firstnameLabel and $lastnameLabel are required.</p>";
	
	echo formInput('text','marriage_man_surname','marriage_man_surname',"Man's $lastnameLabel",$order[$table]['mans_surname'],array('attr'=>array('class'=>'par_field','maxlength'=>45),'disabled'=>$disableParticulars,'error'=>validationError('marriage_man_surname')));
	
	echo formInput('text','marriage_man_forename','marriage_man_forename',"Man's $firstnameLabel",$order[$table]['mans_forenames'],array('attr'=>array('class'=>'par_field','maxlength'=>45),'disabled'=>$disableParticulars,'error'=>validationError('marriage_man_forename')));
	
	echo "<p>OR</p>";
	
	echo formInput('text','marriage_woman_surname','marriage_woman_surname',"Woman's $lastnameLabel (prior to marriage)",$order[$table]['womans_surname'],array('attr'=>array('class'=>'par_field','maxlength'=>45),'disabled'=>$disableParticulars,'error'=>validationError('marriage_woman_surname')));
	
	echo formInput('text','marriage_woman_forename','marriage_woman_forename',"Woman's $firstnameLabel",$order[$table]['womans_forenames'],array('attr'=>array('class'=>'par_field','maxlength'=>45),'disabled'=>$disableParticulars,'error'=>validationError('marriage_woman_forename')));
	
endif;

if($order['recordType'] == 'd'):
	$table = 'death_certificates';
	echo formInput('text','death_surname','death_surname',"$lastnameLabel of Deceased",$order[$table]['surname_deceased'],array('attr'=>array('class'=>'par_field','maxlength'=>45),'disabled'=>$disableParticulars,'required'=>!$disableParticulars,'error'=>validationError('death_surname')));
	
	echo formInput('text','death_forename','death_forename',"$firstnameLabel of Deceased",$order[$table]['forenames_deceased'],array('attr'=>array('class'=>'par_field','maxlength'=>45),'disabled'=>$disableParticulars,'required'=>!$disableParticulars,'error'=>validationError('death_forename')));
	
endif;?>