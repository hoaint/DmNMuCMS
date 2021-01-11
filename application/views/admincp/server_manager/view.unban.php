<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/account-manager">Account Manager</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <?php
            if(isset($error)){
                echo '<div class="alert alert-error">' . $error . '</div>';
            }
            if(isset($success)){
                echo '<div class="alert alert-success">' . $success . '</div>';
            }
        ?>

    </div>
</div>