<?php
use core\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Galaxy Core Center',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/security/login']];
    } else {
        $menuItems[] = [
            'label' => 'Blog', 'url' => ['/blog/core/post/index'],
        ];
        $menuItems[] = [
            'label' => 'Catalog', 'url' => ['/catalog/core/default/index'],
        ];
        $menuItems[] = [
            'label' => 'Tree', 'url' => ['/tree/index'],
        ];
        $menuItems[] = [
            'label' => 'Station', 'url' => ['/station/default/index'],
        ];
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/user/security/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }

    //            $callback = function($menu){
    //                $data = eval($menu['data']);
    //                return [
    //                    'label' => $menu['name'],
    //                    'url' => [$menu['route']],
    //                    'options' => $data,
    //                    'items' => $menu['children']
    //                ];
    //            };

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' =>  $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container" style="width: 100%">
        <div class="col-sm-12">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <div class="row">
                <div class="col-md-2 col-sm-4">
                    <?php
                    use kartik\sidenav\SideNav;
                    use yii\helpers\Url;

                    $type = 'http';
                    $heading = 'Catalog';
                    $item = 'home';

                    echo SideNav::widget([
                        'type' => $type,
                        'encodeLabels' => false,
                        'heading' => $heading,
                        'items' => [
                            ['label' => '类目属性', 'icon' => 'book', 'url' => Url::to('/catalog/core/item-prop/index', $type), 'active' => ($item == 'props')],
                            ['label' => '商品列表', 'icon' => 'tags', 'url' => Url::to('/catalog/core/item/index', $type), 'active' => ($item == 'items')],
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-10 col-sm-8">
                    <?php echo $content ?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>