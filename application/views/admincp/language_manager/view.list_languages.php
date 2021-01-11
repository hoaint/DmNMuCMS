<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/list-languages">List Languages</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span9">
            <div class="box-header well" data-original-title>
                <h2>Language List</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Language</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $languages = $this->website->lang_list();
                        foreach($languages as $key => $lang):
                            echo '<tr id="language_' . $key . '">
                                    <td>' . $lang . '</td>                    
                                    <td>';
                            echo '&nbsp;<a class="btn btn-info" href="' . $this->config->base_url . 'admincp/edit-language/' . $key . '"><i class="icon-edit icon-white"></i> Edit</a>';
                            echo '&nbsp;<a class="btn btn-primary" href="' . $this->config->base_url . 'admincp/export-language/' . $key . '"><i class="icon-edit icon-white"></i> Export</a>';
                            echo '&nbsp;<a class="btn btn-warning" href="#" onclick="App.deleteLanguage(\'' . $key . '\');"><i class="icon-edit icon-white"></i> Delete</a></td></tr>';
                        endforeach;
                    ?>
                    </tbody>
                </table>
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