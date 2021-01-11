$(document).ready(function(){
	$.ajaxSetup({
		type: 'POST',
		dataType: 'json',
		error: function(jqXHR, exception){
			var message = '';
			if(jqXHR.status == 404){
				message = 'Requested page not found. [404]';
			} 
			else if(jqXHR.status == 500){
				message = 'Internal Server Error [500]';
			} 
			else if(exception === 'parsererror'){
				message = jqXHR.responseText;
			} 
			else if(exception === 'timeout'){
				message = 'Time out error.';
			} 
			else if(exception === 'abort'){
				message = 'Ajax request aborted.';
			}
			
			if(message != ''){
				hideLoader();
				$( ".panel-body" ).prepend('<div class="alert alert-warning" role="alert">'+message+'</div>');
				
			}
		}
	});
	$('#sql_data_form').on('submit', function(e){
		e.preventDefault();
		showLoader();
		setTimeout(function(){	
			$.ajax({
				url: 'index.php?action=setup/step6',
				data: '&' + $.param({'submit_sql_data': 1}) + '&' + $('#sql_data_form').serialize(),
				success: function(data){
					if(data.step5_5){
						add_additional_tables(data.version);
					}
					if(data.step6){
						set_progress(data.progress);
						$( ".panel-body" ).prepend('<div class="alert alert-success" role="alert">'+data.message+'</div>');
						step_7();
					}
					if(data.error){
						$( ".panel-body" ).prepend('<div class="alert alert-warning" role="alert">'+data.error+'</div>');
					}
				}
			});
		}, 3000);
	});
	
	$('#upgrade_data_form').on('submit', function(e){
		e.preventDefault();
		showLoader();
		setTimeout(function(){	
			$.ajax({
				url: 'index.php?action=upgrade/step4',
				data: '&' + $.param({'submit_upgrade_data': 1}) + '&' + $('#upgrade_data_form').serialize(),
				success: function(data){
					if(data.step3_5){
						add_additional_upgrade_tables(data.version);
					}
					if(data.step4){
						set_progress(data.progress);
						$( ".panel-body" ).prepend('<div class="alert alert-success" role="alert">'+data.message+'</div>');
						add_upgrade_columns();
					}
					if(data.error){
						$( ".panel-body" ).prepend('<div class="alert alert-warning" role="alert">'+data.error+'</div>');
					}
				}
			});
		}, 3000);
	});
});

function add_additional_tables(version){
	setTimeout(function(){
		$.ajax({
			url: 'index.php?action=setup/add_tables/'+version,
			success: function(data){
				if(data.step5_5){
					add_additional_tables(data.version);
				}
				if(data.step6){
					set_progress(data.progress);
					$( ".panel-body" ).prepend('<div class="alert alert-success" role="alert">'+data.message+'</div>');
					add_columns();
				}
				if(data.error){
					$( ".panel-body" ).prepend('<div class="alert alert-warning" role="alert">'+data.error+'</div>');
				}
			}
		});
	}, 1000);
}

function add_additional_upgrade_tables(version){
	setTimeout(function(){
		$.ajax({
			url: 'index.php?action=upgrade/add_tables/'+version,
			success: function(data){
				if(data.step3_5){
					add_additional_upgrade_tables(data.version);
				}
				if(data.step4){
					set_progress(data.progress);
					$( ".panel-body" ).prepend('<div class="alert alert-success" role="alert">'+data.message+'</div>');
					add_upgrade_columns();
				}
				if(data.error){
					$( ".panel-body" ).prepend('<div class="alert alert-warning" role="alert">'+data.error+'</div>');
				}
			}
		});
	}, 1000);
}

function add_columns(){
	setTimeout(function(){
		$.ajax({
			url: 'index.php?action=setup/step7',
			success: function(data){
				if(data.step6_5){
					add_additional_columns(data.version);
				}
				if(data.step7){
					set_progress(data.progress);
					$( ".panel-body" ).prepend('<div class="alert alert-success" role="alert">'+data.message+'</div>');
					step_8();

				}
			}
		});
	}, 1000);
}

