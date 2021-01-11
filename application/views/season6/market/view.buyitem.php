<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Market'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Sell And Buy Items'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($error)):
                        echo '<div class="e_note">' . $error . '</div>';
                    endif;
                    if(isset($success)):
                        echo '<div class="s_note">' . $success . '</div>';
                    endif;
                    if(!isset($error)):
                        if($this->Mmarket->item_info['price_jewel'] != 0 && $this->Mmarket->item_info['jewel_type'] != 0){
                            $price = $this->Mmarket->get_jewel_image($this->Mmarket->item_info['jewel_type']) . 'x ' . $this->Mmarket->item_info['price_jewel'];
                        } else{
                            switch($this->Mmarket->item_info['price_type']){
                                case 1:
                                    $price = round(($this->Mmarket->item_info['price'] / 100) * $this->config->config_entry('market|sell_tax') + $this->Mmarket->item_info['price']) . ' <b>' . $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1') . '</b>';
                                    break;
                                case 2:
                                    $price = round(($this->Mmarket->item_info['price'] / 100) * $this->config->config_entry('market|sell_tax') + $this->Mmarket->item_info['price']) . ' <b>' . $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_2') . '</b>';
                                    break;
                                case 3:
                                    $price = $this->website->zen_format(round(($this->Mmarket->item_info['price'] / 100) * $this->config->config_entry('market|sell_tax') + $this->Mmarket->item_info['price'])) . ' <b>' . $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_3') . '</b>';
                                    break;
                            }
                        }
                        ?>
                        <script>
                            $(document).ready(function () {
                                $('div#item_info').each(function () {
                                    App.initializeTooltip($(this), true, 'warehouse/item_info');
                                });
                            });
                        </script>
                        <form method="POST" action="" id="buy_item" name="buy_item">
                            <div style="margin-bottom:20px;text-align:center;" id="item_info"
                                 data-info="<?php echo $this->Mmarket->item_info['item']; ?>">
                                <div><img alt="" src="<?php echo $this->itemimage->load($this->iteminfo->id, $this->iteminfo->type, (int)$this->iteminfo->level, 0); ?>"/>
                                </div><?php echo $this->iteminfo->getNameStyle(true); ?></div>
                            <table class="ranking-table">
                                <tbody>
                                <tr>
                                    <td style="width:30%;text-align: left;padding-left: 15px;"><?php echo __('Merchant'); ?>
                                        :
                                    </td>
                                    <td style="width:30%;text-align: left;padding-left: 15px;"><a
                                                href="<?php echo $this->config->base_url; ?>info/character/<?php echo bin2hex($this->Mmarket->item_info['char']); ?>/<?php echo $this->Mmarket->item_info['server']; ?>"><?php echo htmlspecialchars($this->Mmarket->item_info['char']); ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:30%;text-align: left;padding-left: 15px;"><?php echo __('Start Time'); ?>
                                        :
                                    </td>
                                    <td style="width:30%;text-align: left;padding-left: 15px;"><?php echo date('Y-m-d H:i', strtotime($this->Mmarket->item_info['add_date'])); ?></td>
                                </tr>
                                <tr>
                                    <td style="width:30%;text-align: left;padding-left: 15px;"><?php echo __('End Time'); ?>
                                        :
                                    </td>
                                    <td style="width:30%;text-align: left;padding-left: 15px;"><?php echo date('Y-m-d H:i', strtotime($this->Mmarket->item_info['active_till'])); ?></td>
                                </tr>
                                <tr>
                                    <td style="width:30%;text-align: left;padding-left: 15px;"><?php echo __('Price'); ?>
                                        :
                                    </td>
                                    <td style="width:30%;text-align: left;padding-left: 15px;"><?php echo $price; ?></td>
                                </tr>
                                </tbody>
                            </table>
                            <div style="text-align:center;">
                                <button type="submit" name="buy_item"
                                        class="custom_button"><?php echo __('Buy Now'); ?></button>
                            </div>
                        </form>
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
	