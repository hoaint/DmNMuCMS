<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Online Players'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('List online players'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($error)){
                        echo '<div class="i_note">' . $error . '</div>';
                    } else{
                        ?>
                        <script>
                            $(document).ready(function () {
                                $('#content_1').show();
                            });
                        </script>
                        <ul class="tabrow">
                            <?php
                                $args = $this->request->get_args();
                                $i = 0;
                                foreach($this->website->server_list() as $key => $servers):
                                    if($servers['visible'] == 1){
                                        $i++;
                                        if(!empty($args) && ($args[0] == $key))
                                            $selected = 'class="selected"'; else
                                            $selected = '';
                                        ?>
                                        <li <?php echo $selected; ?>><a
                                                    href="<?php echo $this->config->base_url; ?>rankings/online-players/<?php echo $key; ?>"><?php echo $servers['title']; ?></a>
                                        </li>
                                        <?php
                                    }
                                endforeach;
                            ?>
                        </ul>
                    <?php
                        if(isset($online) && $online != false){
                    ?>
                        <table class="ranking-table">
                            <thead>
                            <tr class="main-tr">
                                <th style="text-align:center;">#</th>
                                <th style="text-align:center;"><?php echo __('Name'); ?></th>
                                <th style="text-align:center;"><?php echo __('Class'); ?></th>
                                <th style="text-align:center;"><?php echo __('Level'); ?></th>
                                <?php if($config['online_list']['display_resets'] == 1): ?>
                                    <th style="text-align:center;"><?php echo __('Resets'); ?></th>
                                <?php endif; ?>
                                <?php if($config['online_list']['display_gresets'] == 1): ?>
                                    <th style="text-align:center;"><?php echo __('Grand Reset'); ?></th>
                                <?php endif; ?>
                                <th style="text-align:center;"><?php echo __('Connect Time'); ?></th>
                                <th style="text-align:center;"
                                    class="end"><?php echo __('Connected Since'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $i = 1;
                                foreach($online as $players):
                                    ?>
                                    <tr>
                                        <td style="text-align:center;"><?php echo($i++); ?></td>
                                        <td>
                                            <?php if($config['online_list']['display_country'] == 1): ?><span class="f16"><span class="flag <?php echo $players['country']; ?>"></span></span><?php endif; ?> <a
                                                    href="<?php echo $this->config->base_url; ?>character/<?php echo bin2hex($players['name']); ?>/<?php echo $server; ?>"><?php echo $players['name']; ?></a>
                                        </td>
                                        <td><?php echo $players['class']; ?></td>
                                        <td><?php echo $players['level']; ?></td>
                                        <?php if($config['online_list']['display_resets'] == 1): ?>
                                            <td><?php echo $players['resets']; ?></td>
                                        <?php endif; ?>
                                        <?php if($config['online_list']['display_gresets'] == 1): ?>
                                            <td><?php echo $players['gresets']; ?></td>
                                        <?php endif; ?>
                                        <td style="text-align:center;"><?php echo $players['h']; ?><?php echo __('Hours'); ?><?php echo $players['m']; ?><?php echo __('Minutes'); ?></td>
                                        <td class="end"><?php echo $players['connecttime']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php
                    } else{
                        echo '<div class="i_note">' . __('No Players Found') . '</div>';
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
	