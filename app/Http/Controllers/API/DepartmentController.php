<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Icon;
use App\Models\LogMain;
use App\Models\User;
use App\Models\UserSetting;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->data = Department::paginate($this->limit);
        if (!empty($this->data)) {
            $this->response['success'] = true;
        }
        return $this->response();
    }

    public function byId(string $id)
    {

        $this->data = Department::find($id);
        if (!empty($this->data)) {
            $this->response['success'] = true;
        }
        return $this->response();
    }

    public function create()
    {

        try {
            $validator = Validator::make($this->request, [
                'name' => 'required',
                'code' => 'required',
                'active' => 'required',
            ]);
            if ($validator->fails()) {
                $this->response['errorLists'] = $validator->errors();
                $this->response['statusCode'] = 403;
                return $this->response();
            }

            $department = new Department();
            $department->name = $this->request['name'];
            $department->code = $this->request['code'];
            $department->active = $this->request['active'];
            $department->note = $this->request['note'] ?? null;
            $this->setCreatedId($department);
            $department->save();
            if ($department) {
                $this->response['success'] = true;
                $this->data = $department;
            }

            return $this->response();
        } catch (Exception $e) {
            $this->response['message'] = $e;
        }
    }

    
    public function update()
    {
        try {
            $validator = Validator::make($this->request, [
                'name' => 'required',
                'code' => 'required',
                'id' => 'required',
                'active' => 'required',
            ]);
            if ($validator->fails()) {
                $this->response['errorLists'] = $validator->errors();
                $this->response['statusCode'] = 403;
                $this->response['success'] = false;
                return $this->response();
            }

            $department = Department::find($this->request['id']);
            if(empty($department)){
                $this->response['success'] = false;
                $this->response['message'] = "Department not existing";
                return $this->response();
            }
            $department->name = $this->setUpdateField('name',$department);
            $department->code = $this->setUpdateField('code',$department);
            $department->active = $this->setUpdateField('active',$department);
            $department->note = $this->setUpdateField('note',$department);
            $this->setUpdatedId($department);
            $department->update();
            if ($department) {
                $this->response['success'] = true;
                $this->data = $department;
            }

            return $this->response();
        } catch (Exception $e) {
            $this->response['message'] = $e;
            return $this->response();
        }
    }

   

    public function delete($id)
    {
        $department = Department::find($id);
        if (!empty($department)) {
            // $department->delete();
            
            $this->response['success'] = true;
        }
        return $this->response();
    }
}
