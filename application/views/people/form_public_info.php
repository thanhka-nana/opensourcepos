
<div class="field_row clearfix">
<?php echo form_label($this->lang->line('common_first_name').':', PERSON_FIRST_NAME, $this->CI->is_required(PERSON_FIRST_NAME)); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'first_name',
		'id'=>'first_name',
		'size' => 25,
		'value'=>$person_info->first_name)
	);?>
	</div>
</div>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_last_name').':', PERSON_LAST_NAME, $this->CI->is_required(PERSON_LAST_NAME)); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'last_name',
		'id'=>'last_name',
		'size' => 25,
		'value'=>$person_info->last_name)
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_gender').':', PERSON_GENDER, $this->CI->is_required(PERSON_GENDER)); ?>
	<div class='form_field'>
	<?php echo form_radio(array(
		'name'=>'gender',
		'type'=>'radio',
		'value'=>1,
		'checked'=>$person_info->gender === '1')
	);
	echo '&nbsp;' . $this->lang->line('common_gender_male') . '&nbsp;';
	echo form_radio(array(
		'name'=>'gender',
		'type'=>'radio',
		'value'=>0,
		'checked'=>$person_info->gender === '0')
	);
	echo '&nbsp;' . $this->lang->line('common_gender_female') . '&nbsp;';
	?>
    <?php echo form_radio(array(
        'name'=>'gender',
        'type'=>'radio',
        'value'=>2,
        'checked'=>$person_info->gender === '2')
    );
    echo '&nbsp;' . $this->lang->line('common_gender_undefined') . '&nbsp;';
    ?>
    </div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_email').':', PERSON_EMAIL, $this->CI->is_required(PERSON_EMAIL)); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'email',
        'type'=>'email',
		'size' => 25,
        'id'=>'email',
		'value'=>$person_info->email)
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_phone_number').':', PERSON_TELEPHONE, $this->CI->is_required(PERSON_TELEPHONE)); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'type' => 'text',
		'name'=>'phone_number',
		'id'=>'phone_number',
		'size' => 25,
		'value'=>$person_info->phone_number));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_address_1').':', PERSON_ADDRESS, $this->CI->is_required(PERSON_ADDRESS)); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'address_1',
		'id'=>'address_1',
		'size' => 25,
		'value'=>$person_info->address_1));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_zip').':', PERSON_ZIP, $this->CI->is_required(PERSON_ZIP)); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'zip',
		'id'=>'postcode',
		'size' => 25,
		'value'=>$person_info->zip));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_city').':', PERSON_CITY,  $this->CI->is_required(PERSON_CITY)); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'city',
		'id'=>'city',
		'size' => 25,
		'value'=>$person_info->city));?>
	</div>
</div>

<?php echo form_input(array(
	'name'=>'state',
	'id'=>'state',
	'type'=>'hidden',
	'value'=>$person_info->state));?>
<?php echo form_input(array(
	'name'=>'country',
	'id'=>'country',
	'type'=>'hidden',
	'value'=>$person_info->country));?>	

<script type='text/javascript' language="javascript">
//validation and submit handling
jQuery(document).ready(function($)
{
	nominatim.init({
		fields : {
			postcode : {  
				dependencies :  ["postcode", "city", "state", "country"], 
				response : {  
					field : 'postalcode', 
					format: ["postcode", "village|town|hamlet|city_district|city"]
				}
			},
	
			city : {
				dependencies :  ["postcode", "city", "state", "country"], 
				response : {  
					format: ["postcode", "village|town|hamlet|city_district|city"]
				}
			},
	
			state : {
				dependencies :  ["state", "country"]
			},
	
			country : {
				dependencies :  ["state", "country"] 
			}
			
		},
        country_codes: localStorage['country'],
		language : '<?php echo $this->config->item('language');?>'
	});

});
</script>
