@extends('layouts.app')
@section('title','Autorizar Claves y Autorizaciones Especiales Temporales')

@section('content') 
{!! Form::open(['url' => 'clavesEspeciales/autorizarTemporales', 'class' => 'form-horizontal', 'name' => 'autorizarClaveEspeciales','method' => 'GET']) !!}
        {!! $filter->render('ac_carta_aval.fecha_solicitud') !!}

        <div class="form-group {{ $errors->has('ac_afiliados_temporales.nombre') || $errors->has('ac_carta_aval.cedula_afiliado') ? 'has-error' : ''}}">
          {!! Form::label('ac_afiliados_temporales.nombre', 'Nombre Paciente: ', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-3">
              {!! $filter->field('ac_afiliados_temporales.nombre',['onchange'=>"ValidarAlpha(this.value,'ac_afiliados_temporales_nombre')"]) !!}
              {!! $errors->first('ac_afiliados_temporales.nombre', '<p class="help-block">:message</p>') !!}
          </div>
         
          {!! Form::label('ac_carta_aval.cedula_afiliado', 'C.I: ', ['class' => 'col-sm-2 control-label']) !!}
         <div class="col-sm-3">
              {!! $filter->field('ac_carta_aval.cedula_afiliado') !!}
              {!! $errors->first('ac_carta_aval.cedula_afiliado', '<p class="help-block">:message</p>') !!}
          </div>
        </div>      
        <div class="form-group {{ $errors->has('ac_carta_aval.clave') || $errors->has('ac_estatus.id') ? 'has-error' : ''}}">
          {!! Form::label('ac_carta_aval.clave', 'Clave: ', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-3">
              {!! $filter->field('ac_carta_aval.clave',['onchange'=>"ValidarAlpha(this.value,'ac_carta_aval_clave')"]) !!}
              {!! $errors->first('ac_carta_aval.clave', '<p class="help-block">:message</p>') !!}
          </div>
        </div>    
  {!!$filter->footer!!}    
  {!!$grid!!}    
  {{ Form::close() }}
@endsection
@section('script')
<script>
 function ValidarAlpha(valor,campo){
     var charRegExp = /^([a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/;
     var valor1 = valor;
             if (charRegExp.test(valor1)== true)
             {
                  return true;
              }else{ 
                  $("#result").addClass("alert alert-danger");
                  $("#result").html("Debe introducir solo carácteres Alfabéticos"); 
                  $("#"+campo).focus(); 
                   return false;
               }       
          };
    $("#fecha_desde" ).datepicker({ dateFormat: "dd/mm/yy" });
    $("#fecha_hasta" ).datepicker({ dateFormat: "dd/mm/yy" });          
</script>
@endsection