{!! Theme::partial('header') !!}

<main class="bg-grey pb-30">
    <div class="border-top border-gray">
        <div class="container">
            <div class="archive-header pt-20 pb-20">
                {!! Theme::partial('breadcrumbs') !!}
            </div>
        </div>
    </div>
    {!! Theme::content() !!}
</main>

{!! Theme::partial('footer') !!}
