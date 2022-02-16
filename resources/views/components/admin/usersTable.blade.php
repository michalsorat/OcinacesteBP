@if($users != null)
<table class="adminTable table main-table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">ID</th>
        <th scope="col">Meno</th>
        <th scope="col">Email</th>
        <th scope="col">Rola</th>
        <th scope="col">Vytvorené dňa</th>
        <th scope="col">Uprav rolu</th>
        <th scope="col">Vymaž používateľa</th>
    </tr>
    </thead>
    <tbody>
    @php
        $counter = 1;
    @endphp

    @foreach($users as $user)

        @if($user->id == 1)
            @continue
        @endif

        <tr>
            <td>{{ $counter }}</td>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->Rola['nazov'] }}</td>
            <td>{{ $user->created_at }}</td>
            <td>
                @if($user->rola_id != 3)
                    <label for="editUserIcon" class="btn"><i class="fas fa-edit"></i></label>
                    <input id="editUserIcon" type="submit" data-toggle="modal" data-target="#editUserRole-modal-{{ $user->id }}" hidden />
                @endif
            </td>
            <td>
                @if($user->rola_id != 3)
                    <label for="deleteUserIcon" class="btn"><i class="far fa-trash-alt"></i></label>
                    <input id="deleteUserIcon" type="submit" data-toggle="modal" data-target="#delete-modal-{{ $user->id }}" hidden />
                @endif
            </td>
        </tr>
        @php
            $counter++;
        @endphp


        <div id="delete-modal-{{ $user->id }}" class="modal delete-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Vymazať používateľa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Ste si istý, že chcete vymazať tento účet?</p>
                        <div class="form-group d-flex justify-content-center mt-4">
                            <button type="button" class="btn btn-primary cancel mr-4"
                                    data-dismiss="modal"
                                    aria-label="Close">Zrušiť
                            </button>

                            <form action="{{ route('pouzivatelia.destroy', $user->id) }}"
                                  method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    Vymazať
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="editUserRole-modal-{{ $user->id }}" class="modal edit-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Zmeň rolu používateľovi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <form action="{{ route('pouzivatelia.update', $user->id) }}" method="POST" class="row w-100">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="actualRole" class="col-md-4 col-form-label text-md-right">Aktuálna rola</label>

                                <div class="col-md-6">
                                    <span id="actualRole" class="form-control">{{$user->Rola['nazov']}}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="changeRole" class="col-md-4 col-form-label text-md-right">Zmeniť na</label>

                                <div class="col-md-6">
                                    <select id="rola_id" class="form-control" name="rola_id">
                                        @foreach($roles as $role)

                                            @if($role->rola_id == $user->rola_id)
                                                <option value="{{ $role->rola_id }}"
                                                        selected>{{ $role->nazov }}</option>

                                            @elseif($role->rola_id != 2 && $role->rola_id != 3)
                                                <option
                                                    value="{{ $role->rola_id }}">{{ $role->nazov }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group d-flex justify-content-center mt-4">
                                <button type="button" class="btn btn-primary mr-4"
                                        data-dismiss="modal"
                                        aria-label="Close">Zrušiť
                                </button>

                                <button type="submit" class="btn btn-success">
                                    Aktualizovať rolu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    </tbody>

</table>


@else
    <h1>Žiadne výsledky</h1>
@endif


@if(!empty(Session::get('success')))
    <div class="alert alert-success"> {{ Session::get('success') }}</div>
@endif
@if(!empty(Session::get('error')))
    <div class="alert alert-danger"> {{ Session::get('error') }}</div>
@endif
