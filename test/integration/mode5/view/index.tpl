<p>View: index.tpl</p>
<hr />
@set $switch isset($_GET['switch']) ? $_GET['switch'] : 1

@if $switch == 1
  <p>Podano wartosc $switch == 1 (if)</p>
@elseif $switch == 2
  <p>Podano wartosc $switch == 2 (elseif)</p>
@else
  <p>Podano inna wartosc niz 1 lub 2 (else)</p>
@endif

<a href="?switch=1">?switch=1</a> | <a href="?switch=2">?switch=2</a> | <a href="?switch=3">?switch=3</a>