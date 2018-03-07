<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$ci                 =& get_instance();
$requiredJSFiles    = [
  'libs/jquery-1.10.2.js',
  'libs/jquery-ui.min.js',
  'libs/perfect-scrollbar.min.js',
  'libs/bootstrap.min.js',
  'libs/jquery.validate.min.js',
  'libs/es6-promise-auto.min.js',
  'libs/moment.min.js',
  'libs/bootstrap-datetimepicker.js',
  'libs/bootstrap-selectpicker.js',
  'libs/bootstrap-switch-tags.js',
  'libs/jquery.easypiechart.min.js',
  'libs/chartist.min.js',
  'libs/bootstrap-notify.js',
  'libs/sweetalert2.js',
  'libs/jquery-jvectormap.js',
  'libs/jquery.bootstrap.wizard.min.js',
  'libs/bootstrap-table.js',
  'libs/jquery.datatables.js',
  'libs/dataTables.fixedHeader.min.js',
  'libs/fullcalendar.min.js',
  'libs/paper-dashboard.js',
  'libs/js.cookie.js',
  'libs/bootbox.min.js',
  'libs/form.submit.js',
  'libs/main.js',
  'libs/noty.js'
];
$requiredCSSFiles   = [
  'libs/bootstrap.min.css',
  'libs/datatables.min.css',
  'libs/fixedHeader.dataTables.min.css',
  'libs/animate.min.css',
  'libs/paper-dashboard.css',
  'libs/themify-icons.css',
  'libs/font-awesome.min.css',
  'style.css',
  'libs/noty.css'
];

if(isset($requireJs) && is_array($requireJs) && count($requireJs) > 0) {
  $requiredJSFiles = array_merge($requiredJSFiles, $requireJs);
}
if(isset($requireCSS) && is_array($requireCSS) && count($requireCSS) > 0) {
  $requiredCSSFiles = array_merge($requiredCSSFiles, $requireCSS);
}

$this->minify->js($requiredJSFiles, (isset($assetsNamespace) ? $assetsNamespace : 'global'));
$this->minify->css($requiredCSSFiles, (isset($assetsNamespace) ? $assetsNamespace : 'global'));

$menuItems = [
  [
    'menu_controller'   => 'dashboard',
    'menu_method'       => 'index',
    'menu_name'         => ['dashboard'],
    'menu_text'         => 'Dashboard',
    'menu_text_short'   => 'D',
    'menu_icon'         => 'fa fa-dashboard',
    'menu_has_childs'   => false
  ],
  [
    'menu_controller'   => 'agent',
    'menu_method'       => 'index',
    'menu_name'         => ['agents'],
    'menu_text'         => 'Agents',
    'menu_text_short'   => 'A',
    'menu_icon'         => 'fa fa-users',
    'menu_has_childs'   => false
  ]
  // [
  //   'menu_controller'   => '',
  //   'menu_method'       => '',
  //   'menu_name'         => ['rules_inbund', 'rules_outbund'],
  //   'menu_text'         => 'Rules',
  //   'menu_text_short'   => 'R',
  //   'menu_icon'         => 'fa fa-map-signs',
  //   'menu_has_childs'   => true,
  //   'menu_childs'       => [
  //     [
  //       'menu_controller'   => 'rules',
  //       'menu_method'       => 'inbound',
  //       'menu_name'         => ['rules_inbund'],
  //       'menu_text'         => 'Inbound rules',
  //       'menu_text_short'   => 'IR',
  //       'menu_has_childs'   => false
  //     ],
  //     [
  //       'menu_controller'   => 'rules',
  //       'menu_method'       => 'outbound',
  //       'menu_name'         => ['rules_outbund'],
  //       'menu_text'         => 'Outbound rules',
  //       'menu_text_short'   => 'OR',
  //       'menu_has_childs'   => false
  //     ]
  //   ]
  // ],
//  [
//    'menu_controller'   => '',
//    'menu_method'       => '',
//    'menu_name'         => ['audit/index', 'settings/index', 'users/index', 'keys/index'],
//   'menu_text'         => 'Application',
//    'menu_text_short'   => 'App',
//    'menu_icon'         => 'fa fa-server',
//   'menu_has_childs'   => true,
//    'menu_childs'       => [
//      [
//        'menu_controller'   => 'audit',
//        'menu_method'       => 'index',
//        'menu_name'         => ['audit/index'],
//       'menu_text'         => 'Audit',
//        'menu_text_short'   => 'A',
//        'menu_icon'         => 'fa fa-history',
//        'menu_has_childs'   => false
//      ],
//      [
//        'menu_controller'   => 'settings',
//        'menu_method'       => 'index',
//        'menu_name'         => ['settings/index'],
//        'menu_text'         => 'Settings',
//        'menu_text_short'   => 'S',
//        'menu_icon'         => 'fa fa-cog',
//        'menu_has_childs'   => false
//      ],
//      [
//        'menu_controller'   => 'users',
//        'menu_method'       => 'index',
//        'menu_name'         => ['users/index'],
//        'menu_text'         => 'Users',
//        'menu_text_short'   => 'U',
//       'menu_icon'         => 'fa fa-user',
//        'menu_has_childs'   => false
//      ]
//    ]
//  ]
];

