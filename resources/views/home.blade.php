@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!

                    <ul>
                        @foreach (Auth::user()->boards as $board)
                            <li>
                                <a href="{{ route('board.show', $board) }}">{{ $board->label }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('board.create') }}">
                        Add new board
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
