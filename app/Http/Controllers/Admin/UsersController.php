<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
	function __construct()
    {
    	$this->middleware('auth');
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['delete']]);
    }

    public function index()
    {
        $users = User::all();
        // $users = User::role('Super Admin')->get();
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.users',array('users' => $users, 'id' => 0, 'roles' => $roles));
    }

    public function create(Request $request)
    {
    	// dd($request);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:225|email|unique:users',
            'username' => 'required|max:225|unique:users',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $request['status'] = isset($request['status']) ? $request['status'] : 0;
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'username' => $request['username'],
            'gender' => $request['gender'],
            'phone' => $request['phone'],
            'address' => $request['address'],
            'city' => $request['city'],
            'region' => $request['region'],
            'country' => $request['country'],
            'status' => $request['status'],
            'wishlist' => '[]',
            'password' => Hash::make($request['password'])
        ]);
        $user->assignRole($request['role']);

        return redirect()->to('admin/users')->with('status', 'User added Successfully!');

    }

    public function edit($id)
    {
        $id = base64_decode($id);
       	$user = User::findOrFail($id);
       	$roles = Role::pluck('name', 'name')->all();
       	$userRole = $user->roles->pluck('name')->first();
       	// dd($userRole);
       	return view('admin.users',compact('user', 'id', 'roles', 'userRole'));
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $request['status'] = isset($request['status']) ? $request['status'] : '0';
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'username' => ['required','max:225',
                            Rule::unique('users')->ignore($user->id)
                        ]

        ]);

        $user->name = $request['name'];
        $user->username =$request['username'];
        $user->status = $request['status'];
        $user->phone = $request['phone'];
        $user->address = $request['address'];
        $user->city = $request['city'];
        $user->region = $request['region'];
        $user->country = $request['country'];
        $user->gender = $request['gender'];

        $user->save();
        return redirect('admin/users')->with('status', 'User Updated Successfully!');

    }

    public function delete($id)
    {

        $user = User::where('id', $id)->firstOrFail();
        if ($user) {
            $user->delete();
            return redirect()->back()->with('status', 'User Deleted Successfully!');

        }
        return redirect()->back()->with('status', 'Something Went Wrong!');

    }

    public function check_user_availability(Request $request)
    {
        $checkUsernameAvailability = User::where('username', $request->username)->doesntExist();
        if ($checkUsernameAvailability) {
            return 1;
        }else{
            return 0;
        }
    }

    public function check_user_email_availability(Request $request)
    {
        $checkUserEmailAvailability = User::where('email', $request->email)->doesntExist();

        if ($checkUserEmailAvailability) {
            return 1;
        }else{
            return 0;
        }
    }

    public function get_states($cName, Request $request)
    {
        // echo $cName;
        // exit();
        if ($cName != 'null') {
            $countryArray = array();
            $region= $request->region;
            $country = DB::table('countries')->where('name', $cName)->select('id','phonecode')->first();
            $postal_code = $country->phonecode;
            $states = DB::table('states')->where('country_id', $country->id)->get();

            array_push($countryArray, "<option value='' disabled selected>Select State/Region</option>");
            
            foreach ($states as $stat) {
                if ($stat->name == $region) {
                    $selectFlag = 'selected';
                }else{
                    $selectFlag = '';
                }
                array_push($countryArray, "<option ".$selectFlag." value='".$stat->name."' >".$stat->name."</option>");
            }
            $countryArray = json_encode($countryArray);
            $cArray = array("country_list" => $countryArray, "postal_code" =>  $postal_code);

            echo json_encode($cArray);
        }
    }


}
