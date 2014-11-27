<?php

/*
 * This file is part of the AsseticAngularJsBundle package.
 *
 * (c) Pascal Kuendig <padakuro@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asoc\AsseticAngularJsBundle\Assetic\Filter;

use Asoc\AsseticAngularJsBundle\Angular\TemplateNameFormatterInterface;
use Assetic\Asset\AssetInterface;
use Assetic\Filter\BaseNodeFilter;

/**
 * Compile AngularJS templates for $templateCache.
 *
 * @link http://angularjs.com/
 */
class AngularTemplateFilter extends BaseNodeFilter
{
    /**
     * @var \Asoc\AsseticAngularJsBundle\Angular\TemplateNameFormatterInterface
     */
    private $templateNameFormatter;

    public function __construct(TemplateNameFormatterInterface $templateNameFormatter)
    {
        $this->templateNameFormatter = $templateNameFormatter;
    }

    public function filterLoad(AssetInterface $asset)
    {
        $templateName = $this->templateNameFormatter->getForAsset($asset);

        $content = addslashes($asset->getContent());
        $html = '';
        // Explode by EOL
        $content = preg_split("/\R/", $content);
        foreach ($content as $line) {
            if ($html !== '') {
                $html .= "\n +";
            }
            $html .= sprintf('"%s"', $line);
        }

        $js = <<<JS
angular.module("$templateName", []).run(["\$templateCache", function(\$templateCache) {
  \$templateCache.put("$templateName", $html);
}]);
JS;

        $asset->setContent($js);
    }

    public function filterDump(AssetInterface $asset)
    {
    }
}
