<?php $this->load->view("partial/header"); ?>
<div id = "content_area">
<?php echo form_open('newsletters/show/',array('id'=>'newsletter_form')); ?>
	<div id="title_bar">
		<div id="title" class="float_left"><?php echo $this->lang->line('common_list_of').' '.$this->lang->line('module_'.$controller_name); ?></div>
	</div>
	<div>
	<?php echo form_label($this->lang->line('newsletters_select'). '&nbsp;', 'newsletter',array('required class'=>'wide')); ?>
	<?php echo form_input(array(
		'class' => 'ac_input',
		'name'=>'newsletter_id',
		'size'=>'8',
		'id'=>'newsletter_id')
	);?>
	&nbsp;	
	<?php echo form_submit(array(
		'name'=>'submit',
		'id'=>'submit',
		'value'=>$this->lang->line('common_submit'))
	);?>
	</div>
<?php
echo form_close();
?>
<?php echo form_open('newsletters/show/',array('id'=>'newsletter_form')); ?>

</div>

<?php $this->load->view("partial/footer"); ?>