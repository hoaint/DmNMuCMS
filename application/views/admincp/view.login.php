<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $this->config->config_entry('main|servername'); ?> AdminCP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $this->config->config_entry('main|servername'); ?> AdminCP">
    <link href="<?php echo $this->config->base_url; ?>assets/admincp/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
        body {
            padding-bottom: 40px;
        }

        .sidebar-nav {
            padding: 9px 0;
        }
    </style>
    <link href="<?php echo $this->config->base_url; ?>assets/admincp/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo $this->config->base_url; ?>assets/admincp/css/app.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="row-fluid">
            <div class="span12 center login-header">
                <h2>Welcome to AdminCP</h2>
            </div>
        </div>
        <div class="row-fluid">
            <div class="well span5 center login-box">
                <?php if(isset($error)): ?>
                    <div class="alert alert-error">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <form class="form-horizontal" action="" method="post">
                    <input type="hidden" name="login_admin" value="1"/>
                    <fieldset>
                        <div class="input-prepend" title="Username" data-rel="tooltip">
                            <span class="add-on"><i class="icon-user"></i></span>
                            <input autofocus class="input-large span10" name="username" id="username" type="text"
                                   value="" placeholder="Username" required/>
                        </div>
                        <div class="clearfix"></div>
                        <div class="input-prepend" title="Password" data-rel="tooltip">
                            <span class="add-on"><i class="icon-lock"></i></span>
                            <input class="input-large span10" name="password" id="password" type="password" value=""
                                   placeholder="Password" required/>
                        </div>
                        <?php if(defined('PINCODE') && PINCODE != ''): ?>
                            <div class="clearfix"></div>
                            <div class="input-prepend" title="<?php echo $first['placeholder']; ?>" data-rel="tooltip">
                                <span class="add-on"><i class="icon-pencil"></i></span>
                                <input class="input-large span10" name="first" id="first" type="text" value=""
                                       placeholder="<?php echo $first['placeholder']; ?>" required/>
                            </div>
                            <div class="clearfix"></div>
                            <div class="input-prepend" title="<?php echo $second['placeholder']; ?>" data-rel="tooltip">
                                <span class="add-on"><i class="icon-pencil"></i></span>
                                <input class="input-large span10" name="second" id="second" type="text" value=""
                                       placeholder="<?php echo $second['placeholder']; ?>" required/>
                            </div>
                            <div class="clearfix"></div>
                            <div class="input-prepend" title="<?php echo $third['placeholder']; ?>" data-rel="tooltip">
                                <span class="add-on"><i class="icon-pencil"></i></span>
                                <input class="input-large span10" name="third" id="third" type="text" value=""
                                       placeholder="<?php echo $third['placeholder']; ?>" required/>
                            </div>
                        <?php endif; ?>
                        <div class="clearfix"></div>
                        <p class="center span5">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </p>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<style>@import url('https://fonts.googleapis.com/css?family=Droid+Sans:400,700');</style>
<style>@import url(https://fonts.googleapis.com/css?family=Shojumaru);</style>
<script src="<?php echo $this->config->base_url; ?>assets/admincp/js/jquery-1.9.1.min.js"></script>
<script src="<?php echo $this->config->base_url; ?>assets/admincp/js/jquery-ui-1.9.1-min.js"></script>
</body>
</html>
