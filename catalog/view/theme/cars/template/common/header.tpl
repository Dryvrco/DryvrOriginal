<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="catalog/view/javascript/bootstrap/js/custom.js"></script>

<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/less/bootstrap.less" rel="stylesheet/less" />
<script src="catalog/view/javascript/bootstrap/less-1.7.4.min.js"></script>
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<!--<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">-->
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<link href="catalog/view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="catalog/view/javascript/summernote/summernote.js"></script>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<script src="catalog/view/javascript/common2.js" type="text/javascript"></script>

<script src="catalog/view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />

<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<link href="catalog/view/theme/cars/stylesheet/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<link href="catalog/view/theme/cars/stylesheet/custom.css" rel="stylesheet" type="text/css">
<link href="catalog/view/theme/cars/stylesheet/media.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "525976dd-b914-4957-83b5-9ed7f35ac566", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="//v2.zopim.com/?34tNfpFdstKqxvJoVHqbCx6We6d52IxN";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>
<!--End of Zopim Live Chat Script-->

<!-- Facebook Conversion Code for Dryvr visits -->
<script>(function() {
var _fbq = window._fbq || (window._fbq = []);
if (!_fbq.loaded) {
var fbds = document.createElement('script');
fbds.async = true;
fbds.src = '//connect.facebook.net/en_US/fbds.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(fbds, s);
_fbq.loaded = true;
}
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6027005166565', {'value':'0.01','currency':'USD'}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6027005166565&amp;cd[value]=0.01&amp;cd[currency]=USD&amp;noscript=1" /></noscript>

<?php echo $google_analytics; ?>

</head>
<body>
<div id="top" class=" navbar-fixed-top">
  <div class="container">
    <div class="col-sm-3 logo"><a href=""><img src="catalog/view/theme/cars/image/rent-car-logo.png" class=" img-responsive"></a></div>
    <nav class="navbar">
      <div class="navbar-header yllo">
        <button  aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button"> <span class="sr-only">Menu</span> <i class="fa fa-chevron-down"></i> </button>
      </div>
      <div class="navbar-collapse collapse pull-right" id="navbar">
        <ul class="nav navbar-nav">
        
        <?php if (!isset($this->session->data['customer'])){ ?>
        <li><a href="<?php echo $this->url->link('account/agencylogin'); ?>">Agency Login</a></li>
        <li><a href="<?php echo $login; ?>">Customer Login</a></li>
        <li><a href="index.php?route=account/register">Register</a></li>
        <?php } ?>
        <?php if ($type=='0') { ?>
          <li class="dropdown"><a href="<?php echo $this->url->link('seller/account-dashboard'); ?>" title="<?php echo $ms_account_seller_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $ms_account_seller_account; ?></span> <span class="caret"></span></a>
            <ul class="dropdown-menu dropdown-menu-right">
              <?php if ($ms_seller_created) { ?>
              <?php if ($this->MsLoader->MsSeller->getStatus($this->agency->getSellerId()) == MsSeller::STATUS_ACTIVE) { ?>
              <?php if($this->agency->hasPermission('access', 'seller/account-dashboard')) { ?>
              <li><a href="<?php echo $this->url->link('seller/account-dashboard'); ?>"><?php echo $ms_account_dashboard; ?></a></li>
              <?php } ?>
              <?php } ?>
              <?php if($this->agency->hasPermission('access', 'seller/account-profile')) { ?>
              <li><a href= "<?php echo $this->url->link('seller/account-profile'); ?>"><?php echo $ms_account_sellerinfo; ?></a></li>
              <?php } ?>
              <?php if ($this->MsLoader->MsSeller->getStatus($this->agency->getSellerId()) == MsSeller::STATUS_ACTIVE) { ?>
              <?php if($this->agency->hasPermission('access', 'seller/account-product')) { ?>
              <li><a href= "<?php echo $this->url->link('seller/account-product/add'); ?>"><?php echo $ms_account_newproduct; ?></a></li>
              <?php } ?>
              <?php if($this->agency->hasPermission('access', 'seller/account-product')) { ?>
              <li><a href= "<?php echo $this->url->link('seller/account-product'); ?>"><?php echo $ms_account_products; ?></a></li>
              <?php } ?>
              <?php if($this->agency->hasPermission('access', 'seller/account-order')) { ?>
              <li><a href= "<?php echo $this->url->link('seller/account-order'); ?>"><?php echo $ms_account_orders; ?></a></li>
              <?php } ?>
              <?php if($this->agency->hasPermission('access', 'seller/account-transaction')) { ?>
              <li><a href= "<?php echo $this->url->link('seller/account-transaction'); ?>"><?php echo $ms_account_transactions; ?></a></li>
              <?php } ?>
              <?php if ($this->config->get('msconf_allow_withdrawal_requests')) { ?>
              <?php if($this->agency->hasPermission('access', 'seller/account-withdrawal')) { ?>
              <li><a href= "<?php echo $this->url->link('seller/account-withdrawal'); ?>"><?php echo $ms_account_withdraw; ?></a></li>
              <?php } ?>
              <?php } ?>
              <?php if($this->agency->hasPermission('access', 'seller/account-stats')) { ?>
              <li><a href= "<?php echo $this->url->link('seller/account-stats'); ?>"><?php echo $ms_account_stats; ?></a></li>
              <?php } ?>
              <li><a href="<?php echo $this->url->link('account/logout'); ?>"><?php echo $text_logout; ?></a></li>
              <?php } ?>
              <?php } else { ?>
              <li><a href="<?php echo $this->url->link('account/agencylogin'); ?>"><?php echo $text_login; ?></a></li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>
          <?php if ($type=='1') { ?>
          <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
            <ul class="dropdown-menu dropdown-menu-right">
              <?php if ($logged) { ?>
              <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
              <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
              <li style="display:none"><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
              <li style="display:none"><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
              <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
              <?php } else { ?>
              <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>
          <?php if($telephone!=''){ ?>
          <li><a href="#"><i class="fa fa-phone"></i> <?php echo $telephone; ?></a></li>
          <?php } ?>
        </ul>
      </div>
    </nav>
  </div>
</div>
<div class="topfix"></div>
