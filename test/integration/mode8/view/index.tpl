@extends('parent')

@section('special')
<p>SECTION 'special': index.tpl</p>
@endsection

@section('parent')
@parent
<p>SECTION 'parent': index.tpl</p>
@endsection

<p>SECTION 'content': index.tpl</p>