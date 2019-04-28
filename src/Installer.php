<?php

namespace Pravodev\Laramoud\Installer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{
    /**
     * 
     */
    private $module_path = "modules/";
    

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $prefix = \substr($package->getPrettyName(), 0, 23);
        $path = $this->getModulePath() . $this->getModuleName($package);
        return $path;
    }

    /**
     * {@inheritDoc}
     */
    public function install($repo, $package)
    {
        // $this->initializeVendorDir();
        // $downloadPath = $this->getInstallPath($package);
        // // remove the binaries if it appears the package files are missing
        // if (!is_readable($downloadPath) && $repo->hasPackage($package)) {
        //     $this->binaryInstaller->removeBinaries($package);
        // }
        var_dump(file_exists($this->getInstallPath($package) . '/'));
        $this->installCode($package);
        // $this->binaryInstaller->installBinaries($package, $this->getInstallPath($package));
        // if (!$repo->hasPackage($package)) {
        //     $repo->addPackage(clone $package);
        // }

        // parent::install($repo, $package);
    }
    
    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'laramoud-module' === $packageType;
    }

    /**
     * Get module name
     * 
     * @return string
     */
    public function getModuleName($package)
    {
        $extra = $package->getExtra();
        if(!empty($extra['laramoud']['name'])){
            return $extra['laramoud']['name'];
        }
        return $package->getPrettyName();
    }

    /**
     * Get module path
     * 
     * @return string
     */
    public function getModulePath()
    {
        $extra = $this->composer->getPackage()->getExtra();
        if(!empty($extra['laramoud']['module_path'])){
            return $extra['laramoud']['module_path'];
        }

        return $this->module_path;
    }

    /**
     * Check Module duplicate or no
     * 
     * @return void
     */
    public function checkDuplicateModule($module)
    {
        $duplicate = \file_exists($module['path']);

        if(!$duplicate) return false;

        throw new \InvalidArgumentException(
            'Unable to install module, module with name ' . $module['name']
            .' already installed '
        );
    }
}