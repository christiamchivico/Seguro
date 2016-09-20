@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Actratamientoodontologico <a href="{{ url('/ac-tratamiento-odontologico/create') }}" class="btn btn-primary btn-xs" title="Add New AcTratamientoOdontologico"><span class="glyphicon glyphicon-plus" aria-hidden="true"/></a></h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($actratamientoodontologico as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    
                    <td>
                        <a href="{{ url('/ac-tratamiento-odontologico/' . $item->id) }}" class="btn btn-success btn-xs" title="View AcTratamientoOdontologico"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"/></a>
                        <a href="{{ url('/ac-tratamiento-odontologico/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit AcTratamientoOdontologico"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['/ac-tratamiento-odontologico', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true" title="Delete AcTratamientoOdontologico" />', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete AcTratamientoOdontologico',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ));!!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination-wrapper"> {!! $actratamientoodontologico->render() !!} </div>
    </div>

</div>
@endsection
