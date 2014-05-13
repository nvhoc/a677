<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
	<div class="container-fluid">

		<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
	  	</button>
	  
		<?php if(HeaderController::chkAvailableScreen('5')) { ?>
			<a class="brand" href="index.php?m=002_menu&c=Menu&a=index"><i class="icon-home"></i></a>
		<?php }?>
		
		<ul class="nav pull-right">
		  <li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-off"></i></a>
			<ul class="dropdown-menu">
			  <?php if(HeaderController::chkAvailableScreen('1')) {?>
			  <li><a href="index.php?m=<?php echo BASE_MODULE ?>&c=header&a=logout"><?php echo Lang::getString('ACT_00006')?></a></li>
			<?php } ?>
			 
			</ul>
		  </li>
		</ul>
		<?php if(HeaderController::chkAvailableScreen('2')) {?>
		<ul class="nav pull-right">
		  <li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-flag"></i></a>
			
			<ul class="dropdown-menu">
				<?php 
					foreach($result as $item){
						$language_cd = $item['language_cd'];
						$language_nm = $item['language_nm'];
				?>
				<li>
					<a href="Javascript:void(0)" onclick="JavaScript:changeLanguage('<?php echo $language_cd; ?>')">
						<?php echo $language_nm; ?> 
					</a>
				</li>
				<?php }?>
			</ul>
		  </li>
		</ul>
		<?php } ?>
		
	</div>
  </div>
</div>

<!-- Le javascript
================================================== -->
<script src="/js/jquery.js"></script>
<script src="/js/<?php echo BASE_MODULE ?>/navbar.js"></script>