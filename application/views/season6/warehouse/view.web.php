<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Web Warehouse'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('View & Move your items'); ?></h2>

            <div class="entry">
                <div style="float:right;">
                    <a class="custom_button"
                       href="<?php echo $this->config->base_url; ?>warehouse"><?php echo __('Warehouse'); ?></a>
                    <a class="custom_button"
                       href="<?php echo $this->config->base_url; ?>market"><?php echo __('Market'); ?></a>
                    <a class="custom_button"
                       href="<?php echo $this->config->base_url; ?>market/history"><?php echo __('History'); ?></a>
                </div>
                <div style="padding-top:40px;"></div>
                <?php
                    echo '<div class="alert-box i_note">' . __('Here are stored items moved from game and items earned in market.') . '</div>';
                    echo '<div class="alert-box i_note">' . __('Items which expire will be deleted permanently') . '</div>';
                    if(isset($error)){
                        echo '<div class="alert-box e_note">' . $error . '</div>';
                    } else{
                        ?>
                        <script>
                            $(document).ready(function () {
                                $('span[id^="web_wh_item_"]').each(function () {
                                    App.initializeTooltip($(this), true, 'warehouse/item_info_image');
                                });
                            });
                        </script>
                    <?php if(isset($items) && !empty($items)): ?>
                        <table class="ranking-table">
                            <thead>
                            <tr class="main-tr">
                                <td>#</td>
                                <td><?php echo __('Item'); ?></td>
                                <td><?php echo __('Expires On'); ?></td>
                                <td><?php echo __('Action'); ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($items as $item):
                                    ?>
                                    <tr id="wh_items_<?php echo $item['id']; ?>">
                                        <td><?php echo $item['pos']; ?></td>
                                        <td><span id="web_wh_item_<?php echo $item['pos']; ?>"
                                                  data-info="<?php echo $item['item']; ?>"><a
                                                        href="#"><?php echo $item['name']; ?></a></span></td>
                                        <td><?php echo date('F j, Y, h:i A', $item['expires_on']); ?></td>
                                        <td><a href="javascript:;" id="move_to_game_wh_<?php echo $item['id']; ?>"
                                               data-id="<?php echo $item['id']; ?>"><?php echo __('Move To Warehouse'); ?></a>
                                        </td>
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
	