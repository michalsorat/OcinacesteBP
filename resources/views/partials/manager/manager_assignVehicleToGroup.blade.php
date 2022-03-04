<div class="modal fade" id="assignVehToGroupModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vyber pracovnú čatu na priradenie vozidla</h5>
                <button type="button" id="closeModalAssignVehicle" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-0 px-1">
                <div class="form-group">
                    <table id="selectGroupTable" class="table table-hover table-bordered working-group-table">
                        <thead>
                        <tr>
                            <th scope="col">Vozidlo</th>
                            <th scope="col">Počet zamestnancov</th>
                            <th scope="col">Priradené kategórie</th>
                            <th scope="col">Zvoľ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($workingGroups as $workingGroup)
                            <tr class="text-center group-row">
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
                                <td>
                                    <input type="checkbox" class="select-group-cb" value="{{$workingGroup->id}}-{{$workingGroup->vehicle->vozidlo_id}}">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="assignVehicle">Priraď vozidlo</button>
            </div>
        </div>
    </div>
</div>


