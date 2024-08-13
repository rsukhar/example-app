@extends('blade_pages.layouts.app')

@section('title', 'Список проектов')

@section('header', 'Список проектов')

@section('content')
<x-alert :alert="$alert">
</x-alert>
@foreach ($projects as $project)
    <h2>{{ $project['title'] }}</h2>
    <p>{{ $project['deadline_date'] }}</p>
@endforeach
@endsection