<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/support-departments">Support Departments</a></li>
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
        <div class="span12">
            <p class="left">
                <a href="<?php echo $this->config->base_url; ?>admincp/add-support-department"
                   class="btn btn-large btn-primary"> Add Department</a>
            </p>

            <div class="clearfix"></div>
        </div>

    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Departments</h2>
            </div>
            <div class="box-content">
                <?php if(!empty($department_list)): ?>
                    <table class="table table-striped table-bordered bootstrap-datatable datatable">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Server</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($department_list AS $departments): ?>
                            <tr>
                                <td><?php echo $departments['department_name']; ?></td>
                                <td>
                                    <?php if($departments['is_active'] == 1): ?>
                                        <span class="label label-success">Active</span>
                                    <?php else: ?>
                                        <span class="label label-important">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $this->website->get_title_from_server($departments['server']); ?></td>
                                <td>
                                    <a class="btn btn-success"
                                       href="<?php echo $this->config->base_url . '/admincp/edit-support-department/' . $departments['id']; ?>">
                                        <i class="icon-edit icon-white"></i>
                                        Edit
                                    </a>
                                    <a class="btn btn-danger"
                                       href="<?php echo $this->config->base_url . '/admincp/delete-support-department/' . $departments['id']; ?>">
                                        <i class="icon-trash icon-white"></i>
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">Currently no departments.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>