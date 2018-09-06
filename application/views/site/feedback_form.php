<?php
echo $this->lang->line('feedback_form_intro'). "&nbsp;";
echo $this->lang->line('feedback_form_confidential');
?>
<br>
<br>
<form id="feedback_form" action="<?=site_url('site/feedback_form/' . $feedback_token)?>" method="post">
<fieldset id="contact_form_info">
<legend><?php echo $this->lang->line('common_fields_legend'); ?></legend>

<ul id="error_message_box">
	<?php 
	foreach($error_messages as $error_message) {
		?>
		<li><?=$error_message?></li>
		<?php 
	}
	?>
</ul>
<p class="asterisk"><?=$this->lang->line("score_label")?></p>

<div class="field_row clearfix">	
	<div class="form_field">
		<?php 
		for($i = 0; $i < 3; $i++) {
			echo form_radio(array(
				'name'=>ANALYSIS_SCORE,
				'type'=>'radio',
				'id'=>ANALYSIS_SCORE,
				'value'=>$i,
				'checked'=>$this->input->post(ANALYSIS_SCORE) === "$i")
			);
			echo "&nbsp;" . $this->lang->line('score_'. $i) . "&nbsp;";
		}
		?>
	</div>
</div>

<p id="score_comments"><?=$this->lang->line("score_comments_label")?></p>
<div class="field_row clearfix">	
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>ANALYSIS_COMMENTS,
		'id'=>ANALYSIS_COMMENTS,
		'value'=>$this->input->post(ANALYSIS_COMMENTS, TRUE),
		'cols'=>'50',
		'rows'=>'5')		
	);?>
	</div>
</div>

<?php
echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());

echo form_submit(array(
	'name'=>'subscribe',
	'id'=>'subscribe',
	'value'=>$this->lang->line("common_submit"))
);
?>

<div class="asterisk_message">
<?php echo $this->lang->line('common_fields_required_message'); ?>
</div>
</fieldset>
</form>
