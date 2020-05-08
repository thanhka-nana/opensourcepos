<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<body>
<p>Er werd een online advies aangevraagd.<br>
    De volgende gegevens werden ingevoerd:</p>
<div>
    <?=$this->lang->line('common_first_name') . '+' . $this->lang->line('common_last_name')?>: <?=$first_name. " " . $last_name?><br>
    <?=$this->lang->line('common_email')?>: <?=$email?><br>
    <?=$this->lang->line('common_address_1')?>: <?=$address_1?><br>
    <?=$this->lang->line('common_zip') . '+' . $this->lang->line('common_city') ?>: <?=$zip. " " .$city?><br>
    <?=$this->lang->line('existing_customer')?>: <?=$this->input->post(EXISTING_CUSTOMER) ? $this->lang->line('common_yes') : $this->lang->line('common_no') ?><br>
    <?=$this->lang->line('shoe_size')?>: <strong><?=$this->input->post(SHOE_SIZE)?></strong><br>
    <?=$this->lang->line('shoe_model')?>: <strong><?=$this->input->post(SHOE_MODEL)?></strong><br>
    <?=$this->lang->line('gait_video')?>:
    <?php if (isset($_SESSION[GAIT_VIDEO_PATH])) :?>
        <a href="<?=site_url($_SESSION[GAIT_VIDEO_PATH])?>"><?=site_url($_SESSION[GAIT_VIDEO_PATH])?></a>
    <?php else: ?>
        <strong><?=$this->lang->line('existing_customer')?></strong>
    <?php endif;?>
    <br>
    <?=$this->lang->line('common_comments')?>: <?= $this->input->post( ANALYSIS_COMMENTS) ? $this->input->post( ANALYSIS_COMMENTS ) : "<geen>" ?><br>
</div>
<p>Je kan op deze mail antwoorden door gewoon op reply te drukken.</p>
</body>
</html>
