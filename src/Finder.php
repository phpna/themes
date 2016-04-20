<?php
namespace Phpna\Themes\Src;
use Pingpong\Themes\Finder as BaseFinder;

class Finder extends BaseFinder
{
    /**
     * Find the specified theme by searching a 'theme.json' file as identifier.
     *
     * @param string $path
     * @param string $filename
     *
     * @return $this
     */
    public function scan()
    {
        if ($this->scanned == true) {
            return $this;
        }

        if (is_dir($path = $this->getPath())) {
            $found = $this->finder
                ->in($path)
                ->files()
                ->name(self::FILENAME)
                ->depth('== 1')
                ->followLinks();

            foreach ($found as $file) {
                $this->themes[] = new Theme($this->getInfo($file));
            }
        }

        $this->scanned = true;

        return $this;
    }
}
