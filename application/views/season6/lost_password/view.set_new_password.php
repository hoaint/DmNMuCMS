<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
    <div id="content">
        <div id="box1">
            <div class="title1">
                <h1><?php echo __('Lost Password'); ?></h1>
            </div>
            <div class="box-style1" style="margin-bottom:55px;">
                <h2 class="title"><?php echo __('Set new password'); ?></h2>

                <div class="entry">
                    <?php
                        if(isset($error)){
                            if(is_array($error)){
                                foreach($error as $er){
                                    echo '<div class="e_note">' . $er . '</div>';
                                }
                            } else{
                                echo '<div class="e_note">' . $error . '</div>';
                            }
                        }
                        if(isset($success)){
                            echo '<div class="s_note">' . $success . '</div>';
                        }
                        if(isset($valid) && $valid == 1){
                            ?>
                            <div class="form">
                                <form method="post" action="" id="change_lost_password" name="change_lost_password">
                                    <table>
                                        <tr>
                                            <td style="width: 150px;"><?php echo __('Enter New Password'); ?>:</td>
                                            <td>
                                                <input class="validate[required,minSize[<?php echo $rconfig['min_password']; ?>],maxSize[<?php echo $rconfig['max_password']; ?>]]"
                                                       type="password" name="new_password" id="new_password" value=""/></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Repeat New Password'); ?>:</td>
                                            <td>
                                                <input class="validate[required,minSize[<?php echo $rconfig['min_password']; ?>],maxSize[<?php echo $rconfig['max_password']; ?>],equals[new_password]]"
                                                       type="password" name="new_password2" id="new_password2" value=""/>
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
                                        $("#change_lost_password").validationEngine();
                                    });
                                </script>
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