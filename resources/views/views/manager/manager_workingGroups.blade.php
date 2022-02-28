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
                            <tr class="text-center group-row" id="vehicleId-{{$workingGroup->vehicle->vozidlo_id}}">
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
                        @foreach($workingGroups as $workingGroup)
                            <tr class="text-center vehicle-row" id="vehicleId-{{$workingGroup->vehicle->vozidlo_id}}">
                                <td>
                                    <div>
                                        {{$workingGroup->vehicle->oznacenie}}
                                    </div>
                                </td>
                                <td>{{$workingGroup->vehicle->SPZ}}</td>
                                <td>
                                    {{$workingGroup->vehicle->pocet_najazdenych_km}}
                                </td>
                                <td>
                                    <input type="checkbox" class="add-vehicle-cb" value="">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-secondary ml-3" data-toggle="modal" data-target="#createWorkingGroup">
                        Pridať vozidlo
                    </button>
                    <button type="button" class="btn btn-sm btn-info mr-3 float-md-right mt-1" id="addVehicleToGroup" data-toggle="modal" data-target="#assignVehToGroupModal">
                        Priradiť vozidlo pracovnej čate
                    </button>
                </div>

                <div class="col-xl-8 col-12">
                    <div class="row mt-lg-3 mt-2 pl-2">
                        <h5 class="col-12 col-md-6 col-lg-7 col-xl-8 font-weight-bolder">Detail pracovnej čaty</h5>

                        <div class="row col-12 col-md-6 col-lg-5 col-xl-4">
                            <label for="vehicleProblems" class="col-12 col-sm-6 col-md-5 col-lg-5 p-md-0">Vyber vozidlo čaty</label>
                            <div class="col-sm-6 col-12 col-md-7 col-lg-7 pl-md-0">
                                <select
                                    id="vehicleProblems" class="input-filter form-input w-100" name="vehicleProblems">
                                    <option value="" selected disabled hidden>Zvoľte pracovnú čatu</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value={{$vehicle->vozidlo_id}}>{{$vehicle->oznacenie}}, {{$vehicle->SPZ}}</option>
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

        @include('partials.manager.manager_createWorkingGroup')

        @include('partials.manager.manager_assignVehicleToGroup')

    </section>

    <script>
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
    </script>
@endsection
