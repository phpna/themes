<?php
namespace Phpna\Themes\Src;

use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;
use Illuminate\Translation\Translator;
use Illuminate\View\Factory;
use Pingpong\Themes\Repository as BaseRepository;

class Repository extends BaseRepository
{
    protected $pnconfig;
    protected $type;

    /**
     * The current theme active.
     *
     * @var string
     */
    protected $current;

    public function pnConfig()
    {
        $this->pnconfig = app('phpna')->config;
    }

    /**
     * Get theme path.
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path ?: base_path($this->config->get('themes.path'));
    }

    public function getType()
    {
        return $this->type ?: $this->pnconfig->getDynamics('themes.setting','type');
    }

    public function setType($type,$tmp = true)
    {
        $this->type = $type;
        if(!$tmp){
            $this->pnconfig->setDynamics('themes.setting',array('type' => $type));
        }
        return $this;
    }

    /**
     * Get current theme.
     *
     * @return string
     */
    public function getCurrent()
    {
        if(!$this->current){
            $actives = $this->pnconfig->getDynamics('themes.setting','actives');
            return $actives[$this->getType()];
        }
        return $this->current;
    }

    /**
     * Set current theme.
     *
     * @param string $current
     *
     * @return $this
     */
    public function setCurrent($current,$tmp = true,$type = null)
    {
        $this->current = $current;
        if(!$tmp){
            $type = ($type == null) ? $this->getType() : $type;
            $this->pnconfig->setDynamics('themes.setting',array('actives.'.$type => $current));
        }
        return $this;
    }

    public function getCurrentPath($type = false,$current = false)
    {
        !$current ?: $this->setCurrent($current);
        !$type ?: $this->setType($type);
        return $this->config->get('themes.path').'/'.$this->getCurrent();
    }

    public function asset($path, $secure = null,$current = false,$type = false)
    {
        return app('url')->asset($this->getCurrentPath($type,$current).'/'.$path, $secure);
    }

    public function elixir($file,$type = false,$current = false)
    {
        static $manifest = null;

        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path($this->getCurrentPath($type,$current).'/build/rev-manifest.json')), true);
        }

        if (isset($manifest[$file])) {
            return '/'.$this->getCurrentPath($type,$current).'/build/'.$manifest[$file];
        }

        throw new \InvalidArgumentException("File {$file} not defined in asset manifest.");
    }
}
