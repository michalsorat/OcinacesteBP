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
        <tr class="text-center group-row-main" id="workingGroupID-{{$workingGroup->id}}">
            <td>
                <div>
                    {{$workingGroup->vehicle->oznacenie}}
                </div>
                <div>
                    {{$workingGroup->vehicle->SPZ}}
                </div>
            </td>
            <td class="pt-4">{{count($workingGroup->users)}}</td>
            <td class="text-center">
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

<button type="button" class="btn btn-sm btn-danger ml-3" data-toggle="modal" id="deleteWorkingGroupBtn" data-target="#deleteWorkingGroup">
    Zmazať pracovnú čatu
</button>

<script>
    $('.group-row-main').on('click', function() {
        let workingGroupID = ($(this).attr('id')).split('-')[1];
        $('#vehicleProblems').val(workingGroupID).change();
        $('#deleteWorkingGroupBtn').show();
    });

    $('#deleteWorkingGroupBtn').on('click', function() {
        let workingGroupID = ($('#workingGroupsTable').find('.active').attr('id')).split('-')[1];
        $('#deleteWorkingGroup').find('.modal-body').find('#workingGroupID').val(workingGroupID);
    });
</script>
