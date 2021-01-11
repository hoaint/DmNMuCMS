<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Registration'); ?></h1>
        </div>
        <div id="content_center">
            <?php
                if($config['email_validation'] == 1):
                    echo '<div style="padding: 0 30px 0px 50px;"><div class="s_note">' . __('Your account has been successfully created.') . ' <br />' . __('Please check your email for account activation link.') . '</div></div>';
                else:
                    echo '<div style="padding: 0 30px 0px 50px;"><div class="s_note">' . __('Your account has been successfully created.') . ' <br />' . __('You can now login.') . '</div></div>';
                endif;
            ?>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	