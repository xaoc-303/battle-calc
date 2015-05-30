@extends('battle-calc::layouts.default')

@section('menu')

@stop

@section('content')
@if(isset($content))
{{$content}}
@else
Нет данных
@endif
@stop