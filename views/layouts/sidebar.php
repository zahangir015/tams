<?php

use hail812\adminlte\widgets\Menu;

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?=$assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?=$assetDir?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo Menu::widget([
                'items' => [
                    ['label' => 'USER MANAGEMENT', 'header' => true],
                    ['label' => 'Users',  'icon' => 'users', 'url' => ['/admin/user']],
                    ['label' => 'Create User', 'icon' => 'user-plus', 'url' => ['/admin/user/create']],
                    ['label' => 'AUTHORIZATION MANAGEMENT', 'header' => true],
                    ['label' => 'Roles',  'icon' => 'user-tie', 'url' => ['/admin/role']],
                    ['label' => 'Rules', 'icon' => 'truck', 'url' => ['/admin/rule']],
                    ['label' => 'Routes',  'icon' => 'list-alt', 'url' => ['/admin/route']],
                    ['label' => 'Permission', 'icon' => 'key', 'url' => ['/admin/permission']],
                    ['label' => 'Assignment',  'icon' => 'check-double', 'url' => ['/admin/assignment']],
                    ['label' => 'SALES SETTINGS', 'header' => true],
                    [
                        'label' => 'Supplier Settings',
                        'icon' => 'truck',
                        'badge' => '<span class="right badge badge-info">4</span>',
                        'items' => [
                            ['label' => 'Supplier Category Add',  'icon' => 'list-alt', 'url' => ['/sale/supplier-category/create']],
                            ['label' => 'Category List',  'icon' => 'list-alt', 'url' => ['/sale/supplier-category/index']],
                            ['label' => 'Supplier Add', 'icon' => 'truck', 'url' => ['/sale/supplier/create']],
                            ['label' => 'Supplier List', 'icon' => 'truck', 'url' => ['/sale/supplier/index']],
                        ]
                    ],
                    [
                        'label' => 'Airline Settings',
                        'icon' => 'paper-plane',
                        'badge' => '<span class="right badge badge-info">3</span>',
                        'items' => [
                            ['label' => 'Airline Add', 'icon' => 'paper-plane', 'url' => ['/sale/airline/create']],
                            ['label' => 'Airline List', 'icon' => 'paper-plane', 'url' => ['/sale/airline/index']],
                            ['label' => 'Airline History', 'icon' => 'list', 'url' => ['/sale/airline-history/index']],
                        ]
                    ],
                    ['label' => 'Customers', 'icon' => 'users', 'url' => ['/sale/customer/index']],
                    ['label' => 'Providers', 'icon' => 'paper-plane', 'url' => ['/sale/provider/index']],
                    [
                        'label' => 'Flight Management',
                        'icon' => 'plane',
                        'badge' => '<span class="right badge badge-info">5</span>',
                        'items' => [
                            ['label' => 'Create Ticket ',  'icon' => 'plus', 'url' => ['/sale/ticket/create']],
                            ['label' => 'Upload Ticket',  'icon' => 'upload', 'url' => ['/sale/ticket/upload']],
                            ['label' => 'Ticket List', 'icon' => 'list', 'url' => ['/sale/ticket/index']],
                            ['label' => 'Refund List', 'icon' => 'minus', 'url' => ['/sale/ticket/refund-list']],
                            ['label' => 'Void List', 'icon' => 'circle', 'url' => ['/sale/ticket/void-list']],
                        ]
                    ],
                    ['label' => 'ACCOUNTS', 'header' => true],
                    ['label' => 'Bank Accounts', 'icon' => 'building', 'url' => ['/account/bank-account']],
                    ['label' => 'Invoice', 'icon' => 'building', 'url' => ['/account/invoice/index']],
                    ['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],
                    ['label' => 'Yii2 PROVIDED', 'header' => true],
                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
                    ['label' => 'MULTI LEVEL EXAMPLE', 'header' => true],
                    ['label' => 'Level1'],
                    [
                        'label' => 'Level1',
                        'items' => [
                            ['label' => 'Level2', 'iconStyle' => 'far'],
                            [
                                'label' => 'Level2',
                                'iconStyle' => 'far',
                                'items' => [
                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
                                ]
                            ],
                            ['label' => 'Level2', 'iconStyle' => 'far']
                        ]
                    ],
                    ['label' => 'Level1'],
                    ['label' => 'LABELS', 'header' => true],
                    ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
                    ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
                    ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>