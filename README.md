# atline
Atline - PHP Template System. Combination of Template System Syntax and PHP syntax. For now, You can use clean PHP in Your templates without hacking Template System.

## Examples

### Auto-escape
~~~php
<?php echo htmlspecialchars($data); ?>   // PHP 5.3 syntax
<?=htmlspecialchars($data)?>             // PHP 5.4 syntax
{{ $data }}                              // Atline syntax
~~~

### Foreach loop
~~~php
<?php foreach($array as $key => $val): ?>
    <?=$key?> => <?=$val?>
<?php endforeach; ?>

@foreach $array
    {{ $key }} => {{ $val }}
@endforeach
~~~

### Internal method call - here: translation
~~~php
<?php echo $app->t('translateIndex'); ?>  // PHP 5.3 syntax
<?=$app->t('translateIndex')?>            // PHP 5.4 syntax
{{ t('translateIndex') }}                 // Atline syntax
~~~
