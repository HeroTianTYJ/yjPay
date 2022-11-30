<?php

//微信支付
//初始化
include __DIR__ . '/lib/WechatPay.php';
$WechatPay = new WechatPay([
    'app_id' => '',  //绑定微信支付的公众号AppID
    'app_secret' => '',  //绑定微信支付的公众号AppSecret
    'mch_id' => '',  //微信支付商户号
    'key' => '',  //微信支付商户密钥
    'cert_serial_number' => '',  //微信支付证书序列号，仅调用异步通知方法时可留空
    'cert_private_key' => ''  //微信支付证书私钥，仅调用异步通知方法时可留空
]);
$wechatPayParam = [
    'out_trade_no' => time() . rand(1000, 9999),
    'description' => '测试商品',
    'notify_url' => 'https://www.abc.com',
    'total' => 1
];
//扫码支付
echo '<img src="qrcode.php?data=' . $WechatPay->native($wechatPayParam) . '" alt="二维码">';
//jsapi支付，调用时会跳转页面获取用户openid
echo "<script type=\"text/javascript\" src=\"https://res.wx.qq.com/open/js/jweixin-1.6.0.js\"></script>
<script type=\"text/javascript\">
function jsApiCall () {
  WeixinJSBridge.invoke(
    'getBrandWCPayRequest',
    " . $WechatPay->jsapi($wechatPayParam) . ",
    function () {
      // TODO
    }
  );
}
if (typeof WeixinJSBridge === 'undefined') {
  if (document.addEventListener) {
    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
  } else if (document.attachEvent) {
    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
  }
} else {
  jsApiCall();
}
</script>";
//h5支付，调用的域名须是商户平台设置的h5域名
echo '<script type="text/javascript">window.location.href="' . $this->wechatPay()->h5($wechatPayParam) . '&redirect_url=' . urlencode('https://www.abc.com') . '";</script>';
//异步通知
$notify = $WechatPay->notify();
if ($notify) {
    print_r($notify);
    echo '';
}



//支付宝
//初始化
include __DIR__ . '/lib/Alipay.php';
$Alipay = new Alipay([
    'app_id' => '',  //APPID
    'merchant_private_key' => '',  //应用私钥
    'public_key' => '',  //公钥
    'method' => 'page'  //page代表电脑支付，wap代表手机支付
]);
//支付
echo $Alipay->pay(
    [
        'body' => '测试商品',
        'subject' => '测试商品',
        'out_trade_no' => time() . rand(1000, 9999),
        'total_amount' => 1
    ],
    [
        'return' => 'https://www.abc.com',
        'notify' => 'https://www.abc.com'
    ]
);
//异步通知
if ($Alipay->check($_POST)) {
    echo 'success';
} else {
    echo 'fail';
}
