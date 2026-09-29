<?php

use Botble\Theme\Facades\Theme;
use Illuminate\Routing\Events\RouteMatched;

app('events')->listen(RouteMatched::class, fn () => Theme::registerFacebookIntegration());
