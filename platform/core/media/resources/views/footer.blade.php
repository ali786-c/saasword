@foreach (RvMedia::getConfig('libraries.javascript', []) as $js)
    <script
        src="{{ asset($js) }}?v={{ time() }}"
        type="text/javascript"
    ></script>
@endforeach
