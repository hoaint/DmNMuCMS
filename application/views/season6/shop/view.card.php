<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Shop Cart'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('My Shop Cart Items'); ?></h2>

            <div class="entry">
                <script>
                    $(document).ready(function () {
                        $('span[id^="card_item_"]').each(function () {
                            App.initializeTooltip($(this), true, 'warehouse/item_info_image');
                        });

                        var price_c = 0,
                            price_gc = 0;
                        $('input[id^="add_to_warehouse_1_"]').each(function () {
                            $(this).on('click', function () {
                                var id = $(this).attr('id').split('_').pop(-1);
                                var checked = $(this).is(':checked');
                                if (checked) {
                                    price_c += parseInt($('#price_1_' + id).text());
                                }
                                else {
                                    price_c -= parseInt($('#price_1_' + id).text());
                                }
                                $('#total_price_c').html(price_c);
                            });

                        });
                        $('input[id^="add_to_warehouse_2_"]').each(function () {
                            $(this).on('click', function () {
                                var id = $(this).attr('id').split('_').pop(-1);
                                var checked = $(this).is(':checked');
                                if (checked) {
                                    price_gc += parseInt($('#price_2_' + id).text());
                                }
                                else {
                                    price_gc -= parseInt($('#price_2_' + id).text());
                                }
                                $('#total_price_gc').html(price_gc);
                            });

                        });
                        $('a[id^="remove_item_1_"]').each(function () {
                            $(this).on('click', function (e) {
                                e.preventDefault();
                                var tr = $(this).parents('table:first').find('tr');
                                var id = $(this).attr('id').split('_').pop(-1);
                                var checked = $('#add_to_warehouse_1_' + id).is(':checked');
                                if (checked) {
                                    price_c -= parseInt($('#price_1_' + id).text());
                                    $('#total_price_c').html(price_c);
                                }
                                App.removeItemFromCart(id, tr, 'credits_items');
                            });
                        });
                        $('a[id^="remove_item_2_"]').each(function () {
                            $(this).on('click', function (e) {
                                e.preventDefault();
                                var tr = $(this).parents('table:first').find('tr');
                                var id = $(this).attr('id').split('_').pop(-1);
                                var checked = $('#add_to_warehouse_2_' + id).is(':checked');
                                if (checked) {
                                    price_gc -= parseInt($('#price_2_' + id).text());
                                    $('#total_price_gc').html(price_gc);
                                }
                                App.removeItemFromCart(id, tr, 'gcredits_items');
                            });
                        });
                        $('#buy_items_credits').on('click', function (e) {
                            e.preventDefault();
                            if ($('#items_credits :checkbox:checked').length > 0) {
                                App.buyItems($('#items_credits'), 'credits_items');
                            }
                            else {
                                App.notice(App.lc.translate('Error').fetch(), 'error', App.lc.translate('Please select atleast one item.').fetch());
                            }
                        });
                        $('#buy_items_gcredits').on('click', function (e) {
                            e.preventDefault();
                            if ($('#items_gcredits :checkbox:checked').length > 0) {
                                App.buyItems($('#items_gcredits'), 'gcredits_items');
                            }
                            else {
                                App.notice(App.lc.translate('Error').fetch(), 'error', App.lc.translate('Please select atleast one item.').fetch());
                            }
                        });
                    });
                </script>
                <div id="credits_items">
                    <?php
                        if($credits_items != false){
                            ?>
                            <form method="POST" name="items_credits" id="items_credits" action="">
                                <table class="add_to_card" id="credits_table" cellspacing="0">
                                    <thead>
                                    <th><?php echo __('Item'); ?></th>
                                    <th><?php echo __('Price'); ?></th>
                                    <th></th>
                                    <th></th>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $i = 0;
                                        foreach($credits_items as $key => $items){
                                            $i++;
                                            $even = ($i % 2) ? 'class="even"' : '';
                                            $this->iteminfo->itemData($items['item_hex']);
                                            ?>
                                            <tr id="item_<?php echo $items['id']; ?>" <?php echo $even; ?>>
                                                <td><span id="card_item_<?php echo $i; ?>"
                                                          data-info="<?php echo $items['item_hex']; ?>"><a
                                                                href="#"><?php echo $this->iteminfo->realName(); ?></a></span>
                                                </td>
                                                <td><span
                                                            id="price_1_<?php echo $items['id']; ?>"><?php echo $items['price'] . '</span> ' . $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1'); ?>
                                                </td>
                                                <td><input type="checkbox" id="add_to_warehouse_1_<?php echo $items['id']; ?>"
                                                           name="add_to_warehouse[<?php echo $items['id']; ?>]"/></td>
                                                <td><a href=""
                                                       id="remove_item_1_<?php echo $items['id']; ?>"><?php echo __('Remove'); ?></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                <div style="padding-top:10px;">
                                    <div
                                            style="padding:3px 13px;float:left;"><?php echo __('Total Price'); ?>
                                        : <span id="total_price_c"
                                                style="color: red;">0</span> <?php echo $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1'); ?>
                                    </div>
                                    <div style="float:right;">
                                        <button id="buy_items_credits"
                                                class="custom_button"><?php echo __('Buy Selected'); ?></button>
                                    </div>
                                </div>
                            </form>

                            <?php
                        } else{
                            ?>
                            <div
                                    class="i_note"><?php echo sprintf(__('No items for %s in cart.'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1')); ?></div>
                            <?php
                        }
                    ?>
                </div>
                <div style="padding-top: 20px;clear:both;"></div>
                <div id="gcredits_items">
                    <?php
                        if($gcredits_items != false){
                            ?>
                            <form method="POST" name="items_gcredits" id="items_gcredits" action="">
                                <table class="add_to_card" id="gcredits_table" cellspacing="0">
                                    <thead>
                                    <th><?php echo __('Item'); ?></th>
                                    <th><?php echo __('Price'); ?></th>
                                    <th></th>
                                    <th></th>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $i = 0;
                                        foreach($gcredits_items as $key => $items){
                                            $i++;
                                            $even = ($i % 2) ? 'class="even"' : '';
                                            $this->iteminfo->itemData($items['item_hex']);
                                            ?>
                                            <tr id="item_<?php echo $items['id']; ?>" <?php echo $even; ?>>
                                                <td><span id="card_item_<?php echo $i; ?>"
                                                          data-info="<?php echo $items['item_hex']; ?>"><a
                                                                href="#"><?php echo $this->iteminfo->realName(); ?></a></span>
                                                </td>
                                                <td><span
                                                            id="price_2_<?php echo $items['id']; ?>"><?php echo $items['price'] . '</span> ' . $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_2'); ?>
                                                </td>
                                                <td><input type="checkbox" id="add_to_warehouse_2_<?php echo $items['id']; ?>"
                                                           name="add_to_warehouse[<?php echo $items['id']; ?>]"/></td>
                                                <td><a href=""
                                                       id="remove_item_2_<?php echo $items['id']; ?>"><?php echo __('Remove'); ?></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                <div style="padding-top:10px;">
                                    <div
                                            style="padding:3px 13px;float:left;"><?php echo __('Total Price'); ?>
                                        : <span id="total_price_gc"
                                                style="color: red;">0</span> <?php echo $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_2'); ?>
                                    </div>
                                    <div style="float:right;">
                                        <button id="buy_items_gcredits"
                                                class="custom_button"><?php echo __('Buy Selected'); ?></button>
                                    </div>
                                </div>
                            </form>

                            <?php
                        } else{
                            ?>
                            <div
                                    class="i_note"><?php echo sprintf(__('No items for %s in cart.'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_2')); ?></div>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	