@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('All') }}
                        <div class="float-right">
                            <form method="get">
                                <div class="input-group mb-3">
                                    <input name="s" type="text" class="form-control" placeholder="Search here..."
                                           aria-describedby="button-addon2" value="{{request('s')}}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if($emails->count())
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">From</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($emails as $index=> $email)
                                    <tr data-toggle="collapse" data-target="#accordion{{$index}}" class="clickable"
                                        style="cursor: pointer">
                                        @php
                                            preg_match('#\<(.*?)\>#', $email->from, $match);
                                            $emailAddress = \Illuminate\Support\Arr::get($match,1)?: $email->from;
                                        $name = strtok($email->from, ' <');

                                        @endphp
                                        <th scope="row">{{ $emails->firstItem() + $index }}</th>
                                        <td><a href="mailto:{{$emailAddress}}" target="_blank">{{$name}}</a></td>
                                        <td>{{$email->subject}}</td>
                                        <td>{{Carbon\Carbon::parse($email->received_on)->format('M d h:j A')}}</td>
                                        <td><a class="delete-single-email"
                                               href="{{route('delete_email',['email'=>$email])}}">Delete</a></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">
                                            <div id="accordion{{$index}}" class="collapse">{{$email->body }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $emails->links() }}
                        @elseif(request('s'))
                            <span>{{__('No result found')}}</span>
                        @else
                            <span>{{__('Inbox is empty')}}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
