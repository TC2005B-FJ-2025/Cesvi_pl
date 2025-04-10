<?php

namespace App\Http\Controllers;

use App\Models\ViewUserData;
use App\Models\UserData;
use App\Models\PermisosData;
use Illuminate\Http\Request;
use App\MetaFritterVerso\FritterDynamic;
use Illuminate\Support\Facades\DB;


class UserDataController extends Controller
{

    public function index()
    {
        $catalogo = DB::table('view_sys_cat_menu')
            ->selectRaw('DISTINCT id_modulo as value , modulo as label')
            ->orderBy('modulo')
            ->get();

        $data = ViewUserData::where("view_users_data.status", "alta")->get();
        $form = FritterDynamic::itemsForm('Usuarios');
        $columns = FritterDynamic::columnsTable('Usuarios');
        $props_table = FritterDynamic::propsTable('Usuarios');

        $response = [
            "status" => 200,
            "data" => $data,
            "catalogo" => $catalogo,
            "formItems" => $form,
            "columns" => $columns,
            "props_table" => $props_table,
            "message" => "Informacion Actualizada de usuarios ",
            "type" => "success"
        ];
        $response  = response()->json($response);
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
        
    }

    public function show($id)
    {
        $response  = response()->json(ViewUserData::where("id_keycloak", $id)->get());
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
    }

    public function update($id, Request $request)
    {

        $data = UserData::findOrFail($id);
        $params = $request->all();
        // $params = $params['parametros'];
        $params = $params[0];
        $params = FritterDynamic::opensslDesEncrypt($params);
        $params = (array) json_decode($params);
        $data->update($params);

        $response = [
            "status" => 200,
            "params " =>   $params,
            "message" => "Se modificó correctamente el registro!",
            "type" => "success",
            "tipoComponent" => "notification"
        ];

        $response  = response()->json($response);
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
    }

    public function menuPermisos($id, Request $request)
    {
        $params = $request->all();
        $params = $params[0];
        $params = FritterDynamic::opensslDesEncrypt($params);
        $params = (array) json_decode($params);

        //$params['id_modulo']


        $menus = DB::table("view_sys_cat_menu")
            ->where("id_modulo", $params['id_modulo'])
            ->orderBy('ruta_route')
            ->get();
        $permisos = PermisosData::where("keycloak_id", $id)->get();

        // $id_keycloak = UserData::where('id_user', $id)->value('id_keycloak');

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
            // $menu->keycloak_id = $id_keycloak;
        }
        $response = [
            "status" => 200,
            "menu" => $menus,
            "permisos" => $permisos,
            "message" => "Menu Actualizado",
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
        $params = $params[0];
        $params = FritterDynamic::opensslDesEncrypt($params);
        $params = (array) json_decode($params);
        PermisosData::create($params);

        DB::table('sys_modulos_user')
            ->updateOrInsert(
                ['id_modulo' => $params['id_modulo'], 'id_keycloak' => $params['keycloak_id']],
                ['id_keycloak' => $params['keycloak_id']]
            );

        $response = [
            "status" => 200,
            "message" => "Permiso Actualizado",
            "type" => "success",
            'params' => $params
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
