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
                <h2>Search translation</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" id="translations_form">
                    <table class="table table-striped table-bordered bootstrap-datatable datatable">
                        <tbody>
                        <tr>
                            <td>Enter search string</td>
                            <td><input type="text" id="translation" name="trans" value="" class="input-xxlarge"/></td>
                        </tr>
                        </tbody>
                    </table>
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
    <div class="row-fluid">
        <div class="box span9">
            <div class="box-header well" data-original-title>
                <h2>Translations</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" id="translations_form">
                    <table class="table table-striped table-bordered bootstrap-datatable datatable">
                        <tbody>
                        <?php
                            $i = 0;
                            foreach($translations as $key => $tr):
                                $i++;
                                ?>
                                <tr>
                                    <td><input type="text" id="key" name="key" value="<?php echo $key; ?>"
                                               class="input-xlarge" readonly="readonly"/></td>
                                    <td><input type="text" id="trans-<?php echo $key; ?>" name="trans"
                                               value="<?php echo htmlspecialchars($tr); ?>" class="input-xxlarge"
                                               tabindex="<?php echo $i; ?>"/></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
                <?php
                    if(isset($pagination)):
                        ?>
                        <div style="padding:10px;text-align:center;">
                            <table style="width: 100%;">
                                <tr>
                                    <td><?php echo $pagination; ?></td>
                                </tr>
                            </table>
                        </div>
                    <?php
                    endif;
                ?>
            </div>
        </div>
    </div>
</div>