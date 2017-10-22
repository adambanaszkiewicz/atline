<?php
/**
 * This file is part of the Atline templating system package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Copyright (c) 2015 - 2017 by Adam Banaszkiewicz
 *
 * @license   MIT License
 * @copyright Copyright (c) 2015 - 2017, Adam Banaszkiewicz
 * @link      https://github.com/requtize/atline
 */

namespace Requtize\Atline;

/**
 * Main class for compilation views into PHP Class.
 *
 * @author Adam Banaszkiewicz https://github.com/requtize
 */
class Compiler
{
    /**
     * Stores path to file that will be rendered.
     * 
     * @var string
     */
    private $filepath;

    /**
     * Stores path do Cache directory for views Classes.
     *
     * @var string
     */
    private $cachePath;

    /**
     * Stores RAW data from view. Content that not be parset/compiled.
     * 
     * @var string
     */
    private $raw = '';

    /**
     * Stores prepared content before finding sections. And after preparing sections,
     * stores prepared content wich wasn't in sections.
     * 
     * @var string
     */
    private $prepared = '';

    /**
     * Stores view definition of extending view (parent view).
     * 
     * @var string|boolean
     */
    private $extends;

    /**
     * Stores parent Class name of view.
     * 
     * @var string
     */
    private $extendsClassname = 'View';

    /**
     * Stores array of sections founded in view.
     * 
     * @var array
     */
    private $sections = [];

    /**
     * @todo Make possible to definie own statements and parsing it.
     */
    //private $specialStatements = [];

    /**
     * Tells, if views should be cached.
     * 
     * @var boolean
     */
    private $cached = true;

    /**
     * Filters list which are user as 'raw' - without html encoding.
     * 
     * @var array
     */
    private $rawFilters = [
        'raw'
    ];

    /**
     * @param string  $filepath Path to rendered view file.
     * @param boolean $cached   Compiled views should be cached?
     */
    public function __construct($filepath, $cached = true, array $options)
    {
        $this->filepath = $filepath;
        $this->cached   = $cached;

        if(isset($options['raw-filters']) && is_array($options['raw-filters']))
            $this->rawFilters = array_merge($this->rawFilters, $options['raw-filters']);
    }

    /**
     * Sets Cache directory path.
     * 
     * @param string $cachePath
     * @return self
     */
    public function setCachePath($cachePath)
    {
        $this->cachePath = $cachePath;

        return $this;
    }

    /**
     * Sets class name that should be extending this View.
     * 
     * @param string $className
     * @return self
     */
    public function setExtendsClassname($className)
    {
        $this->extendsClassname = $className;

        return $this;
    }

    /**
     * Gets array og compiled sections whith it's names.
     * 
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * Sets parent view definition.
     * 
     * @param string $definition
     * @return self
     */
    public function setExtendedDefinition($definition)
    {
        $this->extends = $definition;

        return $this;
    }

    /**
     * Gets parent view definition.
     *
     * @return string
     */
    public function getExtendedDefinition()
    {
        return $this->extends;
    }

    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Check if compiled view already exists.
     * 
     * @return boolean
     */
    public function compiledExists()
    {
        return $this->cached === false ? false : file_exists($this->cachePath.'/'.$this->getClassName().'.php');
    }

    /**
     * Generate class name for this view.
     * 
     * @return string
     */
    public function getClassName()
    {
        return 'View'.hash('sha256', $this->filepath);
    }

    /**
     * Generate method name of given section.
     * 
     * @param  string $name Section name.
     * @return string
     */
    public function createSectionMethodName($name)
    {
        return $name == 'main' ? $name : 'section_'.hash('md5', $name);
    }

    /**
     * Compile view, if compiled version doesn't exists in cache.
     * 
     * @return void
     */
    public function compile()
    {
        if($this->compiledExists() === false)
        {
            if(is_file($this->filepath) === false)
            {
                throw new \Exception('File "'.$this->filepath.'" does not exists.');
            }

            $this->raw      = file_get_contents($this->filepath);
            $this->prepared = $this->raw;

            $this->removeComments();
            $this->resolveExtending();
            $this->compileRenders();
            $this->compileEchoes();
            $this->compileConditions();
            $this->compileLoops();
            $this->compileSpecialStatements();
            $this->prepareSections();
            $this->findSections();
            $this->replaceSections();
        }
    }

