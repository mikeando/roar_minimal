<?php require('fb_info.php'); ?>
<html>
  <head prefix="og: http://ogp.me/ns#
    fb: http://ogp.me/ns/fb#
    fbpayment:http://ogp.me/ns/fb/fbpayment#">
    <meta property="fb:app_id"      content="<?php echo $app_id;?>" >
    <meta property="og:type"        content="fbpayment:currency" >
    <meta property="og:url"         content="<?php echo $root_url;?>currency_info.php" >
    <meta property="og:title"       content="dollars" >
    <meta property="og:description" content="In game cash for roar trialpay test" >
    <meta property="og:image"       content="<?php echo $root_url;?>currency.png" >
    <meta property="fbpayment:rate" content="0.5" >
  </head>
</html>

