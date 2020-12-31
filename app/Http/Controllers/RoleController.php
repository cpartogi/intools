<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Rules\NotOnlySpace;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function updatePerm(Request $request)
    {
        $roleID = $request->get('id');
        $permissions = $request->get('perms');

        $role = Role::where('id', $roleID)->first();

        if (!$role) {
            return response(404);
        }

        try {
            $role->permissions()->sync($permissions);

            return response(200);
        } catch (\Exception $e) {
            return response(503);
        }
    }

    public function getList(Request $request)
    {
        $take = $request->input('length');
        $skip = $request->input('start');
        $search = $request->input('search')['value'];

        if (!$search) {
            $roles = Role::skip($skip)->take($take)->get();
            $count = Role::get()->count();
        } else {
            $roles = Role::where('name', 'like', '%'.$search.'%')->orWhere('slug', 'like', '%'.$search.'%')->skip($skip)->take($take)->get();
            $count = Role::where('name', 'like', '%'.$search.'%')->orWhere('slug', 'like', '%'.$search.'%')->get()->count();
        }

        $permissions = Permission::all();

        foreach ($roles as $role) {
            $arrPermissions = [];

            $select2 = '<div class="row"><div class="col-md-8"><select disabled="disabled" data-idrole="'.$role->id.'" multiple="multiple" class="form-control permission" style="width: 100%;" data-dropdown-css-class="select2-purple" data-placeholder="Select Permissions">';

            foreach ($role->permissions as $permission) {
                array_push($arrPermissions, $permission->name);
            }

            foreach ($permissions as $perm) {
                if (in_array($perm->name, $arrPermissions)) {
                    $select2 .= '<option value="'.$perm->id.'" selected>'.$perm->name.'</option>';
                } else {
                    $select2 .= '<option value="'.$perm->id.'">'.$perm->name.'</option>';
                }
            }

            $select2 .= '</select></div>
            <div class="col-md-4">
              <button type="button" data-idrole="'.$role->id.'" class="btn btn-block btn-primary edit-perm">Edit Permission</button>
            </div>
          </div>';

            $role->perms = $select2;
        }

        $result = [
            'draw' => $request->input('draw'),
            'recordsFiltered' => $count,
            'recordsTotal' => $count,
            'data' => @$roles != null ? $roles : [],
        ];

        return response()->json($result);
    }

    public function list()
    {
        $data['title'] = 'User Role Management';
        $data['breadcrumbs'] = 'User Role Management';

        return view('pages.user-role')->with($data);
    }

    public function create()
    {
        $data['title'] = 'Create User Role';
        $data['breadcrumbs'] = 'Create User Role';

        $permissions = Permission::all();

        $data['permissions'] = $permissions;

        return view('pages.user-role-create')->with($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required',
            'name' => ['required', 'min:3', new NotOnlySpace()],
        ]);

        $role = new Role();

        $role->name = $request->get('name');
        $role->slug = $request->get('slug');
        $role->description = $request->get('description');

        try {
            \DB::beginTransaction();
            $role->save();
            $role->permissions()->attach($request->get('permissions'));
            \DB::commit();

            return redirect()->route('role')->with('success', 'Role Berhasil Dibuat');
        } catch (\Exception $e) {
            \DB::rollback();

            return redirect()->route('role')->with('alert', $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $data['title'] = 'Edit User Role';
        $data['breadcrumbs'] = 'Edit User Role';

        $role = Role::where('id', $id)->first();

        if (!$role) {
            return abort(404);
        }

        $data['role'] = $role;

        $permissions = Permission::all();
        $data['permissions'] = $permissions;

        $selectedIDs = [];
        foreach ($role->permissions as $permission) {
            array_push($selectedIDs, $permission->id);
        }

        $data['selectedIDs'] = $selectedIDs;

        return view('pages.user-role-edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'slug' => 'required',
            'name' => ['required', 'min:3', new NotOnlySpace()],
        ]);

        $role = Role::where('id', $id)->first();
        $role->name = $request->get('name');
        $role->slug = $request->get('slug');
        $role->description = $request->get('description');

        try {
            \DB::beginTransaction();
            $role->save();
            $role->permissions()->sync($request->get('permissions'));
            \DB::commit();

            return redirect()->route('role')->with('success', 'Update Berhasil Dilakukan');
        } catch (\Exception $e) {
            \DB::rollback();

            return redirect()->route('role')->with('alert', $e->getMessage());
        }
    }

    public function delete($id)
    {
        $role = Role::where('id', $id)
                ->first();

        if (!$role) {
            return abort(404);
        }

        try {
            $role->delete();

            return redirect()->route('role')->with('success', 'Delete Berhasil Dilakukan');
        } catch (\Exception $e) {
            return redirect()->route('role')->with('alert', $e->getMessage());
        }
    }
}
