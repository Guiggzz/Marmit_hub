<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\Container2kv3yYz\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/Container2kv3yYz/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/Container2kv3yYz.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\Container2kv3yYz\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \Container2kv3yYz\App_KernelDevDebugContainer([
    'container.build_hash' => '2kv3yYz',
    'container.build_id' => '81ffe936',
    'container.build_time' => 1734164501,
    'container.runtime_mode' => \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? 'web=0' : 'web=1',
], __DIR__.\DIRECTORY_SEPARATOR.'Container2kv3yYz');
