@extends('layouts.simple')

@section('content')

    <el-main class="app--centered">

        <div class="app-address">
            <el-breadcrumb separator-class="el-icon-arrow-right" style="margin: 30px 0;">
                <el-breadcrumb-item><a href="/">Home</a></el-breadcrumb-item>
                <el-breadcrumb-item>Edit Address</el-breadcrumb-item>
            </el-breadcrumb>

            <address-form address_="{{ auth()->user()->address() }}"></address-form>

        </div>

    </el-main>

@stop