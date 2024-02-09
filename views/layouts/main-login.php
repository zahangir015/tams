<?php

/* @var $this \yii\web\View */

/* @var $content string */

use hail812\adminlte\widgets\Alert;
use yii\helpers\Url;

\hail812\adminlte3\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700');
$this->registerCssFile('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css');
\hail812\adminlte3\assets\PluginAsset::register($this)->add(['fontawesome', 'icheck-bootstrap']);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MYTRAMS | <?= $this->title ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <?php $this->head() ?>
        <style>
            .divider:after,
            .divider:before {
                content: "";
                flex: 1;
                height: 1px;
                background: #eee;
            }

            .h-custom {
                height: calc(100% - 73px);
            }

            @media (max-width: 450px) {
                .h-custom {
                    height: 100%;
                }
            }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <section class="vh-100" style="background-color: #D6EADF">
        <div class="container-fluid h-custom">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <div class="text-center">
                        <img class="align-center mt-1" src="<?= Url::to('/uploads/logo2.svg') ?>" class="img-fluid"
                             alt="MyTrams">
                        <h2 class="text-dark">Travel Agency Management System</h2>
                        <p>Now manage all your travel & tourism-based business operations - starting from HR, Accounts to salesforce tracking, etc. - through MyTrams.</p>
                        <h1 style="color: #337abe;">mytrams.com</h1>
                        <p style="letter-spacing: 14px; margin-left: 10px;">Easy&Secure</p>
                        <img src="<?= Url::to('/uploads/login_banner.svg') ?>" class="img-fluid" alt="Travel Agency">
                    </div>
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <?php
                    $flashMessages = Yii::$app->session->getAllFlashes();
                    if ($flashMessages) {
                        foreach ($flashMessages as $key => $message) {
                            if ($key == 'error') {
                                echo Alert::widget([
                                    'type' => 'danger',
                                    'body' => "<p>$message</p>",
                                ]);
                            } else {
                                echo Alert::widget([
                                    'type' => $key,
                                    'body' => "<p>$message</p>",
                                ]);
                            }

                        }
                    }
                    ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5">
            <div class="mb-3 mb-md-0" style="color: #337abe;">
                Copyright © <?= date('Y') ?>. All rights reserved.
            </div>
        </div>
    </section>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
