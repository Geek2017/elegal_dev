<?php

namespace Simmfins\Account\Repo;

use App\Helpers\JsTreeHelper;
use Account;
use DB;

class AccountJsTree
{
    // use AccountTransformationTrait, JqGridTrait, AccountValidationTrait;

    /**
     * @var Account
     */
    protected $model;

    /**
     * Mapped searchable fields of the grid.
     *
     * @var array
     */
    private $jqSearchable
        = [
            'acc_code'  => 'accounts.code',
            'acc_title' => 'accounts.title',
            'type'      => 'accounts.type',
        ];

    /**
     * Mapped filter fields of the grid.
     *
     * @var array
     */
    private $jqFilterFields = [
        'type' => 'accounts.type',
    ];

    /**
     * @var AccountTransformer
     */
    protected $accountTransformer;

    /**
     * Use to hold the array of accounts.
     *
     * @var
     */
    private $accounts = [];

    /**
     * Use to hold the array of selected accounts based selected/chained methods.
     * Use `fetch` method to get its value.
     *
     * @var
     */
    private $gl = [];

    /**
     * @var \Simmfins\Services\Helpers\JqGridHelper
     */
    private $jqGridHelper;

    /**
     * Create Variable for Account Validator.
     */
    private $accountValidator;

    /**
     * Create variable for JsTree Helper.
     */
    private $jsTreeHelper;

    /**
     * @param Account            $model
     * @param jsTreeHelper       $jsTreeHelper
     * @param AccountTransformer $accountTransformer
     * @param AccountValidator   $accountValidator
     */
    public function __construct(Account $model, JsTreeHelper $jsTreeHelper, AccountTransformer $accountTransformer, AccountValidator $accountValidator)
    {
        $this->model              = $model;
        $this->jsTreeHelper       = $jsTreeHelper;
        $this->accountTransformer = $accountTransformer;
        $this->accountValidator   = $accountValidator;
    }

    /**
     * Return data for jqgrid.
     *
     * @return mixed
     */
    public function jqGrid($includeCashAccount = 1)
    {
        $accountType = "(CASE 
                WHEN category_type = 'A' THEN 'ASSET'
                WHEN category_type = 'L' THEN 'LIABILITY'
                WHEN category_type = 'R' THEN 'REVENUE'
                WHEN category_type = 'X' THEN 'EXPENSE'
                WHEN category_type = 'Q' THEN 'EQUITY'
                WHEN category_type = 'D' THEN 'DIVIDEND'
            END) AS account_type";

        $query = $this->model
                      ->select(['accounts.*', DB::raw($accountType)]);

        if ($includeCashAccount) {
            $query = $query->forUser();
        }

        $query = $query->orWhereNull('branch_id')
                       ->orderBy('title');

