<!-- Sidebar -->
<?php echo $html?>
	
<!-- Content
================================================== -->
<div id="content" class="container-fluid">
	<div class="row-fluid">
<!-- Main Content
================================================== -->
<div id="main-content" class="row-fluid">
	<div class="span12 pull-left">	
		<ul class="breadcrumb">
			<li class="active" >
				<?php
					$html = ''; 
					$home = Lang::getString('DSP_00201');
					$html .= '<a href="/?m=002_menu&c=Menu&a=index">'.$home.'</a> /';
					$html .= $folderName;
					
					if($screenName == 'DSP_00201') {
						$html = '<span class="divider">'.Lang::getString($screenName).'</span>';	
					}
					else {
						$html .= '<span class="divider">/ '.Lang::getString($screenName).'</span>';
					}
					
					echo $html;
				?>
			</li>
		</ul>		
	
	
<!-- Le CSS
================================================== -->
<style>
.mr4 {
	margin-right: 4px !important;
}
.mt-2 {
	margin-top: -2px;
}
</style>

<!-- Le javascript
================================================== -->
<script src="js/lib/jquery.livefilter.js"></script>
<script src="js/000_base/sidevar.js"></script>

