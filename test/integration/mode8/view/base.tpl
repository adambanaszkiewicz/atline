@section('main')
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <title>Test Atline</title>
    </head>
    <body>
        @show('body.top')
        <h1>Basic</h1>
        <p>View: base.tpl</p>
        <h3>Section special</h3>
        <hr />
        @show('special')
        <hr />
        <h3>Section parent</h3>
        <hr />
        @show('parent')
        <hr />
        <h3>Section content</h3>
        <hr />
        @show('content')
        <hr />
    </body>
</html>
@endsection
@section('special')
<p>SECTION 'special': base.tpl</p>
@endsection