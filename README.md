# Atline
Atline - PHP Template System. Created for using Templating syntax for speed, and PHP syntax for facilities.

Syntax is similar to Twig, Smarty and Django - little from each one.

## Examples

### Echo with Auto-escape
~~~php
{{ var }}
~~~

### Foreach loop
~~~php
@foreach $array
    {{ $key }} => {{ item }}
@endforeach
~~~

### Filters
~~~php
{{ $var|upper }}
~~~

### External method call
~~~php
{{ t('translateIndex') }}
~~~

### License
This code is licensed under MIT License.
