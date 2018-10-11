@extends('layouts.app')

@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')

    <div class="app--wrapper">

        <div class="app-articles">

            <div class="articles">


                @foreach($articles as $article)
                    <a href="{{ route('article', $article->id) }}" target="_blank" class="article">
                        <div class="article-image">
                            <img src="/imagecache/fit-290{{ $article->image_url }}" alt="">
                        </div>

                        <div class="h2">{{ $article->name }}</div>
                    </a>

                @endforeach
            </div>

            <div class="articles-bottom" style="text-align: center;margin: 50px 0;">

                <el-button><a href="/selection/artwork">See artworks of the week</a></el-button>

                @if($articles->hasMorePages())
                    <el-button><a href="{{  $articles->nextPageUrl() }}">See more Articles</a></el-button>
                @endif
                <el-button><a href="{{ route('artists') }}">Browse Artists</a></el-button>
                <el-button><a href="{{ route('auctions') }}">Go to Auctions</a></el-button>
            </div>

            {{ $articles->links() }}

        </div>


    </div>

@endsection