<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Donate'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('With PaymentWall'); ?></h2>

            <div class="entry">
                <div style="background: rgba(255, 255, 255, 0.6);border: 1px solid rgba(0, 0, 0, 0.15);-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;margin:3px;">
                    <div style="padding: 5px; text-align: center;">
                        <?php echo $widget; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	