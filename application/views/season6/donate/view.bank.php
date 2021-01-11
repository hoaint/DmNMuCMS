<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Donate'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Bank Payment'); ?></h2>

            <div class="entry">
                <?php
                    if($this->website->get_country_code($this->website->ip()) == 've'){
                        if(isset($error)){
                            echo '<div class="e_note">' . $error . '</div>';
                        }
                        if(isset($success)){
                            echo '<div class="s_note">' . $success . '</div>';
                        }
                        ?>
                        <div class="form">
                            <form method="post" action="">
                                <table>
                                    <tr>
                                        <td style="width: 150px;">Bank:</td>
                                        <td>
                                            <select name="bank" id="bank">
                                                <option
                                                        value=""><?php echo __('--SELECT--'); ?></option>
                                                <option value="1">Banesco</option>
                                                <option value="2">Banco De Venezuela</option>
                                                <option value="3">Bancaribe</option>
                                                <option value="4">Banco Mercantil</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Refference Number:</td>
                                        <td>
                                            <input type="text" name="ref_number" id="ref_number" value=""/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        </td>
                                        <td>
                                            <button type="submit" name="add_ref_number"
                                                    class="button-style"><?php echo __('Submit'); ?></button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <?php
                    } else{
                        echo '<div class="i_note">' . __('Allowed only from Venezuela') . '</div>';
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
	