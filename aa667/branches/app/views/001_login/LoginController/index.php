<!--Funtion that calls header, footer, sidebar, navbar, error controllers
======================= -->

<!-- Header
================================================== -->
<?php
HeaderController::index();
?>

<body>

	<div class="container-fluid">
	  <form id="frm_signin" class="form-signin">
		<h2 class="form-signin-heading">Please sign in</h2>
			<div class="alert alert-block" id="msg_error">
			</div>
		<input id="log_on_nm" type="text" maxlength="50" class="input-block-level" placeholder="Log in Name">
		<input id="psd" type="password" maxlength="20" class="input-block-level" placeholder="Password">
		<button id="signin" class="btn btn-large btn-primary" type="button">Sign in</button>
	  </form>

	</div> <!-- class="container -->

<!-- Footer
================================================== -->
<?php 
FooterController::index();
?>



<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/js/jquery.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/<?php echo $this->module?>/login.js"></script>

<!-- Transitions
================================================== -->
<script>
</script>

</body>
</html>
