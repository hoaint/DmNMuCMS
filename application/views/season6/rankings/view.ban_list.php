<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Ban List'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('List Banned Players & Accounts'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($error)){
                        echo '<div class="e_note">' . $error . '</div>';
                    } else{
                        ?>
                        <ul class="tabrow">
                            <?php
                                $args = $this->request->get_args();
                                $i = 0;
                                foreach($this->website->server_list() as $key => $server):
                                    if($server['visible'] == 1){
                                        $i++;
                                        if($def_server == $key)
                                            $selected = 'class="selected"'; else
                                            $selected = '';
                                        ?>
                                        <li <?php echo $selected; ?>><a
                                                    href="<?php echo $this->config->base_url; ?>rankings/ban-list/chars/<?php echo $key; ?>"><?php echo $server['title']; ?></a>
                                        </li>
                                        <?php
                                    }
                                endforeach;
                            ?>
                        </ul>
                        <ul class="tabrow">
                            <li <?php if($def_type == 'chars'){
                                echo 'class="selected"';
                            } ?>>
                                <a href="<?php echo $this->config->base_url; ?>rankings/ban-list/chars/<?php echo $def_server; ?>"><?php echo __('Banned Chars'); ?></a>
                            </li>
                            <li <?php if($def_type == 'accounts'){
                                echo 'class="selected"';
                            } ?>>
                                <a href="<?php echo $this->config->base_url; ?>rankings/ban-list/accounts/<?php echo $def_server; ?>"><?php echo __('Banned Accounts'); ?></a>
                            </li>
                        </ul>
                        <?php
                        if(isset($ban_list) && $ban_list != false){
                            ?>
                            <table class="ranking-table">
                                <thead>
                                <tr class="main-tr">
                                    <th style="text-align:center;">#</th>
                                    <th style="text-align:center;"><?php echo __('Name'); ?></th>
                                    <th style="text-align:center;"
                                        class="end"><?php echo __('Ban Time'); ?></th>
                                    <th style="text-align:center;"
                                        class="end"><?php echo __('Ban Reason'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i = 1;
                                    foreach($ban_list as $players):
                                        ?>
                                        <tr>
                                            <td style="text-align:center;"><?php echo($i++); ?></td>
                                            <td><?php echo $players['name']; ?></td>
                                            <td><?php echo $players['time']; ?></td>
                                            <td class="end"><?php echo $players['reason']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php
                        } else{
                            echo '<div class="i_note">' . __('No Bans Found') . '</div>';
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
	