<?php

namespace Composer\Installers;

use Composer\Package\PackageInterface;

class ExpressionEngineInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    private $ee2locations = array(
        'addon'   => 'system/expressionengine/third_party/{$name}/',
        'theme'   => 'themes/third_party/{$name}/',
    );

    /** @var array<string, string> */
    private $ee3locations = array(
        'addon'   => 'system/user/addons/{$name}/',
        'theme'   => 'themes/user/{$name}/',
    );

    public function getlocations(string $frameworkType): array
    {
        if ($frameworkType === 'ee2') {
            $this->locations = $this->ee2locations;
        } else {
            $this->locations = $this->ee3locations;
        }

        return $this->locations;
    }
}
