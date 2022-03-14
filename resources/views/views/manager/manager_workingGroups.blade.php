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
                    @include('components.manager.workingGroupsTable')

                    <h5 class="mt-5 ml-4 font-weight-bolder">Dostupné vozidlá</h5>
                    <div class="tableHolderSlider">
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

                    <div class="mr-3 float-md-right mt-3" id="hiddenButtons">
                        <button type="button" class="btn btn-sm btn-info btn-block" data-toggle="modal" data-target="#assignVehToGroupModal">
                            Zmeniť vozidlo pracovnej čate
                        </button>

                        <button type="button" class="btn btn-sm btn-danger btn-block" data-toggle="modal" data-target="#assignVehToGroupModal">
                            Odstrániť vozidlo z evidencie
                        </button>
                    </div>

                </div>

                {{--                right side--}}
                <div class="col-xl-8 col-12">
                    <div class="row mt-lg-3 mt-2 pl-2">
                        <h5 class="col-12 col-md-6 col-lg-7 col-xl-8 font-weight-bolder">Detail pracovnej čaty</h5>

                        <div class="row col-12 col-md-6 col-lg-5 col-xl-4">
                            <label for="vehicleProblems" class="col-12 col-sm-6 col-md-5 col-lg-5 p-md-0">Vyber vozidlo čaty</label>
                            <div class="col-sm-6 col-12 col-md-7 col-lg-7 pl-md-0">
                                <select id="vehicleProblems" class="input-filter form-input w-100" name="vehicleProblems">
                                    <option value="" selected disabled hidden>Zvoľte pracovnú čatu</option>
                                    @foreach($workingGroups as $workingGroup)
                                        <option value={{$workingGroup->id}}>{{$workingGroup->vehicle->oznacenie}}, {{$workingGroup->vehicle->SPZ}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="group-detail">
                        <div class="row">
                            <div class="col-12 px-4">
                                <h4 class="text-center my-5">Vyberte pracovnú čatu na zobrazenie detailov</h4>
                                @if ($errors->has('newCategories'))
                                    <div class="alert alert-danger mt-3">
                                        <span>Zvoľte aspoň jednu katégóriu riešených problémov čaty!</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="group-detail-chart">
                    </div>

                </div>
            </div>
        </div>
    </section>

    @include('partials.manager.manager_createWorkingGroup')

    @include('partials.manager.manager_assignVehicleToGroup')

    @include('partials.manager.manager_createVehicle')

    @include('partials.manager.manager_deleteGroupConfirmation')

    <script>
        setInterval(function () {
            $(".alert").fadeOut();
        }, 6000);

        $(function () {
            $('[data-toggle="popover"]').popover()
        });

        $('#vehicleProblems').on('change', function() {
            let workingGroupID = $('#vehicleProblems').val();
            $('#workingGroupsTable').find('.active').removeClass('active');
            $('#workingGroupID-'+workingGroupID).toggleClass('active');

            $.ajax({
                url:'/workingGroupDetail/'+ workingGroupID,
                type:'GET',
                success:function(data){
                    $('.group-detail').html(data);
                    $('.group-detail-chart').html('<canvas id="groupChart"></canvas>');
                    $.ajax({
                        url: '/workingGroupChart/' + workingGroupID,
                        type: 'GET',
                        success: function (data) {
                            let months = ['Jan', 'Feb', 'Mar', 'Apr', 'Máj', 'Jún', 'Júl', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'];
                            let inProcessProbData = months.map((month, index) => {
                                let dataObj = {};
                                dataObj.monthName = month;
                                dataObj.countNr = data.inProcessProbMonths[index];
                                return dataObj;
                            });

                            let endedProbData = months.map((month, index) => {
                                let dataObj = {};
                                dataObj.monthName = month;
                                dataObj.countNr = data.finishedProbMonths[index];
                                return dataObj;
                            });

                            let ctx = $('#groupChart');
                            var config = {
                                type: 'bar',
                                data: {
                                    datasets: [
                                        {
                                            label: 'Počet vyriešených problémov',
                                            maxBarThickness: 10,
                                            data: endedProbData,
                                            backgroundColor: 'rgb(13,134,72)',
                                            parsing: {
                                                xAxisKey: 'monthName',
                                                yAxisKey: 'countNr'
                                            }
                                        },
                                        {
                                            label: 'Počet problémov v procese riešenia',
                                            maxBarThickness: 10,
                                            data: inProcessProbData,
                                            backgroundColor: 'rgb(255,172,0)',
                                            parsing: {
                                                xAxisKey: 'monthName',
                                                yAxisKey: 'countNr'
                                            }
                                        }
                                    ],
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    },
                                },
                            }
                            new Chart(ctx, config);
                        },
                        error: function () {
                            $('.group-detail-chart').html('Something went wrong');
                        }
                    });
                },
                error: function () {
                    $('.group-detail').html('Something went wrong');
                }
            });
        });

        // $('.group-row-main').on('click', function() {
        //     let workingGroupID = ($(this).attr('id')).split('-')[1];
        //     $('#vehicleProblems').val(workingGroupID).change();
        //     $('#deleteWorkingGroupBtn').show();
        // });
        //
        // $('#deleteWorkingGroupBtn').on('click', function() {
        //     let workingGroupID = ($('#workingGroupsTable').find('.active').attr('id')).split('-')[1];
        //     $('#deleteWorkingGroup').find('.modal-body').find('#workingGroupID').val(workingGroupID);
        // });

        $('.vehicle-row').on('click', function() {
            $('#vehiclesTable').find('.active').removeClass('active');
            let cb = $(this).find('.add-vehicle-cb');
            if (cb.is(':checked')) {
                cb.prop('checked', false);
                $('#hiddenButtons').hide();
            }
            else {
                $('.add-vehicle-cb').not(this).prop('checked', false);
                cb.prop('checked', true);
                $(this).toggleClass('active');
                $('#hiddenButtons').show();
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
            $('#assignVehicle').hide().find('.select-group-cb');
            let selGroup = $('#selectGroupTable').find('.active');
            selGroup.find('.select-group-cb').prop('checked', false);
            selGroup.find('.active').removeClass('active');
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
