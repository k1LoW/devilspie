<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        devil's pie -
        <?php echo $title_for_layout; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo $baseUrl; ?>css/bootstrap-combined.min.css" rel="stylesheet">
    <style>
    
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <?php
    echo $this->fetch('meta');
    echo $this->fetch('css');
    ?>
    <?php echo $this->Html->css('style'); ?>
    <script>
        var global = Function("return this")();
        global.baseUrl = '<?php echo $baseUrl; ?>';
        global.stdoutEof = '<?php echo STDOUT_EOF; ?>';
    </script>
</head>
<?php
  $bodyclass = $this->fetch('bodyclass');
  ?>
<body class="<?php echo $bodyclass; ?>">
    <?php echo $this->Session->flash('auth'); ?>
    <?php echo $this->Session->flash(); ?>
    <div class="container">
        <?php echo $this->fetch('content'); ?>
    </div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo $baseUrl; ?>js/lib/jquery.min.js"></script>
    <script src="<?php echo $baseUrl; ?>js/lib/bootstrap.min.js"></script>
    <script src="<?php echo $baseUrl; ?>js/lib/run_prettify.js"></script>
    <?php echo $this->Html->script('lib/pubsub'); ?>
    <?php echo $this->fetch('script'); ?>

</body>
</html>