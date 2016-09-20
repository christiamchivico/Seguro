<?php
namespace App\Http\Controllers\ClaveOdontologica;

use DB;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ClaveController;
use App\Models\AcClaveOdontologica;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\ValidarFechaController;

class ClaveOdontologicaController extends Controller{

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request){
        /**
        * Se Obtiene el id del Usuario y el codigo del Proveedor  de la Session 
        */
        $user = \Auth::user();
        $request = array_add($request, 'creador', $user->id);
        
        if(isset($request->codigo_proveedor_id)){
            if(!empty($request->codigo_proveedor_id)){
                $request = array_add($request, 'codigo_proveedor_creador', $request->codigo_proveedor_id);
            }
        }else{
            $request = array_add($request, 'codigo_proveedor_creador', $user->proveedor);

        }
       /**
        * Creacion del campo Clave
        */
        $ClaveControlador = new ClaveController();
        $clave   = $ClaveControlador->RandomClave($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE);     
        $request = array_add($request, 'clave', $clave);
        $request = array_add($request, 'estatus', 1);   //ABIERTO
        
        $this->validate($request,  ['clave'           => 'required|max:10',
                                    'tipo_control'    => 'required',
                                    'cedula_afiliado' => 'required|max:10',
                                    'codigo_contrato' => 'required',
                                    'fecha_atencion1' => 'required|date', 
                                    'telefono'        => 'required'
                        ]);

        /*if(!($ValidarFecha->validarFecha($request))){
            return redirect('home')->with('message', 'Clave se encuentra fuera de rango de fechas  autorizado.');
        }*/
        $acClave = AcClaveOdontologica::create($request->all());
        //return $acClave;
        if(!$acClave){
            Session::flash('message', 'Ha ocurrido un error al generar la Clave Odontológica!');
        }else{
            Session::flash('status', 'Su clave '. $acClave->clave .' ha sido generada!');
        }
        return  view('clavesOdontologicas.gestionar');
        #return($this->show($acClave->id));
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
        $clave = AcClaveOdontologica::findOrFail($id);

        return view('clavesOdontologicas.show', compact('clave'));
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
        $clave = AcClaveOdontologica::findOrFail($id);

        return view('clavesOdontologicas.edit', compact('clave'));
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
        $this->validate($request,  ['clave'           => 'required|max:10',
                                    'tipo_control'    => 'required',
                                    'cedula_afiliado' => 'required|max:10',
                                    'codigo_contrato' => 'required',
                                    'fecha_atencion1' => 'required|date', 
                                    'telefono'        => 'required'
                        ]);
        
        $clafe = AcClaveOdontologica::findOrFail($id);
        $clafe->update($request->all());

        Session::flash('message', 'Clave Odontológica actualizada!');
         
        return redirect('clavesOdonto/gestionar');

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

        Session::flash('flash_message', 'Clave Odontológica eliminada!');

        return redirect('clavesOdonto');
    }
}