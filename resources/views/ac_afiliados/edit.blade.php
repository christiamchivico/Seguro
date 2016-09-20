@extends('layouts.app')

@section('content')

    <h1>Edit Ac_afiliado</h1>
    <hr/>

    {!! Form::model($ac_afiliado, [
        'method' => 'PATCH',
        'url' => ['afiliado/ac_afiliados', $ac_afiliado->id],
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

@endsection