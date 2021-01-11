<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Account Settings'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Set New Email'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($error)){
                        echo '<div class="e_note">' . $error . '</div>';
                    }
                    if(isset($success)){
                        echo '<div class="s_note">' . $success . '</div>';
                    }
                    if($set_new_email == true){
                        ?>
                        <div class="form">
                            <form method="post" action="" id="set_new_email_form">
                                <table>
                                    <tr>
                                        <td style="width:150px;"><?php echo __('New Email'); ?>
                                            :
                                        </td>
                                        <td><input class="validate[required,custom[email],maxSize[50]]" type="email"
                                                   name="email" id="email" value=""/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button type="submit"
                                                    class="button-style"><?php echo __('Submit'); ?></button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
