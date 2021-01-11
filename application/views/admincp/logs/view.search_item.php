<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/find-item">Find Item By Serial</a></li>
        </ul>
    </div>
    <?php
        if(isset($error)){
            echo '<div class="alert alert-error span12">' . $error . '</div>';
        }
        if(isset($success)){
            echo '<div class="alert alert-success span12">' . $success . '</div>';
        }
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Search Item</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <div class="control-group">
                        <label class="control-label" for="server">Server </label>

                        <div class="controls">
                            <select id="server" name="server">
                                <?php
                                    foreach($this->website->server_list() as $key => $value){
                                        echo '<option value="' . $key . '">' . $value['title'] . "</option>\n";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="appendedInputButton">Item Serial</label>

                        <div class="controls">
                            <div class="input-append">
                                <input id="appendedInputButton" size="16" id="serial" name="serial" value=""
                                       type="text">
                                <button class="btn" type="submit" name="search_item">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>