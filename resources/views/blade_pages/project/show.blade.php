@extends('blade_pages.layouts.app')

@section('title', 'Данные о проекте')

@section('header', $project['title'])

@section('content')
<p>Автор: {{ $project['author_id'] }}</p>
<p>Ответственный: {{ $project['assignee_id'] }}</p>
<p>Срок: {{ $project['deadline_date'] }}</p>
@endsection