@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h2>
                        <img src="{{ asset($profileUser->avatar()) }}" alt="{{ $profileUser->name }}" width="50" height="50">
                        {{ $profileUser->name }}
                    </h2>
                    @can('update', $profileUser)
                        <form method="POST" action="{{ route('avatar', $profileUser) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="file" name="avatar">
                            <button class="btn btn-primary"> Upload Avatar </button>
                        </form>
                    @endcan
                </div>
                @forelse($timeline as $date => $activities)
                    <div class="page-header text-center">
                        <h4>{{ $date }}</h4>
                    </div>
                    @foreach($activities as $activity)
                        @if(view()->exists("profiles.activities.{$activity->type}"))
                            @include("profiles.activities.{$activity->type}")
                        @endif
                    @endforeach
                @empty
                     <div class="panel panel-default">
                         <div class="panel-body">
                            {{ $profileUser->name  }} has no activities yet. 
                         </div>
                     </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection