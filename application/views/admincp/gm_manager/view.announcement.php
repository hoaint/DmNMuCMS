<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/gm-announcement">GM Announcement</a></li>
        </ul>
    </div>
    <?php
        if(isset($error)){
            echo '<div class="alert alert-error span9">' . $error . '</div>';
        }
        if(isset($success)){
            echo '<div class="alert alert-success span9">' . $success . '</div>';
        }
    ?>
    <div class="row-fluid">
        <div class="box span9">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> GM Announcement</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="announcement">Announcement Text</label>

                            <div class="controls">
                                <textarea class="cleditor" id="announcement" name="announcement"
                                          rows="4"><?php echo $announcement['announcement']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" name="add_anouncement">Submit</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="box span3">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i> GM Settings</h2>
            </div>
            <div class="box-content">
                <?php
                    $this->load->view('admincp' . DS . 'view.panel_gm_settings');
                ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var editorName = $('.cleditor').attr('name');
            CKEDITOR.replace(editorName);
        });
    </script>
</div>