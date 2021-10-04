<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $profile = Profile::latest()->get();
        if ($request->ajax()) {
            $data = Profile::latest()->select('*');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('name', function ($data) {
                        return Str::title($data->name);
                    })
                    ->addColumn('image', function ($data) {
                        return asset('images/profile').'/'.$data->image;
                    })
                    ->addColumn('action', function($row) {
                        $btn = '';                                
                            $btn = '<div class="table-actions">
                                <a href="" data-toggle="modal" onclick="modalShowFun('.$row->id.')" class="mail-msg"><i class="ik ik-eye"></i></a>
                                <a href="'.route('admin.product.edit',$row->id).'"><i class="ik ik-edit-2"></i></a>
                                <a href="#" class="list-delete" onclick="deleteShowFun('.$row->id.')"><i class="ik ik-trash-2"></i></a>
                            </div>';    
                        return $btn;
                    })
                    ->editColumn('created_at', function($row) {
                          return  $row->created_at->format('d-m-Y');
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('home');
    }

    public function updateAndStore(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'firstname' => 'required | string ',
        //     'lastname' => 'required | string ',
        //     'email' => 'required | email | unique:users',
        //     'password' => 'required | confirmed',
        // ]);
        // if ($validator->fails()) {
        //     return redirect()->back()->with('error', $validator->messages()->first());
        // }

        $request->validate([
            'firstname' => 'required | string ',
            'lastname' => 'required | string ',
            'email' => 'required | email | unique:users,email,'. $user->id,
            'full_name' => 'required | string | unique:companies,name,'.$user->company->id
        ]);

        DB::beginTransaction();
        try
        {

            // store user information
            $user = User::updateOrCreate(
                ['id' => $user->id],[
                'firstname' => request('firstname'),
                'lastname' => request('lastname'),
                'email' => request('email'),
            ]);

            $company = Profile::updateOrCreate(
                ['user_id' => $user->id],[
                'name' => request('full_name')
            ]);

            DB::commit();
            if ($user) {
                return redirect()->back()->with('success', 'User details updated!');
            } else {
                return redirect()->back()->with('error', 'Failed to user details update! Try again.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}
