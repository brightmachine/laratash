<?php
namespace Laratash;

use Illuminate\View\Engines\EngineInterface;
use Illuminate\Filesystem\Filesystem;
use Mustache_Engine;
use App;

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

        $config = $app['config']->get('laratash');
        $config['partials_loader'] = App::make($config['partials_loader']);
        $m = new Mustache_Engine($config);
 
        if (isset($data['__context']) && is_object($data['__context'])) {
            $data = $data['__context'];
        } else {
            $data = array_map(function ($item) {
                return (is_object($item) && method_exists($item, 'toArray')) ? $item->toArray() : $item;
            }, $data);
        }
 
        return $m->render($view, $data);
    }
}
