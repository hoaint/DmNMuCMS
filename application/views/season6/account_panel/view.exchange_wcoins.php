<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Exchange Wcoins'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Exchange'); ?><?php echo $this->website->translate_credits($wcoin_config['credits_type'], $this->session->userdata(['user' => 'server'])); ?><?php echo __('To Wcoins'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($error)){
                        echo '<div class="e_note">' . $error . '</div>';
                    }
                    if(isset($success)){
                        echo '<div class="s_note">' . $success . '</div>';
                    }
                ?>
                <script type="text/javascript">
                    var total = 0,
                        price_credits = parseInt(<?php echo $wcoin_config['reward_coin'];?>);
                    var amountof = ['<?php echo $this->website->translate_credits($wcoin_config['credits_type'], $this->session->userdata(['user' => 'server']));?>', '<?php echo __('WCoins');?>'],
                        willreceive = ['<?php echo __('WCoins');?>', '<?php echo $this->website->translate_credits($wcoin_config['credits_type'], $this->session->userdata(['user' => 'server']));?>'];

                    function calculateWCoins(val) {
                        if ((val.toString().search(/^-?[0-9]+$/) != 0)) {
                            $('#credits').val('0');
                            $('#wcoins').val('0');
                            if (typeof $('#exchange_wcoins').attr("disabled") == 'undefined' || $('#exchange_wcoins').attr("disabled") == false) {
                                $('#exchange_wcoins').attr("disabled", "disabled");
                            }
                        }
                        else {
                            if (val >=  <?php echo $wcoin_config['min_rate'];?>) {
                                if (price_credits < 0) {
                                    total = parseInt(val) * Math.abs(price_credits);
                                }
                                else {
                                    total = parseInt(val) / price_credits;
                                }
                                $('#wcoins').val(Math.floor(total));
                                if (($('#wcoins').val() != 0)) {
                                    $('#exchange_wcoins').removeAttr("disabled");
                                }
                            }
                            else {
                                $('#wcoins').val(0);
                                $('#exchange_wcoins').attr("disabled", "disabled");
                            }
                        }
                    }

                    $(document).ready(function () {
                        $('#exchange_type').on('change', function () {
                            if ($(this).val() == 1) {
                                $('#amountof').html(amountof[0]);
                                $('#willreceive').html(willreceive[0])
                            }
                            else {
                                $('#amountof').html(amountof[1]);
                                $('#willreceive').html(willreceive[1])
                            }
                        });
                    });
                </script>
                <div class="i_note"><?php echo vsprintf(__('Minimal exchange rate is %d %s'), [$wcoin_config['min_rate'], $this->website->translate_credits($wcoin_config['credits_type'], $this->session->userdata(['user' => 'server']))]); ?></div>
                <div class="i_note"><?php echo __('Exchange rate forumula'); ?>:
                    <?php
                        if($wcoin_config['reward_coin'] > 0){
                            echo '1 ' . $this->website->translate_credits($wcoin_config['credits_type'], $this->session->userdata(['user' => 'server'])) . ' / ' . $wcoin_config['reward_coin'];
                        } else{
                            echo '1 ' . $this->website->translate_credits($wcoin_config['credits_type'], $this->session->userdata(['user' => 'server'])) . ' * ' . abs($wcoin_config['reward_coin']);
                        }
                    ?>
                </div>
                <div class="form">
                    <form method="POST" action="" id="wcoin_form" name="wcoin_form">
                        <table>
                            <?php if($wcoin_config['change_back'] == 1){ ?>
                                <tr>
                                    <td style="width:150px;"><?php echo __('Exchange Type'); ?>
                                        :
                                    </td>
                                    <td>
                                        <select name="exchange_type" id="exchange_type">
                                            <option
                                                    value="1"><?php echo $this->website->translate_credits($wcoin_config['credits_type'], $this->session->userdata(['user' => 'server'])); ?><?php echo __('To'); ?><?php echo __('WCoins'); ?></option>
                                            <option
                                                    value="2"><?php echo __('WCoins'); ?><?php echo __('To'); ?><?php echo $this->website->translate_credits($wcoin_config['credits_type'], $this->session->userdata(['user' => 'server'])); ?></option>
                                        </select>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td style="width:150px;"><?php echo __('Amount of'); ?>
                                    <span
                                            id="amountof"><?php echo $this->website->translate_credits($wcoin_config['credits_type'], $this->session->userdata(['user' => 'server'])); ?></span>:
                                </td>
                                <td><input type="text" id="credits" name="credits" value="" class="text"
                                           onblur="calculateWCoins($('#credits').val());"
                                           onkeyup="calculateWCoins($('#credits').val());"/></td>
                            </tr>
                            <tr>
                                <td><?php echo __('Will Receive'); ?> <span
                                            id="willreceive"><?php echo __('WCoins'); ?></span>:
                                </td>
                                <td><input type="text" id="wcoins" name="wcoins" value="" class="text" disabled/></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" id="exchange_wcoins" name="exchange_wcoins"
                                            disabled="disabled"
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
	