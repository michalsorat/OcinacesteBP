<div class="modal fade" id="createWorkingGroup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form class="modal-content" method="POST" action="{{ route('manager.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Vytvoriť pracovnú čatu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="d-flex justify-content-around modal-body p-0">
                <div class="form-group">
                    <h6 class="mt-4 mb-3 font-weight-bolder">Zvoľte vozidlo pracovnej čaty</h6>
                    <select class="input-filter form-input w-100" name="selectedVehicle">
                        <option value="" selected disabled hidden>Vyberte možnosť</option>
                        @foreach($availVehicles as $vehicle)
                            <option value={{$vehicle->vozidlo_id}}>{{$vehicle->oznacenie}}, {{$vehicle->SPZ}}, {{$vehicle->pocet_najazdenych_km}} km</option>
                        @endforeach
                    </select>

                    <h6 class="mt-4 mb-3 font-weight-bolder">Kategórie riešených problémov</h6>

                    @foreach($categories as $category)
                        <div class="form-check my-1">
                            <input class="form-check-input" type="checkbox" name="checkedCategories[]" value="{{$category->kategoria_problemu_id}}" id="catCB{{$category->kategoria_problemu_id}}">
                            <label class="form-check-label" for="catCB{{$category->kategoria_problemu_id}}">
                                {{$category->nazov}}
                            </label>
                        </div>
                    @endforeach

                </div>


                <div class="form-group">
                    <h6 class="mt-4 mb-3 font-weight-bolder">Vyberte členov pracovnej čaty</h6>
                    <table id="selectUsersTable" class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">Meno</th>
                            <th scope="col">Emailová adresa</th>
                            <th scope="col">Vybrať</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr class="user-row">
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td class="text-center">
                                    <input type="checkbox" class="add-user-cb" name="selected_users[]" value="{{$user->id}}">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                @if ($errors->any())
                    <script>
                        $(function() {
                            $('#createWorkingGroup').modal({
                                show: true
                            });
                        });
                    </script>

                    <div class="alert alert-danger">
                        @foreach( $errors->all() as $message )
                            <span>{{ $message }}&nbsp</span>
                        @endforeach
                    </div>
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zrušiť</button>
                <button type="submit" class="btn btn-primary">Vytvoriť</button>
            </div>
        </form>
    </div>
</div>

<script>
    $('.user-row').on('click', function() {
        let cb = $(this).find('.add-user-cb');
        if (cb.is(':checked')) {
            cb.prop('checked', false);
        }
        else {
            cb.prop('checked', true);
        }
    });
</script>
