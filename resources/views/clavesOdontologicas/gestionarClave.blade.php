@extends('layouts.app')
@section('title','Gestionar Clave Odontológica')
@section('content')
    <hr/>
    <h4>Datos del Beneficiario</h4>
    @if (isset($beneficiario))
        <div class="table">
            <table class="table table-bordered table-striped table-hover table-responsive">
                <thead>
                    <tr>
                        <th>Cédula</th><th>Nombre</th><th>Tipo</th><th>Cobertura del Plan</th><th>Colectivo</th><th>Aseguradora</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $beneficiario['cedula_afiliado'] }}</td>
                        <td>{{ $beneficiario['nombre_afiliado'] }}</td>
                        <td>{{ $beneficiario['tipo_afiliado'] }}</td>
                        <td>{{ $beneficiario['plan'] }}</td>
                        <td>{{ $beneficiario['colectivo'] }}</td>
                        <td>{{ $beneficiario['aseguradora'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
    <div class="col-sm-2 col-sm-offset-1">
        <a href="#" class="btn btn-warning" id="btnClave">Clave de primera vez</a>
    </div>
    <div class="col-sm-3 col-sm-offset-4">
        {!! Form::submit('Gestionar Secuencia', ['class' => 'btn btn-primary form-control', 'id' => 'seleccionar', 'disabled' => 'disabled']) !!}
    </div>
    <label></label>
    @endif
    <hr>
    <div class="col-sm-12">
        <div id="formClavePrimaria" hidden="true" >
            <h4>Datos de la Atención Odontológica</h4>
            {!! Form::open(['url' => 'clavesOdonto', 'class' => 'form-horizontal', 'id' => 'procesarClave', 'name' => 'procesar', 'lang' => 'es', 'data-parsley-validate' => '']) !!}
            <div class="form-group {{ $errors->has('fecha_atencion1')}}">
                {!! Form::label('fecha_atencion1', 'Fecha de Atención: ', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-3">
                    {!! Form::date('fecha_atencion1', null, ['class' => 'form-control input-sm', 'required' => 'required','placeholder' => 'dd-mm-aaaa']) !!}
                    {!! $errors->first('fecha_atencion1', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('telefono')}}">
                {!! Form::label('telefono', 'Teléfono Móvil: ', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-2">
                    {!! Form::text('telefono', null, ['class' => 'form-control', 'required' => 'required','placeholder' => '04XX-1234567','pattern' => '\b04\d{2}[-]{1}\d{7}\b']) !!}
                    {!! $errors->first('telefono', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{  $errors->has('tipo_control') }}">
                {!! Form::label('tipo_control', 'Tipo de Control: ', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-2">
                    {!! Form::select('tipo_control', $tipo_control,1, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('tipo_control', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('codigo_proveedor') ? 'has-error' : ''}}">
                {!! Form::label('codigo_proveedor', 'Proveedor: ', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-5">
                    @if (isset($proveedor))
                        {!! Form::label('codigo_proveedor_creador', $proveedor->nombre, ['class' => 'control-label']) !!}
                        {!! Form::hidden('codigo_proveedor_creador',$proveedor->codigo_proveedor,['class' => 'form-control']) !!}
                    @else
                    <div  class="ui-widget" id='div_proveedor'>
                        {!! Form::text('codigo_proveedor', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! Form::hidden('codigo_proveedor_id', null, ['id' => 'codigo_proveedor_id', 'required' => 'required']) !!}
                    </div>
                    @endif
                    {!! $errors->first('codigo_proveedor_creador', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            {!! Form::hidden('max', 0, ['class' => 'form-control', 'required' => 'required','id' => 'max']) !!}
            {!! Form::hidden('cedula_afiliado', $beneficiario['cedula_afiliado'], ['class' => 'form-control','required' => 'required', 'id' => 'cedula_afiliado']) !!}
            {!! Form::hidden('codigo_contrato', $beneficiario['contrato'], ['class' => 'form-control','required' => 'required', 'id' => 'codigo_contrato']) !!}
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4"><!--   -->
                    {!! Form::submit('Generar Clave', ['class' => 'btn btn-primary form-control', 'id' => 'enviar_clave']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('script')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

    <script>
        $(function(){
            //$("#fecha_atencion1").datepicker({ maxDate: "+15D", dateFormat: "dd-mm-yy", beforeShowDay:$.datepicker.noWeekends});
            //$('#procesarClave').parsley();
            $('#btnClave').on('click', function(){
                $('#formClavePrimaria').show()
            });
            $('#iclave').on('click',function(){
                $('#seleccionar').prop('disabled', false);
            });
            
            $( "#codigo_proveedor" ).autocomplete({
                delay: 0,
                source: function(request, response){
                    $.ajax( {
                      url: "{{url('clavesOdonto/proveedores')}}",
                      dataType: "json",
                      data: {
                        q: request.term
                      },
                      success: function( data ) {
             
                        // Handle 'no match' indicated by [ "" ] response
                        response( data.length === 1 && data[ 0 ].length === 0 ? [] : data );
                      }
                    });
                },
                focus: function( event, ui ) {
                    $( "#codigo_proveedor" ).val( ui.item.nombre );
                    return false;
                },
                select: function( event, ui ) {
                    $( "#codigo_proveedor" ).val( ui.item.nombre );
                    $( "#codigo_proveedor_id" ).val( ui.item.codigo_proveedor );
             
                    return false;
                }
            })
            .autocomplete( "instance" )._renderItem = function( ul, item ) {
                return $( "<li>" )
                    .append( "<div>" + item.nombre + "<br></div>" )
                    .appendTo( ul );
            };

        });

    </script>
    <style>
        #codigo_proveedor {
            display: block;
            font-weight: bold;
            margin-bottom: 1em;
          }
    </style>
@endsection