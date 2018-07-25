<?php
namespace Doodles\DoodleProduct\Modules;
use modmore\Commerce\Modules\BaseModule;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

class Doodleproduct extends BaseModule {

    public function getName()
    {
        $this->adapter->loadLexicon('commerce_doodleproduct:default');
        return $this->adapter->lexicon('commerce_doodleproduct');
    }

    public function getAuthor()
    {
        return 'Tony Klapatch for modmore';
    }

    public function getDescription()
    {
        return $this->adapter->lexicon('commerce_doodleproduct.description');
    }

    public function initialize(EventDispatcher $dispatcher)
    {
        // Load our lexicon
        $this->adapter->loadLexicon('commerce_doodleproduct:default');

        // Add the xPDO package, so Commerce can detect the derivative classes.
        // This is used to detect the custom Doodles product class we are using.
        $root = dirname(dirname(__DIR__));
        $path = $root . '/model/';
        $this->adapter->loadPackage('commerce_doodleproduct', $path);

        // Add template path to twig.
        // This could be used in a custom product if you wanted to add custom fields with different themes.
//        /** @var ChainLoader $loader */
//        $root = dirname(dirname(__DIR__));
//        $loader = $this->commerce->twig->getLoader();
//        $loader->addLoader(new FilesystemLoader($root . '/templates/'));
    }

    public function getModuleConfiguration(\comModule $module)
    {
        $fields = [];
        return $fields;
    }
}
