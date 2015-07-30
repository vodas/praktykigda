<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */
include Yii::$app->basePath . '/views/site/include.php';
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>

    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::t('app', Html::encode($this->title)) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => Yii::t('app', 'My Company'),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
        if(Yii::$app->user->isGuest){
            $menuItems = [
                ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
                ['label' => Yii::t('app', 'Find product'), 'url' => ['/site/search-products']],
                ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
                ['label' => Yii::t('app', 'Register'), 'url' => ['/register/register']],
                ['label' => Yii::t('app', 'Login'), 'url' => ['/login/login']]
            ];
        }
        else if(Yii::$app->user->identity->user_role==4) {
            $menuItems = [
                ['label' => Yii::$app->user->identity->username,
                    'items' => [
                        ['label' => Yii::t('app','Dashboard'), 'url' => ['/login/logged']],
                        ['label' => Yii::t('app', 'Change user data'), 'url' => ['/account/change-data']],
                        ['label' => Yii::t('app', 'Change password'), 'url' => ['/account/change-password']],
                        ['label' => Yii::t('app', 'Delete account'), 'url' => ['/account/delete-account']]
                    ],
                ],

                ['label' => Yii::t('app', 'Find product'), 'url' => ['/site/search-products']],
                ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
                ['label' => "CRUD",
                    'items' => [
                        ['label' => Yii::t('app', 'Users'), 'url' => ['/user']],
                        ['label' => Yii::t('app', 'Restaurants'), 'url' => ['/restaurant']],
                        ['label' => Yii::t('app', 'Products'), 'url' => ['/product']],
                        ['label' => Yii::t('app', 'Reviews'), 'url' => ['/review']],
                        ['label' => Yii::t('app', 'Warehouses'), 'url' => ['/warehouse']],
                        ['label' => Yii::t('app', 'Orders'), 'url' => ['/order']]
                    ],
                    ],
                ['label' => Yii::t('app', 'Newsletter'), 'url' => ['/newsletter']],
                ['label' => Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/login/logout'],
                    'linkOptions' => ['data-method' => 'post']],
                //DASHBOARD
                //['label' => Yii::t('app', 'CRUD'), 'url' => ['/site/about']],

            ];
        } else if(Yii::$app->user->identity->user_role==3) {
            $menuItems = [
                ['label' => Yii::$app->user->identity->username,
                    'items' => [
                        ['label' => Yii::t('app','Dashboard'), 'url' => ['/login/logged']],
                        ['label' => Yii::t('app', 'Change user data'), 'url' => ['/account/change-data']],
                        ['label' => Yii::t('app', 'Change password'), 'url' => ['/account/change-password']],
                        ['label' => Yii::t('app', 'Delete account'), 'url' => ['/account/delete-account']]
                    ],
                ],
                ['label' => Yii::t('app', 'Find product'), 'url' => ['/site/search-products']],
                ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
                ['label' => "CRUD",
                    'items' => [
                        ['label' => Yii::t('app', 'Restaurants'), 'url' => ['/restaurant']],
                        ['label' => Yii::t('app', 'Products'), 'url' => ['/product']],
                        ['label' => Yii::t('app', 'Reviews'), 'url' => ['/review']],
                        ['label' => Yii::t('app', 'Warehouses'), 'url' => ['/warehouse']],
                        ['label' => Yii::t('app', 'Orders'), 'url' => ['/order']],
                    ],
                    ],
                ['label' => Yii::t('app', 'Newsletter'), 'url' => ['/newsletter']],
                ['label' => Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/login/logout'],
                    'linkOptions' => ['data-method' => 'post']],
            ];
        } else if (Yii::$app->user->identity->user_role == 2) {
                $menuItems = [
                    ['label' => Yii::$app->user->identity->username,
                        'items' => [
                            ['label' => Yii::t('app','Dashboard'), 'url' => ['/login/logged']],
                            ['label' => Yii::t('app', 'Change user data'), 'url' => ['/account/change-data']],
                            ['label' => Yii::t('app', 'Change password'), 'url' => ['/account/change-password']],
                            ['label' => Yii::t('app', 'Delete account'), 'url' => ['/account/delete-account']]
                        ],
                    ],
                    ['label' => Yii::t('app', 'Find product'), 'url' => ['/site/search-products']],
                    ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
                    ['label' => "CRUD",
                        'items' => [
                            ['label' => Yii::t('app', 'Reviews'), 'url' => ['/review']],
                            ['label' => Yii::t('app', 'Warehouses'), 'url' => ['/warehouse']],
                            ['label' => Yii::t('app', 'Orders'), 'url' => ['/order']]
                        ],
                        ],
                    ['label' => Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                        'url' => ['/login/logout'],
                        'linkOptions' => ['data-method' => 'post']],
                ];
        } else if(Yii::$app->user->identity->user_role == 1) {
            $menuItems = [
                ['label' => Yii::$app->user->identity->username,
                    'items' => [
                        ['label' => Yii::t('app','Dashboard'), 'url' => ['/login/logged']],
                        ['label' => Yii::t('app', 'Change user data'), 'url' => ['/account/change-data']],
                        ['label' => Yii::t('app', 'Change password'), 'url' => ['/account/change-password']],
                        ['label' => Yii::t('app', 'Delete account'), 'url' => ['/account/delete-account']]
                    ],
                ],
                ['label' => Yii::t('app', 'Find product'), 'url' => ['/site/search-products']],
                ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
                ['label' => Yii::t('app', 'Orders'), 'url' => ['/order']],
                ['label' => Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/login/logout'],
                    'linkOptions' => ['data-method' => 'post']],
            ];
        } else {
            $menuItems = [
                ['label' => Yii::$app->user->identity->username,
                    'items' => [
                        ['label' => Yii::t('app','Dashboard'), 'url' => ['/login/logged']],
                        ['label' => Yii::t('app', 'Change user data'), 'url' => ['/account/change-data']],
                        ['label' => Yii::t('app', 'Change password'), 'url' => ['/account/change-password']],
                        ['label' => Yii::t('app', 'Delete account'), 'url' => ['/account/delete-account']]
                    ],
                ],
                ['label' => Yii::t('app', 'Find product'), 'url' => ['/site/search-products']],
                ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
                ['label' => Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/login/logout'],
                    'linkOptions' => ['data-method' => 'post']],
            ];
        }
            echo Nav::widget([
                'items' => $menuItems,
                'options' => ['class' => 'navbar-nav navbar-right'],
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?= Yii::t('app', 'My Company')?> <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
