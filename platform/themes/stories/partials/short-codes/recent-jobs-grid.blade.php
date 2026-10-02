<div class="bg-grey pt-50 pb-50">
    <div class="container">
        <div class="widget-header-1 position-relative mb-30">
            <h5 class="mt-5 mb-30">{!! BaseHelper::clean($title) !!}</h5>
        </div>

        <div class="loop-list loop-list-style-1">
            <div class="row">
                @foreach($posts as $post)
                    <article class="col-lg-3 col-md-4 col-sm-6 mb-40 wow fadeInUp animated" data-wow-delay="0.{{ ($loop->index % 4) * 2 }}s">
                        <div class="post-card-1 border-radius-10 hover-up">
                            {!! Theme::partial('components.post-card', compact('post')) !!}
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-10">
            <a class="btn btn-dark btn-radius font-small box-shadow" href="{{ $viewAllUrl }}">{!! __('View all jobs') !!} <i class="elegant-icon arrow_right font-x-small ml-5"></i></a>
        </div>
    </div>
</div>
