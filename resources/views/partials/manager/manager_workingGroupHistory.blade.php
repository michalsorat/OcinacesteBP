<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pt-0">História stavov pracovnej čaty</h5>

                <button type="button" id="closeModalAssignVehicle" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-0 px-1">
                <div class="form-group">
                    <table id="historyTable" class="table table-hover table-bordered working-group-table">
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
        </div>
    </div>
</div>
