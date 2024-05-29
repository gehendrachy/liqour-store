<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\SalesReport;
use App\PaymentReport;
use App\User;
use App\Vendor;
use App\VendorOrder;
use App\OrderedProduct;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function __construct()
	{
		$this->middleware('role:Super Admin');
	}

	public function index(Request $request)
    {
        $sales_reports = $this->create_update_sales_report($request);

        $report_start_from = isset($request->start_date) ? Carbon::create($request->start_date)->startOfMonth() : Carbon::now()->subMonths()->startOfMonth();
        $report_end_to = isset($request->end_date) ? Carbon::create($request->end_date)->endOfMonth() : Carbon::now()->subMonths()->endOfMonth();

        // dd($report_start_from->toDateString()."------->".$report_end_to->toDateString());

        $sales_reports = SalesReport::where([['report_date', '>=', $report_start_from],['report_date', '<=', $report_end_to]]);

        if ($request->vendor != 'None') {

            $vendor = Vendor::where('slug', $request->vendor)->first();

            if ($vendor) {
                $vendor_id = $vendor->user_id;
                $sales_reports = $sales_reports->where('vendor_id',$vendor_id);
            }
        }

        $sales_reports = $sales_reports->get(); 

        // dd($sales_reports);

        $id = 0;
        $vendor_users = User::with('vendor_details')->whereHas('inventory_products')->get();
        $vendors = collect($vendor_users)->pluck('vendor_details')->all();
        // dd($report_start_from);
        return view('admin.sales-reports', compact('sales_reports', 'report_start_from', 'report_end_to', 'vendors', 'id'));
    }

    public function create(Request $request)
    {
        $validateData = $request->validate([
            "total_sales" => 'required',
            "return_refund" => 'required',
            "total_net_sales" => 'required',
            "commission" => 'required',
            "payment_to_vendor" => 'required',
            "status" => 'required'
        ]);

        $user = User::find($request->vendor_id);
        if (!$user) {
            return redirect()->back()->withInput()->with('error','Vendor not Found!');
        }
        // dd($user->vendor_details->store_name);
        $total_sales = $request->total_sales;
        $return_refund = $request->return_refund;

        $total_net_sales = $total_sales - $return_refund;
        $total_net_sales = round($total_net_sales, 2);

        $commission = round((0.1 * $total_net_sales),2);

        $total_payment_to_vendor = round(($total_net_sales - $commission),2);
        
        $sales_report_created = SalesReport::create([
                                                        'vendor_id' => $request->vendor_id,
                                                        'vendor_name' => $user->vendor_details->store_name,
                                                        'vendor_code' => '',
                                                        'total_sales' => $total_sales,
                                                        'return_refund' => $return_refund,
                                                        'total_net_sales' => $total_net_sales,
                                                        'commission' => $commission,
                                                        'total_payment_to_vendor' => $total_payment_to_vendor,
                                                        'status' => $request->status,
                                                        'created_by' => Auth::user()->name,
                                                        'updated_by' => ''
                                                    ]);
        
        if ($sales_report_created) {
            return redirect('admin/sales-reports')->with('status', 'Sales Report Added Successfully');
        }else{
            return redirect()->back()->with('error','Something went Wrong!');
        }
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $sales_report = SalesReport::findOrFail($id);
        $vendor_users = User::with('vendor_details')->whereHas('inventory_products')->get();
        $vendors = collect($vendor_users)->pluck('vendor_details')->all();
        return view('admin.sales-reports', compact('sales_report', 'vendors', 'id'));
    }

    public function update(Request $request)
    {
        // dd($_POST);
        $validateData = $request->validate([
            "total_sales" => 'required',
            "return_refund" => 'required',
            "total_net_sales" => 'required',
            "commission" => 'required',
            "payment_to_vendor" => 'required',
            "status" => 'required'
        ]);

        $user = User::find($request->vendor_id);

        if (!$user) {
            return redirect()->back()->withInput()->with('error','Vendor not Found!');
        }
        
        $total_sales = $request->total_sales;
        $return_refund = $request->return_refund;

        $total_net_sales = $total_sales - $return_refund;
        $total_net_sales = round($total_net_sales, 2);

        $commission = round((0.1 * $total_net_sales),2);

        $total_payment_to_vendor = round(($total_net_sales - $commission),2);

        $sales_report_updated = SalesReport::where('id', $request->id)
                                            ->update([
                                                        'vendor_id' => $request->vendor_id,
                                                        'vendor_name' => $user->vendor_details->store_name,
                                                        'vendor_code' => '',
                                                        'total_sales' => $total_sales,
                                                        'return_refund' => $return_refund,
                                                        'total_net_sales' => $total_net_sales,
                                                        'commission' => $commission,
                                                        'total_payment_to_vendor' => $total_payment_to_vendor,
                                                        'status' => $request->status,
                                                        'updated_by' => Auth::user()->name,
                                                        'updated_at' => date('Y-m-d H:i:s')
                                                    ]);
        
        if ($sales_report_updated) {
            return redirect('admin/sales-reports')->with('status', 'Sales Report Updated Successfully');
        }else{
            return redirect()->back()->with('error','Something went Wrong!');
        }
    }


    public function delete($id)
    {
        $sales_report = SalesReport::findOrFail($id);
        $sales_report->delete();

        return redirect()->back()->with('status','Discount Coupon Deleted Successfully');
    }

    public function change_sales_report_status(Request $request)
    {
    	$sales_report = SalesReport::where('id', $request->id)->first();
        
        $sales_report->status = $request->status;
        $statusChanged = $sales_report->save();

        if ($statusChanged) {
            $data = array('status'=> 'success');
        }else{
            $data = array('status'=> 'error');
        }

        echo json_encode($data);	
    }


    public function update_vendor_order_calculation()
    {
        // dd('test');
        $all_vendor_orders = VendorOrder::all();
        foreach ($all_vendor_orders as $key => $vendor_order) {

            $subTotalPrice = 0;
            $grand_total = 0;
            $beforeTaxSubTotal = 0;
            $taxTotal = 0;

            foreach($vendor_order->ordered_products as $key => $item){
                $invProd = \App\InventoryProduct::where('id', $item->inventory_product_id)->first();

                $itemPrice = ($item->sub_total/$item->quantity);
                
                $bottleDepositPerItem = $item->pack * (int)$item->quantity * $item->bottle_deposit_rate;

                $beforeTaxSubTotalPerItem =  ($itemPrice * $item->quantity) + $bottleDepositPerItem;
                
                $beforeTaxSubTotal = $beforeTaxSubTotal + $beforeTaxSubTotalPerItem;
                
                $taxPrice = $itemPrice * ($item->tax_rate/100);
                $taxPrice = number_format($taxPrice, 2);

                $taxTotalPerItem = $taxPrice*$item->quantity;

                $taxTotal = $taxTotal + $taxTotalPerItem;

                // $unitPrice = number_format($itemPrice + $taxPrice, 2);

                $sub_total = number_format(($beforeTaxSubTotalPerItem + $taxTotalPerItem), 2);

                $subTotalPrice += $sub_total ; 
                OrderedProduct::where('id',$item->id)->update(['grand_total' => $sub_total]);
            }

            $grand_total = $grand_total = $subTotalPrice + $vendor_order->delivery_fee;
            $update_vendor_order = VendorOrder::where('id',$vendor_order->id)->first();
            $update_vendor_order->sub_total_exc_tax = $beforeTaxSubTotal;
            $update_vendor_order->tax_total = $taxTotal;
            $update_vendor_order->sub_total_inc_tax = $subTotalPrice;
            $update_vendor_order->grand_total = $grand_total;
            $update_vendor_order->save();
        }
    }


    public function create_update_sales_report($request)
    {
        $start_date = isset($request->start_date) ? Carbon::create($request->start_date) : Carbon::now()->subMonths();
        $end_date = isset($request->end_date) ? Carbon::create($request->end_date) : Carbon::now()->subMonths();

        $start = $start_date->startOfMonth();
        $end = $end_date->endOfMonth();
        // $end = Carbon::create($end_date);

        $monthWiseSalesReport = array();
        $sales_reports = array();

        do
        {

            $vendor_orders_this_month = VendorOrder::with('ordered_products')->orderBy('vendor_id')
                                        ->where(function($query) use ($request){

                                            if ($request->vendor != 'None') {

                                                $vendor = Vendor::where('slug', $request->vendor)->first();

                                                if ($vendor) {
                                                    $vendor_id = $vendor->user_id;
                                                    $query->where('vendor_id',$vendor_id);
                                                }

                                            }
                                        })
                                        ->whereYear('created_at',  Carbon::createFromDate($start->format('Y-m-d'))->year)
                                        ->whereMonth('created_at', Carbon::createFromDate($start->format('Y-m-d'))->month)
                                        ->get();


            $vendor_with_orders = $vendor_orders_this_month->groupBy('vendor_id')->all();
            // echo count($vendor_with_orders)."->>";
            
            $sales_reports_this_month = array();

            foreach ($vendor_with_orders as $vendor_id => $single_vendor_orders) {

                $single_sales_report = array();
                
                $single_vendor_order_order_products_alls = $single_vendor_orders->pluck('ordered_products')->flatten();

                $vendor = User::with('vendor_details')->where('id',$vendor_id)->first();
                $sales_report_db = SalesReport::where([['vendor_id', $vendor->id], ['report_date', $start->format('Y-m-d')]]);
                $sales_report_db_exists = $sales_report_db->exists();
                
                $commission_percentage = $sales_report_db_exists ? $sales_report_db->first()->commission_percentage : $vendor->vendor_details->commission_percentage;
                
                $return_refund = $single_vendor_order_order_products_alls->where('status', 6)->sum('grand_total');

                $single_sales_report['vendor_name'] = $vendor->vendor_details->store_name;

                $words = explode(" ", $vendor->vendor_details->store_name);
                $acronym = "";

                foreach ($words as $w) {
                    $acronym .= $w[0];
                }

                $single_sales_report['vendor_code'] = $acronym.'2021';
                $single_sales_report['total_sales'] = $single_vendor_orders->sum('grand_total');
                $single_sales_report['return_refund'] = $return_refund;

                $total_net_sales = $single_vendor_orders->sum('sub_total_exc_tax') - $return_refund;
                $single_sales_report['total_net_sales'] = $total_net_sales;

                $commission = round((($commission_percentage/100) * $total_net_sales),2);

                $single_sales_report['commission'] = $commission;
                $single_sales_report['commission_percentage'] = $commission_percentage;
                $single_sales_report['total_payment_to_vendor'] = round(($total_net_sales - $commission), 2);
                $single_sales_report['created_by'] = Auth::user()->name;
                
                // <--------------------- Create or Update Sales Report Table --------------------->

                $sales_report_update_create = SalesReport::updateOrCreate(['vendor_id' => $vendor->id, 'report_date' => $start->format('Y-m-d')],
                                                                            $single_sales_report);

                $single_sales_report['vendor_id'] = $vendor->id;
                $single_sales_report['report_date'] = $start->format('Y-m');
                $single_sales_report['id'] = $sales_report_update_create->id;

                array_push($sales_reports_this_month, $single_sales_report);
                array_push($sales_reports, $single_sales_report);
            }

            $monthWiseSalesReport[$start->format('Y-m')]['sales_report'] = $sales_reports_this_month;

            // echo $start->format('Y-m')."<br>";
        } while ($start->addMonth() <= $end);

        return $sales_reports;
        // dd($sales_reports);
    }

    public function pay_amount(Request $request)
    {

        $validateData = $request->validate([
            "sales_report_id" => 'required',
            "paid_amount" => 'required|gt:0'
        ]);

        $sales_report = SalesReport::where('id',$request->sales_report_id)->first();
        // dd($sales_report);
        // dd($_POST);

        if (isset($sales_report)) {

            $payment_report = $sales_report->payment_reports()->latest()->first();

            if ($payment_report) {
                $last_due_amount = $payment_report->due_amount;
            }else{
                $last_due_amount = $sales_report->total_payment_to_vendor;
            }

            
            // --------------------Payment ID--------------------------

            do{
               $randomString = Str::random(7);
               $payment_id =  strtoupper("LQS".$randomString);

            }while(!empty(PaymentReport::where('payment_id',$payment_id)->first()));

            $due_amount = $last_due_amount - $request->paid_amount;

            $payment_report_created = PaymentReport::create([
                                                            'payment_id' => $payment_id,
                                                            'sales_report_id' => $sales_report->id,
                                                            'vendor_id' => $sales_report->vendor_id,
                                                            'vendor_name' => $sales_report->vendor_name,
                                                            'vendor_code' => $sales_report->vendor_code,
                                                            'total_amount' => $sales_report->total_sales,
                                                            'return_refund' => $sales_report->return_refund,
                                                            'adjustment' => $sales_report->total_net_sales,
                                                            'commission' => $sales_report->commission,
                                                            'net_amount' => $sales_report->total_payment_to_vendor,
                                                            'paid_amount' => $request->paid_amount,
                                                            'due_amount' => $due_amount,
                                                            'created_by' => Auth::user()->name,
                                                            'updated_by' => ''
                                                        ]);
            if ($payment_report_created) {

                if ($due_amount <= 0) {

                    $sales_report->status = 1;
                }else{

                    $sales_report->status = 2;
                }

                $sales_report->save();

                return redirect()->back()->with('success','Payment Successful!');
            }else{
                return redirect()->back()->with('error','Something went wrong!');
            }
            
        }else{
            return redirect()->back()->with('error','Sales Report Not Found');
        }

        
    }

}
