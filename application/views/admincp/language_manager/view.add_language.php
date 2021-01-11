<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/add-language">Add Languages</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span9">
            <div class="box-header well" data-original-title>
                <h2>Add Language</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php
                    if(isset($error)){
                        echo '<div class="alert alert-error">' . $error . '</div>';
                    }
                    if(isset($success)){
                        echo '<div class="alert alert-success">' . $success . '</div>';
                    }
                ?>
                <form class="form-horizontal" method="POST" action="">
                    <div class="control-group">
                        <label class="control-label" for="name">Language Name</label>

                        <div class="controls">
                            <input type="text" class="input-xlarge" id="name" name="name" value="">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="title">Language Title</label>

                        <div class="controls">
                            <input type="text" class="input-xlarge" id="title" name="title" value="">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="flag">Country Flag</label>

                        <div class="controls">
                            <select id="flag" name="flag">
                                <?php foreach($country_flags as $code => $country): ?>
                                    <option value="<?php echo $code; ?>.gif"><?php echo $country; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="active">Active </label>

                        <div class="controls">
                            <select id="active" name="active">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" name="add_language">Add Language</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="box span3">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Language Manager</h2>
            </div>
            <div class="box-content">
                <?php
                    $this->load->view('admincp' . DS . 'view.panel_language_manager');
                ?>
            </div>
        </div>
    </div>
</div>