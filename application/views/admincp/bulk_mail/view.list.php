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
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2>Email List</h2>

            </div>
            <div class="box-content">
                <?php
                    if(isset($bulk_emails) && !empty($bulk_emails)):
                        ?>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Sent On</th>
                                <th>Sent To / Failed</th>
                                <th>Time Elapsed</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($bulk_emails as $key => $value){
                                    if($value['sending_started'] == null){
                                        $status = 'Not started';
                                    } else{
                                        if($value['sending_finished'] == null || $value['is_finished'] == 0){
                                            $status = 'Still sending';
                                        } else{
                                            $status = date('M/d/Y', $value['sending_finished']);
                                        }
                                    }
                                    echo '<tr>
							<td>' . htmlspecialchars($value['subject']) . '</td>
							<td class="center">' . $status . '</td>
							<td class="center">' . $value['sent_to'] . ' / ' . $value['failed'] . '</span></td>
							<td class="center">' . $this->website->date_diff($value['sending_started'], $value['sending_finished']) . '</td>
							<td class="center">
								<a class="btn btn-danger" href="' . $this->config->base_url . 'admincp/delete-bulk-email/' . $value['seo_subject'] . '"><i class="icon-edit icon-white"></i>
                                    Delete
                                 </a>
								 <a class="btn btn-primary" href="' . $this->config->base_url . 'admincp/resend-bulk-email/' . $value['seo_subject'] . '"><i class="icon-edit icon-white"></i>
                                    Resend
                                 </a>
								 <a class="btn btn-primary" href="' . $this->config->base_url . 'admincp/edit-bulk-email/' . $value['seo_subject'] . '"><i class="icon-edit icon-white"></i>
                                    Edit
                                 </a>
							</td>
						  </tr>';
                                }
                            ?>
                            </tbody>
                        </table>
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
                    <?php
                    else:
                        echo '<div class="alert alert-info">No Bulk Email Found</div>';
                    endif;
                ?>
            </div>
        </div>
    </div>
</div>