        return $this->setJqGridQuery($query)->makeJqGrid();
        //        return $this->jqGridHelper->filter(['type'=>'A'])->data($this->model, $transformer);
    }

    /**
     * Create a partial IN sql.
     * 
     * @param  string $field Conditional field
     * @return string
     */
    private function getBranches($field = 'branch_id')
    {
        $branches = $this->model->getBranchAssignments();

        if (!count($branches)) {
            return $field." IN ('   ')";
        }

        $sql = $field.' IN (';

        foreach ($branches as $branch) {
            $sql .= $branch.',';
        }

        $sql = substr($sql, 0, strlen($sql) - 1).')';

        return $sql;
    }

    /**
     * SQL for retrieving data use in jstree.
     *
     * @return string
     */
    protected function sql($addSpace = 0)
    {
        $icons = "(CASE 
                WHEN type = 'C' AND is_cash_type = 1 THEN 'fa fa-money blue'
                WHEN type = 'C' THEN 'fa fa-book green'
                WHEN type = 'A' AND is_cash_account = 0 THEN 'fa fa-file-o orange2'
                WHEN type = 'A' AND is_cash_account = 1 AND is_default_cash_account = 0 THEN 'fa fa-money red'
                WHEN type = 'A' AND is_cash_account = 1 AND is_default_cash_account = 1 THEN 'fa fa-money orange2'
            END) AS icon";

        $sql1 = $this->getBranches();
        $sql2 = $this->getBranches('b.id');

        $style = '';

        if ($addSpace) {
            $style = ', IF(type="C" OR is_cash_account = 1, CONCAT(IF(is_cash_account = 1, "color: dodgerblue4;", ""), "font-weight: bold;"), "") AS _cell_style_acc_title';
        }

        return "
			SELECT id, id AS correct_id, branch_id, code, IF($addSpace = 0, title, CONCAT(SPACE(level*level), title)) AS title, level, parent, type, category_type, normal_account_balance, is_cash_type,
				is_cash_account, has_check, is_default_cash_account, is_net_income, 0 AS is_branch, $icons $style
			FROM accounts
			WHERE is_cash_account = 0 AND $sql1 OR branch_id IS NULL

			UNION

			SELECT 
				id,
				id AS correct_id,
				branch_id,
				IF(branch_id IS NULL, 
					code, 
					CONCAT(
						SUBSTR(code, 1, LENGTH(code) - 2),
						branch_id,
						RIGHT(code, 2)
					)
				) AS code, 
				IF($addSpace = 0, title, CONCAT(SPACE(level*level), title)) AS title,
				level,
				CONCAT(parent, branch_id) AS parent,
				type, category_type, normal_account_balance, is_cash_type,
				is_cash_account, has_check, is_default_cash_account, is_net_income, 0 AS is_branch, $icons $style
			FROM accounts
			WHERE is_cash_account = 1 AND $sql1

			UNION

			SELECT DISTINCT 
				CONCAT(a.id, b.id) AS id,
				a.id AS correct_id,
				b.id,
				CONCAT(
						SUBSTR(a.code, 1, LENGTH(a.code)),
						b.id
					) AS code,
				IF($addSpace = 0, b.name, CONCAT(SPACE(level*level+6), b.name)) AS title,
				a.level,
				a.id AS parent,
				a.type, a.category_type, a.normal_account_balance, 1 AS is_cash_type,
				a.is_cash_account, a.has_check, a.is_default_cash_account, a.is_net_income, 1 AS is_branch,
				'fa fa-globe grey' AS icon $style
			FROM accounts a
				JOIN branches b ON $sql2
			WHERE a.is_cash_type = 1
			ORDER BY code
		";
    }

    /**
     * Create the data in jstree format.
     * 
     * @return array
     */
    public function getAccountJsTreeFormat()
    {
        $account = DB::select($this->sql());

        $account = $this->setCollection($account)->transformCollection()->get();

        return $this->jsTreeHelper->convertToJsTreeFormat($account, 'parent', 'acc_title', 'id');
    }

    /**
     * Fetch the populated GL Array.
     *
     * @return array $gl
     */
    public function fetch()
    {
        return $this->gl;
    }

    /**
     * Filter the $accounts and fill $gl with account code starting in `1`.
     *
     * @return Account Instance of Account model
     */
    public function assets()
    {
        return $this->fillGl('assets', '1');
    }

    /**
     * Filter the $accounts and fill $gl with account code starting in `2`.
     *
     * @return Account Instance of Account model
     */
    public function liabilities()
    {
        return $this->fillGl('liabilities', '2');
    }

    /**
     * Filter the $accounts and fill $gl with account code starting in `2`.
     *
     * @return Account Instance of Account model
     */
    public function equities()
    {
        return $this->fillGl('equities', '3');
    }

    /**
     * Filter the $accounts and fill $gl with account code starting in `4`.
     *
     * @return Account Instance of Account model
     */
    public function incomes()
    {
        return $this->fillGl('incomes', '4');
    }

    /**
     * Filter the $accounts and fill $gl with account code starting in `5`.
     *
     * @return Account Instance of Account model
     */
    public function expenses()
    {
        return $this->fillGl('expenses', '5');
    }

    public function cashAccounts()
    {
        $this->fillGl('cash_accounts', '1')->filterCashAccount();

        return $this;
    }

    public function checkingAccounts()
    {
        $this->fillGl('checking_accounts', '1')->filterCheckingAccount();

        return $this;
    }

    /**
     * Set/Fill the $accounts with array if empty.
     *
     * @return void
     */
    private function setAccount()
    {
        if (!$this->accounts) {
            $this->accounts = $this->model
                ->select('id', 'code', 'title', 'is_cash_account', 'has_check', 'branch_id')
                ->orderBy('title')
                ->where('type', '=', 'A')
                ->get();
        }
    }

    /**
     * Filter the $gl and fill $gl.
     *
     * @param        string Name/Array key of the filtered accounts
     * @param string $code  Starting `code` of the accounts to fill, usually '1', '2', '3', '4', '5'
     *
     * @return Account Instance of Account model
     */
    private function fillGl($name, $code)
    {
        $this->setAccount();

        $data = $this->filter($code)->values()->toArray();

        $this->gl[ $name ] = $this->accountTransformer->transformCollection($data);

        return $this;
    }

    /**
     * Filter the $gl base on `code` field.
     *
     * @param string $code Starting `code` of the accounts to fill, usually '1', '2', '3', '4', '5'
     *
     * @return array Filtered accounts
     */
    private function filter($code)
    {
        return $this->accounts->filter(function ($row) use ($code) {
            return substr($row[ 'code' ], 0, 1) === $code;
        });
    }

    /**
     * Filter the $gl to get cash account only.
     *
     * @return array Filtered accounts
     */
    private function filterCashAccount()
    {
        $cash = Arrays::filter($this->accounts, function ($row) {
            $data = $row[ 'is_cash_account' ] === 1;

            return $data;
        });

        $cash = array_values($cash);

        $this->gl['cash_accounts'] = $this->accountTransformer->transformCollection($cash);

        return $this;
    }

    /**
     * Filter the $gl to get checking accounts only.
     *
     * @return array Filtered accounts
     */
    private function filterCheckingAccount()
    {
        $check = $this->accounts->where('has_check', 1)->toArray();

        $check = array_values($check);

        $this->gl['checking_accounts'] = $this->accountTransformer->transformCollection($check);

        return $this;
    }

    /**
     * Generate the next account code of the account to be saved.
     * @param  int $parent The parent id of the account to be saved
     * @param  [type] $level  The hierachcy level of account to be saved
     * @return $string The generated account code
     */
    public function getNextAccountCode($parent, $level)
    {
        $accountCode = null;

        $sql = "IFNULL(MAX(code) + 1, CONCAT((SELECT code FROM accounts WHERE id = $parent), '01')) AS code";

        if ($level === 1) {
            $sql = "IFNULL(MAX(code) + 1, '1') AS code";
        }

        if ($level !== 1) {
            $accountCode = $this->model->select(DB::raw($sql))->where('parent', '=', $parent)->pluck('code')->all();
        }

        if ($level === 1) {
            $accountCode = $this->model->select(DB::raw($sql))->whereNull('parent')->pluck('code')->all();
        }

        return $accountCode;
    }

    /**
     * Check if the data is account and if the parent is account
     * update parent Account to has sub_account.
     *
     * @param array $data
     *
     * @return $this
     * @throws \Exception
     */
    public function checkSubAccountOrHasSubAccount(array $data)
    {
        $query     = $this->model->select('parent')->where('id', '>=', $data[ 'id' ])->first();
        $parent_id = $query->parent;
        if ($data[ 'is_cash_account' ] == '1') {
            if ($query) {
                // this will update the has_sub_acc field of the account parent
                //$this->save(['has_sub_acc' => 1, 'is_cashtype' =>1], $parent_id, 1);
                $this->save(['is_cash_type' => 1], $parent_id, 1);

                return $this;
            }
        } else {
            // get the total number of the parent account where is_subacc = 1
            $count = $this->model
                ->select(
                    \DB::raw('COUNT(*)  as total')
                )
                ->where('parent', '=', $parent_id)
                ->where('is_cash_account', '=', 1)->first();

            if ($count->total == 0) {
                // this will update the has_sub_acc field of the account parent
                //$this->save(['has_sub_acc' => 0], $parent_id, 1);
                $this->save(['is_cash_type' => 0], $parent_id, 1);

                return $this;
            }
        }
    }

    /**
     * Get list of cash accounts.
     * @return mixed|void
     */
    public function getCashAccount()
    {
        $this->collection = $this->model
                            ->select(['accounts.*', \DB::raw('CONCAT(code, "  | ", title) as name')])
                            ->where('branch_id', '=', get_branch_session())
                            ->where('is_cash_account', '=', 1)
                            ->orderBy('is_default_cash_account', 'desc')
                            ->get()
                            ->toArray();

        return $this->transform()->get();
    }

    public function getCashFlowAccountDetails($cfa_id)
    {
        $accountDetails = $this->model->select(
                                [
                                    'id',
                                    \DB::raw("CONCAT(title,' [', code, '] ') as title"),
                                ]
                                )->where('id', '=', $cfa_id)->get()->toArray();

        return $accountDetails;
    }

    public function getCashAccounts()
    {
        $accountDetails = [];

        $accountDetails['parent'] = $this->model->select(
                                [
                                    'id',
                                    \DB::raw("CONCAT(title,' [', code, '] ') as title"),
                                    'parent',
                                ]
                            )
        ->where('parent', '=', \DB::raw(7))
        ->get()->toArray();

        $accountDetails['child'] = $this->model->select(
                                [
                                    'id',
                                    \DB::raw("CONCAT(title,' [', code, '] ') as title"),
                                    'parent',
                                ]
                            )
        ->where('parent', '=', \DB::raw(10))
        ->where('branch_id', '=', \DB::raw(getBranchSession()))
        ->get()->toArray();

        return $accountDetails;
    }

    /**
     * Clear the default cash account.
     */
    public function clearDefault($branchId)
    {
        $this->model->updateOrCreate(['branch_id' => $branchId], ['is_default_cash_account' => 0]);
    }

    /**
     * Use to check if branch has already a default cash account.
     * 
     * @param  int  $branchId Branch Id
     * @return bool 
     */
    public function hasDefaultCashAccount($branchId = null)
    {
        if ($branchId === null) {
            $branchId = get_branch_session();
        }

        $result = $this->model
             ->select(['id'])
             ->where('branch_id', '=', $branchId)
             ->where('is_default_cash_account', '=', 1)
             ->first();

        if (isset($result->id)) {
            return true;
        }

        return false;
    }

    /**
     * Return the id of the default cash account of a specific branc.
     * @param  int $branchId The id of the branch
     * @return int
     */
    public function getDefaultCashAccount($branchId = null)
    {
        if (!$branchId) {
            $branchId = get_branch_session();
        }

        $result = $this->model->select('id')->where('branch_id',  $branchId)->where('is_default_cash_account', 1)->first();

        if (isset($result->id)) {
            return $result->id;
        }

        return;
    }
}
