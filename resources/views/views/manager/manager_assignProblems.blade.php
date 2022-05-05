@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <section class="main-container h-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-3 col-12 px-0 pb-3 bg-light">
                    @include('components.manager.workingGroupsTable')
                </div>

                <div class="col-xl-9 col-12">
                    <div class="row mt-lg-3 mt-2 pl-2">
                        <h5 class="col-12 col-md-6 col-lg-7 col-xl-8 font-weight-bolder">Detail pracovnej čaty</h5>

                        <div class="row col-12 col-md-6 col-lg-5 col-xl-4">
                            <label for="vehicleProblems" class="col-12 col-sm-6 col-md-5 col-lg-5 p-md-0">Vyber vozidlo čaty</label>
                            <div class="col-sm-6 col-12 col-md-7 col-lg-7 pl-md-0">
                                <select
                                    id="vehicleProblems" class="input-filter form-input w-100" name="vehicleProblems">
                                    <option value="" selected disabled hidden>Zvoľte pracovnú čatu</option>
                                    @foreach($workingGroups as $workingGroup)
                                        <option value={{$workingGroup->id}}>{{$workingGroup->vehicle->oznacenie}}, {{$workingGroup->vehicle->SPZ}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row manage-group-problems">
                        @include('components.manager.manageGroupProblems')
                    </div>
                </div>

            </div>
        </div>

        @include('partials.manager.manager_createWorkingGroup')

        @include('partials.manager.manager_deleteGroupConfirmation')

    </section>

    <script>
        setInterval(function () {
            $(".alert").fadeOut();
        }, 6000);

        $('#vehicleProblems').on('change', function() {
            let workingGroupID = $('#vehicleProblems').val();
            $('#workingGroupsTable').find('.active').removeClass('active');
            $('#workingGroupID-'+workingGroupID).toggleClass('active');

            $.ajax({
                url:'/manageGroupProblems/'+ workingGroupID,
                type:'GET',
                success:function(data){
                    $('.manage-group-problems').html(data);
                },
                error: function (data) {
                    $('.manage-group-problems').html('Something went wrong');
                }
            });
        });



    </script>
@endsection
