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
                    if(isset($not_found)){
                        echo '<div class="e_note">' . $not_found . '</div>';
                    } else{
                        ?>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $.extend(DmNConfig, {max_stats: <?php echo $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|max_stats');?>});
                                App.calculateStats();
                            });
                        </script>
                    <?php
                        if(isset($error)){
                            echo '<div class="e_note">' . $error . '</div>';
                        }
                        if(isset($success)){
                            echo '<div class="s_note">' . $success . '</div>';
                        }
                    ?>
                        <div class="form">
                            <form method="POST" action="" id="add_stats" name="add_stats">
                                <table>
                                    <tr>
                                        <td style="width:150px;"><?php echo __('Level Up Points'); ?>
                                            :
                                        </td>
                                        <td><input type="text" id="lvlup" name="lvlup"
                                                   value="<?php echo $this->Mcharacter->char_info['LevelUpPoint']; ?>"
                                                   class="text" disabled/></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Strength'); ?> [<span
                                                    class="stats_now"
                                                    id="str"><?php echo $this->Mcharacter->char_info['Strength']; ?></span>]:
                                        </td>
                                        <td><input type="text" id="str_stat" name="str_stat" value=""
                                                   class="validate[custom[integer]] text stats_calc"/></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Agility'); ?> [<span
                                                    class="stats_now"
                                                    id="agi"><?php echo $this->Mcharacter->char_info['Dexterity']; ?></span>]:
                                        </td>
                                        <td><input type="text" id="agi_stat" name="agi_stat" value=""
                                                   class="validate[custom[integer]] text stats_calc"/></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Vitality'); ?> [<span
                                                    class="stats_now"
                                                    id="vit"><?php echo $this->Mcharacter->char_info['Vitality']; ?></span>]:
                                        </td>
                                        <td><input type="text" id="vit_stat" name="vit_stat" value=""
                                                   class="validate[custom[integer]] text stats_calc"/></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Energy'); ?> [<span
                                                    class="stats_now"
                                                    id="ene"><?php echo $this->Mcharacter->char_info['Energy']; ?></span>]:
                                        </td>
                                        <td><input type="text" id="ene_stat" name="ene_stat" value=""
                                                   class="validate[custom[integer]] text stats_calc"/></td>
                                    </tr>
                                    <?php
                                        if(in_array($this->Mcharacter->char_info['Class'], [64, 65, 66, 70])){
                                            ?>
                                            <tr>
                                                <td><?php echo __('Command'); ?> [<span
                                                            class="stats_now"
                                                            id="com"><?php echo $this->Mcharacter->char_info['Leadership']; ?></span>]:
                                                </td>
                                                <td><input type="text" id="com_stat" name="com_stat" value=""
                                                           class="validate[custom[integer]] text stats_calc"/></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button type="submit" id="add_points" name="add_points"
                                                    class="button-style"><?php echo __('Submit'); ?></button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
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
	