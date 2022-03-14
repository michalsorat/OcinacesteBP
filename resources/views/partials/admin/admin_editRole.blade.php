<div class="modal-body text-center py-2 px-0">
    <form action="{{ route('admin.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group row">
            <label for="actualRole" class="col-md-4 col-form-label text-md-right">Aktuálna rola</label>

            <div class="col-md-6">
                <span id="actualRole" class="form-control">{{$user->Rola['nazov']}}</span>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-4 col-form-label text-md-right">Zmeniť na</label>

            <div class="col-md-6">
                <select class="form-control text-center" name="rola_id">
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
