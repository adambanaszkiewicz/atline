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
        <p>View: base2.tpl</p>
        <div id="wrapper">
            <div id="page-wrapper">
                @show('content')
            </div>
        </div>

        @show('body.bottom')
    </body>
</html>
@endsection