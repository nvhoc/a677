<!-- Header
================================================== -->
<?php
$header= new HeaderController();
$header->index();
?>

<body>

<!-- Navbar
================================================== -->
<?php 
	//$navbar = NavbarController();
	//$navbar->index(); 
	NavbarController::index();
?>
	
<?php
	//$sidebar = SidebarController();
	//$sidebar->index(); 
	SidebarController::index();
?>

	<!-- Main Content
	================================================== -->
			<h1>Dashboard</h1>

			<!-- <div class="row-fluid">
			  <div class="span4">
				<h2>Something.</h2>
				<p>Built at Twitter by <a href="http://twitter.com/mdo">@mdo</a> and <a href="http://twitter.com/fat">@fat</a>, Bootstrap utilizes <a href="http://lesscss.org">LESS CSS</a>, is compiled via <a href="http://nodejs.org">Node</a>, and is managed through <a href="http://github.com">GitHub</a> to help nerds do awesome stuff on the web.</p>
			  </div>
			  <div class="span4">
				<h2>Something.</h2>
				<p>Bootstrap was made to not only look and behave great in the latest desktop browsers (as well as IE7!), but in tablet and smartphone browsers via <a href="./scaffolding.html#responsive">responsive CSS</a> as well.</p>
			  </div>
			  <div class="span4">
				<h2>Something.</h2>
				<p>A 12-column responsive <a href="./scaffolding.html#gridSystem">grid</a>, dozens of components, <a href="./javascript.html">JavaScript plugins</a>, typography, form controls, and even a <a href="./customize.html">web-based Customizer</a> to make Bootstrap your own.</p>
			  </div>
			</div> -->
		</div>
	  </div>




<!-- Footer
================================================== -->
<?php 
$footer= new FooterController();
$footer->index();
?>



<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>



</body>
</html>
