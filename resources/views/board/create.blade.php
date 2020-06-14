@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create new board</div>

                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::model($board, ['route' => ['board.store']]) !!}
                        <div class="form-group">
                            {!! Form::label('label', __("Board title:"), ['for' => 'boardTitle']) !!}
                            {!! Form::text('label', null, [
                                'class' => 'form-control input-lg',
                                'aria-describedby' => 'boardTitleHelp',
                                'placeholder' => __('Board to create'),
                            ]) !!}
                            <small id="boardTitleHelp" class="form-text text-muted">
                                The board title must be unique.
                            </small>
                        </div>

                        <div class="form-group">
                            {!! Form::submit(__('Create!'), ['class' => 'btn btn-primary']) !!}
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
