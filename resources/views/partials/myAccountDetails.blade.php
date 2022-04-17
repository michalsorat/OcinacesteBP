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

                <div class="canvas-holder my-3"></div>
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

        $('.canvas-holder').html('<canvas id="userChart"></canvas>');

        $.ajax({
            url:'/userDetails/'+ id,
            type:'GET',
            success:function(data){
                $('#regDate').html(data.regDate);

                let types = ['Zaznačené problémy', 'Vyriešené problémy', 'Prijaté na riešenie', 'V procese riešenia'];
                let chartData = types.map((type, index) => {
                    let dataObj = {};
                    dataObj.typeName = type;
                    dataObj.countNr = data.countsArr[index];
                    return dataObj;
                });

                let ctx = $('#userChart');
                var config = {
                    type: 'bar',
                    data: {
                        datasets: [
                            {
                                label: 'Graf',
                                labels: chartData,
                                maxBarThickness: 10,
                                data: chartData,
                                backgroundColor: [
                                    'rgb(255,188,55)',
                                    'rgb(21,117,38)',
                                    'rgb(112,111,111)',
                                    'rgba(0, 63, 250, 0.61)'],
                                parsing: {
                                    xAxisKey: 'typeName',
                                    yAxisKey: 'countNr'
                                }
                            },
                        ],
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    },
                }
                new Chart(ctx, config);
            },
            error: function () {
                $('#userDetails-modal').find('.modal-body').html('Something went wrong');
            }
        });
    });
</script>
