<?php

use app\models\Company;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<style>
    .wc{
        color: white;
    }
    
    .wc:hover{
        color: white;
    }
</style>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand font-weight-bold" style="background-color: #337abe;">
    <!-- Left navbar links -->
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link wc" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= Url::home() ?>" class="nav-link wc">Home</a>
        </li>
    </ul>

    <div class="h-30 d-flex align-items-center justify-content-center">
        <?php
        $company = null;
        if (isset(Yii::$app->user->identity->agencyId)) {
            $company = Company::find()->select(['name', 'logo'])->where(['agencyId' => Yii::$app->user->identity->agencyId])->one();
        }

        if ($company && $company->logo) {
            ?>
            <div class="image float-left">
                <img src="<?= Url::to('/uploads/company/' . $company->logo) ?>" class="elevation-2"
                     alt="<?= $company->name ?>" width="235" height="75">
            </div>
            <?php
        }
        ?>
    </div>
    <ul class="navbar-nav ml-auto">
        <!--<li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>-->
        <li class="nav-item">
            <a class="nav-link wc" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <div class="user-panel d-flex mb-5">
                    <div class="image">
                        <img src="/uploads/avatar.png" class="img-circle elevation-2"
                             alt="User Image">
                    </div>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header"><?= isset(Yii::$app->user->identity->employee) ? (Yii::$app->user->identity->employee->firstName . ' ' . Yii::$app->user->identity->employee->lastName) : Yii::$app->user->identity->username ?></span>
                <div class="dropdown-divider"></div>
                <a href="/admin/user/change-password" class="dropdown-item">
                    <i class="fas fa-asterisk mr-2"></i> Change Password
                </a>
                <div class="dropdown-divider"></div>
                <?= Html::a('<i class="fas fa-sign-out-alt"></i> Logout', ['/admin/user/logout'], ['data-method' => 'post', 'class' => 'dropdown-item']) ?>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
