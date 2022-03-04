@extends('layouts.manager_app')

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
                {{--                left side--}}
                <div class="col-xl-4 col-12 px-0 pb-3 bg-light">
                    <h5 class="mt-lg-3 mt-2 ml-4 font-weight-bolder">Pracovné čaty</h5>
                    <table class="table working-group-table" id="workingGroupsTable">
                        <thead>
                        <tr class="text-center">
                            <th scope="col">Vozidlo</th>
                            <th scope="col">Počet zamestnancov</th>
                            <th scope="col">Priradené kategórie</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($workingGroups as $workingGroup)
                            <tr class="text-center" id="vehicleId-{{$workingGroup->vehicle->vozidlo_id}}">
                                <td>
                                    <div>
                                        {{$workingGroup->vehicle->oznacenie}}
                                    </div>
                                    <div>
                                        {{$workingGroup->vehicle->SPZ}}
                                    </div>
                                </td>
                                <td class="pt-4">{{count($workingGroup->users)}}</td>
                                <td class="text-left pl-4">
                                    @foreach($workingGroup->assignedCategories as $assignedCategory)
                                        <div>
                                            {{$categories[$assignedCategory->kategoria_problemu_id - 1]->nazov}}
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-secondary ml-3" data-toggle="modal" data-target="#createWorkingGroup">
                        Vytvoriť novú
                    </button>

                    <h5 class="mt-5 ml-4 font-weight-bolder">Dostupné vozidlá</h5>
                    <div id="vehicleTableHolder">
                        <table class="table working-group-table" id="vehiclesTable">
                            <thead>
                            <tr class="text-center">
                                <th scope="col">Označenie</th>
                                <th scope="col">SPZ</th>
                                <th scope="col">Počet najazdených km</th>
                                <th scope="col">Zvoľ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($availVehicles as $availVehicle)
                                <tr class="text-center vehicle-row">
                                    <td>
                                        <div>
                                            {{$availVehicle->oznacenie}}
                                        </div>
                                    </td>
                                    <td>{{$availVehicle->SPZ}}</td>
                                    <td>
                                        {{$availVehicle->pocet_najazdenych_km}}
                                    </td>
                                    <td>
                                        <input type="checkbox" class="add-vehicle-cb" value="{{$availVehicle->vozidlo_id}}">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-secondary ml-3 mt-3" data-toggle="modal" data-target="#createVehicleModal">
                        Pridať vozidlo
                    </button>

                    <button type="button" class="btn btn-sm btn-info mr-3 float-md-right mt-3" id="addVehicleToGroup" data-toggle="modal" data-target="#assignVehToGroupModal">
                        Priradiť vozidlo pracovnej čate
                    </button>
                </div>

                {{--                right side--}}
                <div class="col-xl-8 col-12">
                    <div class="row mt-lg-3 mt-2 pl-2">
                        <h5 class="col-12 col-md-6 col-lg-7 col-xl-8 font-weight-bolder">Detail pracovnej čaty</h5>

                        <div class="row col-12 col-md-6 col-lg-5 col-xl-4">
                            <label for="vehicleProblems" class="col-12 col-sm-6 col-md-5 col-lg-5 p-md-0">Vyber vozidlo čaty</label>
                            <div class="col-sm-6 col-12 col-md-7 col-lg-7 pl-md-0">
                                <select
                                    id="vehicleProblems" class="input-filter form-input w-100" name="vehicleProblems">
                                    <option value="" selected disabled hidden>Zvoľte pracovnú čatu</option>
                                    @foreach($workingGroups as $workingGroup)
                                        <option value={{$workingGroup->vehicle->vozidlo_id}}>{{$workingGroup->vehicle->oznacenie}}, {{$workingGroup->vehicle->SPZ}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row manage-group-problems">
                        {{--                        @include('components.manager.manageGroupProblems')--}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.manager.manager_createWorkingGroup')

    @include('partials.manager.manager_assignVehicleToGroup')

    @include('partials.manager.manager_createVehicle')

    <script>
        setInterval(function () {
            $(".alert").fadeOut();
        }, 6000);

        $('.vehicle-row').on('click', function() {
            $('#vehiclesTable').find('.active').removeClass('active');
            // let vehicleID = ($(this).attr('id')).split('-')[1];
            let cb = $(this).find('.add-vehicle-cb');
            if (cb.is(':checked')) {
                cb.prop('checked', false);
                $('#addVehicleToGroup').hide();
            }
            else {
                $('.add-vehicle-cb').not(this).prop('checked', false);
                cb.prop('checked', true);
                $(this).toggleClass('active');
                $('#addVehicleToGroup').show();
            }
        });

        $('.group-row').on('click', function() {
            $('#selectGroupTable').find('.active').removeClass('active');
            let cb = $(this).find('.select-group-cb');
            if (cb.is(':checked')) {
                cb.prop('checked', false);
                $('#assignVehicle').hide();
            }
            else {
                $('.select-group-cb').not(this).prop('checked', false);
                cb.prop('checked', true);
                $(this).toggleClass('active');
                $('#assignVehicle').show();
            }
        });

        $('#assignVehToGroupModal').on('hidden.bs.modal', function () {
            $('#assignVehicle').hide();
            $('#selectGroupTable').find('.active').removeClass('active');
            $(this).find('form').trigger('reset');
        });

        $('#assignVehicle').on('click', function() {
            let newVehicleID = $('#vehiclesTable').find('.active').find('.add-vehicle-cb').val();
            let workingGroup = $('#selectGroupTable').find('.active').find('.select-group-cb').val();
            if (workingGroup == null) {
                $('#assignVehToGroupModal').find('.modal-body').html('Something went wrong');
            }
            else if (newVehicleID == null) {
                $('#assignVehToGroupModal').find('.modal-body').html('Something went wrong');
            }
            else {
                workingGroup = workingGroup.split('-');
                let workingGroupID = workingGroup[0];
                let oldVehicleID = workingGroup[1];

                $.ajax({
                    url:'/changeAssignedVehicle',
                    type:'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{newVehicleID:newVehicleID, oldVehicleID:oldVehicleID, workingGroupID:workingGroupID},
                    success:function(){
                        window.location= '{{ route('manageWorkingGroups') }}';
                    },
                    error: function () {
                        $('#assignVehToGroupModal').find('.modal-body').html('Something went wrong');
                    }
                });
            }

        });
    </script>
@endsection