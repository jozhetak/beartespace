@extends('layouts.app')

@section('title') Gallery Registration | @parent @endsection

@section('content')

    <div class="app--wrapper">
        Gallery invitation and registration
        <el-button><a href="{{ route('register') }}">Register</a></el-button>

    </div>

@endsection
