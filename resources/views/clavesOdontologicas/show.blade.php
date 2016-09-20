@extends('layouts.app')
@section('title','Consulta Clave Odontológica Detalle')
@section('content') 
@if (isset($afiliado))
<h4>Afiliado</h4>
    @foreach ($afiliado as $data_clave)    
    <div class="table-responsive">    
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Cédula del Afiliado</th>
                    <th>Nombre</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Email</th>
                    <th>Sexo</th>
                    <th>Télefono</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data_clave->cedula_afiliado}}</td>
                    <td>{{ $data_clave->nombre}}</td>
                    <td>{{ date("d-m-Y",strtotime($data_clave->fecha_nacimiento)) }}</td>
                    <td>{{ $data_clave->email}}</td>     
                    @if ($data_clave->sexo == 'M')
                      <td>Masculino</td>
                    @endif
                    @if ($data_clave->sexo == 'F')
                      <td>Femenino</td>
                    @endif
                    <td>{{ $data_clave->telefono}}</td>                                                            
                </tr>
            </tbody>
        </table>
    </div>
    @endforeach 
@endif
    <h4>Clave</h4>
    @foreach ($clave as $data_clave)    
    <div class="table-responsive">    
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Tipo</th>
                    <th>Fecha Atención</th>                    
                    <th>Estatus</th>                                                 
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data_clave->clave}}</td>
                    <td>{{ $data_clave->acTipoControl->descripcion}}</td>
                    <td>{{ date("d-m-Y",strtotime($data_clave->fecha_atencion1)) }}</td>                    
                    <td>{{ $data_clave->acEstatus->nombre}}</td>                                   
                </tr>
            </tbody>
        </table>
    </div>     
    @endforeach 
    <h4>Detalle Clave</h4> 
    <div class="table-responsive">    
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Especialidad</th>
                    <th>Procedimiento</th>    
                    <th>Proveedor</th>   
                    <th>Costo</th>   
                </tr>
            </thead>
            <tbody>
              
            </tbody>
        </table>
    </div>
@endsection