<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Logs'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Account Credits History'); ?></h2>

            <div class="entry">
                <table class="ranking-table">
                    <thead>
                    <tr class="main-tr">
                        <th>#</th>
                        <th><?php echo __('Info'); ?></th>
                        <th><?php echo __('Amount'); ?></th>
                        <th><?php echo __('Date'); ?></th>
                        <th><?php echo __('Ip Address'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($logs as $log):
                            if($log['amount'] >= 0){
                                $amount = '<span style="color: green;">' . $log['amount'] . '</span>';
                            } else{
                                $amount = '<span style="color: red;">' . $log['amount'] . '</span>';
                            }
                            ?>
                            <tr>
                                <td><?php echo $log['pos']; ?></td>
                                <td><?php echo $log['text']; ?></td>
                                <td><?php echo $amount; ?></td>
                                <td><?php echo date('d/m/Y, H:i', $log['date']); ?></td>
                                <td><?php echo $log['ip']; ?></td>
                            </tr>
                        <?php
                        endforeach;
                    ?>
                    </tbody>
                </table>
                <?php
                    if(isset($pagination)):
                        ?>
                        <table style="width: 100%;">
                            <tr>
                                <td><?php echo $pagination; ?></td>
                            </tr>
                        </table>
                    <?php
                    endif;
                ?>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	