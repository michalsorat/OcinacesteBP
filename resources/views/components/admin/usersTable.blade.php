@if($users != null)
<table class="table main-table">
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
                <p class="text-center">
                    @if($user->id != 2)
                        <button type="submit" class="border-0" data-toggle="modal"
                                data-target="#edit-modal-{{ $user->id }}"><i
                                class="fas fa-edit"></i>
                        </button>
                    @endif
                </p>
            </td>
            <td>
                <p class="text-center">
                    @if($user->id != 2)
                        <button type="submit" class="border-0" data-toggle="modal"
                                data-target="#delete-modal-{{ $user->id }}"><i
                                class="far fa-trash-alt"></i>
                        </button>
                    @endif
                </p>
            </td>
        </tr>
        @php
            $counter++;
        @endphp


        <div id="delete-modal-{{ $user->id }}" class="modal delete-modal" tabindex="-1"
             role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Vymazať používateľa</h5>
                    </div>
                    <div class="modal-body">

                        <p>Ste si istý, že chcete vymazať používateľa?</p>
                        <ul class="d-flex align-items-center justify-content-center mt-4">
                            <li>
                                <button type="button" class="btn btn-primary cancel mr-4"
                                        data-dismiss="modal"
                                        aria-label="Close">Zrušiť
                                </button>
                            </li>
                            <li>
                                <form action="{{ route('pouzivatelia.destroy', $user->id) }}"
                                      method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <p class="text-center">
                                        <button type="submit" class="btn btn-danger delete">
                                            Vymazať
                                        </button>
                                    </p>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    </tbody>

</table>

<div id="edit-modal-{{ $user->id }}" class="modal edit-modal" tabindex="-1"
     role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Zmeň rolu používateľovi</h5>
            </div>
            <div class="modal-body text-center">
                <form action="{{ route('pouzivatelia.update', $user->id) }}"
                      method="POST" class="row w-100">
                    @csrf
                    @method('PUT')
                    <label class="update-label">Rola požívateľa:</label>
                    <select id="rola_id" class="form-select form-input m-auto"
                            name="rola_id">
                        @foreach($roles as $role)

                            @if($role->rola_id == $user->Rola['nazov'])
                                <option value="{{ $role->rola_id }}"
                                        selected>{{ $role->nazov }}</option>

                            @else
                                <option
                                    value="{{ $role->rola_id }}">{{ $role->nazov }}</option>
                            @endif
                        @endforeach
                    </select>


                    <ul class="d-flex align-items-center justify-content-center mt-4">
                        <li>
                            <button type="button" class="btn btn-primary cancel mr-4"
                                    data-dismiss="modal"
                                    aria-label="Close">Zrušiť
                            </button>
                        </li>
                        <li>
                            <button type="submit"
                                    class="btn btn-primary update-btn mr-3">Aktualizovať
                            </button>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
</div>
@else
    <h1>Žiadne výsledky</h1>
@endif


@if(!empty(Session::get('success')))
    <div class="alert alert-success"> {{ Session::get('success') }}</div>
@endif
@if(!empty(Session::get('error')))
    <div class="alert alert-danger"> {{ Session::get('error') }}</div>
@endif
