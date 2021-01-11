<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
    <div id="content">
        <div id="box1">
            <div class="title1">
                <h1><?php echo __('Donate'); ?></h1>
            </div>
            <div class="box-style1" style="margin-bottom:55px;">
                <h2 class="title"><?php echo __('With PayPal'); ?></h2>

                <div class="entry">
                    <?php
                        if(isset($error)):
                            if(is_array($error)):
                                foreach($error as $er):
                                    echo '<div class="e_note">' . $er . '</div>';
                                endforeach;
                            else:
                                echo '<div class="e_note">' . $error . '</div>';
                            endif;
                        endif;
                        if(isset($success)):
                            echo '<div class="s_note">' . $success . '</div>';
                        endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>