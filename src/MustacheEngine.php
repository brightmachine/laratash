<?php

namespace Laratash;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\View\Engine as EngineInterface;
use Mustache_Engine;

class MustacheEngine implements EngineInterface
{
    /** @var Filesystem */
    private $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function get($path, array $data = array())
    {
        $view = $this->files->get($path);

        $app = app();

        $config = config('laratash');

        $config['partials_loader'] = $app->make($config['partials_loader']);

        $m = new Mustache_Engine($config);

        if (isset($data['__context']) && is_object($data['__context'])) {
            $data = $data['__context'];
        }

        return $m->render($view, $data);
    }
}
