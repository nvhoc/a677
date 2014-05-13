<!-- Header
================================================== -->
<?php HeaderController::index()?>

<body>

<!-- Navbar
================================================== -->
<?php NavbarController::index() ?>

	<!-- Sidebar
	================================================== -->
		<?php SidebarController::index() ?>

	<!-- Main Content
	================================================== -->
<?php 
$log_on_nm = isset($_GET['log_on_nm']) ? $_GET['log_on_nm'] : 0;
$infoUser = ApiAdminUsersSaveController::loadAdminEditByID($log_on_nm); 
?>
			<h1><?php echo Lang::getString('EDIT_USER');?></h1>
			<div id="show_alert">
				
			</div>
			
			<div class="row-fluid">
				<div class="span12">
						<form>
							<fieldset>
							<legend><?php echo $log_on_nm;?></legend>
							<label><?php echo Lang::getString('CONNECTION_IP');?></label>
							<div class="row-fluid">
								<div class="span3">
									<input id="access_ip" name="access_ip" type="text" class="span12" placeholder="<?php echo Lang::getString('CONNECTION_IP');?>" value="<?php echo $infoUser['access_ip'];?>">
								</div> 
								<div class="span3">
									<label class="checkbox inline">
										<input id="access_ip_unlimited_flg" name="access_ip_unlimited_flg" type="checkbox" <?php echo $infoUser['access_ip_unlimited_flg'] == 1 ? 'checked="checked"' : ''; ?>> <?php echo Lang::getString('NO_IP_LIMITATION');?>
									</label>
								</div>
							</div>
							<span class="help-block"><?php echo Lang::getString('LIST_IP_ADDRESS_OR_RANGE');?></span>
							</fieldset>
							
							<hr />
							
						<div class="row-fluid">
							<div class="span6">
								<fieldset>
									<label><?php echo Lang::getString('AVAILABLE_SCREENS');?></label>
									<?php 
										$results = ApiAdminUsersSaveController::loadAllScreen();
										$arrAvailableScreen = ApiAdminUsersSaveController::loadAvailableScreen($log_on_nm);
										
										foreach ($results as $key => $item)
										{
											$isChecked = '';
											if(in_array($item['id'], $arrAvailableScreen))
												$isChecked = 'checked="checked"';
									?>
									<label id="checkboxes" class="checkbox" >
									  	<input id="<?php echo $item['id'];?>" name="<?php echo $item['id'];?>" value="<?php echo $item['id'];?>" type="checkbox" <?php echo $isChecked;?>><?php echo Lang::getString($item['screen_name']).'['. Lang::getString($item['action_name']).']';?>
									</label>
									<?php }?>
								</fieldset>
							</div>
							
							<div class="span6">
								<fieldset>
									<label><?php echo Lang::getString('ROLE_PRESETS');?></label>
									<button class="btn" type="button" onclick="checkAvailableScreensWhenChooseRole(0)"><?php echo Lang::getString('CHECK_ALL');?></button>
									<button class="btn" type="button" onclick="checkAvailableScreensWhenChooseRole(-1)"><?php echo Lang::getString('CHECK_NONE');?></button>
									<?php 
										$results = AdminUsersEditController::loadAllRoleName();
										if(!empty($results)):
											echo '<div class="btn-toolbar">';
										foreach ($results as $key => $item):
											$list_screen_action_id = AdminUsersEditController::getListScreeenActionIDByRoleID($item['role_id']);
									?>
										<button class="btn" type="button" onclick="checkAvailableScreensWhenChooseRole(<?php echo $item['role_id'];?>)"><?php echo Lang::getString($item['role_name']);?></button>
									<?php 
										endforeach;
											echo "</div>";
										endif;
									?>
								</fieldset>
							</div>
						</div>
							<div class="form-actions">
								<button type="button" class="btn btn-primary" onClick="saveAdminUserEdit('<?php echo $log_on_nm;?>');"><?php echo Lang::getString('SAVE_CHANGES');?></button>
								<button type="button" class="btn" onClick="cancelAdminUserEdit();"><?php echo Lang::getString('CANCEL');?></button>
							</div>
							
						</form>

				</div>
			</div>
		</div>


	  </div>




<!-- Footer
================================================== -->
<?php FooterController::index() ?>



<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script>
var MSG_001 = '<?php echo Lang::getString('MSG_001');?>';
var ERROR_MSG_011 = '<?php echo Lang::getString('ERROR_MSG_011');?>';
var ERROR_MSG_007 = '<?php echo Lang::getString('ERROR_MSG_007');?>';

$(document).ready(function($) {
	$('.alert').hide();
});
</script>
<script src="js/003_setting/adminUsersSave.js"></script>
<script src="js/003_setting/roleSetting.js"></script>

<!-- Transitions
================================================== -->
<script>

</script>

</body>
</html>
