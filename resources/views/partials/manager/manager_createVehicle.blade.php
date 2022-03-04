<div class="modal fade" id="createVehicleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pridať vozidlo do evidencie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="modal-body pr-0" method="POST" action="{{route('createVehicle')}}">
                @csrf
                <div class="form-group row">
                    <label for="vehName" class="col-md-4 col-form-label text-md-right">Označenie vozidla</label>

                    <div class="col-md-6">
                        <input id="vehName" class="form-control" type="text" name="vehName" required autofocus>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="SPZ" class="col-md-4 col-form-label text-md-right">Štátna poznávacia značka (ŠPZ)</label>

                    <div class="col-md-6">
                        <input id="SPZ" type="text" class="form-control" name="spz" required autofocus>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="kmCount" class="col-md-4 col-form-label text-md-right">Stav tachometra (km)</label>

                    <div class="col-md-6">
                        <input id="kmCount" type="text" class="form-control" name="kmCount" required autofocus>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="note" class="col-md-4 col-form-label text-md-right">Poznámka k vozidlu</label>

                    <div class="col-md-6">
                        <input id="note" type="text" class="form-control" name="note">
                    </div>
                </div>

                <div class="justify-content-center d-flex mt-4">
                    <button type="submit" class="btn btn-primary">Pridať vozidlo</button>
                </div>
            </form>
            @if ($errors->has('spz') || $errors->has('vehName') || $errors->has('kmCount'))
                <div class="modal-footer">
                    <script>
                        $(function() {
                            $('#createVehicleModal').modal({
                                show: true
                            });
                        });
                    </script>

                    <div class="alert alert-danger">
                        @foreach( $errors->all() as $message )
                            <span>{{ $message }}&nbsp</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
