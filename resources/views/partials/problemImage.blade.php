<div class="modal-content">
    <div class="modal-body">
        <button type="button" class="close ml-2" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <nav>
            <div class="nav nav-tabs nav-fill" role="tablist">
                <a class="nav-item nav-link active" data-toggle="tab" href="#nav-problem" role="tab" aria-controls="nav-problem" aria-selected="true">Galéria problému</a>
                <a class="nav-item nav-link" data-toggle="tab" href="#nav-problem-solution" role="tab" aria-controls="nav-problem-solution" aria-selected="false">Galéria riešenia problému</a>
            </div>
        </nav>
        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-problem" role="tabpanel" aria-labelledby="nav-home-tab">
                @if(!$problem->problemImage->isEmpty())
                    <div id="carouselGallery" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($problem->problemImage as $image)
                                <div @if($loop->first) class="carousel-item active" @else class="carousel-item" @endif>
                                    <img class="d-block w-100" src="/storage/problemImages/{{$image->nazov_suboru}}">
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselGallery" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselGallery" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                @else
                    <img class="d-block w-100" src="/img/no_image_available.jpg" alt="no-image">
                @endif
            </div>
            <div class="tab-pane fade" id="nav-problem-solution" role="tabpanel" aria-labelledby="nav-profile-tab">
                @if(!$problem->problemSolImage->isEmpty())
                    <div id="carouselGallery" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($problem->problemSolImage as $image)
                                <div @if($loop->first) class="carousel-item active" @else class="carousel-item" @endif>
                                    <img class="d-block w-100" src="/storage/problemImages/{{$image->nazov_suboru}}">
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselGallery" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselGallery" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                @else
                    <img class="d-block w-100" src="/img/no_image_available.jpg" alt="no-image">
                @endif
            </div>
        </div>
    </div>
</div>
