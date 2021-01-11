<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Buy GM'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Buy GameMaster status for your character'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($not_found)){
                        echo '<div class="e_note">' . $not_found . '</div>';
                    } else{
                        if(isset($error)):
                            echo '<div class="e_note">' . $error . '</div>';
                        endif;
                        if(isset($success)):
                            echo '<div class="s_note">' . $success . '</div>';
                        endif;
                        ?>
                        <div class="form">
                            <form method="POST" action="" id="buy_gm_form" name="buy_gm_form">
                                <table>
                                    <tr>
                                        <td style="width: 150px;"><?php echo __('Character'); ?></td>
                                        <td>
                                            <select class="custom-select" name="character" id="character">
                                                <option
                                                        value=""><?php echo __('--SELECT--'); ?></option>
                                                <?php
                                                    if($char_list):
                                                        foreach($char_list as $char):
                                                            if($char['CtlCode'] != $this->config->config_entry('buygm|gm_ctlcode')):
                                                                ?>
                                                                <option
                                                                        value="<?php echo $char['name']; ?>"><?php echo $char['name']; ?></option>
                                                            <?php
                                                            endif;
                                                        endforeach;
                                                    endif;
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Price'); ?></td>
                                        <td><?php echo $this->config->config_entry('buygm|price'); ?><?php echo $this->website->translate_credits($this->config->config_entry('buygm|price_t')); ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button type="submit" id="buy_gm_button"
                                                    class="button-style"><?php echo __('Submit'); ?></button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <br/>
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
