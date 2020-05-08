<form id="newsletter_form" method="post" action="<?= site_url('site/subscribe');?>">
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

<?php
$this->load->view('people/form_public_info', array('person_info' => (object) $person_info));
?>

<div class="field_row clearfix">
<?=$captcha['image']?>
<?=form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash())?>
<div class="form_field">
<?=form_input(array("name" => CAPTCHA, "size" => "25"))?>
</div>
</div>

<div>
<?php
echo form_submit(array(
	'name'=>'subscribe',
	'id'=>'subscribe',
	'value'=>'Schrijf in')
);
?>
</div>

<div class="asterisk_message">
<?php echo $this->lang->line('common_fields_required_message'); ?>
</div>
</fieldset>
</form>
