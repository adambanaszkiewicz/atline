@section('main')
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <title>Test Atline</title>
        @show('head.bottom')
    </head>
    <body>
        @show('body.top')
        <h1>Basic</h1>
        {#
            Multiline
            comment
            ...
        #}
        <p>View: base.tpl</p>
        <div id="wrapper">
            <div id="page-wrapper">
                @show('content')
            </div>
        </div>
        {# One line comment #}
        @show('body.bottom')
    </body>
</html>
@endsection
