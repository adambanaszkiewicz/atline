# atline
Atline - PHP Template System. Combination of Template System Syntax and PHP syntax. You can use clean PHP in Your templates without hacking Template System.

## Examples

### Auto-escape
~~~php
{{ $data }}                              // Atline syntax
<?=htmlspecialchars($data)?>             // PHP 5.4 syntax
<?php echo htmlspecialchars($data); ?>   // PHP 5.3 syntax
~~~

### Foreach loop
~~~php
@foreach $array
    {{ $key }} => {{ $val }}
@endforeach

<?php foreach($array as $key => $val): ?>
    <?=$key?> => <?=$val?>
<?php endforeach; ?>
~~~

### Internal method call (here: translation function)
~~~php
{{ t('translateIndex') }}                 // Atline syntax
<?=$app->t('translateIndex')?>            // PHP 5.4 syntax
<?php echo $app->t('translateIndex'); ?>  // PHP 5.3 syntax
~~~
