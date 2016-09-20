@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Edit AcTratamientoOdontologico {{ $actratamientoodontologico->id }}</h1>

    {!! Form::model($actratamientoodontologico, [
        'method' => 'PATCH',
        'url' => ['/ac-tratamiento-odontologico', $actratamientoodontologico->id],
        'class' => 'form-horizontal'
    ]) !!}

    

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    </div>
    {!! Form::close() !!}

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

</div>
@endsection