<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('About Server'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Information About'); ?><?php echo $this->config->config_entry('main|servername'); ?></h2>

            <div class="entry">
                <div style="float:right;">
                    <?php
                        foreach($this->website->server_list() as $key => $server):
                            if($server['visible'] == 1):
                                ?>
                                <a class="custom_button"
                                   href="<?php echo $this->config->base_url; ?>about/stats/<?php echo $key; ?>"><?php echo $server['title']; ?><?php echo __('Statistics'); ?></a>
                            <?php
                            endif;
                        endforeach;
                    ?>
                </div>
                <div style="padding-top:40px;">
                    <?php echo __('MU Online was created in December 2001 by the Korean gaming company Webzen.. Like in most MMORPGs, players have to create a character among seven different classes and to set their foot on the MU Continent. In order to gain experience and thus to level up, a players needs to fight monsters (mobs). MU is populated by a large variety of monsters, from simple ones like goblins and golems, to frightening ones such as the Gorgon, Kundun or Selupan. Each monster-type is unique, has different spawn points, and drops different items.'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	