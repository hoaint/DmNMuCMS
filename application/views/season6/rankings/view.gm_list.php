<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Gm List'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('List GameMasters'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($error)){
                        echo '<div class="e_note">' . $error . '</div>';
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
                                foreach($this->website->server_list() as $key => $server):
                                    if($server['visible'] == 1){
                                        $i++;
                                        if($def_server == $key)
                                            $selected = 'class="selected"'; else
                                            $selected = '';
                                        ?>
                                        <li <?php echo $selected; ?>><a
                                                    href="<?php echo $this->config->base_url; ?>rankings/gm-list/<?php echo $key; ?>"><?php echo $server['title']; ?></a>
                                        </li>
                                        <?php
                                    }
                                endforeach;
                            ?>
                        </ul>
                    <?php
                        if(isset($gm_list) && $gm_list != false){
                    ?>
                        <table class="ranking-table">
                            <thead>
                            <tr class="main-tr">
                                <th style="text-align:center;">#</th>
                                <th style="text-align:center;"><?php echo __('Name'); ?></th>
                                <th style="text-align:center;"
                                    class="end"><?php echo __('Contact'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $i = 1;
                                foreach($gm_list as $players):
                                    ?>
                                    <tr>
                                        <td style="text-align:center;"><?php echo($i++); ?></td>
                                        <td>
                                            <a href="<?php echo $this->config->base_url; ?>character/<?php echo bin2hex($players['name']); ?>/<?php echo $def_server; ?>"><?php echo $players['name']; ?></a>
                                        </td>
                                        <td class="end"><?php echo $players['contact']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php
                    } else{
                        echo '<div class="i_note">' . __('No GMs Found') . '</div>';
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
	