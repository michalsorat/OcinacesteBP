<div id="deleteWorkingGroup" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vymazať pracovnú čatu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Ste si istý, že chcete vymazať túto pracovnú čatu?</p>
                <div class="form-group d-flex justify-content-center mt-4">
                    <button type="button" class="btn btn-primary cancel mr-4"
                            data-dismiss="modal"
                            aria-label="Close">Zrušiť
                    </button>

                    <form action="{{ route('deleteWorkingGroup') }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            Vymazať
                        </button>
                        <input type="text" id="workingGroupID" name="workingGroupID" value="" hidden/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
