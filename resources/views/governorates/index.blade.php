@extends('layouts.app')

@section('page_title')
    Governorates
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">



        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">List of governorates</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <a href="{{url(route('governorate.create'))}}" class="btn btn-primary"> <i class="fa fa-plus"></i> New Governorate</a>
                @include('flash::message')
                @if(count($records))
                    <div class="table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($records as $record)
                                    <tr>
                                        <td class="text-center">{{$loop->iteration}}</td>
                                        <td class="text-center">{{$record->name}}</td>
                                        <td class="text-center">
                                            <a href="{{url(route('governorate.edit', $record->id))}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        </td>
                                        <td class="text-center">
                                            {!! Form::open([
                                                'action'=>['GovernorateController@destroy', $record->id],
                                                'method'=>'delete'
                                            ]) !!}
                                            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                                            {!! Form::close() !!}

                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="alert alert-danger" role="alert">
                            No data
                        </div>
                @endif
            </div>
            <!-- /.box-body -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->

@endsection