    /**
     * Generates class from compiled contents.
     * 
     * @return string Filepath generated class.
     */
    public function __toString()
    {
        // If compiled version already exists, returns it's content.
        if($this->compiledExists())
        {
            return file_get_contents($this->cachePath.'/'.$this->getClassName().'.php');
        }

        if($this->extends === false || $this->extendsClassname === 'View')
        {
            $header = '<?php

use Requtize\Atline\View;

/**
 * View filepath: '.$this->filepath.'
 */
class '.$this->getClassName().' extends View';
        }
        else
        {
            $header = '<?php

require_once "'.$this->extendsClassname.'.php";

use Requtize\Atline\View;

/**
 * View filepath: '.$this->filepath.'
 */
class '.$this->getClassName().' extends '.$this->extendsClassname.'';
        }

        $content = $header.'
{
    private $sections = [{SECTIONS_NAMES}];

    public function getSections()
    {
        return array_merge(parent::getSections(), $this->sections);
    }

    public function getFilepath()
    {
        return \''.$this->filepath.'\';
    }

    public function getParentFilepath()
    {
        return parent::getFilepath();
    }

    {SECTIONS}
}';
        $sectionsNames   = [];
        $sectionsContent = [];

        /**
         * We must add remaining content as 'content' section.
         * Or, if is set @no-extends tag, as 'main' section.
         */
        $this->sections[] = [
            'name'    => $this->extends === false ? 'main' : 'content',
            'content' => $this->prepared
        ];

        foreach($this->sections as $section)
        {
            $sectionsNames[] = "'{$section['name']}' => '".$this->createSectionMethodName($section['name'])."'";
            $sectionsContent[] = '/**
     * Section name: '.$section['name'].'
     */
    public function '.$this->createSectionMethodName($section['name'])."() {
        extract(\$this->data);
        ?>".$section['content']."<?php
    }";
        }

