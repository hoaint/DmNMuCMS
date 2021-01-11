<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="neo6 - Salvis87@inbox.lv"/>
    <meta name="keywords" content="<?php echo $this->meta->request_meta_keywords(); ?>"/>
    <meta name="description" content="<?php echo $this->meta->request_meta_description(); ?>"/>
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title><?php echo $this->meta->request_meta_title(); ?>
        :: <?php echo __('Page Under Maintenance'); ?></title>
    <link type="text/css" href="<?php echo $this->config->base_url; ?>assets/errors/css/bootstrap.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <link type="text/css" href="<?php echo $this->config->base_url; ?>assets/errors/css/font-awesome.css"
          rel="stylesheet">
    <link type="text/css" href="<?php echo $this->config->base_url; ?>assets/errors/css/custom.css" rel="stylesheet">
    <link type="text/css" href="<?php echo $this->config->base_url; ?>assets/errors/css/animate.css" rel="stylesheet">
    <!-- HTML5 shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<section>
    <div class="container">
        <div class="row row1">
            <div class="col-md-12">
                <h3 class="center capital f1 wow fadeInLeft"><?php echo __('Something went Wrong!'); ?></h3>

                <h1 id="error" class="center wow fadeInRight">503</h1>

                <p class="center wow bounceIn"><?php echo __('Page Under Maintenance'); ?>
                    .</p>

                <p class="center wow bounceIn"><?php echo __('We will be back shortly'); ?>
                    .</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="cflask-holder" class="fadeIn">
                            <span class="tada "><i class="fa fa-flask fa-5x flask flip"></i> 
                                <i id="b1" class="bubble"></i>
                                <i id="b2" class="bubble"></i>
                                <i id="b3" class="bubble"></i>
                            </span>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>
