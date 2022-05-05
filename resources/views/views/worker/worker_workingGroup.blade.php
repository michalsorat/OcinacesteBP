@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mt-xl-5 mt-md-4 mt-2 mx-1">
            <div class="col-lg-6 px-4">
                <h5 class="mt-4 mb-3 font-weight-bolder">Členovia pracovnej čaty</h5>
                <div class="tableHolderSlider" id="removeUsersForm">
                    <table class="table table-hover usersTable">
                        <thead>
                        <tr>
                            <th scope="col">Meno</th>
                            <th scope="col">Emailová adresa</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($workingGroup->users as $user)
                            <tr class="group-user-row-remove">
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-6 px-4 form-group" id="changeCatForm">

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mt-4 mb-3 font-weight-bolder">Vozidlo pracovnej čaty</h5>

                        <div class="form-group row">
                            <label for="spz" class="col-md-4 col-form-label col-form-label-sm">SPZ</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control form-control-sm" id="spz" value="{{ $workingGroup->vehicle->SPZ }}" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kmCount" class="col-md-4 col-form-label col-form-label-sm">Počet naj. km</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control form-control-sm" id="kmCount" value="{{ $workingGroup->vehicle->pocet_najazdenych_km }}" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="brand" class="col-md-4 col-form-label col-form-label-sm">Označenie</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control form-control-sm" id="brand" value="{{ $workingGroup->vehicle->oznacenie }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 pl-5">
                        <h5 class="mt-4 mb-3 font-weight-bolder">Kategórie riešených problémov</h5>

                        @foreach($categories as $category)
                            <div class="form-check my-1">
                                <input class="form-check-input categories-input" type="checkbox" name="newCategories[]" value="{{$category->kategoria_problemu_id}}"/>
                                <label class="form-check-label">
                                    {{$category->nazov}}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="form-group col-lg-6 px-4 mb-5">
                <h5 class="mt-4 mb-3 font-weight-bolder">História pracovnej čaty</h5>
                <div id="history-table-group-holder">
                    <table id="historyTable" class="table table-hover working-group-table">
                        <thead>
                        <tr class="text-center">
                            <th class="align-middle" scope="col">Zmena vykonaná dňa</th>
                            <th class="align-middle" scope="col">Typ</th>
                            <th class="align-middle" scope="col">Popis</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($workingGroup->history as $historyRecord)
                            <tr class="text-center">
                                <td>{{$historyRecord->created_at}}</td>
                                <td>{{$historyRecord->type}}</td>
                                <td>{{$historyRecord->description}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="group-detail-chart-worker col-lg-6 px-4 my-5">

            </div>

        </div>
    </div>

    @include('partials.manager.manager_workingGroupHistory')

    <script>
        $(document).ready(function () {
            let assignedCat = [];
            @foreach($workingGroup->assignedCategories as $category)
            assignedCat.push({{$category->kategoria_problemu_id}});
            @endforeach
            $('.categories-input[type=checkbox]').each(function () {
                if (assignedCat.includes(parseInt($(this).val()))) {
                    $(this).prop('checked', true);
                }
                else {
                    $(this).prop('checked', false);
                }
                $(this).prop('disabled', true);
            });

            let workingGroupID = {{ $workingGroup->id }}

            $('.group-detail-chart-worker').html('<canvas id="groupChart"></canvas>');
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

                    let approvalProbData = months.map((month, index) => {
                        let dataObj = {};
                        dataObj.monthName = month;
                        dataObj.countNr = data.forApproval[index];
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
                                },
                                {
                                    label: 'Počet problémov v čakajúcich na schválenie',
                                    maxBarThickness: 10,
                                    data: approvalProbData,
                                    backgroundColor: 'rgb(20,132,166)',
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
        });
    </script>
@endsection
