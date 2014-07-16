<?php
namespace Sudomake\Liquid\ViewHelpers\Layout;

/*                                                                        *
 * This script is backported from the TYPO3 Flow package "TYPO3.Fluid".   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

class ClassViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * @var \Sudomake\Liquid\Service\ContentService $contentService
     */
    protected $contentService;

    /**
     * @param \Sudomake\Liquid\Service\ContentService $contentService
     * @return void
     */
    public function injectContentService(\Sudomake\Liquid\Service\ContentService $contentService) {
        $this->contentService = $contentService;
    }

    public function initializeArguments() {
        $this->registerArgument('type','lookup array within liquid typoscript', TRUE);
        $this->registerArgument('key','lookup key within liquid.{type} typoscript', TRUE);
    }

    public function render() {
        $type = $this->arguments['type'];
        $key = $this->arguments['key'];
        $cssClassName =  $this->contentService->getLiquidValueByKey($type, $key);
        if($cssClassName !== NULL) {
            return $cssClassName;
        } else {
            return '';
        }
    }
}
