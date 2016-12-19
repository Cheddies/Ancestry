<?php
$table = $order['cert_table'];
$viewFields = explode(',', $order['viewFields']);

if(empty($order[$table]['GRO_index_year'])) $order[$table]['GRO_index_year'] = $order['GRO_year'];
if(empty($order['gro_reg_year'])) $order['gro_reg_year'] = $order['GRO_year'];


if(in_array('GROI_year', $viewFields)) echo formInput('text','GROI_year','GROI_year','Year (yyyy)',$order[$table]['GRO_index_year'],array('attr'=>array('class'=>'gro_field numField','maxlength'=>4,'size'=>5),'disabled'=>$disableGro,'required'=>!$disableGro,'error'=>validationError('GROI_year')));

if(in_array('GROI_quarter', $viewFields)) echo formInput('select','GROI_quarter','GROI_quarter','Quarter',$order[$table]['GRO_index_quarter'],array('attr'=>array('class'=>'gro_field'),'options'=>$quarters,'disabled'=>$disableGro,'required'=>!$disableGro,'error'=>validationError('GROI_quarter')));


if(in_array('GROI_district', $viewFields)) echo formInput('text','GROI_district','GROI_district','District Name',$order[$table]['GRO_index_district'],array('attr'=>array('class'=>'gro_field','maxlength'=>45),'disabled'=>$disableGro,'required'=>!$disableGro,'error'=>validationError('GROI_district')));

if(in_array('GROI_district_number', $viewFields)) echo formInput('text','GROI_district_number','GROI_district_number','District Number',$order[$table]['GRO_district_no'],array('attr'=>array('class'=>'gro_field short_field','maxlength'=>45),'disabled'=>$disableGro,'required'=>!$disableGro,'error'=>validationError('GROI_district_number')));


if(in_array('GROI_volume_number', $viewFields)) echo formInput('text','GROI_volume_number','GROI_volume_number','Volume Number',$order[$table]['GRO_index_volume'],array('attr'=>array('class'=>'gro_field short_field','maxlength'=>45),'disabled'=>$disableGro,'required'=>!$disableGro,'error'=>validationError('GROI_volume_number')));

if(in_array('GROI_reg_number', $viewFields)) echo formInput('text','GROI_reg_number','GROI_reg_number','Reg Number',$order[$table]['GRO_reg_no'],array('attr'=>array('class'=>'gro_field short_field','maxlength'=>11,'size'=>5),'disabled'=>$disableGro,'required'=>!$disableGro,'error'=>validationError('GROI_reg_number')));


if(in_array('GROI_page_number', $viewFields)) echo formInput('text','GROI_page_number','GROI_page_number','Page Number',$order[$table]['GRO_index_page'],array('attr'=>array('class'=>'gro_field short_field','maxlength'=>11,'size'=>5),'disabled'=>$disableGro,'required'=>!$disableGro,'error'=>validationError('GROI_page_number')));

if(in_array('GROI_entry_number', $viewFields)) echo formInput('text','GROI_entry_number','GROI_entry_number','Entry Number',$order[$table]['GRO_entry_no'],array('attr'=>array('class'=>'gro_field short_field','maxlength'=>11,'size'=>5),'disabled'=>$disableGro,'required'=>!$disableGro,'error'=>validationError('GROI_entry_number')));


if(in_array('GROI_reg_month', $viewFields)) echo formInput('select','GROI_reg_month','GROI_reg_month','Month of registration',$order['gro_reg_month'],array('attr'=>array('class'=>'gro_field'),'disabled'=>$disableGro,'required'=>!$disableGro,'options'=>$months,'error'=>validationError('GROI_reg_month')));

if(in_array('GROI_reg_year', $viewFields)) echo formInput('text','GROI_reg_year','GROI_reg_year','Year of registration (yyyy)',$order['gro_reg_year'],array('attr'=>array('class'=>'gro_field numField','maxlength'=>4,'size'=>5),'disabled'=>$disableGro,'required'=>!$disableGro,'error'=>validationError('GROI_reg_year')));


?>