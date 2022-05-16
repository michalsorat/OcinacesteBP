<div class="col-12 px-4">
    <h6 class="mt-lg-3 mt-2 font-weight-bolder">Priradené problémy</h6>
    <div class="table-wrapper">
        <table class="rwd-table group-problems-table">
            <thead>
            <tr>
                <th></th>
                <th>#</th>
                <th>Adresa</th>
                <th>Kategória problému</th>
                <th>Stav problému</th>
                <th>Stav riešenia</th>
                <th>Priorita</th>
                <th>Zvoľ</th>
            </tr>
            </thead>
            <tbody>
            @if($assignedProblems == null)
                <tr>
                    <td colspan="7" class="text-center">Žiadne pridelené problémy zvolenej pracovnej čate</td>
                </tr>
            @else
                @foreach($assignedProblems as $counter=>$problem)
                    <tr>
                        <td onclick="window.location='{{ route('problemDetail', $problem->problem_id) }}'"><i class="fa-solid fa-angles-right"></i></td>
                        <td data-th="#" id="hashtagID">{{ ++$counter }}</td>
                        <td data-th="Adresa">{{ $problem->address }}</td>
                        <td data-th="Kategória problému">{{ $problem->KategoriaProblemu['nazov'] }}</td>
                        <td data-th="Stav problému">{{ $problem->StavProblemu->nazov }}</td>
                        @foreach($solStatusTypes as $type)
                            @if($solStatusAssignedProblems[$counter-1] == $type->typ_stavu_riesenia_problemu_id)
                                <td data-th="Stav riešenia">{{ $type->nazov }}</td>
                            @endif
                        @endforeach
                        <td data-th="Priorita">
                            {{$priorities[$problem->priorita_id - 1]->priorita}}
                        </td>
                        <td data-th="Zvoľ">
                            <input type="checkbox" class="remove-problems-cb" value="{{$problem->problem_id}}">
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <button type="button" class="btn btn-secondary float-right mt-2 mr-md-4" id="removeProblemsBtn">
        Odobrať vybrané
    </button>
</div>

<div class="col-12 px-4">
    <h6 class="mt-lg-3 mt-2 font-weight-bolder">Zoznam problémov na priradenie</h6>
    <div class="table-wrapper">
        <table class="rwd-table group-problems-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Adresa</th>
                <th>Kategória problému</th>
                <th>Stav problému</th>
                <th>Stav riešenia</th>
                <th>Priorita</th>
                <th>Zvoľ</th>
            </tr>
            </thead>
            <tbody>
            @if($problemsToAssign == null)
                <tr>
                    <td colspan="7" class="text-center">Žiadne problémy na pridelenie zodpovedajúce riešeným kategóriam zvolenej pracovnej čaty</td>
                </tr>
            @else
                @foreach($problemsToAssign as $counter=>$problem)
                    <tr>
                        <td data-th="#" id="hashtagID">{{ ++$counter }}</td>
                        <td data-th="Adresa">{{ $problem->address }}</td>
                        <td data-th="Kategória problému">{{ $problem->KategoriaProblemu['nazov'] }}</td>
                        <td data-th="Stav problému">{{ $problem->StavProblemu->nazov }}</td>
                        @foreach($solStatusTypes as $type)
                            @if($solStatusProblemsToAssign[$counter-1] == $type->typ_stavu_riesenia_problemu_id)
                                <td data-th="Stav riešenia">{{ $type->nazov }}</td>
                            @endif
                        @endforeach
                        <td data-th="Priorita">
                            <select class="input-filter form-input" id="priority{{$problem->problem_id}}" name="selected_priority[]">
                                <option value={{$priorities[0]->priorita_id}} selected hidden>Priorita riešenia</option>
                                @foreach($priorities as $priority)
                                    <option value={{$priority->priorita_id}}>{{$priority->priorita}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td data-th="Zvoľ">
                            <input type="checkbox" class="add-problems-cb" value="{{$problem->problem_id}}">
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <button type="button" class="btn btn-secondary float-right mt-2 mr-md-4" id="assignProblemsBtn">
        Pridať vybrané
    </button>
</div>

<script>
    $('#assignProblemsBtn').on('click', function() {
        let sel_problems = [];
        let sel_problems_priorities = [];
        let workingGroupID = $('#vehicleProblems').val();
        $('.add-problems-cb[type="checkbox"]:checked').each(function() {
            let id = $(this).val();
            sel_problems.push(id);
            sel_problems_priorities.push($('#priority' + id).val());
        });

        $.ajax({
            url:'/assignProblemsToGroup',
            type:'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{problemsToAssign:sel_problems, prioritiesToAssign:sel_problems_priorities, workingGroupID:workingGroupID},
            success:function(data){
                $('.manage-group-problems').html(data);
            },
            error: function () {
                $('.manage-group-problems').html('Something went wrong');
            }
        });
    });

    $('#removeProblemsBtn').on('click', function() {
        let sel_problems = [];
        let workingGroupID = $('#vehicleProblems').val();
        $('.remove-problems-cb[type="checkbox"]:checked').each(function() {
            sel_problems.push($(this).val());
        });

        $.ajax({
            url:'/removeProblemsFromGroup',
            type:'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{problemsToAssign:sel_problems, workingGroupID:workingGroupID},
            success:function(data){
                $('.manage-group-problems').html(data);
            },
            error: function () {
                $('.manage-group-problems').html('Something went wrong');
            }
        });
    });

    $('.remove-problems-cb').on('change', function() {
        if ($('.remove-problems-cb').is(':checked')) {
            $('#removeProblemsBtn').show();
        }
        else {
            $('#removeProblemsBtn').hide();
        }
    });

    $('.add-problems-cb').on('change', function() {
        if ($('.add-problems-cb').is(':checked')) {
            $('#assignProblemsBtn').show();
        }
        else {
            $('#assignProblemsBtn').hide();
        }
    });
</script>
