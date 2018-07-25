<?php

/**
 * @param string $filename The name of the file.
 * @return string The file's content
 * @by splittingred
 */
function getSnippetContent($filename = '') {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

if (!defined('MOREPROVIDER_BUILD')) {
    /* define version */
    define('PKG_NAME', 'Commerce_DoodleProduct');
    define('PKG_NAMESPACE', 'commerce_doodleproduct');
    define('PKG_VERSION', '1.0.0');
    define('PKG_RELEASE', 'pl');

    /* load modx */
    require_once dirname(dirname(__FILE__)) . '/config.core.php';
    require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
    $modx= new modX();
    $modx->initialize('mgr');
    $modx->setLogLevel(modX::LOG_LEVEL_INFO);
    $modx->setLogTarget('ECHO');


    echo '<pre>';
    flush();
    $targetDirectory = dirname(dirname(__FILE__)) . '/_packages/';
}
else {
    $targetDirectory = MOREPROVIDER_BUILD_TARGET;
}

$root = dirname(dirname(__FILE__)).'/';
$sources= array (
    'root' => $root,
    'build' => $root .'_build/',
    'events' => $root . '_build/events/',
    'resolvers' => $root . '_build/resolvers/',
    'validators' => $root . '_build/validators/',
    'data' => $root . '_build/data/',
    'plugins' => $root.'_build/elements/plugins/',
    'snippets' => $root.'_build/elements/snippets/',
    'source_core' => $root.'core/components/'.PKG_NAMESPACE,
    'source_assets' => $root.'assets/components/'.PKG_NAMESPACE,
    'lexicon' => $root . 'core/components/'.PKG_NAMESPACE.'/lexicon/',
    'docs' => $root.'core/components/'.PKG_NAMESPACE.'/docs/',
    'model' => $root.'core/components/'.PKG_NAMESPACE.'/model/',
);
unset($root);

// Fix for no settings in package
$modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);
$builder->directory = $targetDirectory;
$builder->createPackage(PKG_NAMESPACE, PKG_VERSION, PKG_RELEASE);
/** @var modNamespace $namespace */
$namespace = $modx->newObject('modNamespace');
$namespace->set('name', PKG_NAMESPACE);
$namespace->set('path', '{core_path}components/' . PKG_NAMESPACE . '/');
$namespace->set('assets_path', '{assets_path}components/' . PKG_NAMESPACE . '/');
/* define some basic attributes */
$attributes = array(
    xPDOTransport::UNIQUE_KEY => 'name',
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RESOLVE_FILES => true,
    xPDOTransport::RESOLVE_PHP => true,
);
/** @var modTransportVehicle $vehicle */
$vehicle = $builder->createVehicle($namespace, $attributes);
$modx->log(modX::LOG_LEVEL_INFO, 'Packaged in namespace.');
flush();
// end fix

// Add the validator to check server requirements
$vehicle->validate('php', array('source' => $sources['validators'] . 'requirements.script.php'));

//$vehicle->resolve('file',array(
//    'source' => $sources['source_assets'],
//    'target' => "return MODX_ASSETS_PATH . 'components/';",
//));
$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));
$vehicle->resolve('php',array(
    'source' => $sources['resolvers'] . 'loadmodules.resolver.php',
));
$builder->putVehicle($vehicle);

$modx->log(modX::LOG_LEVEL_INFO,'Packaged in resolvers.'); flush();

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
));
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in package attributes.'); flush();

$modx->log(modX::LOG_LEVEL_INFO,'Packing...'); flush();
$builder->pack();

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tend = $mtime;
$totalTime = ($tend - $tstart);
$totalTime = sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

