<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Zen Wallet'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Transfer zen between characters and other places.'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($error)){
                        echo '<div class="e_note">' . $error . '</div>';
                    }
                    if(isset($success)){
                        echo '<div class="s_note">' . $success . '</div>';
                    }
                ?>
                <div class="form">
                    <form method="post" action="<?php echo $this->config->base_url; ?>account-panel/zen-wallet"
                          id="zen_wallet_form">
                        <table>
                            <tr>
                                <td style="width: 150px;"><?php echo __('Transfer From'); ?>
                                    :
                                </td>
                                <td>
                                    <select class="custom-select" name="from" id="from">
                                        <?php
                                            if(isset($char_list) && $char_list != false){
                                                foreach($char_list as $char): ?>
                                                    <option
                                                            value="<?php echo $char['name']; ?>"><?php echo $char['name']; ?>
                                                        (<?php echo $this->website->zen_format($char['money']); ?>)
                                                    </option>
                                                <?php
                                                endforeach;
                                            }
                                        ?>
                                        <?php
                                            if(isset($wh_zen) && $wh_zen !== false){
                                                ?>
                                                <option
                                                        value="warehouse"><?php echo __('Warehouse'); ?>
                                                    (<?php echo $this->website->zen_format($wh_zen); ?>)
                                                </option>
                                                <?php
                                            }
                                        ?>
                                        <?php
                                            if(isset($wallet_zen) && $wallet_zen !== false){
                                                ?>
                                                <option
                                                        value="webwallet"><?php echo __('Zen Wallet'); ?>
                                                    (<?php echo $this->website->zen_format($wallet_zen['credits3']); ?>)
                                                </option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 150px;"><?php echo __('Transfer To'); ?>
                                    :
                                </td>
                                <td>
                                    <select class="custom-select" name="to" id="to">
                                        <?php
                                            if(isset($char_list) && $char_list != false){
                                                foreach($char_list as $char): ?>
                                                    <option
                                                            value="<?php echo $char['name']; ?>"><?php echo $char['name']; ?>
                                                        (<?php echo $this->website->zen_format($char['money']); ?>)
                                                    </option>
                                                <?php
                                                endforeach;
                                            }
                                        ?>
                                        <?php
                                            if(isset($wh_zen) && $wh_zen !== false){
                                                ?>
                                                <option
                                                        value="warehouse"><?php echo __('Warehouse'); ?>
                                                    (<?php echo $this->website->zen_format($wh_zen); ?>)
                                                </option>
                                                <?php
                                            }
                                        ?>
                                        <?php
                                            if(isset($wallet_zen) && $wallet_zen !== false){
                                                ?>
                                                <option
                                                        value="webwallet"><?php echo __('Zen Wallet'); ?>
                                                    (<?php echo $this->website->zen_format($wallet_zen['credits3']); ?>)
                                                </option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo __('Enter Amount'); ?>:</td>
                                <td><input type="text" id="zen" name="zen" value="" class="text"/></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" id="transfer_zen" name="transfer_zen"
                                            class="button-style"><?php echo __('Submit'); ?></button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	