<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-gallery">Manage Gallery</a> <span
                        class="divider"></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i>Add Image</h2>

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
                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="add_gallery_image" value="1"/>
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="image">Select Image</label>

                            <div class="controls">
                                <input class="input-file uniform_on" id="image" name="image" type="file">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="section">Select Section</label>

                            <div class="controls">
                                <select id="section" name="section" data-rel="chosen">
                                    <option value="1">Walpapers</option>
                                    <option value="2">Game Screens</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-picture"></i> Gallery</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <ul class="thumbnails gallery">
                    <?php
                        if(isset($gallery['error'])){
                            echo '<div class="alert alert-info">' . $gallery['error'] . '</div>';
                        } else{
                            foreach($gallery as $key => $value){
                                echo '<li id="image-' . $value['id'] . '" class="thumbnail">
								<a style="background:url(' . $this->config->base_url . 'assets/uploads/thumb/' . strstr($value['name'], '.', true) . '_thumb' . strstr($value['name'], '.', false) . ');" href="' . $this->config->base_url . 'assets/uploads/normal/' . $value['name'] . '"><img class="grayscale" src="' . $this->config->base_url . 'assets/uploads/thumb/' . strstr($value['name'], '.', true) . '_thumb' . strstr($value['name'], '.', false) . '" alt=""></a>
							  </li>';
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>