<?php

/*
 * This file is part of the AsseticAngularJsBundle package.
 *
 * (c) Pascal Kuendig <padakuro@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asoc\AsseticAngularJsBundle\Angular;

use Assetic\Asset\AssetInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class SymfonyTemplateNameFormatter implements TemplateNameFormatterInterface
{

    /**
     * Bundle map: bundle root => bundle name
     *
     * Used to map asset files to a bundle
     *
     * @var array
     */
    private $bundleMap;

    public function __construct(KernelInterface $kernel)
    {
        $bundleMap = array();
        foreach ($kernel->getBundles() as $bundle) {
            $bundleMap[$bundle->getPath()] = $bundle->getName();
        }

        $this->bundleMap = $bundleMap;
    }

    /**
     * Create module name for asset.
     * This is bundle name.
     *
     * @param AssetInterface $asset
     * @return string
     */
    public function getModuleForAsset(AssetInterface $asset)
    {
        $sourceRoot = $asset->getSourceRoot();
        $bundleName = $this->bundleMap[$sourceRoot];
        if (!isset($bundleName)) {
            throw new \Exception('Could not map the asset to a bundle');
        }

        return $bundleName . '.templates';
    }

    /**
     * Create template name for asset.
     *
     * @param AssetInterface $asset
     * @return string
     */
    public function getForAsset(AssetInterface $asset)
    {
        $sourceRoot = $asset->getSourceRoot();
        if (!isset($this->bundleMap[$sourceRoot])) {
            throw new \Exception('Could not map the asset to a bundle');
        }

        // get the relative path
        $templateName = $asset->getSourcePath();
        // by convention, all symfony views are in Resources/views/, therefore remove this segment
        $templateName = str_replace('Resources/views/', '', $templateName);
        // remove the .ng extension (our convention)
        $templateName = str_replace('.ng', '', $templateName);
        // prepend bundle name
        $bundleName = $this->bundleMap[$sourceRoot];
        $templateName = sprintf('%s/%s', $bundleName, $templateName);

        return $templateName;
    }

} 