<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
    <div id="content">
        <div id="box1">
            <div class="title1">
                <h1><?php echo __('News'); ?></h1>
            </div>
            <div id="content_center">
                <?php
                    if(empty($news)):
                        echo '<div style="padding: 0 30px 0px 50px;"><div class="i_note">' . __('No News Articles') . '</div></div>';
                    else:
                        if($this->config->config_entry('news|storage') != 'facebook'){
                            foreach($news as $key => $article):
                                ?>
                                <div class="box-style1" style="margin-bottom:25px;">
                                    <h2 class="title"><a
                                                href="<?php echo $article['url']; ?>"><?php echo $article['title']; ?></a>
                                    </h2>

                                    <div class="entry">
                                        <div
                                                style="width:600px;"><?php echo str_replace('&quot;', '"', str_replace('&gt;', '>', str_replace('&lt;', '<', str_replace('Â', '&nbsp;', $article['content'])))); ?></div>
                                        <div class="meta-bg">
                                            <div class="meta">
                                                <p class="tags"><?php echo __('Posted'); ?><?php echo date('d / m / Y', $article['time']); ?></p>
                                                <?php if($this->config->config_entry('news|storage') != 'dmn'): ?>
                                                    <p class="links"><span class="comments"><a
                                                                    href="<?php echo $article['url']; ?>"
                                                                    target="_blank"><?php echo __('Discuss On Forum'); ?></a></span>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            endforeach;
                        } else{
                            echo '<div style="margin-left:90px;">' . str_replace('(document, script, facebook-jssdk)', '(document, \'script\', \'facebook-jssdk\')', $news['contents']) . '</div>';
                        }
                    endif;
                ?>
                <?php
                    if(isset($pagination)){
                        ?>
                        <div style="padding:10px;text-align:center;">
                            <table style="width: 100%;">
                                <tr>
                                    <td><?php echo $pagination; ?></td>
                                </tr>
                            </table>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>