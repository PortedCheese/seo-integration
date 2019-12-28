@extends('admin.layout')

@section('page-title', 'Meta - ')
@section('header-title', 'Meta list')

@section('admin')
    @include('seo-integration::admin.meta.create-static')

    @include('seo-integration::admin.meta.table-pages', ['pages' => $pages])
@endsection
