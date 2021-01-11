<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
    <div id="content">
        <div id="box1">
            <div class="title1">
                <h1><?php if(empty($error)){
                        echo $news['title'];
                    } else{
                        echo 'Undefined';
                    } ?></h1>
            </div>
            <div id="content_center">
                <?php
                    if(!empty($error)):
                        echo '<div style="padding: 0 30px 0px 50px;"><div class="e_note">' . $error . '</div></div>';
                    endif;
                    if(!empty($news)):
                        ?>
                        <div class="box-style3" style="margin-bottom:25px;">
                            <div class="entry">
                                <div
                                        style="width:600px;"><?php echo str_replace('&gt;', '>', str_replace('&lt;', '<', str_replace('Â', '&nbsp;', $news['news_content_full']))); ?></div>
                                <div class="meta-bg">
                                    <div class="meta">
                                        <p class="tags"><?php echo __('Posted'); ?><?php echo date('d / m / Y', $news['time']); ?><?php echo __('By'); ?><?php echo $news['author']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    endif;
                ?>
            </div>
        </div>
    </div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>