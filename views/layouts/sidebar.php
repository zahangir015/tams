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
            'class' => 'nav-item has-treeview mb-2 rounded nav-items'
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
            'class' => 'nav-item has-treeview mb-2 rounded nav-items',
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
            'class' => 'nav-item has-treeview mb-2 rounded nav-items',
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
            'class' => 'nav-item has-treeview mb-2 rounded nav-items',
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
            'class' => 'nav-item has-treeview mb-2 rounded nav-items',
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
            'class' => 'nav-item has-treeview mb-2 rounded nav-items',
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
            'class' => 'nav-item has-treeview mb-2 rounded nav-items',
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
            <?= Menu::widget([
                //'activeCssClass' => 'bg-green-active',
                'items' => $items
            ]); ?>
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
                    for(let k=0;k<sidebarMenu[j].children[1].children.length;k++)
                    {
                        if(sidebarMenu[j].children[1].children[k].classList.contains("has-treeview"))
                        {
                            if(sidebarMenu[j].children[1].children[k].classList.contains("menu-is-opening"))
                            {
                                sidebarMenu[j].children[1].children[k].classList.remove("menu-is-opening");
                            }
                            if(sidebarMenu[j].children[1].children[k].classList.contains("menu-open"))
                            {
                                sidebarMenu[j].children[1].children[k].classList.remove("menu-open");
                            }
                            sidebarMenu[j].children[1].children[k].children[1].style.display = "none";
                        }
                    }
                }
            }
            for(let j=0;j<sidebarMenu[i].children[1].children.length;j++)
            {
                sidebarMenu[i].children[1].children[j].addEventListener("click",function(){
                    for(let k=0;k<sidebarMenu[i].children[1].children.length;k++)
                    {
                        if(j!==k)
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
