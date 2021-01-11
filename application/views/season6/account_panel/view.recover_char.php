<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Recover MasterLevel'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Recover your character master level and class'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($error)){
                        echo '<div class="e_note">' . $error . '</div>';
                    }
                    if(isset($success)){
                        echo '<div class="s_note">' . $success . '</div>';
                    }
                    if(isset($char_list) && $char_list != false){
                        ?>
                        <div class="form">
                            <form method="post" action="">
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
                                        <td></td>
                                        <td>
                                            <button type="submit"
                                                    class="button-style"><?php echo __('Recover'); ?></button>
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
                ?>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	