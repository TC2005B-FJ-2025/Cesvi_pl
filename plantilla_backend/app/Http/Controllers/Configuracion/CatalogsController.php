<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MetaFritterVerso\FritterDynamic;
use Illuminate\Support\Facades\DB;

class CatalogsController extends Controller
{

    public function showAll()
    {
        $form = FritterDynamic::itemsForm("Catalogos");
        $columns = [];
        $props_table = [];
        $data = [];

        $response = [
            "status" => 200,
            "data" => $data,
            "formItems" => $form,
            "columns" => $columns,
            "props_table" => $props_table,
            "message" => "Información Actualizada",
            "type" => "success"
        ];

        // return response()->json($response);
        $response  = response()->json($response);
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
    }

    public function showCatalog($id)
    {
        $cat_table_id = $id;
        $form = [];
        $columns = [];
        $props_table = [];
        $data = [];
        $name_id = "";

        if ($cat_table_id > 0) {
            $sys_cat_tables = DB::table('sys_cat_tables')->where('cat_table_id', $cat_table_id)->get();
            foreach ($sys_cat_tables as $value) {
                $name_table = $value->name_table;
                $view_table = $value->name_view;
                $form_id = $value->form_id;
                $table_id = $value->table_id;
                $name_id = $value->pk;
            }
            $table_name = DB::table('sys_tables')->where('table_id', $table_id)->get();
            $form_name = DB::table('sys_forms')->where('forms_id', $form_id)->get();

            foreach ($form_name as $value) {
                $aux_name_form = $value->name_form;
            }
            foreach ($table_name as $value) {
                $aux_name_table = $value->name_table;
            }

            $form = FritterDynamic::itemsForm($aux_name_form);
            $columns = FritterDynamic::columnsTable($aux_name_table);
            $props_table = FritterDynamic::propsTable($aux_name_table);
            if ($view_table == "NA") {
                if ($name_table === "sys_cat_tables") {
                    $data = DB::table($name_table)->where('type', 'operation')->where('status', 'alta')->get();
                } else {
                    $data = DB::table($name_table)->where('status', 'alta')->get();
                }
            } else {
                $data = DB::table($view_table)->where('status', 'alta')->get();
            }
        }

        $response = [
            "status" => 200,
            "data" => $data,
            "id" => $name_id,
            "formItems" => $form,
            "columns" => $columns,
            "props_table" => $props_table,
            "message" => "Información Actualizada",
            "type" => "success"
        ];
        // return response()->json($response);
        $response  = response()->json($response);
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
    }

    public function create($id, Request $request)
    {
        $params = $request->all();
        // $arr = $params['parametros'];
        
        $params = $params[0];
        $params = FritterDynamic::opensslDesEncrypt($params);
        $arr = (array) json_decode($params);


        $sys_cat_tables = DB::table('sys_cat_tables')->where('cat_table_id', $id)->get();
        foreach ($sys_cat_tables as $value) {
            $name_table = $value->name_table;
        }
        $cont = 0;
        // Desarrollo para cachar valores del multiple select 
        // $aux_arr_val = [];
        // $aux_val = "";
        // $aux_key = "";
        // foreach ($arr as $key => $value) {
        //     $objeto = $value->$key;
        //     if(is_array($objeto)){
        //         $aux_key = $key;
        //         $arrLength = sizeof($objeto);

        //     }else{
        //         $aux_arr_val += [$key=>$value];
        //     }

        // }
        if ($cont == 0) {
            DB::table($name_table)->insert($arr);
        }

        $response = [
            "status" => 200,
            "message" => "Se creo correctamente el registro!",
            "type" => "success",
            "tipoComponent" => "notification",
            "sys_cat_tables" => $sys_cat_tables
        ];
        // return response()->json($response, 200);
        $response  = response()->json($response);
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
    }

    public function update($idcatalog, $idcolumn, Request $request)
    {
        $params = $request->all();
        // $arr = $params['parametros'];

        $params = $params[0];
        $params = FritterDynamic::opensslDesEncrypt($params);
        $arr = (array) json_decode($params);

        $sys_cat_tables = DB::table('sys_cat_tables')->where('cat_table_id', $idcatalog)->get();
        foreach ($sys_cat_tables as $value) {
            $name_table = $value->name_table;
            $pk = $value->pk;
        }

        DB::table($name_table)->where($pk, $idcolumn)->update($arr);

        $response = [
            "status" => 200,
            "message" => "Se actualizo correctamente el registro!",
            "type" => "success",
            "tipoComponent" => "notification"
        ];

        // return response()->json($response, 200);
        $response  = response()->json($response);
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
    }

    public function delete($idcatalog, $idcolumn)
    {
        $sys_cat_tables = DB::table('sys_cat_tables')->where('cat_table_id', $idcatalog)->get();
        foreach ($sys_cat_tables as $value) {
            $name_table = $value->name_table;
            $pk = $value->pk;
        }

        DB::table($name_table)->where($pk, $idcolumn)->update(['status' => 'baja']);

        $response = [
            "status" => 200,
            "message" => "Se elimino correctamente el registro!",
            "type" => "success",
            "tipoComponent" => "notification"
        ];
        // return response()->json($response, 200);
        $response  = response()->json($response);
        $response = FritterDynamic::opensslEncrypt($response);
        return $response;
    }
}
