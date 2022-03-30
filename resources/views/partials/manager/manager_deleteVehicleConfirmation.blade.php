<div id="deleteVehicleModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Zmazať vozidlo z evidencie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Ste si istý, že chcete vymazať toto vozidlo z evidencie?</p>
                <div class="form-group d-flex justify-content-center mt-4">
                    <button type="button" class="btn btn-primary cancel mr-4"
                            data-dismiss="modal"
                            aria-label="Close">Zrušiť
                    </button>

                    <form action="{{ route('deleteVehicle') }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            Vymazať
                        </button>
                        <input type="text" id="vehicleID" name="vehicleID" value="" hidden/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
