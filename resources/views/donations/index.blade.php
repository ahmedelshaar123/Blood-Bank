@extends('layouts.app')

@section('page_title')
    Donation
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">List of donations</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
               @if(count($records))
                   <div class="table table-responsive">
                       <table class="table table-bordered">
                           <thead>
                            <tr>
                                <th class="text text-center">#</th>
                                <th class="text text-center">Name</th>
                                <th class="text text-center">Age</th>
                                <th class="text text-center">Blood type</th>
                                <th class="text text-center">Bags number</th>
                                <th class="text text-center">Hospital name</th>
                                <th class="text text-center">Hospital address</th>
                                <th class="text text-center">Governorate</th>
                                <th class="text text-center">City</th>
                                <th class="text text-center">Phone</th>
                                <th class="text text-center">Notes</th>
                                <th class="text text-center">Delete</th>
                            </tr>
                           </thead>
                           <tbody>
                            @include("flash::message")
                            @foreach($records as $record)
                                <tr>
                                    <td class="text text-center">{{$loop->iteration}}</td>
                                    <td class="text text-center">{{$record->name}}</td>
                                    <td class="text text-center">{{$record->age}}</td>
                                    <td class="text text-center">{{optional($record->blood_type)->blood_type}}</td>
                                    <td class="text text-center">{{$record->bags_number}}</td>
                                    <td class="text text-center">{{$record->hospital_name}}</td>
                                    <td class="text text-center">{{$record->hospital_address}}</td>
                                    <td class="text text-center">{{optional($record->city->governorate)->name}}</td>
                                    <td class="text text-center">{{optional($record->city)->name}}</td>
                                    <td class="text text-center">{{$record->phone}}</td>
                                    <td class="text text-center">{{$record->note}}</td>
                                    <td class="text text-center">
                                        {!! Form::open([
                                            'action'=>['DonationController@destroy', $record->id],
                                            'method'=>'delete',

                                        ])!!}
                                        <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>Delete</button>
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
