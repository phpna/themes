<?php
namespace Phpna\Themes\Src;

use Pingpong\Themes\Theme as BaseTheme;

class Theme extends BaseTheme
{
    protected $type;

    protected $assets = [];

    protected $active = [];

    public function getType()
    {
        return $this->type;
    }

    public function getAssets()
    {
        return $this->assets;
    }

    public function getAssetsConfig($type, $default = null)
    {
        return array_get($this->assets, $type, $default);
    }

    /**
     * Convert theme instance to array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'author' => $this->author,
            'enabled' => $this->enabled,
            'path' => $this->path,
            'assets' => $this->assets,
            'files' => $this->files,
        ];
    }

}
