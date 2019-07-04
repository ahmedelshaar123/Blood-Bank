@extends('layouts.app')

@section('page_title')
    Settings
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
                {!! Form::model($model,[
                'action' => ['SettingController@update',$model->id],
                'method' => 'put'
                ]) !!}
                @include('partials.validation_errors')
                @include('flash::message')
                <div class="form-group">
                    <label for="name">Facebook</label>
                    {!! Form::text('facebook_url',null,[
                    'class' => 'form-control'
                 ]) !!}

                    <label for="name">Twitter</label>
                    {!! Form::text('twitter_url',null,[
                    'class' => 'form-control'
                 ]) !!}

                    <label for="name">Youtube</label>
                    {!! Form::text('youtube_url',null,[
                    'class' => 'form-control'
                 ]) !!}

                    <label for="name">Instagram</label>
                    {!! Form::text('instagram_url',null,[
                    'class' => 'form-control'
                 ]) !!}

                    <label for="name">Googleplus</label>
                    {!! Form::text('googleplus_url',null,[
                    'class' => 'form-control'
                 ]) !!}

                    <label for="name">Whatsapp</label>
                    {!! Form::text('whatsapp_url',null,[
                    'class' => 'form-control'
                 ]) !!}

                    <label for="name">Phone</label>
                    {!! Form::text('phone',null,[
                    'class' => 'form-control'
                 ]) !!}

                    <label for="name">Email</label>
                    {!! Form::text('email',null,[
                    'class' => 'form-control'
                 ]) !!}

                    <label for="name">About app</label>
                    {!! Form::text('about_app',null,[
                    'class' => 'form-control'
                 ]) !!}

                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit">Update</button>
                </div>

                {!! Form::close () !!}
            </div>
            <!-- /.box-body -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->

@endsection
