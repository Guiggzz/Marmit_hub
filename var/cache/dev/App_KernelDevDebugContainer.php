<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerONM4uSC\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerONM4uSC/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerONM4uSC.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerONM4uSC\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerONM4uSC\App_KernelDevDebugContainer([
    'container.build_hash' => 'ONM4uSC',
    'container.build_id' => '73b22a01',
    'container.build_time' => 1733999134,
    'container.runtime_mode' => \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? 'web=0' : 'web=1',
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerONM4uSC');
