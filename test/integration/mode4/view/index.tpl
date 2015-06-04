<p>View: index.tpl</p>
<hr />
<h3>foreach</h3>
<?php
$array = [
    'index' => 'value',
    'price' => 12.547,
    'value indexed numericaly 1',
    'value indexed numericaly 2',
    'boolean' => true
];
var_dump($array);
?>
@foreach $array
    <div>{{ $key }} => {{ $item }}</div>
@endforeach
<hr />


<h3>for</h3>
@for $i = 0; $i < 10; $i++
    <div>Iteracja: {{ $i }}</div>
@endfor
<hr />


<h3>while</h3>
@set $i 0
@while $i < 10
    <div>Iteracja: {{ $i }}</div>
    <?php $i++ ;?>
@endwhile