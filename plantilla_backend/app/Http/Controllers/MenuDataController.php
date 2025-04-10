<?php

namespace App\Http\Controllers;

use App\Models\MenuData;
use App\Models\PermisosData;
use App\Models\ModulosData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MetaFritterVerso\FritterDynamic;

class MenuDataController extends Controller
{

    public function index()
    {
        $data = MenuData::whereNull("submenu_id")->get();

        foreach ($data as $key => $dataRow) {
            $data[$key]['children'] = DB::table('view_sys_menu')->where("submenu_id", $dataRow['menu_id'])->get(["menu_id", "key", "ruta_route", "label", "icon", "order", "submenu"]);
        }

        $response = [
            "status" => 200,
            "data" => $data,
            "message" => "Menu Actualizado",
            "type" => "success"
        ];
        $response  = response()->json($response);
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
    }

    public function show($id, Request $request)
    {
        $parametros = $request->all();

        $caracterABuscar = " ";
        $caracterDeReemplazo = "+";
        $dataF = str_replace($caracterABuscar, $caracterDeReemplazo, $parametros['_us']);

        $us = FritterDynamic::opensslDesEncrypt($dataF);
        $us = json_decode($us, true);

        $data = MenuData::whereNull("submenu_id")
            ->where("keycloak_id", $id)
            ->where("id_modulo", $us['id_modulo'])
            ->get();
        foreach ($data as $key => $dataRow) {
            $data[$key]['children'] = MenuData::where("submenu_id", "=", $dataRow['menu_id'])
                ->where("keycloak_id", "=", $id)
                ->get(["menu_id", "key",  "ruta_route", "label", "icon", "order"]);
        }

        // $user = UserData::join("sys_cat_rol", "sys_users.id_rol", "=", "sys_cat_rol.id_rol")
        //     ->join("sys_cat_companys", "sys_users.id_company", "=", "sys_cat_companys.id_company")
        //     ->select('sys_users.*', 'sys_cat_rol.rol', 'sys_cat_companys.company')
        //     ->where("sys_users.status", "alta")
        //     ->find($id);

        $response = [
            "status" => 200,
            "data" => $data,
            // "user" => $user,
            // "us" => $us,
            "message" => "Menu Actualizado",
            "type" => "success"
        ];
        $response  = response()->json($response);
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
    }

    public function menuPermisos($id)
    {
        $menus = DB::table("view_sys_cat_menu")->get(); //// se quito el where para aparezcan tods los menus y submenus
        $permisos = PermisosData::where("user_id", $id)->get();

        foreach ($menus as $menu) {
            $aux = false;
            $permission_id = 0;
            $menu_id = $menu->menu_id;
            foreach ($permisos as $permiso) {
                $permission_id = $permiso->permission_id;
                if ($permiso->menu_id == $menu_id) {
                    $aux = true;
                    break;
                }
            }
            $menu->checked = $aux;
            $menu->permission_id = $permission_id;
        }
        $response = [
            "status" => 200,
            "menu" => $menus,
            "permisos" => $permisos,
            "message" => "Menu Actualizado",
            "type" => "success"
        ];

        $response  = response()->json($response);
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
    }

    public function modulos($id)
    {
        $modulos = ModulosData::where("id_keycloak", $id)->get();
        $response = [
            "status" => 200,
            "modulos" => $modulos,
            "message" => "Actualizado",
            "type" => "success"
        ];
        // return response()->json($response, 200);
        $response  = response()->json($response);
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
    }

    public function addPermiso(Request $request)
    {
        $params = $request->all();
        // $arr = $params['parametros'];
        $params = $params[0];
        $params = FritterDynamic::opensslDesEncrypt($params);
        $params = (array) json_decode($params);
        PermisosData::create($params);

        $response = [
            "status" => 200,
            "message" => "Permiso Actualizado",
            "type" => "success"
        ];
        return response()->json($response, 200);
    }

    public function deletePermiso($id)
    {
        PermisosData::findOrFail($id)->delete();
        $response = [
            "status" => 200,
            "message" => "Permiso Eliminado",
            "type" => "success"
        ];
        // return response()->json($response, 200);
        $response  = response()->json($response);
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
    }
}
