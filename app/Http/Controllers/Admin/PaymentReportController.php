<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\PaymentReport;
use App\User;
use App\Vendor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentReportController extends Controller
{
    public function __construct()
	{
		$this->middleware('role:Super Admin');
	}

	public function index(Request $request)
    {
        $id = 0;

        if (isset($request->date_filter)) {
            $parts = explode(' - ' , $request->date_filter);
            $date_from = $parts[0];
            $date_to = $parts[1];
        } else {
            $carbon_date_from = new Carbon('last Monday');
            $date_from = $carbon_date_from->toDateString();
            $carbon_date_to = new Carbon('this Sunday');
            $date_to = $carbon_date_to->toDateString();
        }

        // dd($request->date_filter);
        $payment_reports = PaymentReport::where('created_at', '>=', $date_from." 00:00:00")->where('created_at', '<=', $date_to." 23:59:59");
        // $payment_reports = PaymentReport::all();
        // dd($request->vendor);
        if (isset($request->vendor)) {
        	$vendor = Vendor::where('slug',$request->vendor)->first();
        	if ($vendor) {
        		$vendor_id = $vendor->user_id;
        		$payment_reports = $payment_reports->where('vendor_id',$vendor_id);
        	}
        }

        $payment_reports = $payment_reports->orderBy('created_at')->get(); 
        

        $vendor_users = User::with('vendor_details')->whereHas('inventory_products')->get();
        $vendors = collect($vendor_users)->pluck('vendor_details')->all();
        return view('admin.payment-reports', compact('payment_reports', 'vendors', 'id'));
    }

    public function create(Request $request)
    {
        
        
        $validateData = $request->validate([
        	"vendor_id" => 'required',
            "total_amount" => 'required',
            "return_refund" => 'required',
            "adjustment" => 'required',
            "commission" => 'required',
            "net_amount" => 'required',
            "paid_amount" => 'required',
            "due_amount" => 'required'
        ]);
        // dd($_POST);
        $user = User::find($request->vendor_id);

        if (!$user) {
            return redirect()->back()->withInput()->with('error','Vendor not Found!');
        }
        // dd($user->vendor_details->store_name);


        $total_amount = $request->total_amount;
        $return_refund = $request->return_refund;
        $paid_amount = $request->paid_amount;

        $last_payment_report = PaymentReport::where('vendor_id', $request->vendor_id)->latest()->first();
        // dd($last_payment_report);
        if ($last_payment_report) {
        	$old_due_amount = $last_payment_report->due_amount;
        }else{
        	$old_due_amount = 0;
        }

        $adjustment = $total_amount - $return_refund;
        $adjustment = round($adjustment, 2);

        $commission = round((0.1 * $adjustment),2);

        $net_amount = round(($adjustment - $commission),2);

        $due_amount = round(($net_amount + $old_due_amount - $paid_amount),2);

        // --------------------Vendor Code--------------------------

        $words = explode(" ", $user->vendor_details->store_name);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= $w[0];
        }

        $vendor_code = $acronym. (2021+ $request->vendor_id);

        // --------------------Payment ID--------------------------

        do{
           $randomString = Str::random(2).rand().$acronym;
           $payment_id =  "LQS".$randomString;
         }while(!empty(PaymentReport::where('payment_id',$payment_id)->first()));

     //    echo "<pre>";
     //    var_dump([
					// 'payment_id' => $payment_id,
     //                'vendor_id' => $request->vendor_id,
     //                'vendor_name' => $user->vendor_details->store_name,
     //                'vendor_code' => $vendor_code,
     //                'total_amount' => $total_amount,
     //                'return_refund' => $return_refund,
     //                'adjustment' => $adjustment,
     //                'commission' => $commission,
     //                'net_amount' => $net_amount,
     //                'paid_amount' => $paid_amount,
     //                'due_amount' => $due_amount,
     //                'created_by' => Auth::user()->name,
     //                'updated_by' => ''
     //            ]);
     //    echo "</pre>";

     //    dd($_POST);

        $payment_report_created = PaymentReport::create([
															'payment_id' => $payment_id,
										                    'vendor_id' => $request->vendor_id,
										                    'vendor_name' => $user->vendor_details->store_name,
										                    'vendor_code' => $vendor_code,
										                    'total_amount' => $total_amount,
										                    'return_refund' => $return_refund,
										                    'adjustment' => $adjustment,
										                    'commission' => $commission,
										                    'net_amount' => $net_amount,
										                    'paid_amount' => $paid_amount,
										                    'due_amount' => $due_amount,
										                    'created_by' => Auth::user()->name,
										                    'updated_by' => ''
										                ]);
        
        if ($payment_report_created) {
            return redirect('admin/payment-reports')->with('status', 'Payment Report Added Successfully');
        }else{
            return redirect()->back()->with('error','Something went Wrong!');
        }
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $payment_report = PaymentReport::findOrFail($id);
        $vendor_users = User::with('vendor_details')->whereHas('inventory_products')->get();
        $vendors = collect($vendor_users)->pluck('vendor_details')->all();
        return view('admin.payment-reports', compact('payment_report', 'vendors', 'id'));
    }

    public function update(Request $request)
    {
        // dd($_POST);
        $validateData = $request->validate([
            "total_amount" => 'required',
            "return_refund" => 'required',
            "adjustment" => 'required',
            "commission" => 'required',
            "payment_to_vendor" => 'required',
            "status" => 'required'
        ]);

        $user = User::find($request->vendor_id);

        if (!$user) {
            return redirect()->back()->withInput()->with('error','Vendor not Found!');
        }
        
        $total_amount = $request->total_amount;
        $return_refund = $request->return_refund;

        $adjustment = $total_amount - $return_refund;
        $adjustment = round($adjustment, 2);

        $commission = round((0.1 * $adjustment),2);

        $net_amount = round(($adjustment - $commission),2);

        $payment_report_updated = PaymentReport::where('id', $request->id)
                                            ->update([
                                                        'vendor_id' => $request->vendor_id,
                                                        'vendor_name' => $user->vendor_details->store_name,
                                                        'vendor_code' => '',
                                                        'total_amount' => $total_amount,
                                                        'return_refund' => $return_refund,
                                                        'adjustment' => $adjustment,
                                                        'commission' => $commission,
                                                        'net_amount' => $net_amount,
                                                        'status' => $request->status,
                                                        'updated_by' => Auth::user()->name,
                                                        'updated_at' => date('Y-m-d H:i:s')
                                                    ]);
        
        if ($payment_report_updated) {
            return redirect('admin/payment-reports')->with('status', 'Payment Report Updated Successfully');
        }else{
            return redirect()->back()->with('error','Something went Wrong!');
        }
    }


    public function delete($id)
    {
        $payment_report = PaymentReport::findOrFail($id);
        $payment_report->delete();

        return redirect()->back()->with('status','Discount Coupon Deleted Successfully');
    }

    public function change_payment_report_status(Request $request)
    {
    	$payment_report = PaymentReport::where('id', $request->id)->first();
        
        $payment_report->status = $request->status;
        $statusChanged = $payment_report->save();

        if ($statusChanged) {
            $data = array('status'=> 'success');
        }else{
            $data = array('status'=> 'error');
        }

        echo json_encode($data);	
    }

    public function get_last_vendor_payment_report(Request $request)
    {
    	$vendor_id = $request->vendor_id;

    	if($vendor_id){
    		$last_payment_report = PaymentReport::where('vendor_id', $vendor_id)->latest()->first();
            // dd($last_payment_report);
    		$tableResponse = '';

    		if ($last_payment_report) {
    			$tableResponse .=   '<tr style="border: 1px solid red;">
    
									    <td>
									        <input class="form-control" type="text" name="total_amount" value="'.$last_payment_report->total_amount.'" required readonly>
									    </td>

									    <td>
									        <input class="form-control" type="text" name="return_refund" value="'.$last_payment_report->return_refund.'" required readonly>
									    </td>

									    <td>
									        <input class="form-control" type="text" name="adjustment" value="'.number_format($last_payment_report->adjustment, 2, '.', '').'" required readonly>
									    </td>

									    <td>
									        <input class="form-control" type="text" name="commission" value="'.number_format($last_payment_report->commission, 2, '.', '').'" required readonly>
									    </td>

									    <td>
									        <input class="form-control" type="text" name="net_amount" value="'.number_format($last_payment_report->net_amount, 2, '.', '').'" required readonly>
									    </td>

									    <td>
									        <input class="form-control" type="text" name="paid_amount" value="'.number_format($last_payment_report->paid_amount, 2, '.', '').'" required readonly>
									    </td>

									    <td>
									        <input id="oldDueAmountInput" class="form-control" type="text" name="due_amount" value="'.number_format($last_payment_report->due_amount, 2, '.', '').'" required readonly>
									    </td>

									</tr>';
    		}else{
    			$tableResponse .= '<input id="oldDueAmountInput" class="form-control" type="hidden" value="0" required readonly>';
    		}

            $tableResponse .=   '<tr>
								    
								    <td>
								        <input id="totalAmountInput" class="form-control do-calculation" type="text" name="total_amount" value="" required>
								    </td>

								    <td>
								        <input id="returnRefundInput" class="form-control do-calculation" type="text" name="return_refund" value="0" required>
								    </td>

								    <td>
								        <input id="adjustmentInput" class="form-control" type="text" name="adjustment" required readonly>
								    </td>

								    <td>
								        <input id="commissionInput" class="form-control" type="text" name="commission" required readonly>
								    </td>

								    <td>
								        <input id="netAmountInput" class="form-control" type="text" name="net_amount" required readonly>
								    </td>

								    <td>
								        <input id="paidAmountInput" class="form-control do-calculation" type="text" name="paid_amount" value="0" required>
								    </td>

								    <td>
								        <input id="dueAmountInput" class="form-control" type="text" name="due_amount" required readonly>
								    </td>

								</tr>';
    		
    	}else{
    		$tableResponse = '<tr>
								<td class="text-center" colspan="9" style="background-color: #a4797e !important; color: white;"><strong >Select Vendor First</strong></td>
							</tr>';
    	}



    	$response = array('tableResponse' => $tableResponse);
    	echo json_encode($response);
    }
}
