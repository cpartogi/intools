<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateRole(Request $request)
    {
        $userID = $request->get('id');
        $roles = $request->get('roles');

        $user = User::where('id', $userID)->first();

        try {
            $user->roles()->sync($roles);

            return response(200);
        } catch (\Exception $e) {
            return response(503)->json($e->getMessage());
        }
    }

    public function switchActive(Request $request)
    {
        $userID = $request->get('id');
        $isActive = $request->get('is_active') == 'true' ? 1 : 0;

        $user = User::where('id', $userID)->first();

        // if not the same then update
        if ($user->is_active !== $isActive) {
            try {
                $user->is_active = $isActive;
                $user->save();

                return response(200);
            } catch (\Exception $e) {
                return response(503)->json($e->getMessage());
            }
        }
    }

    public function getList(Request $request)
    {
        $take = $request->input('length');
        $skip = $request->input('start');
        $search = $request->input('search')['value'];

        if (!$search) {
            $users = User::skip($skip)->take($take)->get();
            $count = User::get()->count();
        } else {
            $users = User::where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')->skip($skip)->take($take)->get();
            $count = User::where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')->get()->count();
        }

        $roles = Role::all();

        foreach ($users as $user) {
            $arrRoles = [];

            $select2 = '<div class="row"><div class="col-md-8"><select disabled="disabled" data-iduser="'.$user->id.'" multiple="multiple" class="form-control role" style="width: 100%;" data-dropdown-css-class="select2-purple" data-placeholder="Select Permissions">';

            foreach ($user->roles as $role) {
                array_push($arrRoles, $role->name);
            }

            foreach ($roles as $role) {
                if (in_array($role->name, $arrRoles)) {
                    $select2 .= '<option value="'.$role->id.'" selected>'.$role->name.'</option>';
                } else {
                    $select2 .= '<option value="'.$role->id.'">'.$role->name.'</option>';
                }
            }

            $select2 .= '</select></div>
            <div class="col-md-4">
              <button type="button" data-iduser="'.$user->id.'" class="btn btn-block btn-primary edit-user">Edit</button>
            </div>
          </div>';

            $user->role = $select2;
        }

        $result = [
            'draw' => $request->input('draw'),
            'recordsFiltered' => $count,
            'recordsTotal' => $count,
            'data' => @$users != null ? $users : [],
        ];

        return response()->json($result);
    }

    public function list()
    {
        $data['title'] = 'Intools User Management';
        $data['breadcrumbs'] = 'Dashboard / Intools User Management';

        return view('pages.user')->with($data);
    }

    public function logout(Request $request)
    {
        if (Auth::user()) {
            Auth::logout();
        }

        return redirect('/oauth2/sign_out');
    }
}
