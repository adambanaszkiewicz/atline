# Atline
Atline - PHP Template System. Created for using Templating syntax for (writing) speed, and PHP syntax for facilities.

## Advantages
- Usage PHP code
- Views extending
- Autoescape echoed data
- Filters
- Easy to implement

## Examples

### Echo with Auto-escape
~~~php
{{ var }}
~~~

### Conditions
~~~php
@if $arg == 1
    ...
@elseif $arg == 2
    ...
@else
    ...
@endif
~~~

### Filters
~~~php
{{ $var|upper }}
~~~

### Foreach loop
~~~php
@foreach $array
    {{ $key }} => {{ item }}
@endforeach
~~~

### External method call
~~~php
{{ t('translateIndex') }}
~~~

## License
This code is licensed under MIT License.
