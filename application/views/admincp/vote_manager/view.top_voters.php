<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/top-voters">Check Top Voters</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Top Voters</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php
                    /*if(count($vote_links) > 0){
                        echo '<table class="table">
                              <thead>
                                  <tr>
                                      <th>Name</th>
                                      <th>Url</th>
                                      <th>Reward</th>
                                      <th>Server</th>
                                      <th>Action</th>
                                  </tr>
                              </thead>
                              <tbody>';
                        foreach($vote_links as $key=>$value){
                            echo '<tr>
                                    <td>'.htmlspecialchars($value['name']).'</td>
                                    <td>'.htmlspecialchars($value['votelink']).'</td>
                                    <td class="center">'.$value['reward'].'</td>
                                    <td class="center">'.$this->website->get_title_from_server($value['server']).'</td>
                                    <td class="center">
                                        <a class="btn btn-info" href="'.$this->config->base_url.'admincp/edit-vote/'.$value['id'].'">
                                            <i class="icon-edit icon-white"></i>
                                            Edit
                                        </a>
                                        <a class="btn btn-danger" href="'.$this->config->base_url.'admincp/delete-vote/'.$value['id'].'">
                                            <i class="icon-trash icon-white"></i>
                                            Delete
                                        </a>
                                    </td>
                                  </tr>';
                        }
                        echo '</tbody></table>';
                    }
                    else{
                        echo '<div class="alert alert-info">No voting links found</div>';
                    }*/
                ?>
            </div>
        </div>
    </div>
</div>