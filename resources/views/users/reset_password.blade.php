@extends('layouts.app')
@section('page_title')
    Reset password
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">



        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">


                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                {!! Form::open([
                    'action'=>'UserController@saveResetPassword',
                    'method'=>'POST',
                ]) !!}
                @include('partials.validation_errors')
                @include('flash::message')
                <div class="form-group">
                    <label>Current password</label>
                    <input class="form-control" type="password" name="oldpassword">

                    <label>New password</label>
                    <input class="form-control" type="password" name="password">

                    <label>Confirm new password</label>
                    <input class="form-control" type="password" name="password_confirmation">
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                {!! Form::close()!!}

            </div>
            <!-- /.box-body -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->

@endsection
