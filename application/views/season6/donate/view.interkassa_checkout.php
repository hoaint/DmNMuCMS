<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Donate'); ?></h1>
        </div>
        <?php
            if(isset($error)){
                echo '<div class="e_note">' . $error . '</div>';
            } else{
                ?>
                <div class="box-style1" style="margin-bottom: 20px;">
                    <h2 class="title"><?php echo $desc; ?></h2>
                    <div class="entry">
                        <form action="<?php echo $payment->getFormAction(); ?>" method="post">
                            <?php foreach($payment->getFormValues() as $field => $value): ?>
                                <input type="hidden" name="<?php echo $field; ?>" value="<?php echo $value; ?>"/>
                            <?php endforeach; ?>
                            <input type="hidden" name="ik_x_userinfo"
                                   value="<?php echo $this->session->userdata(['user' => 'username']); ?>-server-<?php echo $this->session->userdata(['user' => 'server']); ?>"/>
                            <div style="text-align:center;">
                                <button class="custom_button" type="submit"><?php echo __('Buy Now'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            }
        ?>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	