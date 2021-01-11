</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="select_server">
    <div class="modal-header">
        <h2><?php echo __('Select Server'); ?></h2>
        <a class="close" href="javascript:;"></a>
    </div>
    <div style="margin: 20px;" class="form">
        <?php
            if(!$servers = $this->website->server_select_box('id="switch_server"')){
                echo '<b style="color: red;">' . __('Currently this is only one server.') . '</b>';
            } else{
                echo $servers;
            }
        ?>

    </div>
</div>
<a data-modal-div="select_server" href="#" id="server_click" style="display: hidden;"></a>
<div id="footer">
    <div id="copyright">
        <p><?php echo __('Copyright'); ?> <?php echo date('Y'); ?>
            &copy; <?php echo $this->config->config_entry('main|servername'); ?>
            . <?php echo __('All Rights Reserved.'); ?></p>
        <p style=""><?php echo __('Powered By'); ?> <a href="http://dmncms.net" target="_blank">DmN MuCMS</a></p>
    </div>
</div>
<div id="loading"><img
            src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/images/ajax-loader.gif"
            alt=""/> <?php echo __('Loading...'); ?></div>
<script
        src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/js/jed.js"></script>
<script
        src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/js/jquery.leanModal.min.js"></script>
<script
        src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/js/jquery.tooltip.js"></script>


<script
        src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/js/ejs.js"></script>
<script
        src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/js/helpers.js"></script>
<script
        src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/js/app.js"></script>
<script type="text/javascript">
    var DmNConfig = {
        base_url: '<?php echo $this->config->base_url;?>',
        tmp_dir: '<?php echo $this->config->config_entry('main|template');?>',
        currenttime: '<?php echo date('M d, Y h:i:s', time());?>',
        ajax_page_load: <?php echo $this->config->config_entry('main|use_ajax_page_load');?>,
		timers: <?php echo json_encode($this->website->load_event_timers());?>
    };

    $(document).ready(
        App.initialize,
        <?php if($this->session->userdata(['user' => 'logged_in'])): ?>
        App.checkSupportTickets(),
        <?php endif;?>
        App.locale = <?php echo $this->translations;?>
        <?php if(strtotime($this->config->config_entry("main|grand_open_timer")) >= time()): ?>
        , App.count_down('<?php echo $this->config->config_entry("main|grand_open_timer");?>')
        <?php endif; ?>
        , App.initLocalization()
    );
</script>
<script
        src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/js/validation/jquery.validationEngine-en.js"
        type="text/javascript"></script>
<script
        src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/js/validation/jquery.validationEngine.js"
        type="text/javascript"></script>
<?php
    if($this->request->get_method() == 'fortumo'){
        ?>
        <script src="https://assets.fortumo.com/fmp/fortumopay.js" type="text/javascript"></script>
        <?php
    }
?>
</body>
</html>