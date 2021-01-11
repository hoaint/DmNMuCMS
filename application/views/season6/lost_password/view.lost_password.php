<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
    <div id="content">
        <div id="box1">
            <div class="title1">
                <h1><?php echo __('Lost Password'); ?></h1>
            </div>
            <div class="box-style1" style="margin-bottom:55px;">
                <h2 class="title"><?php echo __('Find out your password in case you have lost it.'); ?></h2>

                <div class="entry">
                    <?php
                        if(isset($error)):
                            echo '<div class="e_note">' . $error . '</div>';
                        endif;
                        if(isset($success)):
                            echo '<div class="s_note">' . $success . '</div>';
                        endif;
                        if(!isset($secret_question_list)):
                            ?>
                            <div class="form">
                                <form method="post" action="<?php echo $this->config->base_url; ?>lost-password"
                                      id="lostpassword_form" name="lostpassword_form">
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
                                            <td style="width: 150px;"><?php echo __('Username'); ?>:</td>
                                            <td><input type="text" name="lost_info" id="lost_info" value=""
                                                       class="validate[required,minSize[<?php echo $rconfig['min_username']; ?>]]"/>
                                            </td>
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
                                    $("#lostpassword_form").validationEngine();
                                });
                            </script>
                        <?php
                        endif;
                        if(isset($secret_question_list)):
                            ?>
                            <div class="form">
                                <form method="post"
                                      action="<?php echo $this->config->base_url; ?>lost-password/by-question/"
                                      id="lostpassword_secret_form" name="lostpassword_secret_form">
                                    <table>
                                        <tr>
                                            <td><?php echo __('Secret Questions'); ?>:</td>
                                            <td>
                                                <select name="fpas_ques" id="fpas_ques" class="validate[required]">
                                                    <?php
                                                        foreach($secret_question_list as $key => $value):
                                                            ?>
                                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                        <?php
                                                        endforeach;
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Secret Answer'); ?>:</td>
                                            <td>
                                                <input class="validate[required,minSize[4],maxSize[50]]" type="text"
                                                       name="fpas_answ" id="fpas_answ" value=""/>
                                            </td>
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
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $("#lostpassword_secret_form").validationEngine();
                                    });
                                </script>
                            </div>
                        <?php
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