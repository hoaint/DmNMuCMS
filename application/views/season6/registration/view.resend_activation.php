<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
    <div id="content">
        <div id="box1">
            <div class="title1">
                <h1><?php echo __('Registration'); ?></h1>
            </div>
            <div class="box-style1" style="margin-bottom:55px;">
                <h2 class="title"><?php echo __('Resend activation code.'); ?></h2>
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
                        if(isset($not_required)){
                            echo '<div class="e_note">' . $not_required . '</div>';
                        } else{
                            ?>
                            <div class="form">
                                <form method="post" action="" id="resend_activation_form" name="resend_activation_form">
                                    <table>
                                        <?php if($this->website->is_multiple_accounts() == true): ?>
                                            <tr>
                                                <td style="width: 150px;"><?php echo __('Server'); ?>:</td>
                                                <td>
                                                    <select name="server" id="server" class="validate[required]">
                                                        <option value=""><?php echo __('Select Server'); ?></option>
                                                        <?php
                                                            foreach($this->website->server_list() as $key => $server):
                                                                ?>
                                                                <option value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                                            <?php
                                                            endforeach;
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td style="width: 150px;"><?php echo __('Email'); ?>:</td>
                                            <td><input type="text" name="email" id="email" value=""
                                                       class="validate[required,custom[email],maxSize[50]]"/></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                        <?php if(isset($security_config['captcha_type']) && $security_config['captcha_type'] == 1): ?>
                                            <tr>
                                                <td><?php echo __('Security'); ?>:</td>
                                                <td>
                                                    <div class="QapTcha"></div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if(isset($security_config['captcha_type']) && $security_config['captcha_type'] == 3): ?>
                                            <script src="https://www.google.com/recaptcha/api.js"></script>
                                            <tr>
                                                <td><?php echo __('Security'); ?>:</td>
                                                <td>
                                                    <div class="g-recaptcha"
                                                         data-sitekey="<?php echo $security_config['recaptcha_pub_key']; ?>"></div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
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
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    <?php if (isset($security_config['captcha_type']) && $security_config['captcha_type'] == 1): ?>
                                    App.buildCaptcha('.QapTcha');
                                    <?php endif; ?>
                                    $("#resend_activation_form").validationEngine();
                                });
                            </script>
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