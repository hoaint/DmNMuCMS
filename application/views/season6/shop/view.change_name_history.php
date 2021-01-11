<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Change Name'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Change Name History'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($error)){
                        echo '<div class="i_note">' . $error . '</div>';
                    } else{
                        ?>
                        <?php
                        if(isset($change_history) && $change_history != false){
                            ?>
                            <table class="ranking-table">
                                <thead>
                                <tr class="main-tr">
                                    <th style="text-align:center;">#</th>
                                    <th style="text-align:center;"><?php echo __('Old Name'); ?></th>
                                    <th style="text-align:center;"><?php echo __('New Name'); ?></th>
                                    <th style="text-align:center;"><?php echo __('Date'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i = 1;
                                    foreach($change_history as $history):
                                        ?>
                                        <tr>
                                            <td style="text-align:center;"><?php echo($i++); ?></td>
                                            <td><?php echo $history['old_name']; ?></td>
                                            <td><?php echo $history['new_name']; ?></td>
                                            <td class="end"><?php echo date('d/m/Y, H:i', strtotime($history['change_date'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php
                        } else{
                            echo '<div class="i_note">' . __('You have not changed any character name') . '</div>';
                        }
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
	