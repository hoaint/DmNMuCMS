<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h2><?php echo __('Module Error'); ?></h2>
        </div>
        <div style="padding: 0 30px 0px 50px;">
            <div
                    class="i_note"><?php echo __('This module has been disabled.'); ?></div>
        </div>

    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	