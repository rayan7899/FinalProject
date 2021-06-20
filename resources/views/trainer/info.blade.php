@extends('layouts.app')
@section('content')
<div class="container">
    <x-trainer-info :user="$user"/>
</div>
@stop