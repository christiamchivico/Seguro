<?php
namespace App\Http\Controllers;

use DB;
use Session;
use Carbon\Carbon;
use App\Http\Requests;
use App\Models\AcClave;
use App\Models\AcClavesDetalle;
use App\Models\AcAfiliado;
use App\Models\AcAfiliadoTemporal;
use App\Models\AcFeriado;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use \Database\Query\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClaveController extends Controller{
    
    function RandomClave($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE){
        $source = 'abcdefghijklmnopqrstuvwxyz';
        if($uc==1){ $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';}
        if($n ==1){ $source .= '1234567890';}
        if($sc==1){ $source .= '|@#~$%()=^*+[]{}-_';}
        if($length>0){
            $rstr = "";
            $source = str_split($source,1);
            for($i=1; $i<=$length; $i++){
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1,count($source));
                $rstr .= $source[$num-1];
            }

        }
        return $rstr;
    }
    /**
     * Procesar Guardado de Claves
     * 
     * @return Response
     */
    public function procesarGuardar(Request $request){
       $monto_total = 0;     
       $costo       = 0;
       $monto       = array();
       
//       if (isset($request->max) &&($request->max == 0)){
//           Session::flash('result', 'Debe agregar al menos un prpcedimiento');
//           return false;
//          // return redirect::to('claves/generarFinal')->with('result', 'Debe agregar al menu un procedimiento');
//       }     
      /**
       * Se Obtiene el id del Usuario y el codigo del Proveedor  de la Session 
       */
       $user = \Auth::user();
       $request = array_add($request, 'codigo_proveedor_creador', $user->proveedor);
       $request = array_add($request, 'creador', $user->id);
      /**
       * Creacion de la Clave
       */
       $clave   = $this->RandomClave($length=8,$uc=TRUE,$n=0,$sc=FALSE);     
       $request = array_add($request, 'clave', $clave);
      /**
       * Validacion del codigo_contrato = 0, estatus = 5 => Pendiente, sino estatus = 3 => Aprobado
       */
        if (( isset($request->codigo_contrato)) && ($request->codigo_contrato == 0)){
            $estatus_clave = 5;
            $request = array_add($request, 'estatus_clave', $estatus_clave);
        } elseif (isset($request->codigo_contrato)){
             $estatus_clave = 3;
              $request = array_add($request, 'estatus_clave', $estatus_clave);            
        }
        for($i = 0; $i < $request->max; $i++):
           $baremos['id_procedimiento'] = $request->input(['id_tratamiento'.$i]);
           $baremos['id_proveedor']     = $request->input(['id_proveedor'.$i]);                                      
           $baremo = DB::table('ac_baremos')
          ->where([['id_procedimiento', '=', $baremos['id_procedimiento']], ['id_proveedor','=',$baremos['id_proveedor']]])
          ->select('monto' )
          ->get();                       
          foreach ($baremo as $data) {
             $monto[$i]    =   $data->monto;                
             $monto_total = $monto_total + $data->monto;
          }
        endfor; 
        $request = array_add($request, 'costo_total', $monto_total);
        $request = array_add($request, 'cantidad_servicios', count($monto_total));
        $claves = $this->store($request);
        if(isset($claves)){
            for($i = 0; $i < $request->max; $i++):                
                 $clavesDetalle = new AcClavesDetalle;   
                 $clavesDetalle->id_clave             = $claves->id;
                 $clavesDetalle->codigo_servicio      = $request->input(['id_servicio'.$i]);
                 $clavesDetalle->codigo_especialidad  = $request->input(['id_especialidad'.$i]);
                 $clavesDetalle->id_procedimiento     = $request->input(['id_tratamiento'.$i]);
                 $clavesDetalle->costo                = $monto[$i];
                 $clavesDetalle->codigo_proveedor     = $request->input(['id_proveedor'.$i]);
                 $clavesDetalle->detalle              = $request->detalle_servicio;      
                 $clavesDetalle->save();
                 Session::flash('status', 'Su clave '.$clave.' ha sido generada!');
                 return view('claves.generar');
            endfor;

         }else{
                 Session::flash('respuesta', 'Ocurrió un error al generar la Clave. ');
                 return view('claves.generar');
              }
    }
    /**
     * Validar Horario Creación de Claves.
     * 
     * @return Response
     */
    public function validarHorario(){
        $officialDate = Carbon::now();
        $feriado = new AcFeriado();
        if(!$officialDate->isWeekend() && ($officialDate->hour >= 6 && $officialDate->hour <= 19) && !($feriado->isFeriado())){
            return true;
        }else{
            return false; 
// . $officialDate->toDayDateTimeString() . "  hora " . $officialDate->hour . " minuto " . $officialDate->minute . " w " . $officialDate->isWeekend() . " " . $officialDate->tzName);
        }
    }
    /**
     * Display a listing of the resource.
     * @param Request
     * @return Response
     */
    public function generar(Request $request){
        if($this->validarHorario()){
            
        }else{
            //redirect('home')->with('message', 'No se encuentra dentro del Horario Autorizado.');
        }
        if(isset($request->cedula)){
            if(empty($request->cedula)){
                return redirect()->back()->withInput()->with('message', 'El campo cédula de obligatorio.');
            }else{
                try{
                    $afiliadoIni = AcAfiliado::where('cedula', '=', $request->cedula)->firstOrFail();
                }catch(ModelNotFoundException $e){  // catch(Exception $e) catch any exception
                    //dd(get_class_methods($e)); // lists all available methods for exception object
                    $tipoAfiliado = \App\Models\AcTipoAfiliado::pluck('nombre', 'id')->toArray();
                    $estado = \App\Models\AcEstado::pluck('es_desc', 'es_id')->toArray();
                    $aseguradora = \App\Models\AcAseguradora::pluck('nombre', 'codigo_aseguradora')->toArray();    
                    try{
                        $afiliadosTemporale = AcAfiliadoTemporal::where('cedula', '=', $request->cedula)->firstOrFail();
                        $colectivo = \App\Models\AcColectivo::pluck('nombre', 'codigo_colectivo')->toArray();
                        return view('afiliadosTemporales.edit', compact('afiliadosTemporale','tipoAfiliado','aseguradora','estado','colectivo'));
                    }catch(ModelNotFoundException $e){
                        return redirect()->back()->withInput()->with('respuesta', 'No existe el Afiliado. Presione si desea ');
                        //return view('afiliadosTemporales.create', compact('tipoAfiliado','aseguradora','estado'));
                    }
                }
                $contratos = DB::table('ac_contratos')
                            ->where([['cedula_titular', '=', $afiliadoIni->cedula_titular],['fecha_inicio','<=',date('Y-m-d').' 00:00:00'],['fecha_fin','>=',date('Y-m-d').' 00:00:00']])
                            ->join('ac_afiliados', 'ac_afiliados.cedula',"=", 'ac_contratos.cedula_afiliado')
                            ->join('ac_tipo_afiliado', 'ac_afiliados.tipo_afiliado',"=", 'ac_tipo_afiliado.id')
                            ->join('ac_planes_extranet', 'ac_planes_extranet.codigo_plan',"=", 'ac_contratos.codigo_plan')
                            ->join('ac_colectivos', 'ac_colectivos.codigo_colectivo',"=", 'ac_contratos.codigo_colectivo')
                            ->join('ac_aseguradora', 'ac_colectivos.codigo_aseguradora',"=", 'ac_aseguradora.codigo_aseguradora')
                            ->select('codigo_contrato','cedula_afiliado','ac_afiliados.nombre as nombre_afiliado','ac_afiliados.apellido as apellido_afiliado',
                                    'ac_planes_extranet.nombre as plan','ac_colectivos.nombre as colectivo','ac_aseguradora.nombre as aseguradora','ac_tipo_afiliado.nombre as tipo_afiliado')
                            ->get();
                return view('claves.generar', compact('contratos'));
            }
        }else{
            return view('claves.generar');
        }
    }
    /**
     * Display a listing of the resource.
     * @param  Request $request
     * @return Response
     */
    public function buscarCobertura(Request $request){
        
         $coberturas = DB::table('ac_procedimientos_medicos')
                ->where([['ac_procedimientos_medicos.codigo_especialidad','=',\Input::get('especialidad')],
                        ['ac_procedimientos_medicos.codigo_servicio','=',\Input::get('servicio')]])
                ->join('ac_cobertura_extranet', function($join){
                        $join->on('ac_procedimientos_medicos.codigo_examen'      ,"=", 'ac_cobertura_extranet.id_procedimiento')
                             ->on('ac_procedimientos_medicos.codigo_especialidad',"=", 'ac_cobertura_extranet.id_especialidad')
                             ->on('ac_procedimientos_medicos.codigo_servicio'    ,"=", 'ac_cobertura_extranet.id_servicio');
                })
                ->join('ac_aseguradora', 'ac_aseguradora.codigo_aseguradora',"=", 'ac_cobertura_extranet.id_aseguradora')
                ->join('ac_servicios_extranet', 'ac_servicios_extranet.codigo_servicio',"=", 'ac_procedimientos_medicos.codigo_servicio')
                ->join('ac_especialidades_extranet', 'ac_especialidades_extranet.codigo_especialidad',"=", 'ac_procedimientos_medicos.codigo_especialidad')
                ->select('ac_planes_extranet.nombre as plan','id_servicio','ac_servicios_extranet.descripcion as servicio',
                        'id_especialidad','ac_especialidades_extranet.descripcion as especialidad','id_procedimiento','tipo_examen');
        
        
        
        
        
        
        if(empty($request->icedula)){
            return redirect()->back()->withInput()->with('message', 'Debe seleccionar un beneficiario.');
        }
        $id = $request->input('icedula');
        $beneficiario['contrato'] = $request->input(['contrato'.$id]);
        $beneficiario['cedula_afiliado'] = $request->input('cedula_afiliado'.$id);
        $beneficiario['nombre_afiliado'] = $request->input('nombre_afiliado'.$id);
        $beneficiario['plan'] = $request->input('plan'.$id);
        $beneficiario['colectivo'] = $request->input('colectivo'.$id);
        $beneficiario['aseguradora'] = $request->input('aseguradora'.$id);
        $beneficiario['tipo_afiliado'] = $request->input('tipo_afiliado'.$id);
        $coberturas = DB::table('ac_contratos')
                ->where([['codigo_contrato', '=', $beneficiario['contrato']]])
                ->join('ac_planes_extranet', 'ac_planes_extranet.codigo_plan',"=", 'ac_contratos.codigo_plan')
                ->join('ac_cobertura_extranet', 'ac_cobertura_extranet.id_plan',"=", 'ac_planes_extranet.codigo_plan')
                ->join('ac_colectivos', 'ac_colectivos.codigo_colectivo',"=", 'ac_contratos.codigo_colectivo')
                ->join('ac_aseguradora', 'ac_aseguradora.codigo_aseguradora',"=", 'ac_colectivos.codigo_aseguradora')
                ->join('ac_procedimientos_medicos', function($join){
                        $join->on('ac_procedimientos_medicos.codigo_examen',"=", 'ac_cobertura_extranet.id_procedimiento')
                             ->on('ac_procedimientos_medicos.codigo_especialidad',"=", 'ac_cobertura_extranet.id_especialidad')
                             ->on('ac_procedimientos_medicos.codigo_servicio',"=", 'ac_cobertura_extranet.id_servicio');
                })
                ->join('ac_servicios_extranet', 'ac_servicios_extranet.codigo_servicio',"=", 'ac_procedimientos_medicos.codigo_servicio')
                ->join('ac_especialidades_extranet', 'ac_especialidades_extranet.codigo_especialidad',"=", 'ac_procedimientos_medicos.codigo_especialidad')
                ->select('codigo_contrato','ac_planes_extranet.nombre as plan','id_servicio','ac_servicios_extranet.descripcion as servicio',
                        'id_especialidad','ac_especialidades_extranet.descripcion as especialidad','id_procedimiento','tipo_examen')
                ->get(); // +++++++ array(StdClass)
        $especialidades_cobertura = array_pluck($coberturas,'especialidad','id_especialidad'); // ++++++++++++++++ ARRAY
        $servicios = array_pluck($coberturas,'servicio','id_servicio');
        $proveedores = DB::table('ac_procedimientos_medicos')
                            ->where([['ac_procedimientos_medicos.codigo_examen', '=', '1'],['ac_procedimientos_medicos.codigo_especialidad', '=', '3'],
                                ['ac_procedimientos_medicos.codigo_servicio', '=', '2']])
                            ->join('ac_baremos', 'ac_procedimientos_medicos.id',"=", 'id_procedimiento')
                            ->join('ac_proveedores_extranet', 'ac_proveedores_extranet.codigo_proveedor',"=", 'id_proveedor')
                            ->select('nombre','codigo_proveedor')->get();
        $proveedor = array_pluck($proveedores,'nombre','codigo_proveedor');
        return view('claves.generarFinal', compact('beneficiario','coberturas','especialidades_cobertura','servicios','proveedor'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $claves = AcClave::paginate(15);
        return view('claves.index', compact('claves'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('claves.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request){
//        $this->validate($request,  ['cedula_afiliado' => 'required|max:10',
//                                    'codigo_contrato' => 'required',
//                                    'fecha_cita'      => 'required|date', 
//                                    'telefono'        => 'required'
//                        ]);
        $acClave = AcClave::create($request->all());
        return $acClave;
        //return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        $clave = AcClave::findOrFail($id);

        return view('claves.show', compact('clave'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $clave = AcClave::findOrFail($id);

        return view('claves.edit', compact('clave'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, clave,cedula_afiliado,codigo_proveedor,codigo_contrato,codigo_especialidad,codigo_servicio,codigo_tipo_examen,estatus,estatus_clave,creador,tipo_afiliado);

        $clafe = AcClave::findOrFail($id);
        $clafe->update($request->all());

        Session::flash('flash_message', 'Clave actualizada!');

        return redirect('claves');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        AcClave::destroy($id);

        Session::flash('flash_message', 'Clave eliminada!');

        return redirect('claves');
    }

}
