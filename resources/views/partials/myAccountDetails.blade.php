<div id="userDetails-modal" class="modal edit-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detaily používateľského konta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <h4 class="mb-0">{{Auth::user()->name}}</h4>
                <span class="text-muted mb-2">{{Auth::user()->email}}</span>

                <div class="d-flex justify-content-between align-items-center mt-4 px-5">
                    <div class="stats">
                        <label for="createdProblemsCount">Počet zaznačených problémov</label> <span id="createdProblemsCount"></span>
                    </div>
                    <div class="stats">
                        <label for="solvedProblemsCount">Počet vyriešených problémov</label> <span id="solvedProblemsCount"></span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 px-5">
                    <div class="stats">
                        <label for="acceptedProblemsCount">Počet problémov prijatých na riešenie</label> <span id="acceptedProblemsCount"></span>
                    </div>
                    <div class="stats">
                        <label for="inProgressProblemsCount">Počet problémov v procese riešenia</label> <span id="inProgressProblemsCount"></span>
                    </div>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-start">
                <label for="regDate">Dátum registrácie:</label> <span id="regDate"></span>
            </div>
        </div>
    </div>
</div>

<script>
    $('#userDetails').on('click', function(e) {
        e.preventDefault();
        let id = $(this).attr('href');
        $("#userDetails-modal").modal('show');

        $.ajax({
            url:'/userDetails/'+ id,
            type:'GET',
            success:function(data){
                $('#createdProblemsCount').html(data.createdProblemsCount);
                $('#solvedProblemsCount').html(data.solvedProblemsCount);
                $('#acceptedProblemsCount').html(data.acceptedProblemsCount);
                $('#inProgressProblemsCount').html(data.inProgressProblemsCount);
                $('#regDate').html(data.regDate);
            },
            error: function () {
                $('#userDetails-modal').find('.modal-body').html('Something went wrong');
            }
        });
    });
</script>
