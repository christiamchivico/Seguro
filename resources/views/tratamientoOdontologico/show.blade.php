@extends('layouts.app')

@section('content')
<div class="container">

    <h1>AcTratamientoOdontologico {{ $actratamientoodontologico->id }}
        <a href="{{ url('ac-tratamiento-odontologico/' . $actratamientoodontologico->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit AcTratamientoOdontologico"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
        {!! Form::open([
            'method'=>'DELETE',
            'url' => ['actratamientoodontologico', $actratamientoodontologico->id],
            'style' => 'display:inline'
        ]) !!}
            {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"/>', array(
                    'type' => 'submit',
                    'class' => 'btn btn-danger btn-xs',
                    'title' => 'Delete AcTratamientoOdontologico',
                    'onclick'=>'return confirm("Confirm delete?")'
            ));!!}
        {!! Form::close() !!}
    </h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
                <tr>
                    <th>ID</th><td>{{ $actratamientoodontologico->id }}</td>
                </tr>
                
            </tbody>
        </table>
    </div>

</div>
@endsection