?>
<!DOCTYPE html>
<html>
<head>
  <title><?= $ci->settings->application_title; ?> <?= isset($pageTitle) && $pageTitle != '' ? '&raquo; '.$pageTitle : ''; ?></title>
  <base href="<?= $this->config->item('base_url')?>" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
  <meta name="viewport" content="width=device-width" />
  <meta http-equiv="cache-control" content="max-age=0" />
  <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="expires" content="0" />
  <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
  <meta http-equiv="pragma" content="no-cache" />
  <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
  <script src="https://cdn.ravenjs.com/3.12.1/raven.min.js"></script>
  <script type="text/javascript">
  var baseURL = '<?= $ci->baseURL; ?>';
  var serverIP = location.host.split(':');
  var serverIP = serverIP[0];
  var enableSentry = <?= $this->settings->application_sentry_status === 'on' ? 1 : 0; ?>;
  if(enableSentry) Raven.config('<?= $this->settings->application_sentry_dsn_public; ?>').install();
  </script>
  <?= $this->minify->deploy_css(false); ?>
  <?= $this->minify->deploy_js(false); ?>
</head>
<body>
  <div id="loader"></div>
  <div class="wrapper">
    <?php if(!isset($disableSidebar)) { ?>
      <div class="sidebar" data-background-color="black" data-active-color="danger">
        <div class="logo">
          <a href="#" class="simple-text">
          	<img src="<?= $this->baseURL.(isset($this->settings->application_logo) && $this->settings->application_logo != '' ? 'files/app/'.$this->settings->application_logo : 'assets/img/'); ?>" alt="" style="padding: 5px;max-width: 100%;"/>
          </a>
        </div>
        <div class="sidebar-wrapper">
          <ul class="nav">
            <?php foreach($menuItems as $menuItem) { ?>
              <?php if($menuItem['menu_has_childs'] === true) { ?>
                <li class="<?= in_array($menuActive, $menuItem['menu_name']) ? 'active' : '';?>">
                  <a data-toggle="collapse" href="#<?= $menuItem['menu_text']; ?>" aria-expanded="<?= in_array($menuActive, $menuItem['menu_name']) ? 'true' : '';?>">
                      <i class="<?= $menuItem['menu_icon']; ?>"></i>
                      <p><?= $menuItem['menu_text']; ?><b class="caret"></b></p>
                  </a>
                  <div class="collapse <?= in_array($menuActive, $menuItem['menu_name']) ? 'in' : '';?>" id="<?= $menuItem['menu_text']; ?>">
                      <ul class="nav">
                        <?php foreach($menuItem['menu_childs'] as $childItem) { ?>
                          <li class="<?= in_array($menuActive, $childItem['menu_name']) ? 'active' : '';?>">
                            <a href="<?= $childItem['menu_controller'].'/'.$childItem['menu_method']; ?>">
                              <span class="sidebar-mini"><?= $childItem['menu_text_short']; ?></span>
                              <span class="sidebar-normal"><?= $childItem['menu_text']; ?></span>
                            </a>
                          </li>
                        <?php } ?>
                      </ul>
                  </div>
                </li>
              <?php } else { ?>
              <li class="<?= in_array($menuActive, $menuItem['menu_name']) ? 'active' : '';?>">
                <a href="<?= $menuItem['menu_controller'].'/'.$menuItem['menu_method']; ?>">
                  <i class="<?= $menuItem['menu_icon']; ?>"></i>
                  <p><?= $menuItem['menu_text']; ?></p>
                </a>
              </li>
              <?php } ?>
            <?php } ?>
          </ul>
            </div>
          </div>
          <?php } ?>

            <div class="main-panel" <?= isset($disableSidebar) ? 'style="width: 100%;"' : ''; ?>>
              <nav class="navbar navbar-default" style="width: 100%;">
                <div class="container-fluid">
                  <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar bar1"></span>
                      <span class="icon-bar bar2"></span>
                      <span class="icon-bar bar3"></span>
                    </button>
                    <span class="navbar-brand" href="/"> <?= @$pageTitle; ?></span>
                  </div>
                  <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                      <li>
                        <a href="<?= $this->config->item('base_url'); ?>app/my_profile">
                          <i class="fa fa-user"></i>
                          <p>My Profile</p>
                        </a>
                      </li>
                      <li>
                        <a href="<?= $this->config->item('base_url'); ?>logout">
                          <i class="fa fa-sign-out"></i>
                          <p>Logout</p>
                        </a>
                      </li>
                    </ul>

                  </div>
                </div>
              </nav>

              <div class="content">
                <div class="container-fluid">
