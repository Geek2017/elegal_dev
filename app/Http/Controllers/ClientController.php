<?php

namespace App\Http\Controllers;

use App\Business;
use App\Client;
use App\ContactInfo;
use App\Helpers\LogActivity;
use App\Profile;
use App\Billing;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
     private $rules = array(
        'first-name' => 'required|max:255',
        'last-name' => 'required|max:255',
        'business-address' => 'required|max:255',
        'email' => 'sometimes|required|email|unique:clients',
    );

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->can('browse-client')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        LogActivity::addToLog(null, 'Browse', 'Clients');
        return view('user.client.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('add-client')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        return view('user.client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('add-client')){
            flash('You have no Permission!', 'danger');
            return back();
        }

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $count = Client::count();
        $data = new Client();
        $data->count = str_pad($count + 1, 5, 0, STR_PAD_LEFT);
        $data->email = $request->input('email',null);
        $data->walkin = $request->input('walk_in') == 'on' ? 1:0;
        $data->author = Auth::user()->id;
        if($data->save()){
            $profile = new Profile();
            $profile->client_id = $data->id;
            $profile->firstname = $request->input('first-name',null);
            $profile->middlename = $request->input('middle-name',null);
            $profile->lastname = $request->input('last-name',null);
            $profile->image = $request->input('image',null);
            $profile->save();

            LogActivity::addToLog($profile->firstname.' '.$profile->lastname, 'Add', 'Client');

            if($profile->image != null){
                File::move(public_path().'/temp/image/'.$profile->image, public_path().'/uploads/image/'.$profile->image);
                $files = File::files(public_path().'/temp/image/');
                File::delete($files);
            }

            $business = new Business();
            $business->client_id = $data->id;
            if($request->input('billing') == 1){
                $business->billing = 0;
            }
            $business->main = 1;
            $business->name = $request->input('business-name',null);
            $business->oic = $profile->firstname.' '.$profile->lastname;
            if($business->save()){
                $infos = array(
                    array('permanent_address', $request->input('business-address')),
                    array('telephone', $request->input('business-telephone')),
                    array('mobile', $request->input('business-mobile')),
                );
                foreach ($infos as $info){
                    $contact = new ContactInfo();
                    $contact->business_id = $business->id;
                    $contact->type = $info[0];
                    $contact->description = $info[1];
                    $contact->save();
                }
            }

            if($request->input('billing') == 1){
                $business = new Business();
                $business->client_id = $data->id;
                $business->name = $request->input('billing-business-name',null);
                $business->oic = $request->input('billing-in-charge',null);
                if($business->save()){
                    $infos = array(
                        array('permanent_address', $request->input('billing-address')),
                        array('telephone', $request->input('billing-telephone'))
                    );
                    foreach ($infos as $info){
                        $contact = new ContactInfo();
                        $contact->business_id = $business->id;
                        $contact->type = $info[0];
                        $contact->description = $info[1];
                        $contact->save();
                    }
                }
            }

                // print_r($data->walkin);

            if($data->walkin==0){
            return redirect()->route('create-contract', array('id'=>$data->id));

            }else{
            return redirect()->route('walk-in.charge-slip.create'); 
            }
                
            

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        if(!auth()->user()->can('read-client')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        $client = $client->with('profile')
            ->with('business')->find($client->id);

        $fullName = $client->profile->firstname.' '.$client->profile->lastname;

        LogActivity::addToLog($fullName, 'Read', 'Client');
        return view('user.client.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        if(!auth()->user()->can('edit-client')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        $client = $client->load(['profile', 'business', 'billingAddress']);
        // die($client);
        return view('user.client.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        if(!auth()->user()->can('edit-client')){
            flash('You have no Permission!', 'danger');
            return back();
        }
        $this->rules['email'] = 'required';
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $client = $client->load(['profile', 'business', 'billingAddress']);
        
        $client->fill([
            'email' => $request->input('email', null),
            'walkin' => ($request->input('walk_in') == 'on') ? 1:0,
        ]);        

        if($client->save()){
            $profile = Profile::where('client_id', $client->id)->first();
            $profile->firstname = $request->input('first-name');
            $profile->middlename = $request->input('middle-name');
            $profile->lastname = $request->input('last-name');
            if($client->profile->image != $request->input('image')){
                File::delete(public_path().'/uploads/image/'.$client->image);
                $profile->image = $request->input('image');
                File::move(public_path().'/temp/image/'.$request->input('image'), public_path().'/uploads/image/'.$request->input('image'));
                $files = File::files(public_path().'/temp/image/');
                File::delete($files);
            }
            $profile->save();
            $fullName = $profile->firstname.' '.$profile->lastname;
            LogActivity::addToLog($fullName, 'Edit', 'Client');
            // update client business
            $business = Business::find($client->business->id)->first();
            $business->fill([
                'billing' => ($request->input('billing') == 1) ? 0 : 1,
                'name' => $request->input('business-name', null),
                'oic' => $profile->firstname.' '.$profile->lastname,
            ]);
            $business->save();
            // update client-business-contact-info
            $infos = [
                ['permanent_address', $request->input('business-address')],
                ['telephone', $request->input('business-telephone')],
                ['mobile', $request->input('business-mobile')],
            ];
            // delete first the contact infos the insert new
            ContactInfo::where('business_id', $client->business->id)->delete();
            foreach ($infos as $info) {
                $contact = new ContactInfo();
                $contact->business_id = $client->business->id;
                $contact->type = $info[0];
                $contact->description = $info[1];
                $contact->save();
            }
            // delete first the billing address contact info then insert new
            $billingAddress = ($client->billingAddress) ? $client->billingAddress : null;
            if ($client->billingAddress) {
                ContactInfo::where('business_id', $client->billingAddress->id)->delete();
            }
            if ($client->billingAddress && $request->input('billing') == 0) {
                Business::find($client->billingAddress->id)->delete();
            }
            if (!$client->billingAddress && $request->input('billing') == 1) {
                $billingAddress = Business::create([
                        'client_id' => $client->id,
                        'name'      => $request->input('billing-business-name', null),
                        'oic'       => $request->input('billing-in-charge', null)
                    ]);
            } else if ($billingAddress) {
                $billingAddress->name = $request->input('billing-business-name');
                $billingAddress->oic = $request->input('billing-in-charge');
                $billingAddress->save();
            }
            if ($request->input('billing') == 1) {
                $infos = [
                    ['permanent_address', $request->input('billing-address')],
                    ['telephone', $request->input('billing-telephone')]
                ];
                foreach ($infos as $info) {
                    $contact = new ContactInfo();
                    $contact->business_id = $billingAddress->id;
                    $contact->type = $info[0];
                    $contact->description = $info[1];
                    $contact->save();
                }
            }


            return redirect()
                ->route('client.edit', [$client->id])
                ->with('message', 'Client information, successfully updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Client $client)
    {
//        $data = Client::find($request->input('id'));
//        if($data->trashed()){
//            return response()->json('deleted');
//        }

        if($client->trashed($request->input('id'))){
            return response()->json('deleted');
        }


//        if($request->input('type') == 'check'){
//            $count = Transaction::where('client_id', $request->input('id'))
//                ->where('status','!=','pending')
//                ->count();
//            if($count > 0){
//                return response()->json('unable');
//            }else{
//                return response()->json('able');
//            }
//        }
//
//        if($request->input('type') == 'delete'){
//            $data = Client::find($request->input('id'));
//            if($data->trashed()){
//                return response()->json('deleted');
//            }
//        }

    }

    public function clientDestroy(Request $request){
        $data = Client::find($request->input('id'));
        if($data->delete()){
            return response()->json('deleted');
        }else{
            return response()->json($data);
        }
    }

    public function clientList()
    {
        $list = Client::with(['profile', 'business']);

        $data = DataTables::of($list->get())
            ->addColumn('count', function ($list) {
                $info = $list->count;
                return $info;
            })
            ->addColumn('name', function ($list) {
                $info = $list->profile->firstname.' '.$list->profile->lastname;
                return $info;
            })
            ->addColumn('address', function ($list) {
                $info = $list->business->address->description;
                return $info;
            })
            ->addColumn('action', function ($list) {
                $count = Transaction::where('user_id',$list->id)->count();
                $menu = [];
               // $menu[] = '<button data-id="'.$list->id.'" type="button" class="btn-white btn btn-xs"><i class="fa fa-check text-success"></i> Edit</button>';
                if(auth()->user()->can('edit-client')) {
                    $menu[] = '<a href="' . route('client.edit', array('client' => $list->id)) . '" class="btn-white btn btn-xs"><i class="fa fa-pencil text-success"></i> edit</a>';
                }
                if(auth()->user()->can('read-client')) {
                    $menu[] = '<a href="' . route('client.show', array('client' => $list->id)) . '" class="btn-white btn btn-xs"><i class="fa fa-search text-success"></i> show</a>';
                }
                if($count < 1){
                    $menu[] = '<button data-id="'.$list->id.'" type="button" class="btn-white btn btn-xs delete-client"><i class="fa fa-times text-danger"></i> delete</button>';
                }
                return '<div class="btn-group text-right">'.implode($menu).'</div>';
            })
            ->make(true);

        return $data;
    }

    public function getClientList(){
//        $data =  Client::with('profile')
//            ->get();

        $data = Profile::where('client_id','!=',null)
            ->orderBy('firstname','asc')
            ->get();

        return response()->json($data);
    }

    public function clientListSelect(Request $request)
    {
        $search = $request->get('q');
        $client_type = $request->get('client_type');

        $clients = Profile::select([
                    "clients.id",
                    "profiles.lastname",
                    "profiles.middlename",
                    "profiles.firstname",
                    \DB::raw("CONCAT(profiles.lastname, ', ' ,profiles.firstname, ' ', IF(profiles.middlename IS NOT NULL, profiles.middlename, '') ) as text"),
                    \DB::raw("
                        (
                            SELECT COUNT(case_managements.id) 
                            FROM case_managements
                            inner join transactions on transactions.id = case_managements.transaction_id 
                            where transactions.client_id = profiles.client_id
                        )
                        as has_cases
                        "),
                    \DB::raw("
                            (
                                SELECT contact_infos.description 
                                FROM businesses 
                                left join contact_infos on contact_infos.business_id = businesses.id
                                where 
                                businesses.client_id = clients.id
                                and (
                                      businesses.main = 1
                                      OR 
                                      (businesses.billing = 0 OR businesses.billing = 1)
                                    )
                                and contact_infos.type = 'permanent_address'
                                limit 1
                        )
                        as client_business_address
                    "),
                    \DB::raw("
                            (
                                SELECT contact_infos.description 
                                FROM businesses 
                                left join contact_infos on contact_infos.business_id = businesses.id
                                where 
                                businesses.client_id = clients.id
                                and (
                                      businesses.main = 0
                                      AND 
                                      businesses.billing = 1
                                    )
                                and contact_infos.type = 'permanent_address'
                                limit 1
                        )
                        as client_billing_address
                    ")

                ])
            ->join('clients', 'clients.id', '=', 'profiles.client_id')
            ->whereRaw("
                (
                    profiles.firstname like '%{$search}%' OR
                    profiles.lastname like '%{$search}%' OR
                    clients.email like '%{$search}%'
                )
            ");

        if (strtolower($client_type) == 'w') {
            $clients->where('clients.walkin', 1);
        }
        
        return response()->json(['results' => $clients->get()]);
    }

    public function unpaidBills(Request $request)
    {
        $inputs = $request->except('_token');

        if (!isset($inputs['id'])) {
            throw new \Exception("id is required", 1);
        }

        $preSelect = [
            'billings.*',
            \DB::raw("FORMAT(billings.total, 2) as format_total")
        ];

        $bills = Billing::select(array_merge($preSelect, [
                \DB::raw('COALESCE(operational_funds.amount,0) as of_amount'),
                \DB::raw('COALESCE(operational_funds.balance, 0) as of_amount_balance'),
                \DB::raw('COALESCE(operational_funds.total_amount_paid, 0) as of_total_amount_paid'),
                \DB::raw("DATE_FORMAT(billings.bill_date, '%M %Y') as bill_month"),
                \DB::raw("FORMAT(billings.balance, 2) as format_balanace")
            ]))
            ->leftJoin('operational_funds', 'operational_funds.billing_id', '=', 'billings.id')
            // ->with([
                // 'serviceReports' => function($query) use ($preSelect) {
                //     return $query->addSelect($preSelect);
                // }, 
                // 'serviceReports.feeDetail', 
                // 'serviceReports.chargeables' => function($query) use ($preSelect) {
                    // return $query->addSelect($preSelect);
                // }
            // ])
            ->where('billings.client_id', $inputs['id'])
            ->whereRaw("
                    (
                        billings.paid = 0
                        OR
                        (billings.paid = 1 and billings.balance > 0)
                    )
                ")
            ->orderBy('billings.bill_date')
            ->get();

        return response()->json(['bills' => $bills]);
    }

    // public function latestBilling(Request $request)
    // {
    //     $inputs = $request->except('_token');

    //     if (!isset($inputs['id'])) {
    //         throw new \Exception("id is required", 1);
    //     }

    //     $preSelect = [
    //         '*',
    //         \DB::raw("FORMAT(total, 2) as format_total")
    //     ];

    //     $latest = Billing::select($preSelect)
    //         ->with([
    //             'serviceReports' => function($query) use ($preSelect) {
    //                 return $query->addSelect($preSelect);
    //             }, 
    //             'serviceReports.feeDetail', 
    //             'serviceReports.chargeables' => function($query) use ($preSelect) {
    //                 return $query->addSelect($preSelect);
    //             }
    //         ])
    //         ->where('client_id', $inputs['id'])
    //         ->orderBy('bill_date', 'DESC')
    //         ->first();

    //     return response()->json(['bill' => $latest]);
    // }

}