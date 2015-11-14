<h3>Function: t</h3>
{{ t('translate') }}

<h3>Function: t (safe)</h3>
{{ t('translate')|safe }}

<h3>Filter: none</h3>
{{ $data }}

<h3>Filter: raw</h3>
{{ $data|raw }}

<h3>Internal PHP's function</h3>
{{ number_format(1.3345245, 2) }}
