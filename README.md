# Atline
Atline - PHP Template System. Created for using Templating syntax for (writing) speed, and PHP syntax for facilities.

## Installation via composer.json

```json
"requtize/atline": "^1.0.0"
```

## Advantages
- Usage PHP code
- **Multidimentional Views extending**
- Autoescape echoed data
- Filters
- Easy to implement

## Examples

### Echo with Auto-escape
~~~php
{{ $var }}
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
{{ $var | upper }}
~~~

### Loops
~~~php
@loop $array
    {{ $key }} => {{ $item }}
@endloop

// Or

@foreach $array
    {{ $key }} => {{ $item }}
@endforeach
~~~

### PHP internal function call
~~~php
{{ number_format($invoice->getCost(), 2) }}
~~~

### External method call (Environment extended class method)
~~~php
{{ t('translateIndex') }}
~~~

## License
This code is licensed under MIT License.
