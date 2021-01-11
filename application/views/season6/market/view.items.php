<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Market'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title">
                <?php echo __('Sell And Buy Items'); ?>
                <div style="float:right;">
                    <?php
                        foreach($this->website->server_list() as $key => $server):
                            if($server['visible'] == 1 && $key != $def_server):
                                ?>
                                <a class="custom_button"
                                   href="<?php echo $this->config->base_url; ?>market/index/1/<?php echo $key; ?>"><?php echo $server['title']; ?><?php echo __('Market'); ?></a>
                            <?php
                            endif;
                        endforeach;
                    ?>
                </div>
            </h2>
            <div class="entry">
                <div style="float:right;">
                    <a class="custom_button" href=""
                       id="show_filter"><?php echo __('Show Filter'); ?></a>
                    <a class="custom_button"
                       href="<?php echo $this->config->base_url; ?>warehouse"><?php echo __('Warehouse'); ?></a>
                    <a class="custom_button"
                       href="<?php echo $this->config->base_url; ?>warehouse/web"><?php echo __('Web Warehouse'); ?></a>
                    <a class="custom_button"
                       href="<?php echo $this->config->base_url; ?>market/history"><?php echo __('History'); ?></a>
                </div>
                <div style="padding-top:40px;"></div>
                <div style="clear:left;"></div>
                <div style="padding-top:20px;"></div>
                <script>
                    $(document).ready(function () {
                        $('span[id^="market_item_"]').each(function () {
                            App.initializeTooltip($(this), true, 'warehouse/item_info_image');
                        });
                        var filterStatus = App.readCookie('DmN_Market_Filter_Status'),
                            posCookie = App.readCookie('DmN_Market_Filter_Position');
                        if (filterStatus !== null) {
                            if (filterStatus == 'hidden') {
                                $("#item_filter_overlay").hide();
                            }
                            else {
                                $("#item_filter_overlay").show();
                            }
                        }
                        if (posCookie !== null) {
                            $('#item_filter_overlay').css({
                                top: posCookie.split('-')[0] + 'px',
                                left: posCookie.split("-")[1] + 'px'
                            });
                        }
                        else {
                            $('#item_filter_overlay').css({top: '400px', left: '200px'});
                        }
                        $('#item_filter_overlay').draggable({
                            handle: '.modal-header2',
                            stop: function (event, ui) {
                                var currentPos = $(this).position();
                                var currentTop = Math.round(currentPos.top);
                                var currentLeft = Math.round(currentPos.left);
                                App.setCookie('DmN_Market_Filter_Position', currentTop + '-' + currentLeft, 14);
                            }
                        });
                        $('#show_filter').on('click', function (e) {
                            e.preventDefault();
                            $("#item_filter_overlay").show();
                            App.setCookie('DmN_Market_Filter_Status', 'visible', 1);
                        });
                        $('#item_filter_overlay a.close').on('click', function () {
                            $("#item_filter_overlay").hide();
                            App.setCookie('DmN_Market_Filter_Status', 'hidden', 1);
                        });

                        <?php
                        $names = [];
                        if(!empty($item_title_list)){
                            foreach($item_title_list AS $key => $value){
                                $names[] = $value['item_name'];
                            }
                        }
                        ?>

                        var availableItems = <?php echo json_encode($names);?>;
                        $("#item").autocomplete({
                            source: availableItems
                        });
                    });
                </script>
                <?php if(isset($items) && !empty($items)): ?>
                    <table class="ranking-table">
                        <thead>
                        <tr class="main-tr">
                            <td>#</td>
                            <td><?php echo __('Item'); ?></td>
                            <td><?php echo __('Merchant'); ?></td>
                            <td><?php echo __('Price + Tax'); ?>
                                (<?php echo $this->config->config_entry('market|sell_tax'); ?>%)
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach($items as $item):
                                $color = ($item['highlighted'] == 1) ? '#FF3333' : '';
                                ?>
                                <tr style="background-color:<?php echo $color; ?>;">
                                    <td><?php echo $item['icon']; ?></td>
                                    <td style="background-color:<?php echo $color; ?>;"><span
                                                id="market_item_<?php echo $item['pos']; ?>"
                                                data-info="<?php echo $item['item']; ?>"><a
                                                    href="<?php echo $this->config->base_url; ?>market/buy/<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($item['seller']); ?></td>
                                    <td><?php echo $item['price']; ?></td>
                                </tr>
                            <?php
                            endforeach;
                        ?>
                        </tbody>
                    </table>
                    <?php
                    if(isset($pagination)):
                        ?>
                        <table style="width: 100%;">
                            <tr>
                                <td><?php echo $pagination; ?></td>
                            </tr>
                        </table>
                    <?php
                    endif;
                    ?>
                <?php
                else:
                    ?>
                    <div
                            class="w_note"><?php echo __('No Items Found.'); ?></div>
                <?php
                endif;
                ?>
                <div id="item_filter_overlay">
                    <div id="item_filter">
                        <div class="modal-header2">
                            <h2><?php echo __('Items Filter'); ?></h2>
                            <a class="close" href="javascript:;"></a>
                        </div>
                        <div class="item_filter_content">
                            <?php if(!empty($item_title_list)){ ?>
                                <form method="post" action="">
                                    <label
                                            style="font-weight: bold;margin:3px;font-size:12px;"><?php echo __('Search Item'); ?>
                                        :</label>

                                    <div
                                            style="border-bottom:1px solid gray;width: 90%;margin:5px;display:block;"></div>
                                    <input style="margin:3px;" type="text" name="item" id="item" value=""/>
                                    <button class="custom_button" type="submit" value="search_item"
                                            name="search_item"><?php echo __('Search'); ?></button>
                                    <br/>
                                </form>
                            <?php } ?>
                            <form method="post" action="">
                                <label
                                        style="font-weight: bold;margin:3px;font-size:12px;"><?php echo __('Items Level'); ?>
                                    :</label>

                                <div style="border-bottom:1px solid gray;width: 90%;margin:5px;display:block;"></div>
                                <?php for($i = 0; $i <= 15; $i++): ?>
                                    <input type="checkbox" name="lvl[]"
                                           value="<?php echo $i; ?>" <?php if(isset($_SESSION['filter']['lvl'])){
                                        foreach($_SESSION['filter']['lvl'] as $level){
                                            if($level == $i){
                                                echo 'checked="checked"';
                                            }
                                        }
                                    } ?>/> <?php echo $i; ?> LVL<br/>
                                <?php endfor; ?>
                                <div style="border-bottom:1px solid gray;width: 90%;margin:5px;display:block;"></div>
                                <label
                                        style="font-weight: bold;margin:3px;font-size:12px;"><?php echo __('Items Options'); ?>
                                    :</label>

                                <div style="border-bottom:1px solid gray;width: 90%;margin:5px;display:block;"></div>
                                <input type="checkbox" name="luck"
                                       value="1" <?php if(isset($_SESSION['filter']['luck'])){
                                    echo 'checked="checked"';
                                } ?>/> <?php echo __('Item Luck'); ?><br/>
                                <input type="checkbox" name="skill"
                                       value="1" <?php if(isset($_SESSION['filter']['skill'])){
                                    echo 'checked="checked"';
                                } ?>/> <?php echo __('Item Skill'); ?><br/>
                                <input type="checkbox" name="ancient"
                                       value="1" <?php if(isset($_SESSION['filter']['ancient'])){
                                    echo 'checked="checked"';
                                } ?>/> <?php echo __('Item Ancient'); ?><br/>

                                <div style="border-bottom:1px solid gray;width: 90%;margin:5px;display:block;"></div>
                                <label
                                        style="font-weight: bold;margin:3px;font-size:12px;"><?php echo __('Items Excellent'); ?>
                                    :</label>

                                <div style="border-bottom:1px solid gray;width: 90%;margin:5px;display:block;"></div>
                                <?php
                                    $exe_count = (defined('MU_VERSION') && MU_VERSION >= 5) ? 9 : 6;
                                    for($i = 1; $i <= $exe_count; $i++):
                                        ?>
                                        <input type="checkbox" name="excellent[]"
                                               value="<?php echo $i; ?>" <?php if(isset($_SESSION['filter']['excellent'])){
                                            foreach($_SESSION['filter']['excellent'] as $exe){
                                                if($exe == $i){
                                                    echo 'checked="checked"';
                                                }
                                            }
                                        } ?>/> <?php echo __('Excellent'); ?><?php echo $i; ?>
                                        <br/>
                                    <?php endfor; ?>
                                <div style="border-bottom:1px solid gray;width: 90%;margin:5px;display:block;"></div>
                                <label
                                        style="font-weight: bold;margin:3px;font-size:12px;"><?php echo __('Items Category'); ?>
                                    :</label>

                                <div style="border-bottom:1px solid gray;width: 90%;margin:5px;display:block;"></div>
                                <?php if(isset($_SESSION['filter']['cat'])){
                                    echo $this->webshop->load_cat_list_table($_SESSION['filter']['cat']);
                                } else{
                                    echo $this->webshop->load_cat_list_table();
                                } ?>
                                <div style="border-bottom:1px solid gray;width: 90%;margin:5px;display:block;"></div>
                                <label
                                        style="font-weight: bold;margin:3px;font-size:12px;"><?php echo __('Items For Class'); ?>
                                    :</label>

                                <div style="border-bottom:1px solid gray;width: 90%;margin:5px;display:block;"></div>
                                <input type="radio" name="class"
                                       value="sm" <?php if(isset($_SESSION['filter']['class']) && $_SESSION['filter']['class'] == 'sm'){
                                    echo 'checked="checked"';
                                } ?>/> DW / SM / GM<br/>
                                <input type="radio" name="class"
                                       value="bk" <?php if(isset($_SESSION['filter']['class']) && $_SESSION['filter']['class'] == 'bk'){
                                    echo 'checked="checked"';
                                } ?>/> DK / BK / BM<br/>
                                <input type="radio" name="class"
                                       value="me" <?php if(isset($_SESSION['filter']['class']) && $_SESSION['filter']['class'] == 'me'){
                                    echo 'checked="checked"';
                                } ?>/> ELF / ME / HE<br/>
                                <input type="radio" name="class"
                                       value="mg" <?php if(isset($_SESSION['filter']['class']) && $_SESSION['filter']['class'] == 'mg'){
                                    echo 'checked="checked"';
                                } ?>/> MG / DM<br/>
                                <input type="radio" name="class"
                                       value="dl" <?php if(isset($_SESSION['filter']['class']) && $_SESSION['filter']['class'] == 'dl'){
                                    echo 'checked="checked"';
                                } ?>/> DL / LE<br/>
                                <input type="radio" name="class"
                                       value="bs" <?php if(isset($_SESSION['filter']['class']) && $_SESSION['filter']['class'] == 'bs'){
                                    echo 'checked="checked"';
                                } ?>/> SUM / BS / DIM<br/>
                                <input type="radio" name="class"
                                       value="rf" <?php if(isset($_SESSION['filter']['class']) && $_SESSION['filter']['class'] == 'rf'){
                                    echo 'checked="checked"';
                                } ?>/> RF / FS<br/>
                                <?php if(defined('MU_VERSION') && MU_VERSION >= 5): ?>
                                    <input type="radio" name="class"
                                           value="gl" <?php if(isset($_SESSION['filter']['class']) && $_SESSION['filter']['class'] == 'gl'){
                                        echo 'checked="checked"';
                                    } ?>/> GL / ML<br/>
                                <?php endif; ?>
								<?php if(defined('MU_VERSION') && MU_VERSION >= 9): ?>
                                    <input type="radio" name="class"
                                           value="rw" <?php if(isset($_SESSION['filter']['class']) && $_SESSION['filter']['class'] == 'rw'){
                                        echo 'checked="checked"';
                                    } ?>/> Rune Wizard<br/>
                                <?php endif; ?>
								<?php if(defined('MU_VERSION') && MU_VERSION >= 10): ?>
                                    <input type="radio" name="class"
                                           value="sl" <?php if(isset($_SESSION['filter']['class']) && $_SESSION['filter']['class'] == 'sl'){
                                        echo 'checked="checked"';
                                    } ?>/> Slayer<br/>
                                <?php endif; ?>
                                <div style="margin:10px;display:block;width: 90%;text-align:center;">
                                    <button class="custom_button" type="submit" value="filter_items"
                                            name="filter_items"><?php echo __('Filter Items'); ?></button>
                                    <button class="custom_button" type="submit" value="reset_filter"
                                            name="reset_filter"><?php echo __('Reset Filter'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	