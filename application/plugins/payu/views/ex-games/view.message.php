<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo $about['name']; ?></h1>
        </div>
        <div id="content_center">
            <div class="box-style1" style="margin-bottom:55px;">
                <h2 class="title"><?php echo $about['user_description']; ?></h2>
                <div class="entry">
                    <?php
						if(isset($error)){
							echo '<div class="e_note">' . $error . ' </div>';
						}
						if(isset($success)){
							echo '<div class="s_note">' . $success . ' </div>';
						}
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	