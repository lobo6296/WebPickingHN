<?php echo $header; ?>
    <title>Administrator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8" />
    <link rel="icon" type="image/ico" href="admin-login/images/favicon.ico" />
    <!-- Bootstrap -->
    <link href="admin-login/css/minoral.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body >

    <!-- Wrap all page content here -->
    <div id="wrap">

      <!-- Make page fluid -->
      <div class="row">
        
        <!-- Page content -->
      <tr> <div id="content" class="col-md-12 full-page login">

          <div class="welcome">
    <img src="admin-login/images/logo.png" alt class="logo">
       <h1><strong>CROPA</strong>.</h1>
       <h5>Grupo Cropa | Copyright ©2018</h5>
            
      <?php if ($success) { ?>
      <div class="success"><?php echo $success; ?></div>
      <?php } ?>
      <?php if ($error_warning) { ?>
	  <div class="alert alert-danger"><strong></strong><?php echo $error_warning; ?></div>
      <?php } ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        
              <section>
                <div class="input-group">
                  <input type="text" class="form-control logpadding" name="username" value="<?php echo $username; ?>" placeholder="Usuario">
                  <div class="input-group-addon"><i class="fa fa-user"></i></div>
                </div>
                <div class="input-group">
                  <input type="password" class="form-control logpadding" name="password" value="<?php echo $password; ?>" placeholder="Clave">
                  <div class="input-group-addon"><i class="fa fa-key"></i></div>
                </div>
				<strong><div class="input-group">
					<select name="estacion" class="form-control form-control-lg" style="color:Black";>
						<option value="1">Tegucigalpa</option>
						<option value="2">San Pedro Sula</option>
					</select>
				</div></strong>
                <?php if ($forgotten) { ?>
                <span style="float:left;" class="help-block"><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></span>
                <?php } ?>				
              </section>
              <section class="new-acc">
                <button class="btn btn-greensea">Login</button>
              </section>
            </form>
         
    <!-- 
    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="admin-login/js/bootstrap.min.js"></script>
    <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js?lang=css&amp;skin=sons-of-obsidian"></script>
    <script src="admin-login/js/jquery.nicescroll.min.js"></script>
    <script src="admin-login/js/plugins/jquery.blockUI.js"></script>
    <script src="admin-login/js/minoral.min.js"></script>
    --> 
    <?php if ($redirect) { ?>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#form').submit();
	}
});
//--></script> 