<p>Vul hieronder uw e-mailadres in als u onze nieuwsbrief in de toekomst niet meer wil aankrijgen.</p>

<form id="contact_form_info" method="post" action="<?= site_url('site/unsubscribe/'.$id);?>">
<ul id="error_message_box">
	<?php 
	foreach($error_messages as $error_message) {
		?>
		<li><?=$error_message?></li>
		<?php 
	}
	?>
</ul>
<p>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_email').':', 'email', array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'email',
		'id'=>'email',
		'value'=>$this->input->post(PERSON_EMAIL, true))
	);?>

	</div>
	<div class='form_field'>
    <?php echo form_submit(array(
		'name'=>'submit',
		'value'=>$this->lang->line('common_submit'))
	);?>
	</div>
</div>
</p>


<div class="asterisk_message">
<?php echo $this->lang->line('common_fields_required_message'); ?>
</div>
</fieldset>
</form>
