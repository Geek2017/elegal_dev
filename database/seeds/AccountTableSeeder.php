<?php

use Illuminate\Database\Seeder;

use App\Account;

class AccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('accounts')->truncate();

        $accounts = [
            [
                'id' => 1,
                'title' => 'ASSETS',
                'level' => 1,
                'parent' => null,
                'type' => 'C', // Category
                'category_type' => 'A',
                'normal_account_balance' => 'D'
            ],
            [
                'id' => 2,
                'title' => 'LIABILITIES',
                'level' => 1,
                'parent' => null,
                'type' => 'C', // Category
                'category_type' => 'L',
                'normal_account_balance' => 'C'
            ],
            [
                'id' => 3,
                'title' => 'FUND BALANCE',
                'level' => 1,
                'parent' => null,
                'type' => 'C', // Category
                'category_type' => 'Q',
                'normal_account_balance' => 'C',
            ],
            [
                'id' => 4,
                'title' => 'REVENUES',
                'level' => 1,
                'parent' => null,
                'type' => 'C', // Category
                'category_type' => 'R',
                'normal_account_balance' => 'C',
            ],
            [
                'id' => 5,
                'title' => 'OPERATING EXPENSES',
                'level' => 1,
                'parent' => null,
                'type' => 'C', // Category
                'category_type' => 'X',
                'normal_account_balance' => 'D',
            ],

            // Current ASSETS
            
            [
                'id' => 6,
                'title' => 'CURRENT ASSETS',
                'level' => 2,
                'parent' => 1, // ASSETS
                'type' => 'C', // Category
                'category_type' => 'A',
                'normal_account_balance' => 'D',
            ],
            
            // Current Assets #1 CASH
            [
                'id' => 7,
                'title' => 'CASH',
                'level' => 3,
                'parent' => 6, // CURRENT ASSETS
                'type' => 'C', // Category
                'category_type' => 'A',
                'normal_account_balance' => 'D',
            ],

            // Cash Accounts

            [
                'id' => 8,
                'title' => 'CASH ON HAND',
                'level' => 4,
                'parent' => 7, // CASH
                'type' => 'A', // Category
                'category_type' => 'A',
                'normal_account_balance' => 'D',
                'is_cash_type' => 1,
            ],
            [
                'id' => 9,
                'title' => 'PETTY CASH FUND',
                'level' => 4,
                'parent' => 7, // CASH
                'type' => 'A', // Account
                'category_type' => 'A',
                'normal_account_balance' => 'D',
                'is_cash_type' => 1,  
            ],
            [
                'id' => 10,
                'title' => 'CASH IN BANK',
                'level' => 4,
                'parent' => 7, // CASH
                'type' => 'A', // Category
                'category_type' => 'A',
                'normal_account_balance' => 'D',
                'is_cash_type' => 1,
            ],

            // Profesional Fees - Recievable
            
            [
                'id' => 11,
                'title' => 'PROFESSIONAL FEE - RECEIVABLE',
                'level' => 3,
                'parent' => 6, // CURRENT ASSETS
                'type' => 'C', // Category
                'category_type' => 'A',
                'normal_account_balance' => 'D',
            ],

            // PROFESSIONAL FEES
            
            // special
            
            // [
            //     'id' => 12,
            //     'title' => 'ACCEPTANCE FEE',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 13,
            //     'title' => 'COMPLETION FEE',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 14,
            //     'title' => 'ONE TIME FEE',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 15,
            //     'title' => 'PROJECT FEE',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 16,
            //     'title' => 'CONTINGENT FEE',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 17,
            //     'title' => 'SUCCESS FEE',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 18,
            //     'title' => 'OTHER FEE',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],

            // // general
            
            // [
            //     'id' => 19, 
            //     'title' => 'Acceptance Fee / Initial Fee',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 8
            // [
            //     'id' => 20, 
            //     'title' => 'Annotation of Certificate of Sale',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 9
            // [
            //     'id' => 21, 
            //     'title' => 'Appearance Fee',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 10
            // [
            //     'id' => 22, 
            //     'title' => 'Appearance Fee: Arraignment',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 11
            // [
            //     'id' => 23, 
            //     'title' => 'Assistance to Successfully Defeat TRO',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 12
            // [
            //     'id' => 24, 
            //     'title' => 'Commission',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 13
            // [
            //     'id' => 25, 
            //     'title' => 'Completion: Compromise Agreement',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 14
            // [
            //     'id' => 26, 
            //     'title' => 'Contingent Fee',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 15
            // [
            //     'id' => 27, 
            //     'title' => 'Documentation (Page) Fee',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 16
            // [
            //     'id' => 28, 
            //     'title' => 'Documentation (Responsive Pleading)',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 17
            // [
            //     'id' => 29, 
            //     'title' => 'Documentation (Time) Fee',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 18
            // [
            //     'id' => 30, 
            //     'title' => 'Incentive Fee: Case Dismissal',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 19
            // [
            //     'id' => 31, 
            //     'title' => 'Merger Execution',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 20
            // [
            //     'id' => 32, 
            //     'title' => 'Miscellaneous Fees',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 21
            // [
            //     'id' => 33, 
            //     'title' => 'Monthly Retainer',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 22
            // [
            //     'id' => 34, 
            //     'title' => 'Notarial Fee',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 23
            // [
            //     'id' => 35, 
            //     'title' => 'Paralegal Charge',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 24
            // [
            //     'id' => 36, 
            //     'title' => 'PF - Court of Appeals',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 25
            // [
            //     'id' => 37, 
            //     'title' => 'PF - Supreme Court',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 26
            // [
            //     'id' => 38, 
            //     'title' => 'Sale Completion',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 27
            // [
            //     'id' => 40, 
            //     'title' => 'Settlement Fee',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 28
            // [
            //     'id' => 41, 
            //     'title' => 'Time Service Fee',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ], // 29

            // // chargeable expense
            // [
            //     'id' => 42, 
            //     'title' => 'Communications Expenses',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 43, 
            //     'title' => 'Computer Printing',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 44, 
            //     'title' => 'Copying Charge',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 45, 
            //     'title' => 'Courier Charge',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 46, 
            //     'title' => 'E-Load',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 47, 
            //     'title' => 'Expenses',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 48, 
            //     'title' => 'Gasoline Allowance',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 49, 
            //     'title' => 'Legal Fee',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 50, 
            //     'title' => 'Medical Certificate',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 51, 
            //     'title' => 'Office Stationary',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 52, 
            //     'title' => 'Others',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 53, 
            //     'title' => 'Photocopy',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 54, 
            //     'title' => 'Photocopying [ Annexes / Exhibits ]',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 55, 
            //     'title' => 'Postage',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 56, 
            //     'title' => 'Postage & Transportation',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 57, 
            //     'title' => 'Postal Charge',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 58, 
            //     'title' => 'Processing Fee',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 59, 
            //     'title' => 'Professional Fee',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 60, 
            //     'title' => 'Representation Expenses',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 61, 
            //     'title' => 'Supplies',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 62, 
            //     'title' => 'Telephone Tolls',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 63, 
            //     'title' => 'TMG Clearance',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 64, 
            //     'title' => 'Transportation',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 65, 
            //     'title' => 'Travel Allowance',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
            // [
            //     'id' => 66, 
            //     'title' => 'Travel Expense',
            //     'level' => 4,
            //     'parent' => 11, // PROFESSIONAL FEE - RECEIVABLE
            //     'type' => 'A', // Category
            //     'category_type' => 'A',
            //     'normal_account_balance' => 'D',
            // ],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
