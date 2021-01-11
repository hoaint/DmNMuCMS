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
    <link href="<?php echo $this->config->base_url; ?>assets/admincp/css/jquery-ui-1.8.21.custom.css" rel="stylesheet">
    <link href='<?php echo $this->config->base_url; ?>assets/admincp/css/chosen.css' rel='stylesheet'>
    <link href='<?php echo $this->config->base_url; ?>assets/admincp/css/uniform.default.css' rel='stylesheet'>
    <link href='<?php echo $this->config->base_url; ?>assets/admincp/css/colorbox.css' rel='stylesheet'>
    <link href='<?php echo $this->config->base_url; ?>assets/admincp/css/jquery.noty.css' rel='stylesheet'>
    <link href='<?php echo $this->config->base_url; ?>assets/admincp/css/noty_theme_default.css' rel='stylesheet'>
    <link href='<?php echo $this->config->base_url; ?>assets/admincp/css/jquery.iphone.toggle.css' rel='stylesheet'>
    <link href='<?php echo $this->config->base_url; ?>assets/admincp/css/opa-icons.css' rel='stylesheet'>
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="<?php echo $this->config->base_url; ?>assets/admincp/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">
        DmNConfig = {
            base_url: '<?php echo $this->config->base_url;?>',
            tmp_dir: '<?php echo $this->config->config_entry('main|template');?>'
        }
    </script>


</head>
<body>
<div class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse"
               data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="btn-group pull-right">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-user"></i><span
                            class="hidden-phone"> <?php echo $this->session->userdata(['admin' => 'username']); ?></span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logout">Logout</a></li>
                </ul>
            </div>
            <div class="top-nav nav-collapse">
                <ul class="nav">
                    <li><a href="<?php echo $this->config->base_url; ?>" target="_blank">Go To Website</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">