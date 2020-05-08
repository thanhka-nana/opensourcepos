
<form id="online_advice" method="post" action="<?= site_url('site/advice_form');?>">
    <ul id="error_message_box">
        <?php
        foreach($error_messages as $error_message) {
            ?>
            <li><?=$error_message?></li>
            <?php
        }
        ?>
    </ul>

    <fieldset id="advice_form_info">
        <legend><?php echo $this->lang->line('common_fields_legend'); ?></legend>

        <div style="display:block; width: 100%;">

            <div style="float: left; margin-right: 10%;">
                <?php
                $this->load->view('people/form_public_info', array('person_info' => (object) $person_info));
                ?>
            </div>

            <div style="float: left; margin-right: 10%;">

                <?php echo form_label($this->lang->line('existing_customer_description'), EXISTING_CUSTOMER, $this->CI->is_required(EXISTING_CUSTOMER)) ?>

                <div class="field_row clearfix">
                    <div class='form_field'>
                        <?php echo form_radio(array(
                                'name'=>'existing_customer',
                                'type'=>'radio',
                                'value'=>1,
                                'checked'=>$this->input->post(EXISTING_CUSTOMER) === '1')
                        );
                        echo '&nbsp;' . $this->lang->line('common_yes') . '&nbsp;';
                        echo form_radio(array(
                                'name'=>'existing_customer',
                                'type'=>'radio',
                                'value'=>0,
                                'checked'=>$this->input->post(EXISTING_CUSTOMER) === '0')
                        );
                        echo '&nbsp;' . $this->lang->line('common_no');
                        ?>
                    </div>
                </div>

                <?php echo form_label($this->lang->line('shoe_size_description').' *', SHOE_SIZE, $this->CI->is_required(SHOE_SIZE)); ?>

                <div class="field_row clearfix">
                    <div class='form_field'>
                        <?php echo form_input(array(
                                'name'=>SHOE_SIZE,
                                'id'=>SHOE_SIZE,
                                'value'=>$this->input->post(SHOE_SIZE),
                                'type'=>'number',
                                'step' => '0.5',
                                'min' => '35',
                                'max' => '49')
                        );?>
                    </div>


                </div>

                <?php echo form_label($this->lang->line('shoe_model_description'), SHOE_MODEL, $this->CI->is_required(SHOE_MODEL)); ?>

                <div class="field_row clearfix">
                    <div class='form_field'>
                        <?php echo form_input(array(
                                'name'=>SHOE_MODEL,
                                'id'=>SHOE_MODEL,
                                'value'=>$this->input->post(SHOE_MODEL))
                        );?>
                    </div>
                </div>

                <?php echo form_label($this->lang->line('shoe_comments_description'), ANALYSIS_COMMENTS, $this->CI->is_required(ANALYSIS_COMMENTS)); ?>

                <div class="field_row clearfix">
                    <div class='form_field'>
                        <?php echo form_textarea(array(
                                'name'=>ANALYSIS_COMMENTS,
                                'id'=>ANALYSIS_COMMENTS,
                                'value'=>$this->input->post(ANALYSIS_COMMENTS),
                                'cols'=>'40',
                                'rows'=>'1', 'style' => 'height: 53px;')
                        );?>
                    </div>
                </div>
            </div>

            <div style="clear: both;"></div>

        </div>

        <div class="asterisk_message">
            <strong>
                <?php echo $this->lang->line('common_fields_required_message'); ?>
            </strong>
        </div>

        <div>
            <?=form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash())?>
            <?php
            echo form_submit(array(
                    'name'=>'submit',
                    'id'=>'subscribe',
                    'value'=>$this->lang->line('common_submit')
                )
            );
            ?>
        </div>

    </fieldset>

</form>


