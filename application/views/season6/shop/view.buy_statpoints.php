<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Buy Stats'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Buy StatPoints for your character'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($not_found)){
                        echo '<div class="e_note">' . $not_found . '</div>';
                    } else{
                        if(isset($error)){
                            echo '<div class="e_note">' . $error . '</div>';
                        }
                        if(isset($success)){
                            echo '<div class="s_note">' . $success . '</div>';
                        }
                        ?>
                        <script type="text/javascript">
                            var price = 0;

                            function calculateStatPoints(val) {
                                if ((val.toString().search(/^-?[0-9]+$/) != 0)) {
                                    $('#points').val('0');
                                    $('#price').val('0');
                                    if (typeof $('#buy_points_button').attr("disabled") == 'undefined' || $('#buy_points_button').attr("disabled") == false) {
                                        $('#buy_points_button').attr("disabled", "disabled");
                                    }
                                }
                                else {
                                    price = Math.ceil((parseInt(val) * <?php echo $this->config->config_entry('buypoints|price');?>) / <?php echo $this->config->config_entry('buypoints|points');?>);
                                    $('#price').val(price);
                                    if (($('#price').val() != 0)) {
                                        $('#buy_points_button').removeAttr("disabled");
                                    }
                                }
                            }
                        </script>
                        <div class="i_note"><span><?php echo __('INFO'); ?>:</span>
                            <b><?php echo $this->config->config_entry('buypoints|points'); ?></b> <?php echo __('point(s) price '); ?>
                            <b><?php echo $this->config->config_entry('buypoints|price'); ?><?php echo $this->website->translate_credits($this->config->config_entry('buypoints|price_type')); ?></b>
                        </div>
                        <div class="form">
                            <form method="POST" action="" id="buy_stats_form" name="buy_stats_form">
                                <table>
                                    <tr>
                                        <td style="width: 150px;"><?php echo __('Character'); ?></td>
                                        <td>
                                            <select name="character" id="character">
                                                <option value=""><?php echo __('--SELECT--'); ?></option>
                                                <?php
                                                    if($char_list):
                                                        foreach($char_list as $char): ?>
                                                            <option value="<?php echo $char['name']; ?>"><?php echo $char['name']; ?></option>
                                                        <?php
                                                        endforeach;
                                                    endif;
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Amount Of Points'); ?></td>
                                        <td><input type="text" name="points" id="points" value="" class="text"
                                                   onblur="calculateStatPoints($('#points').val());"
                                                   onkeyup="calculateStatPoints($('#points').val());"/></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Price'); ?></td>
                                        <td><input type="text" name="price" id="price" value="" class="text"
                                                   readonly="readonly"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button type="submit" id="buy_points_button"
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