        return str_replace([
            '{SECTIONS}',
            '{SECTIONS_NAMES}',
            '?><?php',
            '?><?='
        ], [
            implode("\n\n  ", $sectionsContent),
            implode(',', $sectionsNames),
            ' ',
            ' echo '
        ], $content);
    }

    /**
     * Extending view.
     *
     * Examples:
     *
     * - Extends view by view named by definition: master.index
     * @extends('master.index')
     *
     * - Tells that this view should not be extending.
     * @no-extends
     */
    public function resolveExtending()
    {
        /**
         * Extending of view.
         */
        preg_match_all('/@extends\(\'([a-zA-Z0-9\.\-]+)\'\)/', $this->prepared, $matches);

        if(isset($matches[1][0]))
        {
            $this->extends  = trim($matches[1][0]);

            $this->prepared = trim(str_replace($matches[0][0], '', $this->prepared));
        }

        /**
         * Tag means that this view should not be extending.
         */
        preg_match_all('/@no-extends/', $this->prepared, $matches);

        if(isset($matches[0][0]) && count($matches[0][0]) == 1)
        {
            $this->extends  = false;

            $this->prepared = trim(str_replace($matches[0][0], '', $this->prepared));
        }
    }

    /**
     * Example:
     *
     * - Include view named by definition: view.definition
     * @render('view.definition')
     * 
     * @return void
     */
    public function compileRenders()
    {
        preg_match_all('/@render\(\'([^\(\\\')]+)\'(.*)?\)/', $this->prepared, $matches);

        if(isset($matches[1][0]))
        {
            foreach($matches[1] as $key => $val)
            {
                if($matches[2][$key])
                {
                    $matches[2][$key] = trim($matches[2][$key], ' ,');

                    $this->prepared = trim(str_replace($matches[0][$key], "<?= \$env->render('{$matches[1][$key]}', array_merge(\$this->allData(), {$matches[2][$key]})); ?>", $this->prepared));
                }
                else
                {
                    $this->prepared = trim(str_replace($matches[0][$key], "<?= \$env->render('{$matches[1][$key]}', \$this->allData()); ?>", $this->prepared));
                }
            }
        }
    }

    /**
     * Examples:
     *
     * {# this is comment #}
     * {#
     *   Comments can
     *   be multiline.
     * #}
     *
     * @return void
     */
    public function removeComments()
    {
        preg_match_all('/{#\s*(.+?)\s*#}(\r?\n)?/s', $this->prepared, $matches);

        if(isset($matches[0]) && is_array($matches[0]))
        {
            $this->prepared = str_replace($matches[0], '', $this->prepared);
        }
    }

    /**
     * Examples:
     *
     * - Calls the same section, from parent view.
     * @parent
     *
     * - Shows content from section named: section.name
     * @show('section.name')
     *
     * @return void
     */
    public function prepareSections()
    {
        // Replace parent sections to show
        preg_match_all('/@parent/', $this->prepared, $matches);

        if(isset($matches[0][0]))
        {
            foreach($matches[0] as $key => $section)
            {
                $this->prepared = trim(str_replace($matches[0][$key], "<?= parent::{explode('::', __METHOD__)[1]}(); ?>", $this->prepared));
            }
        }

        // Replace sections show
        preg_match_all('/@show\(\'([a-zA-Z0-9\.\-]+)\'\)/', $this->prepared, $matches);

        if(isset($matches[1][0]))
        {
            foreach($matches[1] as $key => $section)
            {
                $this->prepared = trim(str_replace($matches[0][$key], "<?= \$this->section('{$matches[1][$key]}'); ?>", $this->prepared));
            }
        }
    }

    /**
     * Example:
     *
     * - Defined section named: custom.section
     * @section('custom.section')
     *   Section content.
     *   
     *   Can be multilined...
     * @endsection
     */
    public function findSections()
    {
        $index    = 0;
        $lineNo   = 0;
        $started  = false;
        $lines    = explode("\n", $this->prepared);

        foreach($lines as $line)
        {
            if(strpos(trim($line), '@section(') === 0)
            {
                $started = true;
                $lineNo  = 0;

                preg_match_all('/@section\(\'([a-zA-Z0-9\.\-]+)\'\)/', $line, $matches);

                $this->sections[$index] = [
                    'name'    => isset($matches[1][0]) ? $matches[1][0] : uniqid(),
                    'full'    => '',
                    'content' => ''
                ];
            }

            if($started)
            {
                $this->sections[$index]['full'] .= $line;

                if(strpos(trim($line), '@endsection') === 0)
                {
                    $started = false;
                    $index++;
                }
                elseif($lineNo == 0)
                {

                }
                else
                {
                    $this->sections[$index]['content'] .= "\n{$line}";
                }
            }

            $lineNo++;
        }
    }

    /**
     * Replace sections in content by nothing.
     *
     * @return void
     */
    public function replaceSections()
    {
        // First replace sections contents by nothing
        $this->prepared = preg_replace('/@section\s*(.+?)\s*@endsection(\r?\n)?/s', '', $this->prepared);
    }

    /**
     * Compile echoes - variables and methods/functions.
     *
     * Examples:
     *
     * - Escaped (default) variable
     * {{ variable }}
     *
     * - Not escaped variable
     * {{ variable|raw }}
     *
     * - Not escaped and trimmed (trim and other default PHPs functions are allowed)
     * {{ variable|raw|trim }}
     *
     * - Method/function named asset with it's value.
     * {{ asset('/path/to/something.sth') }}
     *
     * - Method/function call with filter
     * {{ dosth('argument')|upper }}
     *
     * @todo Unification for objects and arrays. Call $var.index.property except $var['index']->property.
     *
     * @return void
     */
    public function compileEchoes()
    {
        preg_match_all('/{{(.+?)}}/s', $this->prepared, $matches);

        foreach($matches[0] as $key => $val)
        {
            $exploded = explode('|', $matches[1][$key]);

            array_walk($exploded, function(& $val) {
                $val = trim($val);
            });

            $varName = trim(array_shift($exploded));
            
            /**
             * Checks if is variable or method.
             */
            if(strpos($varName, '$') !== 0)
            {
                /**
                 * Explode for function name.
                 */
                $segments = explode('(', $varName);

                if(count($segments) == 2)
                {
                    if(! function_exists($segments[0]))
                    {
                        $varName = "\$env->$varName";
                    }
                }
                else
                {
                    $varName = "\$env->$varName";
                }

                $isFunctionCall = true;
            }
            else
            {
                $isFunctionCall = false;
            }

            $needToBeSafe = true;

            foreach($this->rawFilters as $name)
            {
                if(in_array($name, $exploded))
                {
                    $needToBeSafe = false;
                }
            }

            // We add 'safe' filter only for variables.
            // If function call have to be save echoed, user have to add this filter manually.
            if($isFunctionCall === false && $needToBeSafe)
            {
                $exploded[] = 'safe';
            }

            if(count($exploded))
            {
                $varName = $this->createFiltersMethods($exploded, $varName);
            }

            $this->prepared = str_replace($matches[0][$key], '<?= '.$varName.'; ?>', $this->prepared);
        }
    }

    /**
     * Examples:
     *
     * @if $var == 1 && ($i + 2) == 3
     *   // DO something...
     * @elseif $var == 0 && ($i + 2) == 9
     *   // DO something else if...
     * @else
     *   // DO something else
     * @endif
     *
     * @return void
     */
    public function compileConditions()
    {
        // Find if-s
        $this->prepared = preg_replace('/@if\s?(.+)/', '<?php if($1) { ?>', $this->prepared);

        // Find elseif-s
        $this->prepared = preg_replace('/@elseif\s?(.+)/', '<?php } elseif($1) { ?>', $this->prepared);

        // Find else-s
        $this->prepared = preg_replace('/@else/', '<?php } else { ?>', $this->prepared);

        // Find endif-s
        $this->prepared = preg_replace('/@endif/', '<?php } ?>', $this->prepared);
    }

    /**
     * Examples:
     *
     * - Foreach simple - automatically adds AS statement
     * @foreach $elements   - means @foreach $elements as $key => $item
     *
     * - Foreach default
     * @foreach $elements as $item
     *
     * - For loop
     * @for $i = 1; $i<10; $i++
     *
     * - While loop
     * @while $var >= 0
     *
     * - End of loops
     * @endfoeach
     * @endfor
     * @endwhile
     *
     * @return void
     */
    public function compileLoops()
    {
        // Foreach
        preg_match_all('/@foreach\s?(.*)/', $this->prepared, $matches);

        foreach($matches[0] as $key => $val)
        {
            $def = trim($matches[1][$key]);

            // Is definition has not got 'as' keyword, we add it automatically
            if(strpos($def, ' as ') === false)
            {
                $def = "$def as \$key => \$item";
            }

            // Replace content
            $this->prepared = str_replace($matches[0][$key], '<?php foreach('.$def.') { ?>', $this->prepared);
        }

        // Endforeach
        $this->prepared = preg_replace('/@endforeach/', '<?php } ?>', $this->prepared);


        // @loop as @foreach short-hand
        preg_match_all('/@loop\s?(.*)/', $this->prepared, $matches);

        foreach($matches[0] as $key => $val)
        {
            $def = trim($matches[1][$key]);

            // Is definition has not got 'as' keyword, we add it automatically
            if(strpos($def, ' as ') === false)
            {
                $def = "$def as \$key => \$item";
            }

            // Replace content
            $this->prepared = str_replace($matches[0][$key], '<?php foreach('.$def.') { ?>', $this->prepared);
        }

        // Endloop
        $this->prepared = preg_replace('/@endloop/', '<?php } ?>', $this->prepared);



        // For
        $this->prepared = preg_replace('/@for\s?(.*)/', '<?php for($1) { ?>', $this->prepared);
        // Endfor
        $this->prepared = preg_replace('/@endfor/', '<?php } ?>', $this->prepared);



        // While
        $this->prepared = preg_replace('/@while\s?(.*)/', '<?php while($1) { ?>', $this->prepared);
        // Endwhile
        $this->prepared = preg_replace('/@endwhile/', '<?php } ?>', $this->prepared);
    }

    /**
     * Compile special statements.
     *
     * Exaples:
     *
     * - Sets variable with value
     * @set $variable 'value'
     *
     * @return void
     */
    public function compileSpecialStatements()
    {
        /**
         * Statemet that sets the variable value.
         */
        preg_match_all('/@set\s?(.*)/', $this->prepared, $matches);

        foreach($matches[0] as $key => $val)
        {
            $exploded = explode(' ', $matches[1][$key]);
            $varName  = array_shift($exploded);
            $value    = implode(' ', $exploded);

            $this->prepared = str_replace($matches[0][$key], "<?php $varName = $value; \$this->appendData([$varName => $value]); ?>", $this->prepared);
        }
        
        /**
         * @todo Make possible to definie owv statements and parsing it.
         */
        /*foreach($this->specialStatements as $statement => $callback)
        {
            preg_match_all('/@'.$statement.'\s?(.*)/', $this->prepared, $matches);

            call_user_func_array($callback, [$this, $matches]);
        }*/
    }

    /**
     * Creates filters methods for varible.
     * 
     * @param  array  $names    Array of filters to apply.
     * @param  string $variable Variable name for filtering.
     * @return string
     */
    public function createFiltersMethods(array $names, $variable)
    {
        if($names === array())
        {
            return $variable;
        }

        $filter = array_shift($names);

        if($filter === 'raw')
        {
            $result = $this->createFiltersMethods($names, $variable);
        }
        else
        {
            $begin = '';
            $end   = '';

            if(function_exists($filter))
            {
                $begin = "{$filter}(";
            }
            else
            {
                $begin = "\$env->filter('{$filter}', ";
            }

            $result = $begin.$this->createFiltersMethods($names, $variable).')';
        }

        return $result;
    }
}
