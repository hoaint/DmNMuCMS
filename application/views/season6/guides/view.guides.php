<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
    <div id="content">
        <div id="box1">
            <div class="title1">
                <h1><?php echo __('Guides'); ?></h1>
            </div>
            <div id="content_center">
                <?php
                    if(empty($guides)):
                        echo '<div style="padding: 0 30px 0px 50px;"><div class="i_note">' . __('No Guides Articles') . '</div></div>';
                    else:
                        ?>
                        <div class="box-style1" style="margin-bottom:25px;">
                            <div class="entry">
                                <ul class="style2">
                                    <?php
                                        $i = 0;
                                        foreach($guides as $key => $article):
                                            $style = ($i == 0) ? 'class="first"' : '';
                                            ?>
                                            <li <?php echo $style; ?>><a
                                                        href="<?php echo $this->config->base_url; ?>guides/read/<?php echo $this->website->seo_string($article['title']); ?>/<?php echo $article['id']; ?>"><?php echo $article['title']; ?>
                                                    <a/></li>
                                            <?php
                                            $i++;
                                        endforeach;
                                    ?>
                                </ul>
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