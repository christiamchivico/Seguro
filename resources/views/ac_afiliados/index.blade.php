@extends('layouts.app')

@section('content')

    <h1>Ac_afiliados <a href="{{ url('afiliado/ac_afiliados/create') }}" class="btn btn-primary pull-right btn-sm">Add New Ac_afiliado</a></h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($ac_afiliados as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    
                    <td>
                        <a href="{{ url('afiliado/ac_afiliados/' . $item->id . '/edit') }}">
                            <button type="submit" class="btn btn-primary btn-xs">Update</button>
                        </a> /
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['afiliado/ac_afiliados', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $ac_afiliados->render() !!} </div>
    </div>

@endsection
