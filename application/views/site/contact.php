<span class="tekst">Je kan bij <strong>RunWalk</strong> vrijblijvend terecht met al je vragen over onze artikelen of sportmedische problemen door het onderstaande formulier in te vullen. Wij doen ons best om uw vraag zo snel mogelijk te beantwoorden <br><br>Bedankt!<br>
<br>

<form id="contact_form" method="post" action="<?= site_url('site/contact');?>" accept-charset="UTF-8">

<ul id="error_message_box">
	<?php 
	foreach($error_messages as $error_message) {
		?>
		<li><?=$error_message?></li>
		<?php 
	}
	?>
</ul>

<fieldset id="contact_form_info">
<legend><?php echo $this->lang->line('common_fields_legend'); ?></legend>

<div class="field_row clearfix">	
<?php echo form_label('Naam *', PERSON_NAME, array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>PERSON_NAME,
		'id'=>PERSON_NAME,
		'value'=>$this->input->post(PERSON_NAME, TRUE),
		'size'=>40)
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_email').' *', PERSON_EMAIL, array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>PERSON_EMAIL,
		'id'=>PERSON_EMAIL,
		'value'=>$this->input->post(PERSON_EMAIL, TRUE),
		'size'=>40)
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_comments').' *', PERSON_COMMENTS, array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>PERSON_COMMENTS,
		'id'=>PERSON_COMMENTS,
		'value'=>$this->input->post(PERSON_COMMENTS, TRUE),
		'cols'=>'50',
		'rows'=>'5')		
	);?>
	</div>
</div>

<div class="field_row clearfix">
	<?=$captcha['image']?>
	<div class="form_field">
		<?=form_input(array("name" => "captcha", "size" => "20"))?>
	</div>
</div>

<?php

echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());

echo form_submit(array(
	'name'=>'submit',
	'value'=>$this->lang->line('common_submit')
	)
);
?>
<div class="asterisk_message">
<?php echo $this->lang->line('common_fields_required_message'); ?>
</div>
</fieldset>
<?php 
echo form_close();
?>
