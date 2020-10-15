@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Activities') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if($activities->count())
                            <table class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Activity</th>
                                    <th scope="col">User</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($activities as $index=> $activity)
                                    <tr>
                                        <th scope="row">{{ $activities->firstItem() + $index }}</th>
                                        <td>{{$activity->activity_type}}</td>
                                        <td>{{$activity->activity}}</td>
                                        <td>{{$activity->user?$activity->user->name:'System'}}</td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $activities->links() }}
                        @else
                            <span>{{__('No activities found')}}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
