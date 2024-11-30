<?php


namespace App\Models;


use App\Http\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    public $timestamps = true;
    protected $hidden = ['password'];




    public function getDepartmentList($id)
    {
        $user = $this->find($id);
        if (!empty($user->dept_ids)) {
            $parseArray = json_decode($user->dept_ids);
            $roleReferences = DepartmentRoleReference::with(['departmentRole', 'department'])->whereIn('dept_id', $parseArray)->get();
            $user['departments'] = $this->checkAndPushDeparment($roleReferences)['departments'];
            $user['roles'] = $this->checkAndPushDeparment($roleReferences)['roles'];
            $user['menus'] = $this->buildMenuTree($user['roles']);
            return $user;
        }
        return;
    }


    private function checkAndPushDeparment($departmentRoleReferences)
    {
        // return $departmentRoleReferences;
        $response = ['departments' => [], 'roles' => []];
        if (!empty($departmentRoleReferences)) {
            // return $departmentRoleReferences;
            foreach ($departmentRoleReferences as $item) {
                // print_r($item['department_role']);
                if (!$this->findObjectById($item->department->id, $response['departments'])) {
                    $response['departments'][] = $item->department;
                }

                if (!$this->findObjectById($item->departmentRole->id, $response['roles'])) {
                    $response['roles'][] = $item->departmentRole;
                }
            }
        }
        return $response;
    }

    function findObjectById($id, $array)
    {
        // return $array;
        foreach ($array as $element) {
            if ($id == $element->id) {
                return $element;
            }
        }

        return false;
    }

    function buildMenuTree($menuItems, $parentId = null)
    {
        $menuTree = [];
        foreach ($menuItems as $item) {
            if ($item->menu == 1) {
                if ($item['menu_parent_id'] == $parentId) {
                    $children = $this->buildMenuTree($menuItems, $item['id']);
                    if ($children) {
                        $item['children'] = $children;
                    } else {
                        $item['children'] = null;
                    }
                    $menuTree[] = $item;
                }
            }
        }
        return $menuTree;
    }
}
