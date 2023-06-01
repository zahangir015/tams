<?php

use hail812\adminlte\widgets\Menu;
use yii\helpers\Url;
use app\modules\admin\components\Helper;
//dd((Helper::checkRoute('/country/') || Helper::checkRoute('/city/') || Helper::checkRoute('/company/')), false);
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Url::home() ?>" class="brand-link">
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
                    [
                        'label' => 'Agency Management',
                        'icon' => 'sitemap',
                        'items' => [
                            [
                                'label' => 'Plan Management',
                                'iconStyle' => 'far',
                                'items' => [
                                    ['label' => 'Plan List', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/agent/plan/index'], 'visible' => Helper::checkRoute('/agent/plan/index')],
                                    ['label' => 'Plan Add', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/agent/plan/create'], 'visible' => Helper::checkRoute('/agent/plan/create')],
                                ]
                            ],
                            [
                                'label' => 'Agency Management',
                                'iconStyle' => 'far',
                                'items' => [
                                    ['label' => 'Agency List', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/agent/agency/index'], 'visible' => Helper::checkRoute('/agent/agency/index')],
                                    ['label' => 'Agency Add', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/agent/agency/create'], 'visible' => Helper::checkRoute('/agent/agency/create')],
                                ]
                            ],
                        ]
                    ],
                    [
                        'label' => 'General Settings',
                        'icon' => 'cogs',
                        'items' => [
                            ['label' => 'Countries', 'icon' => 'globe', 'url' => ['/country'], 'visible' => Helper::checkRoute('/country/')],
                            ['label' => 'Cities', 'icon' => 'globe', 'url' => ['/city'], 'visible' => Helper::checkRoute('/city/')],
                            ['label' => 'Company', 'icon' => 'building', 'url' => ['/company'], 'visible' => Helper::checkRoute('/company/')],
                        ],
                        'visible' => Helper::checkRoute('/country/') || Helper::checkRoute('/city/') || Helper::checkRoute('/company/')
                    ],
                    [
                        'label' => 'User Management',
                        'icon' => 'users-cog',
                        'items' => [
                            ['label' => 'Users', 'icon' => 'users', 'url' => ['/admin/user/index'], 'visible' => Helper::checkRoute('admin/user/index')],
                            ['label' => 'Create User', 'icon' => 'user-plus', 'url' => ['/admin/user/create'], 'visible' => Helper::checkRoute('admin/user/create')],
                        ]
                    ],
                    [
                        'label' => 'Authorization',
                        'icon' => 'users-cog',
                        'items' => [
                            ['label' => 'Roles', 'icon' => 'user-tie', 'url' => ['/admin/role'], 'visible' => Helper::checkRoute('/admin/role')],
                            ['label' => 'Rules', 'icon' => 'truck', 'url' => ['/admin/rule'], 'visible' => Helper::checkRoute('/admin/rule')],
                            ['label' => 'Routes', 'icon' => 'list-alt', 'url' => ['/admin/route'], 'visible' => Helper::checkRoute('/admin/route')],
                            ['label' => 'Permission', 'icon' => 'key', 'url' => ['/admin/permission'], 'visible' => Helper::checkRoute('/admin/permission')],
                            ['label' => 'Assignment', 'icon' => 'check-double', 'url' => ['/admin/assignment'], 'visible' => Helper::checkRoute('/admin/assignment')],
                        ]
                    ],
                    [
                        'label' => 'HRM',
                        'icon' => 'users',
                        'items' => [
                            [
                                'label' => 'Branch Settings',
                                'icon' => 'sitemap',
                                'items' => [
                                    ['label' => 'Branch Add', 'icon' => 'plus', 'url' => ['/hrm/branch/create'], 'visible' => Helper::checkRoute('/hrm/branch/create')],
                                    ['label' => 'Branch List', 'icon' => 'list', 'url' => ['/hrm/branch/index'], 'visible' => Helper::checkRoute('/hrm/branch/')],
                                ],
                            ],
                            [
                                'label' => 'Department',
                                'icon' => 'building',
                                'items' => [
                                    ['label' => 'Department Add', 'icon' => 'plus', 'url' => ['/hrm/department/create'], 'visible' => Helper::checkRoute('/hrm/department/create')],
                                    ['label' => 'Department List', 'icon' => 'list', 'url' => ['/hrm/department/index'], 'visible' => Helper::checkRoute('/hrm/department/')],
                                ],
                            ],
                            [
                                'label' => 'Designation',
                                'icon' => 'user-tie',
                                'items' => [
                                    ['label' => 'Designation Add', 'icon' => 'plus', 'url' => ['/hrm/designation/create'], 'visible' => Helper::checkRoute('/hrm/designation/create')],
                                    ['label' => 'Designation List', 'icon' => 'list', 'url' => ['/hrm/designation/index'], 'visible' => Helper::checkRoute('/hrm/designation/')],
                                ]
                            ],
                            [
                                'label' => 'Employee Manage',
                                'icon' => 'users',
                                'items' => [
                                    ['label' => 'Employee Add', 'icon' => 'plus', 'url' => ['/hrm/employee/create'], 'visible' => Helper::checkRoute('/hrm/employee/create')],
                                    ['label' => 'Employee List', 'icon' => 'list', 'url' => ['/hrm/employee/index'], 'visible' => Helper::checkRoute('/hrm/employee/')],
                                ]
                            ],
                            [
                                'label' => 'Weekend & Holidays',
                                'icon' => 'suitcase',
                                'items' => [
                                    ['label' => 'Weekend', 'icon' => 'calendar-day', 'url' => ['/hrm/weekend/index'], 'visible' => Helper::checkRoute('/hrm/weekend/')],
                                    ['label' => 'Public Holiday', 'icon' => 'calendar-times', 'url' => ['/hrm/public-holiday/index'], 'visible' => Helper::checkRoute('/hrm/weekend/')],
                                ]
                            ],
                            [
                                'label' => 'Shift & Roster',
                                'icon' => 'suitcase',
                                'items' => [
                                    ['label' => 'Shift List', 'icon' => 'calendar-plus', 'url' => ['/hrm/shift/index'], 'visible' => Helper::checkRoute('/hrm/shift/')],
                                    ['label' => 'Department Shifts', 'icon' => 'calendar-check', 'url' => ['/hrm/department-shift/index'], 'visible' => Helper::checkRoute('/hrm/department-shift/')],
                                    ['label' => 'Employee Shifts', 'icon' => 'calendar-check', 'url' => ['/hrm/employee-shift/index'], 'visible' => Helper::checkRoute('/hrm/employee-shift/')],
                                    ['label' => 'Roster List', 'icon' => 'calendar', 'url' => ['/hrm/roster/index'], 'visible' => Helper::checkRoute('/hrm/roster/')],
                                ]
                            ],
                            [
                                'label' => 'Leave Settings',
                                'icon' => 'cogs',
                                'items' => [
                                    ['label' => 'Leave Type', 'icon' => 'calendar-plus', 'url' => ['/hrm/leave-type/index'], 'visible' => Helper::checkRoute('/hrm/leave-type/')],
                                    ['label' => 'Yearly Allocation', 'icon' => 'calendar-check', 'url' => ['/hrm/yearly-leave-allocation/index'], 'visible' => Helper::checkRoute('/hrm/yearly-leave-allocation/')],
                                    ['label' => 'Employee Allocation', 'icon' => 'calendar-check', 'url' => ['/hrm/employee-leave-allocation/index'], 'visible' => Helper::checkRoute('/hrm/employee-leave-allocation/')],
                                    ['label' => 'Approval Policy', 'icon' => 'calendar', 'url' => ['/hrm/leave-approval-policy/index'], 'visible' => Helper::checkRoute('/hrm/leave-approval-policy/')],
                                ]
                            ],
                            [
                                'label' => 'Leave Management',
                                'icon' => 'cogs',
                                'items' => [
                                    ['label' => 'Leave Applications', 'icon' => 'calendar-plus', 'url' => ['/hrm/leave-application/index'], 'visible' => Helper::checkRoute('/hrm/leave-application/')],
                                    ['label' => 'Applied Leaves', 'icon' => 'calendar-plus', 'url' => ['/hrm/leave-application/applied-leaves'], 'visible' => Helper::checkRoute('/hrm/leave-application/applied-leaves')],
                                    ['label' => 'Approval History', 'icon' => 'calendar-plus', 'url' => ['/hrm/leave-application/approval-history'], 'visible' => Helper::checkRoute('/hrm/leave-application/approval-history')],
                                    ['label' => 'Apply', 'icon' => 'calendar-check', 'url' => ['/hrm/leave-application/apply'], 'visible' => Helper::checkRoute('/hrm/leave-application/apply')],
                                ]
                            ],
                            [
                                'label' => 'Attendance Management',
                                'icon' => 'users',
                                'items' => [
                                    ['label' => 'All Attendance', 'icon' => 'calendar-plus', 'url' => ['/hrm/attendance/index'], 'visible' => Helper::checkRoute('/hrm/attendance/index')],
                                    ['label' => 'Attendance', 'icon' => 'calendar-plus', 'url' => ['/hrm/attendance/individual-attendance'], 'visible' => Helper::checkRoute('/hrm/attendance/individual-attendance')],
                                ]
                            ],
                            [
                                'label' => 'Payroll',
                                'icon' => 'users',
                                'items' => [
                                    ['label' => 'Payroll Types', 'icon' => 'calendar-plus', 'url' => ['/hrm/payroll-type/index'], 'visible' => Helper::checkRoute('/hrm/payroll-type/')],
                                    ['label' => 'Employee Payroll', 'icon' => 'calendar-plus', 'url' => ['/hrm/employee-payroll/index'], 'visible' => Helper::checkRoute('/hrm/employee-payroll/')],
                                    ['label' => 'Payslip', 'icon' => 'calendar-plus', 'url' => ['/hrm/payslip/index'], 'visible' => Helper::checkRoute('/hrm/payslip/')],
                                ]
                            ],
                        ]
                    ],
                    [
                        'label' => 'Sales Management',
                        'icon' => 'truck',
                        'items' => [
                            ['label' => 'Providers', 'icon' => 'paper-plane', 'url' => ['/sale/provider/index'], 'visible' => Helper::checkRoute('/sale/provider/')],
                            [
                                'label' => 'Supplier Settings',
                                'icon' => 'truck',
                                'items' => [
                                    ['label' => 'Supplier Category Add', 'icon' => 'plus', 'url' => ['/sale/supplier-category/create'], 'visible' => Helper::checkRoute('/sale/supplier-category/create')],
                                    ['label' => 'Category List', 'icon' => 'list-alt', 'url' => ['/sale/supplier-category/index'], 'visible' => Helper::checkRoute('/sale/supplier-category/')],
                                    ['label' => 'Supplier Add', 'icon' => 'plus', 'url' => ['/sale/supplier/create'], 'visible' => Helper::checkRoute('/sale/supplier/create')],
                                    ['label' => 'Supplier List', 'icon' => 'truck', 'url' => ['/sale/supplier/index'], 'visible' => Helper::checkRoute('/sale/supplier/')],
                                ]
                            ],
                            [
                                'label' => 'Airline Settings',
                                'icon' => 'paper-plane',
                                'items' => [
                                    ['label' => 'Airline Add', 'icon' => 'paper-plane', 'url' => ['/sale/airline/create'], 'visible' => Helper::checkRoute('/sale/airline/create')],
                                    ['label' => 'Airline List', 'icon' => 'paper-plane', 'url' => ['/sale/airline/index'], 'visible' => Helper::checkRoute('/sale/airline/')],
                                ]
                            ],
                            [
                                'label' => 'Customer Settings',
                                'icon' => 'users',
                                'items' => [
                                    ['label' => 'Star Category Add', 'icon' => 'star', 'url' => ['/sale/star-category/create'], 'visible' => Helper::checkRoute('/sale/star-category/create')],
                                    ['label' => 'Star Categories', 'icon' => 'list-alt', 'url' => ['/sale/star-category/index'], 'visible' => Helper::checkRoute('/sale/star-category/')],
                                    ['label' => 'Customer Add', 'icon' => 'user-plus', 'url' => ['/sale/customer/create'], 'visible' => Helper::checkRoute('/sale/customer/create')],
                                    ['label' => 'Customer List', 'icon' => 'list', 'url' => ['/sale/customer/index'], 'visible' => Helper::checkRoute('/sale/customer/')],
                                ]
                            ],

                            [
                                'label' => 'Flight Management',
                                'icon' => 'plane',
                                'items' => [
                                    ['label' => 'Add Ticket', 'icon' => 'plus-circle', 'url' => ['/sale/ticket/create'], 'visible' => Helper::checkRoute('/sale/ticket/create')],
                                    ['label' => 'Upload Ticket', 'icon' => 'upload', 'url' => ['/sale/ticket/upload'], 'visible' => Helper::checkRoute('/sale/ticket/upload')],
                                    ['label' => 'Ticket List', 'icon' => 'list', 'url' => ['/sale/ticket/index'], 'visible' => Helper::checkRoute('/sale/ticket/')],
                                    ['label' => 'Ticket Supplier List', 'icon' => 'list', 'url' => ['/sale/ticket/ticket-supplier-list'], 'visible' => Helper::checkRoute('/sale/ticket/ticket-supplier-list')],
                                    ['label' => 'Refund List', 'icon' => 'minus-circle', 'url' => ['/sale/ticket/refund-list'], 'visible' => Helper::checkRoute('/sale/ticket/refund-list')],
                                    ['label' => 'Void List', 'icon' => 'circle', 'url' => ['/sale/ticket/void-list'], 'visible' => Helper::checkRoute('/sale/ticket/void-list')],
                                    ['label' => 'Flight Proposal', 'icon' => 'circle', 'url' => ['/sale/flight-proposal/index'], 'visible' => Helper::checkRoute('/sale/flight-proposal/')],
                                ]
                            ],
                            [
                                'label' => 'Holiday Management',
                                'icon' => 'suitcase',
                                'items' => [
                                    ['label' => 'Category List', 'icon' => 'list', 'url' => ['/sale/holiday-category/index'], 'visible' => Helper::checkRoute('/sale/holiday-category/')],
                                    ['label' => 'Add Holiday', 'icon' => 'plus-circle', 'url' => ['/sale/holiday/create'], 'visible' => Helper::checkRoute('/sale/holiday/create')],
                                    ['label' => 'Holiday List', 'icon' => 'list', 'url' => ['/sale/holiday/index'], 'visible' => Helper::checkRoute('/sale/holiday/')],
                                    ['label' => 'Holiday Supplier List', 'icon' => 'list', 'url' => ['/sale/holiday/holiday-supplier-list'], 'visible' => Helper::checkRoute('/sale/holiday/holiday-supplier-list')],
                                    ['label' => 'Refund List', 'icon' => 'minus-circle', 'url' => ['/sale/holiday/refund-list'], 'visible' => Helper::checkRoute('/sale/holiday/refund-list')],
                                ]
                            ],
                            [
                                'label' => 'Hotel Management',
                                'icon' => 'hotel',
                                'items' => [
                                    ['label' => 'Add Hotel', 'icon' => 'plus-circle', 'url' => ['/sale/hotel/create'], 'visible' => Helper::checkRoute('/sale/hotel/create')],
                                    ['label' => 'Hotel List', 'icon' => 'list', 'url' => ['/sale/hotel/index'], 'visible' => Helper::checkRoute('/sale/hotel/')],
                                    ['label' => 'Hotel Supplier List', 'icon' => 'list', 'url' => ['/sale/hotel/hotel-supplier-list'], 'visible' => Helper::checkRoute('/sale/hotel/hotel-supplier-list')],
                                    ['label' => 'Refund List', 'icon' => 'minus-circle', 'url' => ['/sale/hotel/refund-list'], 'visible' => Helper::checkRoute('/sale/hotel/refund-list')],
                                ]
                            ],
                            [
                                'label' => 'Visa Management',
                                'icon' => 'passport',
                                'items' => [
                                    ['label' => 'Add Visa', 'icon' => 'plus-circle', 'url' => ['/sale/visa/create'], 'visible' => Helper::checkRoute('/sale/visa/create')],
                                    ['label' => 'Visa List', 'icon' => 'list', 'url' => ['/sale/visa/index'], 'visible' => Helper::checkRoute('/sale/visa/')],
                                    ['label' => 'Visa Supplier List', 'icon' => 'list', 'url' => ['/sale/visa/visa-supplier-list'], 'visible' => Helper::checkRoute('/sale/visa/visa-supplier-list')],
                                    ['label' => 'Refund List', 'icon' => 'minus-circle', 'url' => ['/sale/visa/refund-list'], 'visible' => Helper::checkRoute('/sale/visa/refund-list')],
                                ]
                            ],
                        ]
                    ],
                    [
                        'label' => 'Accounts Management',
                        'icon' => 'file-invoice-dollar',
                        'items' => [
                            ['label' => 'Bank Accounts', 'icon' => 'building', 'url' => ['/account/bank-account'], 'visible' => Helper::checkRoute('/account/bank-account/')],
                            [
                                'label' => 'Invoice Management',
                                'icon' => 'file-invoice-dollar',
                                'items' => [
                                    ['label' => 'Create Invoice', 'icon' => 'plus-circle', 'url' => ['/account/invoice/create'], 'visible' => Helper::checkRoute('/account/invoice/create')],
                                    ['label' => 'Invoice List', 'icon' => 'list', 'url' => ['/account/invoice/index'], 'visible' => Helper::checkRoute('/account/invoice/')],
                                ]
                            ],
                            ['label' => 'Bill', 'icon' => 'money-bill', 'url' => ['/account/bill/index'], 'visible' => Helper::checkRoute('/account/bill/')],
                            ['label' => 'Refund Transactions', 'icon' => 'money-bill', 'url' => ['/account/refund-transaction/index'], 'visible' => Helper::checkRoute('/account/refund-transaction/')],
                            ['label' => 'Ledger', 'icon' => 'book', 'url' => ['/account/ledger/index'], 'visible' => Helper::checkRoute('/account/ledger/')],
                            ['label' => 'Payment Timeline', 'icon' => 'list', 'url' => ['/account/service-payment-timeline/index'], 'visible' => Helper::checkRoute('/account/service-payment-timeline/')],
                            [
                                'label' => 'Journal Management',
                                'icon' => 'file-invoice-dollar',
                                'items' => [
                                    ['label' => 'Account Type', 'icon' => 'list', 'url' => ['/account/account-type/index'], 'visible' => Helper::checkRoute('/account/account-type/')],
                                    ['label' => 'Account Group', 'icon' => 'list', 'url' => ['/account/account-group/index'], 'visible' => Helper::checkRoute('/account/account-group/')],
                                    ['label' => 'Chart Of Account', 'icon' => 'list', 'url' => ['/account/chart-of-account/index'], 'visible' => Helper::checkRoute('/account/chart-of-account/')],
                                    ['label' => 'Journal Entry', 'icon' => 'list', 'url' => ['/account/journal/index'], 'visible' => Helper::checkRoute('/account/journal/')],
                                ]
                            ],
                            [
                                'label' => 'Expense Management',
                                'icon' => 'file-invoice-dollar',
                                'items' => [
                                    ['label' => 'Categories', 'icon' => 'list', 'url' => ['/account/expense-category/index'], 'visible' => Helper::checkRoute('/account/expense-category/index')],
                                    ['label' => 'Sub Categories', 'icon' => 'list', 'url' => ['/account/expense-sub-category/index'], 'visible' => Helper::checkRoute('/account/expense-sub-category/')],
                                    ['label' => 'Expenses', 'icon' => 'list', 'url' => ['/account/expense/index'], 'visible' => Helper::checkRoute('/account/expense/')],
                                ]
                            ],
                        ]
                    ],
                    /*['label' => 'Yii2 PROVIDED', 'header' => true],
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
                    ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],*/
                ],
            ]);?>
        </nav>
    </div>
</aside>