@extends('layouts.main')

@section('content')
    {{--<h1>wef</h1>--}}
    {{--@foreach($instances as $instance)--}}
        {{--{{ $resource[0]->ims($instance->ID)}}--}}
        {{--<img src="data:image/png;base64, {{$resource[0]->ims($instance->ID)}}" alt="{{$instance->ID}}" height="250" width="600">--}}
    {{--@endforeach--}}

<patient-show :instances="{{ json_encode($instances) }}"></patient-show>
    @section('footer')
        This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
    @endsection
@endsection