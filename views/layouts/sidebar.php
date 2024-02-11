<style>
    .nav-sidebar .nav-item>.nav-link{
        color: white;
        background-color: #337abe;
    }

    .nav-sidebar .nav-item>.nav-link:hover{
        color: white;
    }
</style>
<?php

use hail812\adminlte\widgets\Menu;
use yii\helpers\Url;
use app\modules\admin\components\Helper;
$items = [
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
                    ['label' => 'Agency Request', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/agent/agency-account-request/index'], 'visible' => Helper::checkRoute('/agent/agency-account-request/index')],
                ]
            ],
        ],
        'options' => [
            'class' => 'nav-item has-treeview mb-2 rounded'
        ],
        'linkOptions' => ['class' => 'nav-link text-white'],
    ],
    [
        'label' => 'Support Management',
        'icon' => 'handshake',
        'items' => [
            [
                'label' => 'Inquiry Management',
                'iconStyle' => 'far',
                'items' => [
                    ['label' => 'Inquiry List', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/support/inquiry/index'], 'visible' => Helper::checkRoute('/support/inquiry/index')],
                    ['label' => 'Inquiry Add', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/support/inquiry/create'], 'visible' => Helper::checkRoute('/support/inquiry/create')],
                ]
            ],
        ],
        'options' => [
            'class' => 'nav-item has-treeview mb-2 rounded',
        ],
        'linkOptions' => ['class' => 'nav-link text-white'],
    ],
    [
        'label' => 'General Settings',
        'icon' => 'cogs',
        'items' => [
            ['label' => 'Countries', 'icon' => 'globe', 'url' => ['/country'], 'visible' => Helper::checkRoute('/country/')],
            ['label' => 'Cities', 'icon' => 'globe', 'url' => ['/city'], 'visible' => Helper::checkRoute('/city/')],
            //['label' => 'Company', 'icon' => 'building', 'url' => ['/company'], 'visible' => Helper::checkRoute('/company/')],
            ['label' => 'Company Profile', 'icon' => 'building', 'url' => ['/company/company-profile'], 'visible' => Helper::checkRoute('/company/view')],
            ['label' => 'Providers/GDS', 'icon' => 'paper-plane', 'url' => ['/sale/provider/index'], 'visible' => Helper::checkRoute('/sale/provider/')],
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
        ],
        'options' => [
            'class' => 'nav-item has-treeview mb-2 rounded',
        ],
        'linkOptions' => ['class' => 'nav-link text-white'],
        'visible' => Helper::checkRoute('/country/') || Helper::checkRoute('/city/') || Helper::checkRoute('/company/') || Helper::checkRoute('/company/view')
    ],
    [
        'label' => 'Authorization',
        'icon' => 'users-cog',
        'items' => [
            ['label' => 'Roles', 'icon' => 'user-tie', 'url' => ['/admin/role'], 'visible' => Helper::checkRoute('/admin/role/')],
            ['label' => 'Rules', 'icon' => 'truck', 'url' => ['/admin/rule'], 'visible' => Helper::checkRoute('/admin/rule/')],
            ['label' => 'Routes', 'icon' => 'list-alt', 'url' => ['/admin/route'], 'visible' => Helper::checkRoute('/admin/route/')],
            ['label' => 'Permission', 'icon' => 'key', 'url' => ['/admin/permission'], 'visible' => Helper::checkRoute('/admin/permission/')],
            ['label' => 'Assignment', 'icon' => 'check-double', 'url' => ['/admin/assignment'], 'visible' => Helper::checkRoute('/admin/assignment/')],
            [
                'label' => 'User Management',
                'icon' => 'users-cog',
                'items' => [
                    ['label' => 'Users', 'icon' => 'users', 'url' => ['/admin/user/index'], 'visible' => Helper::checkRoute('/admin/user/index')],
                    ['label' => 'Create User', 'icon' => 'user-plus', 'url' => ['/admin/user/create'], 'visible' => Helper::checkRoute('/admin/user/create')],
                ],
            ],
        ],
        'options' => [
            'class' => 'nav-item has-treeview mb-2 rounded',
        ],
        'linkOptions' => ['class' => 'nav-link text-white'],
        'visible' => Helper::checkRoute('/admin/')
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
                'label' => 'User Management',
                'icon' => 'users-cog',
                'items' => [
                    ['label' => 'Users', 'icon' => 'users', 'url' => ['/admin/user/index'], 'visible' => Helper::checkRoute('/admin/user/index')],
                    ['label' => 'Permission Assignment', 'icon' => 'check-double', 'url' => ['/admin/assignment/index'], 'visible' => Helper::checkRoute('/admin/assignment/index')],
                ],
            ],
            [
                'label' => 'Weekend & Holidays',
                'icon' => 'suitcase',
                'items' => [
                    ['label' => 'Weekend', 'icon' => 'calendar-day', 'url' => ['/hrm/weekend/index'], 'visible' => Helper::checkRoute('/hrm/weekend/')],
                    ['label' => 'Public Holiday', 'icon' => 'calendar-times', 'url' => ['/hrm/public-holiday/index'], 'visible' => Helper::checkRoute('/hrm/public-holiday/')],
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
        ],
        'options' => [
            'class' => 'nav-item has-treeview mb-2 rounded',
        ],
        'linkOptions' => ['class' => 'nav-link text-white'],
    ],
    [
        'label' => 'Sales Management',
        'icon' => 'truck',
        'items' => [
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
                    ['label' => 'Hotel Categories', 'icon' => 'list', 'url' => ['/sale/hotel-category/index'], 'visible' => Helper::checkRoute('/sale/hotel-category/')],
                    ['label' => 'Room Types', 'icon' => 'list', 'url' => ['/sale/room-type/index'], 'visible' => Helper::checkRoute('/sale/room-type/')],
                    ['label' => 'Add Hotel', 'icon' => 'plus-circle', 'url' => ['/sale/hotel/create'], 'visible' => Helper::checkRoute('/sale/hotel/create')],
                    ['label' => 'Hotel List', 'icon' => 'list', 'url' => ['/sale/hotel/index'], 'visible' => Helper::checkRoute('/sale/hotel/')],
                    ['label' => 'Hotel Supplier List', 'icon' => 'list', 'url' => ['/sale/hotel/hotel-supplier-list'], 'visible' => Helper::checkRoute('/sale/hotel/hotel-supplier-list')],
                    ['label' => 'Refund List', 'icon' => 'minus-circle', 'url' => ['/sale/hotel/refund-list'], 'visible' => Helper::checkRoute('/sale/hotel/refund-list')],
                    ['label' => 'Hotel Proposal', 'icon' => 'circle', 'url' => ['/sale/hotel-proposal/index'], 'visible' => Helper::checkRoute('/sale/hotel-proposal/')],
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
            [
                'label' => 'Sales Report',
                'icon' => 'passport',
                'items' => [
                    ['label' => 'Flight Report', 'icon' => 'list', 'url' => ['/sale/report/ticket-sales-report'], 'visible' => Helper::checkRoute('/sale/report/ticket-sales-report')],
                    ['label' => 'Holiday Report', 'icon' => 'list', 'url' => ['/sale/report/holiday-sales-report'], 'visible' => Helper::checkRoute('/sale/report/holiday-sales-report')],
                    ['label' => 'Hotel Report', 'icon' => 'list', 'url' => ['/sale/report/hotel-sales-report'], 'visible' => Helper::checkRoute('/sale/report/hotel-sales-report')],
                    ['label' => 'Visa Report', 'icon' => 'list', 'url' => ['/sale/report/visa-sales-report'], 'visible' => Helper::checkRoute('/sale/report/visa-sales-report')],
                ]
            ],
        ],
        'options' => [
            'class' => 'nav-item has-treeview mb-2 rounded',
        ],
        'linkOptions' => ['class' => 'nav-link text-white'],
    ],
    [
        'label' => 'Accounts Management',
        'icon' => 'file-invoice-dollar',
        'items' => [
            ['label' => 'Bank Accounts', 'icon' => 'building', 'url' => ['/account/bank-account'], 'visible' => Helper::checkRoute('/account/bank-account/')],
            ['label' => 'Contra Entry', 'icon' => 'arrow-right', 'url' => ['/account/contra-entry'], 'visible' => Helper::checkRoute('/account/contra-entry/')],
            [
                'label' => 'Advance Management',
                'icon' => 'file-invoice-dollar',
                'items' => [
                    ['label' => 'Add Advance Pay', 'icon' => 'plus-circle', 'url' => ['/account/advance-payment/create'], 'visible' => Helper::checkRoute('/account/advance-payment/create')],
                    ['label' => 'Customer Payments', 'icon' => 'list', 'url' => ['/account/advance-payment/index'], 'visible' => Helper::checkRoute('/account/advance-payment/')],
                    ['label' => 'Supplier Payments', 'icon' => 'list', 'url' => ['/account/advance-payment/supplier-advance-payment'], 'visible' => Helper::checkRoute('/account/advance-payment/')],
                ]
            ],
            [
                'label' => 'Invoice Management',
                'icon' => 'file-invoice-dollar',
                'items' => [
                    ['label' => 'Create Invoice', 'icon' => 'plus-circle', 'url' => ['/account/invoice/create'], 'visible' => Helper::checkRoute('/account/invoice/create')],
                    ['label' => 'Invoice List', 'icon' => 'list', 'url' => ['/account/invoice/index'], 'visible' => Helper::checkRoute('/account/invoice/')],
                ]
            ],
            [
                'label' => 'Bill Management',
                'icon' => 'file-invoice-dollar',
                'items' => [
                    ['label' => 'Create Bill', 'icon' => 'plus-circle', 'url' => ['/account/bill/create'], 'visible' => Helper::checkRoute('/account/bill/create')],
                    ['label' => 'Bill List', 'icon' => 'list', 'url' => ['/account/bill/index'], 'visible' => Helper::checkRoute('/account/bill/')],
                ]
            ],
            ['label' => 'Refund Transactions', 'icon' => 'money-bill', 'url' => ['/account/refund-transaction/index'], 'visible' => Helper::checkRoute('/account/refund-transaction/')],
            [
                'label' => 'Ledger Management',
                'icon' => 'list',
                'items' => [
                    ['label' => 'Customer Ledger', 'icon' => 'list', 'url' => ['/account/ledger/customer-ledger'], 'visible' => Helper::checkRoute('/account/ledger/customer-ledger')],
                    ['label' => 'Supplier Ledger', 'icon' => 'list', 'url' => ['/account/ledger/supplier-ledger'], 'visible' => Helper::checkRoute('/account/ledger/supplier-ledger')],
                    ['label' => 'Bank Ledger', 'icon' => 'list', 'url' => ['/account/ledger/bank-ledger'], 'visible' => Helper::checkRoute('/account/ledger/bank-ledger')],
                ]
            ],
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
            [
                'label' => 'Reports',
                'icon' => 'file-invoice-dollar',
                'items' => [
                    ['label' => 'Profit Loss', 'icon' => 'list', 'url' => ['/account/account-report/profit-loss'], 'visible' => Helper::checkRoute('/account/account-report/profit-loss')],
                ]
            ],
        ],
        'options' => [
            'class' => 'nav-item has-treeview mb-2 rounded',
        ],
        'linkOptions' => ['class' => 'nav-link text-white'],
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
];
?>
<aside class="main-sidebar elevation-4" style="background-color: #337abe;">
    <a href="<?= Url::home() ?>" class="h-100 p-2 text-center">
        <img src="<?= Url::to('/uploads/logo.svg') ?>" alt="MY TRAMS" class="brand-image elevation-3 text-white mt-1 ml-3"
             style="opacity: .8;">
        <!-- <span class="brand-text font-weight-light">TRAMS</span> -->
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <!-- <= Menu::widget([
                //'activeCssClass' => 'bg-green-active',
                'items' => $items
            ]); ?> -->
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
<li class="nav-item has-treeview mb-2 rounded nav-items"><a class="nav-link " href="#" ><i class="nav-icon fas fa-sitemap"></i> <p>Agency Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon far fa-circle"></i> <p>Plan Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/agent/plan/index" ><i class="nav-icon far fa-dot-circle"></i> <p>Plan List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/agent/plan/create" ><i class="nav-icon far fa-dot-circle"></i> <p>Plan Add  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon far fa-circle"></i> <p>Agency Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/agent/agency/index" ><i class="nav-icon far fa-dot-circle"></i> <p>Agency List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/agent/agency/create" ><i class="nav-icon far fa-dot-circle"></i> <p>Agency Add  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/agent/agency-account-request/index" ><i class="nav-icon far fa-dot-circle"></i> <p>Agency Request  </p></a></li>
</ul>
</li>
</ul>
</li>
<li class="nav-item has-treeview mb-2 rounded nav-items"><a class="nav-link " href="#" ><i class="nav-icon fas fa-handshake"></i> <p>Support Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon far fa-circle"></i> <p>Inquiry Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/support/inquiry/index" ><i class="nav-icon far fa-dot-circle"></i> <p>Inquiry List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/support/inquiry/create" ><i class="nav-icon far fa-dot-circle"></i> <p>Inquiry Add  </p></a></li>
</ul>
</li>
</ul>
</li>
<li class="nav-item has-treeview mb-2 rounded nav-items"><a class="nav-link " href="#" ><i class="nav-icon fas fa-cogs"></i> <p>General Settings <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/country" ><i class="nav-icon fas fa-globe"></i> <p>Countries  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/city" ><i class="nav-icon fas fa-globe"></i> <p>Cities  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/company/company-profile" ><i class="nav-icon fas fa-building"></i> <p>Company Profile  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/provider/index" ><i class="nav-icon fas fa-paper-plane"></i> <p>Providers/GDS  </p></a></li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-truck"></i> <p>Supplier Settings <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/sale/supplier-category/create" ><i class="nav-icon fas fa-plus"></i> <p>Supplier Category Add  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/supplier-category/index" ><i class="nav-icon fas fa-list-alt"></i> <p>Category List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/supplier/create" ><i class="nav-icon fas fa-plus"></i> <p>Supplier Add  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/supplier/index" ><i class="nav-icon fas fa-truck"></i> <p>Supplier List  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-paper-plane"></i> <p>Airline Settings <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/sale/airline/create" ><i class="nav-icon fas fa-paper-plane"></i> <p>Airline Add  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/airline/index" ><i class="nav-icon fas fa-paper-plane"></i> <p>Airline List  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-users"></i> <p>Customer Settings <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/sale/star-category/create" ><i class="nav-icon fas fa-star"></i> <p>Star Category Add  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/star-category/index" ><i class="nav-icon fas fa-list-alt"></i> <p>Star Categories  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/customer/create" ><i class="nav-icon fas fa-user-plus"></i> <p>Customer Add  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/customer/index" ><i class="nav-icon fas fa-list"></i> <p>Customer List  </p></a></li>
</ul>
</li>
</ul>
</li>
<li class="nav-item has-treeview mb-2 rounded nav-items"><a class="nav-link " href="#" ><i class="nav-icon fas fa-users-cog"></i> <p>Authorization <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/admin/role" ><i class="nav-icon fas fa-user-tie"></i> <p>Roles  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/admin/rule" ><i class="nav-icon fas fa-truck"></i> <p>Rules  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/admin/route" ><i class="nav-icon fas fa-list-alt"></i> <p>Routes  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/admin/permission" ><i class="nav-icon fas fa-key"></i> <p>Permission  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/admin/assignment" ><i class="nav-icon fas fa-check-double"></i> <p>Assignment  </p></a></li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-users-cog"></i> <p>User Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/admin/user/index" ><i class="nav-icon fas fa-users"></i> <p>Users  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/admin/user/create" ><i class="nav-icon fas fa-user-plus"></i> <p>Create User  </p></a></li>
</ul>
</li>
</ul>
</li>
<li class="nav-item has-treeview mb-2 rounded nav-items"><a class="nav-link " href="#" ><i class="nav-icon fas fa-users"></i> <p>HRM <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-sitemap"></i> <p>Branch Settings <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/hrm/branch/create" ><i class="nav-icon fas fa-plus"></i> <p>Branch Add  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/branch/index" ><i class="nav-icon fas fa-list"></i> <p>Branch List  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-building"></i> <p>Department <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/hrm/department/create" ><i class="nav-icon fas fa-plus"></i> <p>Department Add  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/department/index" ><i class="nav-icon fas fa-list"></i> <p>Department List  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-user-tie"></i> <p>Designation <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/hrm/designation/create" ><i class="nav-icon fas fa-plus"></i> <p>Designation Add  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/designation/index" ><i class="nav-icon fas fa-list"></i> <p>Designation List  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-users"></i> <p>Employee Manage <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/hrm/employee/create" ><i class="nav-icon fas fa-plus"></i> <p>Employee Add  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/employee/index" ><i class="nav-icon fas fa-list"></i> <p>Employee List  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-users-cog"></i> <p>User Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/admin/user/index" ><i class="nav-icon fas fa-users"></i> <p>Users  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/admin/assignment/index" ><i class="nav-icon fas fa-check-double"></i> <p>Permission Assignment  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-suitcase"></i> <p>Weekend &amp; Holidays <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/hrm/weekend/index" ><i class="nav-icon fas fa-calendar-day"></i> <p>Weekend  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/public-holiday/index" ><i class="nav-icon fas fa-calendar-times"></i> <p>Public Holiday  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-suitcase"></i> <p>Shift &amp; Roster <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/hrm/shift/index" ><i class="nav-icon fas fa-calendar-plus"></i> <p>Shift List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/department-shift/index" ><i class="nav-icon fas fa-calendar-check"></i> <p>Department Shifts  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/employee-shift/index" ><i class="nav-icon fas fa-calendar-check"></i> <p>Employee Shifts  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/roster/index" ><i class="nav-icon fas fa-calendar"></i> <p>Roster List  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-cogs"></i> <p>Leave Settings <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/hrm/leave-type/index" ><i class="nav-icon fas fa-calendar-plus"></i> <p>Leave Type  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/yearly-leave-allocation/index" ><i class="nav-icon fas fa-calendar-check"></i> <p>Yearly Allocation  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/employee-leave-allocation/index" ><i class="nav-icon fas fa-calendar-check"></i> <p>Employee Allocation  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/leave-approval-policy/index" ><i class="nav-icon fas fa-calendar"></i> <p>Approval Policy  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-cogs"></i> <p>Leave Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/hrm/leave-application/index" ><i class="nav-icon fas fa-calendar-plus"></i> <p>Leave Applications  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/leave-application/applied-leaves" ><i class="nav-icon fas fa-calendar-plus"></i> <p>Applied Leaves  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/leave-application/approval-history" ><i class="nav-icon fas fa-calendar-plus"></i> <p>Approval History  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/leave-application/apply" ><i class="nav-icon fas fa-calendar-check"></i> <p>Apply  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-users"></i> <p>Attendance Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/hrm/attendance/index" ><i class="nav-icon fas fa-calendar-plus"></i> <p>All Attendance  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/attendance/individual-attendance" ><i class="nav-icon fas fa-calendar-plus"></i> <p>Attendance  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-users"></i> <p>Payroll <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/hrm/payroll-type/index" ><i class="nav-icon fas fa-calendar-plus"></i> <p>Payroll Types  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/employee-payroll/index" ><i class="nav-icon fas fa-calendar-plus"></i> <p>Employee Payroll  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/hrm/payslip/index" ><i class="nav-icon fas fa-calendar-plus"></i> <p>Payslip  </p></a></li>
</ul>
</li>
</ul>
</li>
<li class="nav-item has-treeview mb-2 rounded nav-items"><a class="nav-link " href="#" ><i class="nav-icon fas fa-truck"></i> <p>Sales Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-plane"></i> <p>Flight Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/sale/ticket/create" ><i class="nav-icon fas fa-plus-circle"></i> <p>Add Ticket  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/ticket/upload" ><i class="nav-icon fas fa-upload"></i> <p>Upload Ticket  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/ticket/index" ><i class="nav-icon fas fa-list"></i> <p>Ticket List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/ticket/ticket-supplier-list" ><i class="nav-icon fas fa-list"></i> <p>Ticket Supplier List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/ticket/refund-list" ><i class="nav-icon fas fa-minus-circle"></i> <p>Refund List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/ticket/void-list" ><i class="nav-icon fas fa-circle"></i> <p>Void List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/flight-proposal/index" ><i class="nav-icon fas fa-circle"></i> <p>Flight Proposal  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-suitcase"></i> <p>Holiday Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/sale/holiday-category/index" ><i class="nav-icon fas fa-list"></i> <p>Category List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/holiday/create" ><i class="nav-icon fas fa-plus-circle"></i> <p>Add Holiday  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/holiday/index" ><i class="nav-icon fas fa-list"></i> <p>Holiday List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/holiday/holiday-supplier-list" ><i class="nav-icon fas fa-list"></i> <p>Holiday Supplier List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/holiday/refund-list" ><i class="nav-icon fas fa-minus-circle"></i> <p>Refund List  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-hotel"></i> <p>Hotel Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/sale/hotel-category/index" ><i class="nav-icon fas fa-list"></i> <p>Hotel Categories  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/room-type/index" ><i class="nav-icon fas fa-list"></i> <p>Room Types  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/hotel/create" ><i class="nav-icon fas fa-plus-circle"></i> <p>Add Hotel  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/hotel/index" ><i class="nav-icon fas fa-list"></i> <p>Hotel List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/hotel/hotel-supplier-list" ><i class="nav-icon fas fa-list"></i> <p>Hotel Supplier List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/hotel/refund-list" ><i class="nav-icon fas fa-minus-circle"></i> <p>Refund List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/hotel-proposal/index" ><i class="nav-icon fas fa-circle"></i> <p>Hotel Proposal  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-passport"></i> <p>Visa Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/sale/visa/create" ><i class="nav-icon fas fa-plus-circle"></i> <p>Add Visa  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/visa/index" ><i class="nav-icon fas fa-list"></i> <p>Visa List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/visa/visa-supplier-list" ><i class="nav-icon fas fa-list"></i> <p>Visa Supplier List  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/visa/refund-list" ><i class="nav-icon fas fa-minus-circle"></i> <p>Refund List  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-passport"></i> <p>Sales Report <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/sale/report/ticket-sales-report" ><i class="nav-icon fas fa-list"></i> <p>Flight Report  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/report/holiday-sales-report" ><i class="nav-icon fas fa-list"></i> <p>Holiday Report  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/report/hotel-sales-report" ><i class="nav-icon fas fa-list"></i> <p>Hotel Report  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/sale/report/visa-sales-report" ><i class="nav-icon fas fa-list"></i> <p>Visa Report  </p></a></li>
</ul>
</li>
</ul>
</li>
<li class="nav-item has-treeview mb-2 rounded nav-items"><a class="nav-link " href="#" ><i class="nav-icon fas fa-file-invoice-dollar"></i> <p>Accounts Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/account/bank-account" ><i class="nav-icon fas fa-building"></i> <p>Bank Accounts  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/account/contra-entry" ><i class="nav-icon fas fa-arrow-right"></i> <p>Contra Entry  </p></a></li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-file-invoice-dollar"></i> <p>Advance Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/account/advance-payment/create" ><i class="nav-icon fas fa-plus-circle"></i> <p>Add Advance Pay  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/account/advance-payment/index" ><i class="nav-icon fas fa-list"></i> <p>Customer Payments  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/account/advance-payment/supplier-advance-payment" ><i class="nav-icon fas fa-list"></i> <p>Supplier Payments  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-file-invoice-dollar"></i> <p>Invoice Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/account/invoice/create" ><i class="nav-icon fas fa-plus-circle"></i> <p>Create Invoice  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/account/invoice/index" ><i class="nav-icon fas fa-list"></i> <p>Invoice List  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-file-invoice-dollar"></i> <p>Bill Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/account/bill/create" ><i class="nav-icon fas fa-plus-circle"></i> <p>Create Bill  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/account/bill/index" ><i class="nav-icon fas fa-list"></i> <p>Bill List  </p></a></li>
</ul>
</li>
<li class="nav-item"><a class="nav-link " href="/account/refund-transaction/index" ><i class="nav-icon fas fa-money-bill"></i> <p>Refund Transactions  </p></a></li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-list"></i> <p>Ledger Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/account/ledger/customer-ledger" ><i class="nav-icon fas fa-list"></i> <p>Customer Ledger  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/account/ledger/supplier-ledger" ><i class="nav-icon fas fa-list"></i> <p>Supplier Ledger  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/account/ledger/bank-ledger" ><i class="nav-icon fas fa-list"></i> <p>Bank Ledger  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-file-invoice-dollar"></i> <p>Journal Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/account/account-type/index" ><i class="nav-icon fas fa-list"></i> <p>Account Type  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/account/account-group/index" ><i class="nav-icon fas fa-list"></i> <p>Account Group  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/account/chart-of-account/index" ><i class="nav-icon fas fa-list"></i> <p>Chart Of Account  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/account/journal/index" ><i class="nav-icon fas fa-list"></i> <p>Journal Entry  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-file-invoice-dollar"></i> <p>Expense Management <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/account/expense-category/index" ><i class="nav-icon fas fa-list"></i> <p>Categories  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/account/expense-sub-category/index" ><i class="nav-icon fas fa-list"></i> <p>Sub Categories  </p></a></li>
<li class="nav-item"><a class="nav-link " href="/account/expense/index" ><i class="nav-icon fas fa-list"></i> <p>Expenses  </p></a></li>
</ul>
</li>
<li class="nav-item has-treeview"><a class="nav-link " href="#" ><i class="nav-icon fas fa-file-invoice-dollar"></i> <p>Reports <i class="right fas fa-angle-left"></i> </p></a>
<ul class='nav nav-treeview'>
<li class="nav-item"><a class="nav-link " href="/account/account-report/profit-loss" ><i class="nav-icon fas fa-list"></i> <p>Profit Loss  </p></a></li>
</ul>
</li>
</ul>
</li></ul>
        </nav>
    </div>
</aside>
<script>
    let sidebarMenu = document.getElementsByClassName("nav-items");
    for(let i=0;i<sidebarMenu.length;i++)
    {
        sidebarMenu[i].addEventListener("click",function(){
            for(let j=0;j<sidebarMenu.length;j++)
            {
                if(i!==j)
                {
                    if(sidebarMenu[j].classList.contains("menu-is-opening"))
                    {
                        sidebarMenu[j].classList.remove("menu-is-opening");
                    }
                    if(sidebarMenu[j].classList.contains("menu-open"))
                    {
                        sidebarMenu[j].classList.remove("menu-open");
                    }
                    sidebarMenu[j].children[1].style.display = "none";
                }
            }
            for(let j=0;j<sidebarMenu[i].children[1].children.length;j++)
            {
                sidebarMenu[i].children[1].children[j].addEventListener("click",function(){
                    for(let k=0;k<sidebarMenu[i].children[1].children.length;k++)
                    {
                        if(j!=k)
                        {
                            if(sidebarMenu[i].children[1].children[k].classList.contains("has-treeview"))
                            {
                                if(sidebarMenu[i].children[1].children[k].classList.contains("menu-is-opening"))
                                {
                                    sidebarMenu[i].children[1].children[k].classList.remove("menu-is-opening");
                                }
                                if(sidebarMenu[i].children[1].children[k].classList.contains("menu-open"))
                                {
                                    sidebarMenu[i].children[1].children[k].classList.remove("menu-open");
                                }
                                sidebarMenu[i].children[1].children[k].children[1].style.display = "none";
                            }
                        }
                    }
                })
            }
        })
    }
</script>
