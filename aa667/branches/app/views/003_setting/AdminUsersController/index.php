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

			<h1><?php echo Lang::getString('DSP_00301');?></h1>
			
			<div class="row-fluid">
				<div class="span12 well">
					<div class="form-search">
						
							<input id="search_002_01" name="search_002_01" maxlength="50" type="text" class="input-large search-query">
							<button id="btnSearch" class="btn" type="button" onClick="SearchAdminUsers(1);"><?php echo Lang::getString('ACT_00002');?></button>
						
					</div>				
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span12">
					<table id="result" class="table table-bordered table-hover">

					<col width="15%" />
					<col width="30%" />
					<col />
					<col width="30%" />

					<thead>
						<tr>
							<th><?php echo Lang::getString('LOG_ON_NAME');?></th>
							<th><?php echo Lang::getString('USER_NAME');?></th>
							<th><?php echo Lang::getString('CONNECTION_IP');?></th>
							<th><?php echo Lang::getString('AVAILABLE_SCREENS');?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					</table>

					<div id="pagination" class="pagination">
						<ul>
						</ul>
					</div>
<label id="lblPrev" class="hide"><?php echo Lang::getString('PREV');?></label>
<label id="lblNext" class="hide"><?php echo Lang::getString('NEXT');?></label>
<label id="lblNoIpLimitation" class="hide"><?php echo Lang::getString('NO_IP_LIMITATION');?></label>
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
<script src="js/003_setting/adminUsersSearch.js"></script>

<!-- Transitions
================================================== -->
<script>

jQuery(document).ready(function($) {

	//$('table#result').hide();

//	$('#btnSearch').click(function() {
//		$('tr.hideme').toggle();
//	});
	SearchAdminUsers(1);

	$('#search_002_01').keypress(function(e){
		if(e.which == 13)
		{
			SearchAdminUsers(1);
		}
	});

});


</script>

</body>
</html>
