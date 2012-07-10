<!doctype html>
<html>
    <!--<head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
      <meta name='loginza-verification' content='dca52328b8011ae3dabacaec56bea3c3' />

      <? if (!empty($metaTags)){ ?>
          <title><?= $metaTags->title ?></title>
          <meta name="description" content="<?= $metaTags->description ?>" />
          <meta name="keywords" content="<?= $metaTags->keywords ?>" />
      <? } else{  ?>
        <title>Free-write.ru</title>
      <? } ?>
      <style>
          .notitle .ui-dialog-titlebar {display:none}
      </style>
      <meta name="author" content="" />
      <meta name="viewport" content="width=device-width,initial-scale=1" />


        <link type="text/css" rel="stylesheet" href="/css/smoothness/jquery-ui-1.8.20.custom.css">
        <link type="text/css" rel="stylesheet" href="/css/main.css" />
        <link rel="shortcut icon" href="/img/favicon.ico" />
        <script src="/js/main/jquery-1.6.2.js"></script>
        <script src="/js/jquery.ui.datepicker-ru.js"></script>
        <script src="/js/main/jquery-ui-1.8.15.js"></script>
        <script src="/js/main/jquery.validate.js"></script>
        <script src="/js/main/jquery.form.js"></script>

        <script src="/js/department_navigation.js"></script>
        <script src="/js/topBanner.js"></script>

        <script src="/js/jquery.mousewheel.js"></script>
        <script src="/js/jquery.jscrollpane.min.js"></script>
        <script src="/js/jquery.styleSelect.js"></script>



        
        <script src="/js/payment.js"></script>
        <script src="/js/login.js"></script>
        <script src="/js/timeago.js"></script>
        <script src="/js/timeago.locale.js"></script>

    </head> -->
    <? $this->load->view ('main_layouts/slave/head_tag') ?>
    <body>

        <div id="body">
            <div>
                <? $this->load->view('main_layouts/slave/header') ?>
                <? $this->load->view('content/'.$ContentTemplate); ?>
                <? $this->load->view('main_layouts/slave/social_plugin') ?>
            </div>


        </div>

        <? $this->load->view('main_layouts/slave/footer') ?>
    </body>


</html>
