@extends('layouts.app')

@section('content')
  @include('partials.page-header')
  <div class="container">
    <div class="box py-6 px-6">
      <div class="content">
        @while(have_posts()) @php(the_post())
          @includeFirst(['partials.content-single-' . get_post_type(), 'partials.content-single'])
        @endwhile
      </div>
    </div>
  </div>
@endsection
