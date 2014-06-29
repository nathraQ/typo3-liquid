<?php
namespace Sudomake\Liquid\Controller;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Martin Sonnenholzer <martin.sonnenholzer@googlemail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * LiquidController
 */
class LiquidController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {



    /**
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObject
     */
    protected $contentObject;

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

    protected function initializeView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view) {
        if($view instanceof \TYPO3\CMS\Fluid\View\TemplateView) {
            if($this->contentService->hasPartialRootPaths()) {
                $view->setPartialRootPaths($this->contentService->getPartialRootPaths());
            }
            $this->contentService->resolveTemplatePathAndFilename($view);
            $this->contentService->resolveFrameForLayoutPathAndFilename($view);
        }

        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($this->settings, $this->contentService->getContentSettings());
        $view->assign('settings', $this->settings);
        $view->assign('contentObject', $this->contentObject->data);
    }

    protected function initializeAction() {
       $this->contentObject = $this->configurationManager->getContentObject();
    }

	/**
	 * action show
	 *
	* @return void
	 */
	public function showAction() {
	}

}