<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <title>合心易-<?= $model->title; ?></title>
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/swipeslider.css">
    <script type="text/javascript" src="assets/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="assets/js/swipeslider.js"></script>
</head>

<body>
    <div class="gd_body">
        <div class="top">
            <figure id="full_feature" class="swipslider">
                <ul class="sw-slides">
                <?php foreach ($goodImages as $k => $v){?>
                    <li class="sw-slide">
                        <img src="<?= $v?>" alt="<?= $model->title; ?>">
                    </li>
                <?php }?>
                </ul>
            </figure>
        </div>
        <div class="title">
            <h2><?= $model->title; ?></h2>
            <div>
                <span class="tag">品牌</span>
                <span><?= $model->brand->title; ?></span>
            </div>
            <div class="code">商品编码：<?= $model->good_num; ?></div>
        </div>
        <div class="detail"><?= $model->description; ?></div>
        <div class="gd_footer">
            <style> 
                a{ text-decoration:none;color: #fff; } 
            </style> 
            <div class="gd_footer_btn" id="goStore"><a href="https://itunes.apple.com/cn/app/%E5%90%88%E5%BF%83%E6%98%93/id1310355736?mt=8">选择商家</a></div>
        </div>
    </div>
    <script type="text/javascript" src="assets/js/app.js"></script>
</body>
<script>
    $(window).load(function () {
        $('#full_feature').swipeslider({
            autoPlay: false,
            prevNextButtons: false
        });
        $('#goStore').on('click',function(){

        });
    });
</script>

</html>