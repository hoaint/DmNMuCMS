<?php
	$this->load->view($this->config->config_entry('main|template').DS.'view.header');
	$this->load->view($this->config->config_entry('main|template').DS.'view.left_sidebar');
?>	
<div class="news_main">
	<?php 
	if(isset($config_not_found)):
		echo '<div class="box-style1"><div class="entry"><div class="e_note">'.$config_not_found.'</div></div></div>';
	else:
		if(isset($module_disabled)):
			echo '<div class="box-style1"><div class="entry"><div class="e_note">'.$module_disabled.'</div></div></div>';
		else:
	?>		
	<div class="heding">
		<h2><?php echo $about['name']; ?></h2>
	</div>
	<div class="content_rght_info m5">
			<div class="entry other" >
				<?php if(isset($js)): ?>
					<script src="<?php echo $js;?>"></script>
					<?php endif;?>
					<script type="text/javascript">	
					var walletOne = new walletOne();
					walletOne.setUrl('<?php echo $this->config->base_url . $this->request->get_controller();?>');
					$(document).ready(function(){
						$('button[id^="buy_walletone_"]').on('click', function () {
							walletOne.checkout($(this).attr('id').split('_')[2]);
						});
					});
					</script>
					<?php
					if(isset($error)){
						echo '<div class="e_note">' . $error . '</div>';
					} 
					else{
						if(!empty($packages_walletone)){
							foreach($packages_walletone as $packages){
								echo '<ul id="paypal-options">
										<li>
											<h4 class="left">' . $packages['package'] . '</h4>
											<h3 class="left"><span id="reward_' . $packages['id'] . '">' . $packages['reward'] . '</span> ' . $this->website->translate_credits($plugin_config['reward_type'], $this->session->userdata(array('user' => 'server'))) . ' (<span id="price_' . $packages['id'] . '" data-price="' . number_format($packages['price'], 2, '.', ',') . '">' . number_format($packages['price'], 2, '.', ',') . '</span> <span id="currency_' . $packages['id'] . '">' . $this->Mwallet_one->currency_code_to_text($packages['currency']) . '</span>)</h3>
											<button class="right custom_button" id="buy_walletone_' . $packages['id'] . '" style="margin-top: 8px;" value="buy_paypal_' . $packages['id'] . '">' . __('Buy Now') . '</button>
										</li>
								</ul>';
							}
						} 
						else{
							echo '<div class="i_note">' . __('No WaletOne Packages Found.') . '</div>';
						}
					}
					?>
	</div>
	<?php
		endif;
	endif;
	?>
</div>
<?php
	$this->load->view($this->config->config_entry('main|template').DS.'view.footer');
?>		

	

