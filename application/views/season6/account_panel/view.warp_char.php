<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Warp Character'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Teleport your character to another location'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($disabled)){
                        echo '<div class="e_note">' . __('This module has been disabled.') . '</div>';
                    } else{
                        if(isset($error)){
                            echo '<div class="e_note">' . $error . '</div>';
                        }
                        if(isset($success)){
                            echo '<div class="s_note">' . $success . '</div>';
                        }
                        if(isset($char_list) && $char_list != false){
                            ?>
                            <div class="form">
                                <form method="post" action="<?php echo $this->config->base_url; ?>account-panel/warp-char">
                                    <table>
                                        <tr>
                                            <td style="width: 150px;"><?php echo __('Character'); ?></td>
                                            <td>
                                                <select class="custom-select" name="character">
                                                    <?php foreach($char_list as $char): ?>
                                                        <option
                                                                value="<?php echo $char['name']; ?>"><?php echo $char['name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Location'); ?></td>
                                            <td><select name="world">
                                                    <option value="0">Lorencia</option>
                                                    <option value="1">Dungeon</option>
                                                    <option value="2">Devias</option>
                                                    <option value="3">Noria</option>
                                                    <option value="4">Losttower</option>
                                                    <option value="6">Arena</option>
                                                    <option value="7">Atlans</option>
                                                    <option value="8">Tarkan</option>
                                                    <option value="10">Icarus</option>
                                                    <option value="30">Valley Of Loren</option>
                                                    <option value="33">Aida</option>
                                                    <option value="34">CryWolf</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <button type="submit"
                                                        class="button-style"><?php echo __('Warp'); ?></button>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                            <?php
                        } else{
                            ?>
                            <div
                                    class="e_note"><?php echo __('Character not found.'); ?></div>
                            <?php
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
	