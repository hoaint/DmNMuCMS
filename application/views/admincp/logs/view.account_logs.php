<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-account">Account Logs</a></li>
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
                <h2><i class="icon-edit"></i> Search logs</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" id="account_logs_form">
                    <div class="control-group">
                        <label class="control-label" for="date01">Date from</label>

                        <div class="controls">
                            <input type="text" class="input-xlarge datepicker" id="date01" name="date01"
                                   value="<?php if(isset($account_filter_date_1)) { echo $account_filter_date_1; } ?>">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="date02">Date to</label>

                        <div class="controls">
                            <input type="text" class="input-xlarge datepicker" id="date02" name="date02"
                                   value="<?php if(isset($account_filter_date_2)) { echo $account_filter_date_2; } ?>">
                        </div>
                    </div>
					 <div class="control-group">
                        <label class="control-label" for="log_string">Log String</label>

                        <div class="controls">
                            <input type="text" class="input-xlarge" id="log_string" name="log_string" value="<?php if(isset($account_filter_text_string)) { echo $account_filter_text_string; } ?>">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-block btn-default" name="apply_logs_filter" id="apply_logs_filter">Apply Filter</button>
						<button type="submit" class="btn btn-warning btn-block" value="1" name="reset_logs_filter" id="reset_logs_filter">Reset Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
	<div class="row-fluid">
			<div class="col-lg-12">
      <div class="box">
            <div class="box-header well">
              <h2>List of logs</h2>
            </div>
            <!-- /.box-header -->
            <div class="box-content">
              <table id="accountLogs" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Account</th>
                  <th class="no-sort">Log</th>
				  <th class="no-sort">Credits</th>
                  <th>Date</th>
                  <th class="no-sort">Ip</th>
                  <th>Server</th>
                </tr>
                </thead>
                
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
		</div>	
    </div> 
</div>
<script>
  $(function () {
		$('#apply_logs_filter').on('click', function(e) {
			e.preventDefault();
			$.fn.filterAccountLog();
		});

		$('#reset_logs_filter').on('click', function(e) {
			e.preventDefault();
			$.fn.resetAccountLogFilter();
		});
		
		
			
    var accountTable = $('#accountLogs').DataTable({
			"dom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
			"pagingType": "bootstrap",
			"language": {
				"lengthMenu": "_MENU_ records per page",
				"zeroRecords": "Nothing found - sorry",
				"infoEmpty": "No records available",
				"search": "Search: ",
				"searchPlaceholder": "Account",
				"processing": ""
			},
			"order": [
				[3, 'desc']
			],
			"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false,
			}],
			"stateSave": true,
			"processing": true,
			"serverSide": true,
			"ajax": {
				url: DmNConfig.base_url + 'admincp/load-account-logs',
				type: "post"
			}
		});
    $.fn.extend({
				filterAccountLog: function(){
					$.ajax({
						type: 'POST',
						dataType: 'json',
						data: $('#account_logs_form').serialize(),
						url: DmNConfig.base_url + 'admincp/filter-account-logs',
						success: function (data){
							if (data.success){
								$('#accountLogs').DataTable().ajax.reload();
							}
						}
					});
				},
				resetAccountLogFilter: function(){
					$.ajax({
						type: 'POST',
						dataType: 'json',
						url: DmNConfig.base_url + 'admincp/filter_account_logs_reset',
						success: function (data){
							if (data.success){
								$('#accountLogs').DataTable().ajax.reload();
							}
						}
					});
				}
		});
  })
</script>