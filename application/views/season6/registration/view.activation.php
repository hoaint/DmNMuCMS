<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Registration'); ?></h1>
        </div>
        <?php
            if(isset($error)):
                ?>
                <div style="padding: 0 30px 0px 50px;">
                    <div class="e_note"><?php echo $error; ?></div>
                </div>
            <?php
            endif;
            if(isset($success)):
                ?>
                <div style="padding: 0 30px 0px 50px;">
                    <div class="s_note"><?php echo $success; ?></div>
                </div>
            <?php
            endif;
        ?>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	