function add_upgrade_columns(){
	setTimeout(function(){
		$.ajax({
			url: 'index.php?action=upgrade/step5',
			success: function(data){
				if(data.step4_5){
					add_additional_upgrade_columns(data.version);
				}
				if(data.step5){
					set_progress(data.progress);
					$( ".panel-body" ).prepend('<div class="alert alert-success" role="alert">'+data.message+'</div>');
					upgrade_step_6();
				}
			}
		});
	}, 1000);
}

function add_additional_columns(version){
	setTimeout(function(){
		$.ajax({
			url: 'index.php?action=setup/add_columns/'+version,
			success: function(data){
				if(data.step6_5){
					add_additional_columns(data.version);
				}
				if(data.step7){
					set_progress(data.progress);
					$( ".panel-body" ).prepend('<div class="alert alert-success" role="alert">'+data.message+'</div>');
					step_8();
				}
				if(data.error){
					$( ".panel-body" ).prepend('<div class="alert alert-warning" role="alert">'+data.error+'</div>');
				}
			}
		});
	}, 1000);
}

function add_additional_upgrade_columns(version){
	setTimeout(function(){
		$.ajax({
			url: 'index.php?action=upgrade/add_columns/'+version,
			success: function(data){
				if(data.step4_5){
					add_additional_upgrade_columns(data.version);
				}
				if(data.step5){
					set_progress(data.progress);
					$( ".panel-body" ).prepend('<div class="alert alert-success" role="alert">'+data.message+'</div>');
					upgrade_step_6();
				}
				if(data.error){
					$( ".panel-body" ).prepend('<div class="alert alert-warning" role="alert">'+data.error+'</div>');
				}
			}
		});
	}, 1000);
}

function step_8(){
	setTimeout(function(){	
		$.ajax({
			url: 'index.php?action=setup/step8',
			success: function(data){
				if(data.step8){
					set_progress(data.progress);
					$( ".panel-body" ).prepend('<div class="alert alert-success" role="alert">'+data.message+'</div>');
					step_9();	
				}
			}
		});
	}, 3000);
}

function step_9(){
	setTimeout(function(){	
		$.ajax({
			url: 'index.php?action=setup/step9',
			success: function(data){
				if(data.step9){
					set_progress(data.progress);
					$( ".panel-body" ).prepend('<div class="alert alert-success" role="alert">'+data.message+'</div>');
					setTimeout(function(){	
						hideLoader();
						$(location).attr('href', data.redirect);
					}, 3000);	
				}
			}
		});
	}, 3000);
}

function upgrade_step_6(){
	setTimeout(function(){
		$.ajax({
			url: 'index.php?action=upgrade/step6',
			data: {'submit_upgrade_data': 1},
			success: function(data){
				if(data.step6){
					set_progress(data.progress);
					$( ".panel-body" ).prepend('<div class="alert alert-success" role="alert">'+data.message+'</div>');
					upgrade_step_7();
				}
				if(data.error){
					$( ".panel-body" ).prepend('<div class="alert alert-warning" role="alert">'+data.error+'</div>');
				}
			}
		});
	}, 3000);
}

function upgrade_step_7(){
	setTimeout(function(){
		$.ajax({
			url: 'index.php?action=upgrade/step7',
			data: {'submit_upgrade_data': 1},
			success: function(data){
				if(data.step7){
					set_progress(data.progress);
					$( ".panel-body" ).prepend('<div class="alert alert-success" role="alert">'+data.message+'</div>');
					setTimeout(function(){
						hideLoader();
						$(location).attr('href', data.redirect);
					}, 3000);
				}
				if(data.error){
					$( ".panel-body" ).prepend('<div class="alert alert-warning" role="alert">'+data.error+'</div>');
				}
			}
		});
	}, 3000);
}

function set_progress(progress){
	$('#progress').html(progress);
	$('#progress').css({'width': progress});
}

function showLoader(){		
	$('#loading').fadeIn(300);
}

function hideLoader(){
	$('#loading').fadeOut(300);
}