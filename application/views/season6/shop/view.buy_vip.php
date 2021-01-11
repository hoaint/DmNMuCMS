<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Buy vip'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Buy vip status.'); ?></h2>

            <div class="entry">
                <?php if(!empty($vip_packages)): ?>
                    <ul class="plans">
                        <?php
                            $i = 0;
                            foreach($vip_packages AS $packages):
                                $i++;
                                $style = ($i % 2) ? 'price-green' : 'price-red';
                                ?>
                                <li class="plan highlight">
                            <span class="price <?php echo $style; ?>">
                               <?php echo $packages['price']; ?>
                            </span>

                                    <div class="details">
                                        <h1 class="plan-title"><?php echo $packages['package_title']; ?></h1>
                                        <p class="plan-description"><?php echo $this->website->translate_credits($packages['payment_type'], $packages['server']); ?></p>
                                    </div>
                                    <a href="<?php echo $this->config->base_url . 'shop/buy-vip/' . $packages['id']; ?>"
                                       class="select">Select plan</a>
                                </li>
                            <?php
                            endforeach;
                        ?>
                    </ul>
                <?php else: ?>
                    <div
                            class="i_note"><?php echo __('All vip packages are disabled.'); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
