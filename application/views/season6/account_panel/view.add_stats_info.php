<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Add Stats'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Add level up points. Str. Agi. Vit. etc'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($char_list) && $char_list != false){
                        ?>
                        <table class="ranking-table">
                            <thead>
                            <tr class="main-tr">
                                <th><?php echo __('Character'); ?></th>
                                <th><?php echo __('Level Up Points'); ?></th>
                                <th><?php echo __('Manage'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($char_list as $char){
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo $this->config->base_url; ?>character/<?php echo bin2hex($char['name']); ?>/<?php echo $this->session->userdata(['user' => 'server']); ?>"><?php echo $char['name']; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $char['points']; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo $this->config->base_url; ?>add-stats/<?php echo bin2hex($char['name']); ?>"><?php echo __('Add Stats'); ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                            </tbody>
                        </table>
                        <?php
                    } else{
                        ?>
                        <div
                                class="e_note"><?php echo __('Character not found.'); ?></div>
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
	