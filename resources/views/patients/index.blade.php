@extends('layouts.main')

@section('content')
    {{--<h1>--}}
        {{--pacienti--}}
    {{--</h1>--}}

           {{--<table class="table table-striped table-hover">--}}
           	{{--<thead>--}}
           		{{--<tr>--}}
           			{{--<th>#</th>--}}
           			{{--<th>Name</th>--}}
           			{{--<th></th>--}}
           			{{--<th></th>--}}
           		{{--</tr>--}}
           	{{--</thead>--}}
           	{{--<tbody>--}}
            {{--@foreach($patients as $patient)--}}
                {{--<tr>--}}
					{{--<td>--}}
						{{--{{ $loop->iteration }}--}}
					{{--</td>--}}
                    {{--<td>--}}
                        {{--{{ $patient->tags[0]->value }}--}}
                    {{--</td>--}}
					{{--<td>--}}
						{{--<a href="{{ route('res.show',['id' => $patient->publicId]) }}">dw</a>--}}
					{{--</td>--}}
                {{--</tr>--}}
                {{--@endforeach--}}
           	{{--</tbody>--}}
           {{--</table>--}}

<div class="container">
    <list :table-data="{{ $patients }}"></list>
</div>

@section('footer')
    This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render

@endsection
@endsection