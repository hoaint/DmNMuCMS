<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.left_sidebar');
?>
<div class="news_main">
    <div class="heding">
        <h2><?php echo $about['name']; ?></h2>
    </div>
    <div class="content_rght_info m5">
        <div class="other">
            <?php
				if(isset($config_not_found)):
					echo '<div class="e_note">' . $config_not_found . '</div>';
				else:
					if(isset($module_disabled)):
						echo '<div class="e_note">' . $module_disabled . '</div>';
					else:
					if(isset($js)):
						?>
						<script src="<?php echo $js; ?>"></script>
					<?php endif;
					?>
						<script>
							var ruudExchange = new ruudExchange();
							ruudExchange.setUrl('<?php echo $this->config->base_url . $this->request->get_controller();?>');
							$(document).ready(function () {
								$('#ruud_exchange_form').on("submit", function (e) {
									e.preventDefault();
									ruudExchange.submit($(this));
								});
							});
						</script>

					<?php
						if(!empty($char_list)):
						list($zen, $cred) = explode('/', $plugin_config['ratio']);
					?>
						<div class="i_note"><?php echo _('Exchange info'); ?>:
							<?php
								echo _('You can get') . ' ' . $cred . ' ' . _('Ruud') . ' for ' . $this->website->zen_format($zen) . ' ' .  $this->website->translate_credits($plugin_config['payment_type'], $this->session->userdata(['user' => 'server']));
							?>
						</div>
						<div class="form">
							<form method="post" action="" id="ruud_exchange_form">
								<table>
									<tr>
										<td style="width: 150px;"><?php echo _('Location'); ?></td>
										<td>
											<select name="character">
												<?php foreach($char_list as $chars): ?>
													<option
														value="<?php echo $chars['id']; ?>"><?php echo $chars['Name']; ?></option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>
									<tr>
										<td style="width:150px;"><?php echo sprintf(_('Amount of %s'), $this->website->translate_credits($plugin_config['payment_type'], $this->session->userdata(['user' => 'server']))); ?>:
										</td>
										<td><input type="text" id="credits" name="credits" value=""
												   class="text"
												   onblur="ruudExchange.calculateCurrency($('#credits').val(), '<?php echo $plugin_config['ratio']; ?>');"
												   onkeyup="ruudExchange.calculateCurrency($('#credits').val(), '<?php echo $plugin_config['ratio']; ?>');"/>
										</td>
									</tr>
									<tr>
										<td><?php echo _('Will Receive'); ?> <?php echo _('Ruud'); ?>:</td>
										<td><input type="text" id="game_currency"
												   name="game_currency" value="" class="text"
												   disabled/></td>
									</tr>
									<tr>
										<td></td>
										<td>
											<button type="submit" id="exchange_ruud"
													name="exchange_ruud" disabled="disabled"
													class="button-style"><?php echo _('Submit'); ?></button>
										</td>
									</tr>
								</table>
							</form>
						</div>
						<?php
					else:
						echo '<div class="i_note">' . _('No characters found.') . '</div>';
					endif;
					endif;
				endif;
			?>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>	

