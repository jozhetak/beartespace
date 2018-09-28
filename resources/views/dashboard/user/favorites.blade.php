@extends('dashboard.index')

@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('admin-content')


    <div class="app--wrapper">

        <div class="app-favorites">

            <el-breadcrumb separator-class="el-icon-arrow-right" style="margin-bottom: 30px;">
                <el-breadcrumb-item><a href="/">Home</a></el-breadcrumb-item>
                <el-breadcrumb-item><a href="/dashboard">Dashboard</a></el-breadcrumb-item>
                <el-breadcrumb-item>Favorites</el-breadcrumb-item>
            </el-breadcrumb>


            <el-tabs value="artworks">
                <el-tab-pane label="Artworks" name="artworks">
                    @include('partials.artworks', $artworks)
                </el-tab-pane>
                <el-tab-pane label="Artists" name="artists">Artists</el-tab-pane>
                <el-tab-pane label="Galleries" name="galleries">Galleries</el-tab-pane>
            </el-tabs>


        </div>

    </div>

@endsection


