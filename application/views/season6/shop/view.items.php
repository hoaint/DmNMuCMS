<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <?php if($this->config->config_entry('shop_' . $this->session->userdata(['user' => 'server']) . '|discount') == 1 && strtotime($this->config->config_entry('shop_' . $this->session->userdata(['user' => 'server']) . '|discount_time')) > time()): ?>
            <div class="discount_notice">
                <div class="ribbon-discount-green">
                    <div class="ribbon-green"><?php echo __('PROMO'); ?></div>
                </div>
                <div
                        class="content"><?php echo $this->config->config_entry('shop_' . $this->session->userdata(['user' => 'server']) . '|discount_notice'); ?></div>
            </div>​
        <?php
        endif;
        ?>
        <div class="title1">
            <h1><?php echo __('Shop'); ?></h1>
        </div>
        <div id="content_center">
            <div class="box-style1" style="margin-bottom: 20px;">
                <h2 class="title"><?php echo __('View Items'); ?></h2>

                <div class="entry">
                    <div id="ucp_info">
                        <div style="padding:3px;text-align: center;">
                            <?php echo $this->webshop->load_cat_list(false, '', false); ?>
                        </div>
                    </div>
                    <?php
                        if(isset($error)){
                            echo '<div style="clear:left;"></div><div class="e_note">' . $error . '</div>';
                        } else{
                            ?>
                            <?php
                            if(isset($items) && !empty($items)){
                                echo '<table class="item_table" style="padding-top:10px;">';
                                foreach($items as $item){
                                    if($item['pos'] == 1){
                                        echo '<tr style="text-align:center;">';
                                    }
                                    echo '<td>
									<table class="each_item">
										<tr>
											<td class="item_name"><div class="items"><a id="shop_item_title_' . $item['id'] . '" href="" data-name="' . $item['name'] . '" data-info="' . $item['name'] . '&lt;br /&gt;' . $item['class'] . '">' . $this->website->set_limit($item['name'], 15, '...') . '</a></div></td>
										</tr>
										<tr>
											<td class="item_bg"><div class="item_image" id="shop_item_image_' . $item['id'] . '" data-name="' . $item['name'] . '" style="cursor:pointer;">' . $item['image'] . '</div></td>
										</tr>
										<tr><td class="item_footer"></td></tr>
									</table>
								</td>';
                                    if($item['pos'] == $total_columns){
                                        echo '</tr>';
                                    }
                                }
                                echo '</table>';
                            } else{
                                echo '<div style="clear:left;"></div><div class="w_note">' . __('Currently No Items In Webshop') . '</div>';
                            }
                            ?>
                            <?php
                            if(isset($pagination)){
                                ?>
                                <div style="padding:10px;text-align:center;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td><?php echo $pagination; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <?php
                            }
                        }
                    ?>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('a[id^="shop_item_title_"], div[id^="shop_item_image_"]').on('click', function (e) {
                        e.preventDefault();
                        var buy_dialog = $('<div id="item_content" style="margin: 0 auto;"></div>');
                        var item_name = $(this).attr('data-name');
                        var id = $(this).attr('id').split('_')[3];
                        $.ajax({
                            url: DmNConfig.base_url + 'shop/get_item_data',
                            data: {id: id},
                            success: function (data) {
                                if (data.error) {
                                    App.notice(App.locale.error, 'error', data.error);
                                }
                                else {
                                    EJS.config({cache: false});
                                    var html = new EJS({url: DmNConfig.base_url + 'assets/default_assets/js_templates/buy_item.ejs'}).render(data);
                                    if ($('#item_content').dialog("isOpen") == true) {
                                        $('#item_content').dialog('destroy');
                                    }
                                    buy_dialog.dialog({
                                        width: 660,
                                        height: 'auto',
                                        title: "<?php echo __('Buy');?> " + item_name,
                                        dialogClass: 'fixed',
                                        show: {
                                            effect: "blind",
                                            duration: 500
                                        },
                                        hide: {
                                            effect: "blind",
                                            duration: 500
                                        },
                                        close: function () {
                                            $(this).dialog('destroy');
                                        }
                                    });
                                    buy_dialog.html(html);
                                    App.initializeModalBoxes();
                                }
                            }
                        });
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
	