<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

use App\Account;
use Helpers\JsTreeHelper;

class AccountController extends Controller
{

    private $jsTreeHelper;

    private $rules = array(
        // 'code'                   => 'required|max:30',
        'title'                  => 'required|max:191',
        'level'                  => 'nullable|integer',
        'type'                   => 'required|in:A,C', // Account, Category
        'category_type'          => 'required|in:A,L,R,X,Q,D', // A=ssets, L=iability, R=evenue, X=Expense, Q=Equity, D=ividents
        'normal_account_balance' => 'required|in:D,C', // Debit, Credit
    );

    public function __construct(JsTreeHelper $jsTreeHelper)
    {
        $this->jsTreeHelper = $jsTreeHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.accounting.chart_of_accounts');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response('Some fields are required', 500);
        }

        $account = Account::create($request->except('_token'));


        return response()->json($account);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $account = Account::find($id);
        
        $account->fill($request->except('_token'));
        $account->save();

        return response('success');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = Account::findorFail($id);

        $account->delete();

        return response('delete success');
    }

    public function chartOfAccountList(Request $request)
    {
        $accounts = DB::select($this->sql());
        
        $result = array_map(function ($value) {
                return (array)$value;
            }, $accounts);

        return $this->jsTreeHelper->convertToJsTreeFormat($result, 'parent', 'title', 'id');
    }

    /**
     * SQL for retrieving data use in jstree.
     *
     * @return string
     */
    private function sql($addSpace = 0)
    {
        $icons = "(CASE 
                WHEN type = 'C' AND is_cash_type = 1 THEN 'fa fa-money blue'
                WHEN type = 'C' THEN 'fa fa-book green'
                WHEN type = 'A' AND is_cash_account = 0 THEN 'fa fa-file-o orange2'
                WHEN type = 'A' AND is_cash_account = 1 AND is_default_cash_account = 0 THEN 'fa fa-money red'
                WHEN type = 'A' AND is_cash_account = 1 AND is_default_cash_account = 1 THEN 'fa fa-money orange2'
            END) AS icon";

        $style = '';

        if ($addSpace) {
            $style = ', IF(type="C" OR is_cash_account = 1, CONCAT(IF(is_cash_account = 1, "color: dodgerblue4;", ""), "font-weight: bold;"), "") AS _cell_style_acc_title';
        }

        return "
            SELECT id, id AS correct_id, code, IF($addSpace = 0, title, CONCAT(SPACE(level*level), title)) AS title, level, parent, type, category_type, normal_account_balance, is_cash_type,
                is_cash_account, has_check, is_default_cash_account, is_net_income, 0 AS is_branch, $icons $style
            FROM accounts
            WHERE is_cash_account = 0 

            UNION

            SELECT 
                id,
                id AS correct_id,
                code,
                IF($addSpace = 0, title, CONCAT(SPACE(level*level), title)) AS title,
                level,
                parent,
                type, category_type, normal_account_balance, is_cash_type,
                is_cash_account, has_check, is_default_cash_account, is_net_income, 0 AS is_branch, $icons $style
            FROM accounts
            WHERE is_cash_account = 1
        ";
    }
}
