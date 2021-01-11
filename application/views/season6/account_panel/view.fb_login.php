<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Facebook Login'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Facebook Account Login'); ?></h2>

            <div class="entry">
                <?php if($this->website->is_multiple_accounts() == true): ?>
                    <div class="form">
                        <form method="post" action="" id="fb_login_form">
                            <table>
                                <tr>
                                    <td style="width: 150px;"><?php echo __('Server'); ?>
                                        :
                                    </td>
                                    <td>
                                        <select name="server" id="server">
                                            <option value="">Select Server</option>
                                            <?php
                                                foreach($this->website->server_list() as $key => $server):
                                                    ?>
                                                    <option
                                                            value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                                <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </td>
                                <tr>
                                    <td></td>
                                    <td>
                                        <button type="submit"
                                                class="button-style"><?php echo __('Submit'); ?></button>
                                    </td>
                                </tr>
                                </tr>
                            </table>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	