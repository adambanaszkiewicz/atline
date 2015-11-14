<?php
/**
 * This file is part of the Atline templating system package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Copyright (c) 2015 by Adam Banaszkiewicz
 *
 * @license   MIT License
 * @copyright Copyright (c) 2015, Adam Banaszkiewicz
 * @link      https://github.com/requtize/atline
 */

namespace Atline\Atline;

/**
 * @author    Adam Banaszkiewicz https://github.com/requtize
 * @version   0.2.0
 * @date      2015.11.14
 */
class Engine
{
    /**
     * Stores definition resolver Which is responsible for translating
     * view definitions into path to view-file.
     *
     * @var Atline\DefinitionResolverInterface
     */
    private $definitionResolver;

    /**
     * Stores object of Environment. Object is responsible for additionally
     * methods used in views. Default have methodsL render(), filter()
     * 
     * @var Atline\Environment
     */
    private $environment;

    /**
     * Stores cache path for storing cached files.
     * 
     * @var string
     */
    private $cachePath;

    /**
     * Stores default view definition for extending view.
     * 
     * @var string
     */
    private $defaultExtends;

    /**
     * Stores default view data to pass.
     * 
     * @var array
     */
    private $defaultData = [];

    /**
     * Tells, if views should be cached.
     * 
     * @var boolean
     */
    private $cached = true;

    /**
     * @param string      $cachePath Cache path.
     * @param Environment $env
     */
    public function __construct($cachePath, Environment $env)
    {
        $this->cachePath    = $cachePath;
        $this->environment  = $env;

        // If directory notexists, we try to create it.
        if(is_dir($this->cachePath) === false)
        {
            mkdir($this->cachePath, 0777, true);
        }

        $this->environment->setEngine($this);
        $this->defaultData = ['env' => $this->environment];
    }

    /**
     * Tells, if views should be cached.
     * If true - Cached views will be re-used.
     * If false - View will be always generated new.
     * 
     * @param boolean $boolean
     * @return self
     */
    public function setCached($boolean)
    {
        $this->cached = $boolean;

        return $this;
    }

    /**
     * Sets definition resolver. Translating view definitions into files path.
     * 
     * @param DefinitionResolverInterface $resolver
     * @return self
     */
    public function setDefinitionResolver(DefinitionResolverInterface $resolver)
    {
        $this->definitionResolver = $resolver;

        return $this;
    }

    /**
     * Gets definition resolver.
     * 
     * @return DefinitionResolverInterface
     */
    public function getDefinitionResolver()
    {
        return $this->definitionResolver;
    }

    /**
     * Sets default data to pass to view.
     * 
     * @param array $data Array of data.
     * @return self
     */
    public function setDefaultData(array $data)
    {
        $this->defaultData = array_merge($data, ['env' => $this->environment]);

        return $this;
    }

    /**
     * Sets view definition that should be extending for view wich has not set exyending view.
     * 
     * @param string $definition View definition.
     * @return self
     */
    public function setDefaultExtends($definition)
    {
        $this->defaultExtends = $definition;

        return $this;
    }

    /**
     * Renders view (definition in $definition variable) and pass data from $data variable.
     * Saves compiled views in Cache directory. If cached file already exists - its only
     * call it.
     *
     * @return  string Rendered content of view.
     */
    public function render($definition, array $data = [])
    {
        $className    = null;
        $compilers    = [];
        $index        = 0;
        $defExtAdded  = false;

        do
        {
            $filepath = $this->definitionResolver->resolve($definition);
            $compilers[$index] = new Compiler($filepath, $this->cached);
            $compilers[$index]->setCachePath($this->cachePath);

            /**
             * We want the class name of first view.
             */
            if($className === null)
            {
                $className = $compilers[$index]->getClassName();
            }

            /**
             * If compiled version already exists, we dont need compile all view.
             */
            if($compilers[$index]->compiledExists())
            {
                $compilers[$index]->resolveExtending();
            }
            // Compile view and generate PHP Class content.
            else
            {
                $compilers[$index]->compile();
            }

            if($index > 0)
            {
                $compilers[$index - 1]->setExtendsClassname($compilers[$index]->getClassName());
            }

            // If there is no extending view, we render only this view.
            if($this->defaultExtends === null)
            {
                $compilers[$index]->setExtendedDefinition(false);
                $defExtAdded = true;
            }
            // Otherwise, we render all extendings view with this view.
            elseif($this->defaultExtends && $defExtAdded === false && $compilers[$index]->getExtendedDefinition() === null)
            {
                $compilers[$index]->setExtendedDefinition($this->defaultExtends);
                $defExtAdded = true;
            }

            $index++;
        }
        while ($definition = $compilers[$index - 1]->getExtendedDefinition());

        /**
         * Save contents of PHP class into file if not exists yet.
         */
        foreach($compilers as $compiler)
        {
            if($compiler->compiledExists() === false)
            {
                $this->saveToCache($compiler->getClassName(), $compiler->__toString());
            }
        }

        /**
         * Clases haven't got namespaces so we must include these manualy.
         */
        include_once $this->cachePath."/{$className}.php";

        ob_start();
        $view = new $className;
        // Data passed to view from called method.
        $view->appendData($data);
        // Default data to pass.
        $view->appendData($this->defaultData);
        $view->main();
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Saves View Class content info file in Cache directory.
     * 
     * @param  string $className Class filename.
     * @param  string $content   Class content to save.
     * @return void
     */
    private function saveToCache($className, $content)
    {
        file_put_contents($this->cachePath."/{$className}.php", $content);
    }
}
