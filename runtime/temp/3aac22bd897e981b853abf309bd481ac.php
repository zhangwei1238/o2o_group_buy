<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"C:\wwwroot\119.29.24.237\public/../application/index\view\pay\paysuccess.html";i:1505615882;s:74:"C:\wwwroot\119.29.24.237\public/../application/index\view\public\head.html";i:1505615883;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $title; ?></title>
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="__STATIC__/index/css/base.css" />
    <link rel="stylesheet" href="__STATIC__/index/css/common.css" />
    <!-- 动态加载css -->
    <link rel="stylesheet" href="__STATIC__/index/css/<?php echo $controller; ?>.css" />
    <script type="text/javascript" src="__STATIC__/index/js/html5shiv.js"></script>
    <script type="text/javascript" src="__STATIC__/index/js/respond.min.js"></script>
    <script type="text/javascript" src="__STATIC__/index/js/jquery-1.11.3.min.js"></script>
</head>
<body>
    <div class="header-bar">
        <div class="header-inner">
            <ul class="father">
                <li><a><?php echo $city['name']; ?></a></li>
                <li>|</li>
                <li class="city">
                    <a>切换城市<span class="arrow-down-logo"></span></a>
                    <div class="city-drop-down">
                        <h3>热门城市</h3>
                        <ul class="son">
                        <?php if(is_array($citys) || $citys instanceof \think\Collection || $citys instanceof \think\Paginator): $i = 0; $__LIST__ = $citys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <li><a href="<?php echo url('index/index',['city'=>$vo['uname']]); ?>"><?php echo $vo['name']; ?></a></li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                            
                        </ul>
                        
                    </div>
                </li>
                <?php if($user): ?>
                    <li>欢迎您：<?php echo $user['username']; ?></li>
                    <li><a href="<?php echo url('user/logout'); ?>">退出</a></li>
                <?php else: ?>
                <li><a href="<?php echo url('user/register'); ?>">注册</a></li>
                <li>|</li>
                <li><a href="<?php echo url('user/login'); ?>">登录</a></li>
                <?php endif; ?>
                <li><a href="<?php echo url('bis/login/index'); ?>">商户中心</a></li>

            </ul>
        </div>
    </div>
    <?php if($controller == 'pay'): else: ?>
        <div class="search">
            <img src="__STATIC__/index/image/logo.png" />
            
        </div>
    <?php endif; ?>
<!--支付第三步-->
    <div class="first">
        <div class="search">
            <img src="__STATIC__/index/image/logo.png" />
            <div class="w-order-nav-new">
                <ul class="nav-wrap">
                    <li>
                        <div class="no"><span>1</span></div>
                        <span class="text">确认订单</span>
                    </li>
                    <li class="to-line "></li>
                    <li>
                        <div class="no"><span>2</span></div>
                        <span class="text">选择支付方式</span>
                    </li>
                    <li class="to-line "></li>
                    <li class="current">
                        <div class="no"><span>3</span></div>
                        <span class="text">购买成功</span>
                    </li>
                </ul>
            </div>
        </div>

        <div style="color:red;width: 980px;height: 300px;margin: 0 auto;text-align: center;line-height: 300px;font-size: 36px;">恭喜，购买成功！</div>
    </div>

    <div class="footer">
        <ul class="first">
           
        </ul>
        <ul class="second">
            
        </ul>
    </div>