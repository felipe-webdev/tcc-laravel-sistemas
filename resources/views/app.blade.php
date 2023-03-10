@extends('base')

@section('meta-view')
@endsection

@section('css-view')
@endsection

@section('title-view')
  <title>RH Xpert</title>
@endsection

@section('vue-view')
  <main id="xpert">
    <xpert/>
  </main>
@endsection

@section('js-view')
  <script src="{{ asset('js/app.js') }}" type="module"></script>
@endsection