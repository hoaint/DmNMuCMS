<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Clear Inventory'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Clear character inventory, equipment etc..'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($char_list) && $char_list != false){
                        ?>
                        <div class="form">
                            <form method="post" action="<?php echo $this->config->base_url; ?>clear-inventory"
                                  id="clear_inventory_form">
                                <table>
                                    <tr>
                                        <td style="width: 150px;"><?php echo __('character'); ?></td>
                                        <td>
                                            <select class="custom-select" name="character" id="character">
                                                <?php foreach($char_list as $char): ?>
                                                    <option
                                                            value="<?php echo $char['name']; ?>"><?php echo $char['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Select Option(s) To Clear'); ?></td>
                                        <td><input type="checkbox" name="inventory"
                                                   value="1"/> <?php echo __('Inventory'); ?>
                                            <br/>
                                            <?php if($this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_size') > 1920): ?>
                                                <input type="checkbox" id="exp_inv_1" name="exp_inv_1"
                                                       value="1"/> <?php echo __('Expanded Inventory'); ?> 1
                                                <br/>
                                                <input type="checkbox" id="exp_inv_2" name="exp_inv_2"
                                                       value="1"/> <?php echo __('Expanded Inventory'); ?> 2
                                                <br/>
                                            <?php endif; ?>
                                            <input type="checkbox" id="equipment" name="equipment"
                                                   value="1"/> <?php echo __('Equipment'); ?>
                                            <br/>
                                            <input type="checkbox" id="store" name="store"
                                                   value="1"/> <?php echo __('Personal Store'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button type="submit" id="clear_inv_button"
                                                    class="button-style"><?php echo __('Clear Inventory'); ?></button>
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
	