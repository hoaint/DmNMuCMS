<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
    <div id="content">
        <div id="box1">
            <div class="title1">
                <h1><?php echo __('Registration'); ?></h1>
            </div>
            <div id="content_center">
                <div class="box-style1" style="margin-bottom:55px;">
                    <h2 class="title"><?php echo __('Create your account in just few clicks'); ?></h2>

                    <div class="entry">
                        <div class="form">
                            <form method="post" action="<?php echo $this->config->base_url; ?>registration"
                                  id="registration_form">
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
                                        <td>
                                            <input class="validate[required,minSize[<?php echo $config['min_username']; ?>],maxSize[<?php echo $config['max_username']; ?>]]"
                                                   type="text" name="user" id="user" value=""/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><?php echo __('Nickname'); ?>:</td>
                                        <td>
                                            <input class="validate[required,minSize[<?php echo $config['min_username']; ?>],maxSize[<?php echo $config['max_username']; ?>]]"
                                                   type="text" name="nick" id="nick" value=""/>
                                        </td>
                                    </tr>
                                    <?php if($config['req_email'] == 1): ?>
                                        <tr>
                                            <td><?php echo __('Email'); ?>:</td>
                                            <td>
                                                <input class="validate[required,custom[email],maxSize[50]]" type="text"
                                                       name="email" id="email"
                                                       value="<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>"/>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($config['req_secret'] == 1): ?>
                                        <tr>
                                            <td><?php echo __('Secret Questions'); ?>:</td>
                                            <td>
                                                <select name="fpas_ques" id="fpas_ques" class="validate[required]">
                                                    <?php
                                                        foreach($this->website->secret_questions() as $key => $value):
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
                                    <?php endif; ?>
                                    <?php if($config['email_validation'] == 0 || $config['generate_password'] == 0): ?>
                                        <tr>
                                            <td><?php echo __('Password'); ?>:</td>
                                            <td>
                                                <input class="validate[required,minSize[<?php echo $config['min_password']; ?>],maxSize[<?php echo $config['max_password']; ?>]]"
                                                       type="password" name="pass" id="pass" value=""/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Repeat Password'); ?>:</td>
                                            <td>
                                                <input class="validate[required,minSize[<?php echo $config['min_password']; ?>],maxSize[<?php echo $config['max_password']; ?>],equals[pass]]"
                                                       type="password" name="rpass" id="rpass" value=""/>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(isset($show_ref) && $show_ref == true): ?>
                                        <tr>
                                            <td><?php echo __('Referrer'); ?>:</td>
                                            <td>
                                                <input type="text" name="referrer" id="referrer"
                                                       value="<?php echo $ref; ?>" readonly/>
                                                <input type="hidden" name="ref_server" id="ref_server"
                                                       value="<?php echo $server; ?>" readonly/>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td colspan="2" align="left">
                                            <input class="validate[required]" type="checkbox" name="rules" id="rules"/>
                                            <?php echo __('I have read and agree to the <a href="" id="rules_dialog"><b>game rules.</b></a>'); ?>
                                        </td>
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
                                        <td>
                                        </td>
                                        <td>
                                            <button type="submit"
                                                    class="button-style"><?php echo __('Submit'); ?></button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        <?php if (isset($security_config['captcha_type']) && $security_config['captcha_type'] == 1): ?>
                        App.buildCaptcha('.QapTcha');
                        <?php endif; ?>
                        <?php if (isset($security_config['captcha_type']) && $security_config['captcha_type'] == 3): ?>
                        $.extend(DmNConfig, {use_recaptcha_v2: 1});
                        <?php endif; ?>
                        $("#registration_form").validationEngine('attach', {
                            scroll: false,
                            onValidationComplete: function (form, status) {
                                if (status == true) {
                                    App.registerAccount(form);
                                }
                            }
                        });
                        $("#rules_dialog").on('click', function (e) {
                            e.preventDefault();
                            App.initializeRulesDialog('<?php echo __('Server Rules'); ?>');
                        });
                    });
                </script>
            </div>
        </div>
    </div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>