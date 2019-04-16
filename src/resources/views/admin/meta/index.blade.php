@extends('admin.layout')

@section('page-title', 'Meta - ')
@section('header-title', 'Meta list')

@section('admin')
    <div class="container">
        <div class="row">
            @include('seo-integration::admin.meta.create-static')
        </div>
        <div class="row">
            <div class="table-responsive">
                @include('seo-integration::admin.meta.table-pages', ['pages' => $pages])
            </div>
        </div>
    </div>
@endsection
