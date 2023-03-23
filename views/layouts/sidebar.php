<?php

use hail812\adminlte\widgets\Menu;
use yii\helpers\Url;
use app\modules\admin\components\Helper;
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">TRAMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="<?= Url::home() ?>" class="nav-link">Home</a>
            </div>
        </div>
        <nav class="mt-2">
            <?= Menu::widget([
                'items' => [
                    ['label' => 'GENERAL SETTINGS', 'header' => true],
                    ['label' => 'Countries', 'icon' => 'globe', 'url' => ['/country'], 'visible' => Helper::checkRoute('country')],
                    ['label' => 'Cities', 'icon' => 'globe', 'url' => ['/city'], 'visible' => Helper::checkRoute('city')],
                    ['label' => 'Company', 'icon' => 'building', 'url' => ['/company'], 'visible' => Helper::checkRoute('company')],
                    ['label' => 'USER MANAGEMENT', 'header' => true],
                    ['label' => 'Users', 'icon' => 'users', 'url' => ['/admin/user/index'], 'visible' => Helper::checkRoute('admin/user/index')],
                    ['label' => 'Create User', 'icon' => 'user-plus', 'url' => ['/admin/user/create'], 'visible' => Helper::checkRoute('admin/user/create')],
                    ['label' => 'AUTHORIZATION MANAGEMENT', 'header' => true],
                    ['label' => 'Roles', 'icon' => 'user-tie', 'url' => ['/admin/role']],
                    ['label' => 'Rules', 'icon' => 'truck', 'url' => ['/admin/rule']],
                    ['label' => 'Routes', 'icon' => 'list-alt', 'url' => ['/admin/route']],
                    ['label' => 'Permission', 'icon' => 'key', 'url' => ['/admin/permission']],
                    ['label' => 'Assignment', 'icon' => 'check-double', 'url' => ['/admin/assignment']],
                    ['label' => 'HRM', 'header' => true],
                    [
                        'label' => 'Branch Settings',
                        'icon' => 'sitemap',
                        'items' => [
                            ['label' => 'Branch Add', 'icon' => 'plus', 'url' => ['/hrm/branch/create']],
                            ['label' => 'Branch List', 'icon' => 'list', 'url' => ['/hrm/branch/index']],
                        ]
                    ],
                    [
                        'label' => 'Department',
                        'icon' => 'building',
                        'items' => [
                            ['label' => 'Department Add', 'icon' => 'plus', 'url' => ['/hrm/department/create']],
                            ['label' => 'Department List', 'icon' => 'list', 'url' => ['/hrm/department/index']],
                        ]
                    ],
                    [
                        'label' => 'Designation',
                        'icon' => 'user-tie',
                        'items' => [
                            ['label' => 'Designation Add', 'icon' => 'plus', 'url' => ['/hrm/designation/create']],
                            ['label' => 'Designation List', 'icon' => 'list', 'url' => ['/hrm/designation/index']],
                        ]
                    ],
                    [
                        'label' => 'Employee Manage',
                        'icon' => 'users',
                        'items' => [
                            ['label' => 'Employee Add', 'icon' => 'plus', 'url' => ['/hrm/employee/create']],
                            ['label' => 'Employee List', 'icon' => 'list', 'url' => ['/hrm/employee/index']],
                        ]
                    ],

                    [
                        'label' => 'Weekend & Holidays',
                        'icon' => 'suitcase',
                        'items' => [
                            ['label' => 'Weekend', 'icon' => 'calendar-day', 'url' => ['/hrm/weekend/index']],
                            ['label' => 'Public Holiday', 'icon' => 'calendar-times', 'url' => ['/hrm/public-holiday/index']],
                        ]
                    ],
                    [
                        'label' => 'Shift & Roster',
                        'icon' => 'suitcase',
                        'items' => [
                            ['label' => 'Shift List', 'icon' => 'calendar-plus', 'url' => ['/hrm/shift/index']],
                            ['label' => 'Department Shifts', 'icon' => 'calendar-check', 'url' => ['/hrm/department-shift/index']],
                            ['label' => 'Employee Shifts', 'icon' => 'calendar-check', 'url' => ['/hrm/employee-shift/index']],
                            ['label' => 'Roster List', 'icon' => 'calendar', 'url' => ['/hrm/roster/index']],
                        ]
                    ],
                    [
                        'label' => 'Leave Settings',
                        'icon' => 'cogs',
                        'items' => [
                            ['label' => 'Leave Type', 'icon' => 'calendar-plus', 'url' => ['/hrm/leave-type/index']],
                            ['label' => 'Yearly Allocation', 'icon' => 'calendar-check', 'url' => ['/hrm/yearly-leave-allocation/index']],
                            ['label' => 'Employee Allocation', 'icon' => 'calendar-check', 'url' => ['/hrm/employee-leave-allocation/index']],
                            ['label' => 'Approval Policy', 'icon' => 'calendar', 'url' => ['/hrm/leave-approval-policy/index']],
                        ]
                    ],
                    [
                        'label' => 'Leave Management',
                        'icon' => 'cogs',
                        'items' => [
                            ['label' => 'Leave Applications', 'icon' => 'calendar-plus', 'url' => ['/hrm/leave-application/index']],
                            ['label' => 'Applied Leaves', 'icon' => 'calendar-plus', 'url' => ['/hrm/leave-application/applied-leaves']],
                            ['label' => 'Approval History', 'icon' => 'calendar-plus', 'url' => ['/hrm/leave-application/approval-history']],
                            ['label' => 'Apply', 'icon' => 'calendar-check', 'url' => ['/hrm/leave-application/apply']],
                        ]
                    ],
                    [
                        'label' => 'Attendance Management',
                        'icon' => 'users',
                        'items' => [
                            ['label' => 'All Attendance', 'icon' => 'calendar-plus', 'url' => ['/hrm/attendance/index']],
                            ['label' => 'Attendance', 'icon' => 'calendar-plus', 'url' => ['/hrm/attendance/individual-attendance']],
                        ]
                    ],
                    [
                        'label' => 'Payroll',
                        'icon' => 'users',
                        'items' => [
                            ['label' => 'Payroll Types', 'icon' => 'calendar-plus', 'url' => ['/hrm/payroll-type/index']],
                            ['label' => 'Employee Payroll', 'icon' => 'calendar-plus', 'url' => ['/hrm/employee-payroll/index']],
                            ['label' => 'Payslip', 'icon' => 'calendar-plus', 'url' => ['/hrm/payslip/index']],
                        ]
                    ],
                    ['label' => 'SALES', 'header' => true],
                    ['label' => 'Providers', 'icon' => 'paper-plane', 'url' => ['/sale/provider/index']],
                    [
                        'label' => 'Supplier Settings',
                        'icon' => 'truck',
                        'items' => [
                            ['label' => 'Supplier Category Add', 'icon' => 'plus', 'url' => ['/sale/supplier-category/create']],
                            ['label' => 'Category List', 'icon' => 'list-alt', 'url' => ['/sale/supplier-category/index']],
                            ['label' => 'Supplier Add', 'icon' => 'plus', 'url' => ['/sale/supplier/create']],
                            ['label' => 'Supplier List', 'icon' => 'truck', 'url' => ['/sale/supplier/index']],
                        ]
                    ],
                    [
                        'label' => 'Airline Settings',
                        'icon' => 'paper-plane',
                        'items' => [
                            ['label' => 'Airline Add', 'icon' => 'paper-plane', 'url' => ['/sale/airline/create']],
                            ['label' => 'Airline List', 'icon' => 'paper-plane', 'url' => ['/sale/airline/index']],
                            //['label' => 'Airline History', 'icon' => 'list', 'url' => ['/sale/airline-history/index']],
                        ]
                    ],
                    [
                        'label' => 'Customer Settings',
                        'icon' => 'users',
                        'items' => [
                            ['label' => 'Star Category Add', 'icon' => 'star', 'url' => ['/sale/star-category/create']],
                            ['label' => 'Star Categories', 'icon' => 'list-alt', 'url' => ['/sale/star-category/index']],
                            ['label' => 'Customer Add', 'icon' => 'user-plus', 'url' => ['/sale/customer/create']],
                            ['label' => 'Customer List', 'icon' => 'list', 'url' => ['/sale/customer/index']],
                        ]
                    ],

                    [
                        'label' => 'Flight Management',
                        'icon' => 'plane',
                        'items' => [
                            ['label' => 'Add Ticket', 'icon' => 'plus-circle', 'url' => ['/sale/ticket/create']],
                            ['label' => 'Upload Ticket', 'icon' => 'upload', 'url' => ['/sale/ticket/upload']],
                            ['label' => 'Ticket List', 'icon' => 'list', 'url' => ['/sale/ticket/index']],
                            ['label' => 'Ticket Supplier List', 'icon' => 'list', 'url' => ['/sale/ticket/ticket-supplier-list']],
                            ['label' => 'Refund List', 'icon' => 'minus-circle', 'url' => ['/sale/ticket/refund-list']],
                            ['label' => 'Void List', 'icon' => 'circle', 'url' => ['/sale/ticket/void-list']],
                        ]
                    ],
                    [
                        'label' => 'Holiday Management',
                        'icon' => 'suitcase',
                        'items' => [
                            ['label' => 'Category List', 'icon' => 'list', 'url' => ['/sale/holiday-category/index']],
                            ['label' => 'Add Holiday', 'icon' => 'plus-circle', 'url' => ['/sale/holiday/create']],
                            ['label' => 'Holiday List', 'icon' => 'list', 'url' => ['/sale/holiday/index']],
                            ['label' => 'Holiday Supplier List', 'icon' => 'list', 'url' => ['/sale/holiday/holiday-supplier-list']],
                            ['label' => 'Refund List', 'icon' => 'minus-circle', 'url' => ['/sale/holiday/refund-list']],
                        ]
                    ],
                    [
                        'label' => 'Hotel Management',
                        'icon' => 'hotel',
                        'items' => [
                            ['label' => 'Add Hotel', 'icon' => 'plus-circle', 'url' => ['/sale/hotel/create']],
                            ['label' => 'Hotel List', 'icon' => 'list', 'url' => ['/sale/hotel/index']],
                            ['label' => 'Hotel Supplier List', 'icon' => 'list', 'url' => ['/sale/hotel/hotel-supplier-list']],
                            ['label' => 'Refund List', 'icon' => 'minus-circle', 'url' => ['/sale/hotel/refund-list']],
                        ]
                    ],
                    [
                        'label' => 'Visa Management',
                        'icon' => 'passport',
                        'items' => [
                            ['label' => 'Add Visa', 'icon' => 'plus-circle', 'url' => ['/sale/visa/create']],
                            ['label' => 'Visa List', 'icon' => 'list', 'url' => ['/sale/visa/index']],
                            ['label' => 'Visa Supplier List', 'icon' => 'list', 'url' => ['/sale/visa/visa-supplier-list']],
                            ['label' => 'Refund List', 'icon' => 'minus-circle', 'url' => ['/sale/visa/refund-list']],
                        ]
                    ],
                    ['label' => 'ACCOUNTS', 'header' => true],
                    ['label' => 'Bank Accounts', 'icon' => 'building', 'url' => ['/account/bank-account'], 'visible' => Helper::checkRoute('account/bank-account/*')],
                    [
                        'label' => 'Invoice Management',
                        'icon' => 'file-invoice-dollar',
                        'items' => [
                            ['label' => 'Create Invoice', 'icon' => 'plus-circle', 'url' => ['/account/invoice/create']],
                            ['label' => 'Invoice List', 'icon' => 'list', 'url' => ['/account/invoice/index']],
                        ]
                    ],
                    ['label' => 'Bill', 'icon' => 'money-bill', 'url' => ['/account/bill/index']],
                    ['label' => 'Refund Transactions', 'icon' => 'money-bill', 'url' => ['/account/refund-transaction/index']],
                    ['label' => 'Ledger', 'icon' => 'book', 'url' => ['/account/ledger/index']],
                    ['label' => 'Payment Timeline', 'icon' => 'list', 'url' => ['/account/service-payment-timeline/index']],
                    [
                        'label' => 'Journal Management',
                        'icon' => 'file-invoice-dollar',
                        'items' => [
                            ['label' => 'Account Type', 'icon' => 'list', 'url' => ['/account/account-type/index']],
                            ['label' => 'Account Group', 'icon' => 'list', 'url' => ['/account/account-group/index']],
                            ['label' => 'Chart Of Account', 'icon' => 'list', 'url' => ['/account/chart-of-account/index']],
                            ['label' => 'Journal Entry', 'icon' => 'list', 'url' => ['/account/journal/index']],
                        ]
                    ],
                    [
                        'label' => 'Expense Management',
                        'icon' => 'file-invoice-dollar',
                        'items' => [
                            ['label' => 'Categories', 'icon' => 'list', 'url' => ['/account/expense-category/index']],
                            ['label' => 'Sub Categories', 'icon' => 'list', 'url' => ['/account/expense-sub-category/index']],
                            ['label' => 'Expenses', 'icon' => 'list', 'url' => ['/account/expense/index']],
                        ]
                    ],
                    [
                        'label' => 'Simple Link', 'icon' => 'th',
                    ],
                    ['label' => 'Yii2 PROVIDED', 'header' => true],
                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Gii', 'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
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
    </div>
</aside>