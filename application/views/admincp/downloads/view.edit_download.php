<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-downloads">Manage Downloads</a> <span
                        class="divider"></li>
        </ul>
    </div>

    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i>Edit Download Links</h2>
            </div>
            <div class="box-content">
                <?php
                    if(isset($error)){
                        echo '<div class="alert alert-error">' . $error . '</div>';
                    }
                    if(isset($success)){
                        echo '<div class="alert alert-success">' . $success . '</div>';
                    }
                    if(isset($not_found)){
                        echo '<div class="alert alert-error">' . $not_found . '</div>';
                    } else{
                        ?>
                        <form class="form-horizontal" method="post" action="">
                            <fieldset>
                                <legend></legend>
                                <div class="control-group">
                                    <label class="control-label" for="link_name">File Title</label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="link_name" id="link_name"
                                               value="<?php echo $file_info['link_name']; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="link_desc">File Description</label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" id="link_desc" name="link_desc"
                                               value="<?php echo $file_info['link_desc']; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="link_size">File Size</label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" id="link_size" name="link_size"
                                               value="<?php echo $file_info['link_size']; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="link_type">File Type</label>

                                    <div class="controls">
                                        <select id="link_type" name="link_type">
                                            <option value="Installer" <?php if($file_info['link_type'] == 'Installer'){
                                                echo 'selected="selected"';
                                            } ?>>Installer
                                            </option>
                                            <option value="Archive" <?php if($file_info['link_type'] == 'Archive'){
                                                echo 'selected="selected"';
                                            } ?>>Archive
                                            </option>
                                            <option value="Other" <?php if($file_info['link_type'] == 'Other'){
                                                echo 'selected="selected"';
                                            } ?>>Other
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="link_url">File Url</label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" id="link_url" name="link_url"
                                               value="<?php echo $file_info['link_url']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </fieldset>
                        </form>
                    <?php } ?>
            </div>
        </div>
    </div>
</div>