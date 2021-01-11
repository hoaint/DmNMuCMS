<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/bulk-mail">Bulk Mail</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <ul class="nav nav-pills">
                <li role="presentation" class="active"><a
                            href="<?php echo $this->config->base_url; ?>admincp/create-bulk-email">Create Bulk Email</a>
                </li>
                <li role="presentation"><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/email">Email
                        Settings</a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php
        if(isset($error)){
            echo '<div class="alert alert-error span12">' . $error . '</div>';
        }
        if(isset($success)){
            echo '<div class="alert alert-success span12">' . $success . '</div>';
        }
    ?>
</div>