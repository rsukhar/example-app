@extends('blade_pages.layouts.app')

@section('title', 'Данные о проекте')

@section('header', $project['title'])

@section('content')
<p>{{__('Автор: ') }} {{ $project['owner_id'] }}</p>
<p>{{__('Ответственный: ') }}{{ $project['assignee_id'] }}</p>
<p>{{__('Срок: ') }}{{ $project['deadline_date'] }}</p>
@endsection