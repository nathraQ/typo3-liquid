<?php
namespace Sudomake\Liquid\View\Liquid;

/*                                                                        *
 * This script is backported from the TYPO3 Flow package "TYPO3.Fluid".   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3\CMS\Fluid\View\Exception;

/**
 * The main template view. Should be used as view if you want Fluid Templating
 *
 * @api
 */
class ShowHtml extends \TYPO3\CMS\Fluid\View\TemplateView {

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

    protected function getTemplatePathAndFilename($actionName = NULL) {
        if($this->contentService->hasTemplatePathAndFilename()) {
            return $this->contentService->getTemplatePathAndFilename();
        }
        if ($this->templatePathAndFilename !== NULL) {
            return $this->templatePathAndFilename;
        }
        if ($actionName === NULL) {
            /** @var $actionRequest \TYPO3\CMS\Extbase\Mvc\Request */
            $actionRequest = $this->controllerContext->getRequest();
            $actionName = $actionRequest->getControllerActionName();
        }
        $actionName = ucfirst($actionName);

        $paths = $this->expandGenericPathPattern($this->templatePathAndFilenamePattern, FALSE, FALSE);
        foreach ($paths as &$templatePathAndFilename) {
            $templatePathAndFilename = $this->resolveFileNamePath(str_replace('@action', $actionName, $templatePathAndFilename));
            if (is_file($templatePathAndFilename)) {
                return $templatePathAndFilename;
            }
        }
        throw new Exception\InvalidTemplateResourceException('Template could not be loaded. I tried "' . implode('", "', $paths) . '"', 1225709595);
    }

    protected function getLayoutPathAndFilename($layoutName = 'Default') {
        if($this->contentService->hasLayoutName()) {
            $layoutName =  $this->contentService->getLayoutName();
        }
        if ($this->layoutPathAndFilename !== NULL) {
            return $this->layoutPathAndFilename;
        }
        $paths = $this->expandGenericPathPattern($this->layoutPathAndFilenamePattern, TRUE, TRUE);
        $layoutName = ucfirst($layoutName);
        foreach ($paths as &$layoutPathAndFilename) {
            $layoutPathAndFilename = $this->resolveFileNamePath(str_replace('@layout', $layoutName, $layoutPathAndFilename));
            if (is_file($layoutPathAndFilename)) {
                return $layoutPathAndFilename;
            }
        }
        throw new Exception\InvalidTemplateResourceException('The template files "' . implode('", "', $paths) . '" could not be loaded.', 1225709596);
    }

    protected function getPartialRootPaths() {
        if($this->contentService->hasPartialRootPaths()) {
            return $this->contentService->getPartialRootPaths();
        }
        if ($this->partialRootPaths !== NULL) {
            return $this->partialRootPaths;
        }
        /** @var $actionRequest \TYPO3\CMS\Extbase\Mvc\Request */
        $actionRequest = $this->controllerContext->getRequest();
        return array(str_replace('@packageResourcesPath', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($actionRequest->getControllerExtensionKey()) . 'Resources/', $this->partialRootPathPattern));
    }

    public function getTemplateRootPaths() {
        if($this->contentService->hasTemplateRootPaths()) {
            return $this->contentService->getTemplateRootPaths();
        }
        if ($this->templateRootPaths !== NULL) {
            return $this->templateRootPaths;
        }
        /** @var $actionRequest \TYPO3\CMS\Extbase\Mvc\Request */
        $actionRequest = $this->controllerContext->getRequest();
        return array(str_replace('@packageResourcesPath', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($actionRequest->getControllerExtensionKey()) . 'Resources/', $this->templateRootPathPattern));
    }

    protected function getLayoutRootPaths() {
        if($this->contentService->hasLayoutRootPaths()) {
            return $this->contentService->getLayoutRootPaths();
        }
        if ($this->layoutRootPaths !== NULL) {
            return $this->layoutRootPaths;
        }
        /** @var $actionRequest \TYPO3\CMS\Extbase\Mvc\Request */
        $actionRequest = $this->controllerContext->getRequest();
        return array(str_replace('@packageResourcesPath', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($actionRequest->getControllerExtensionKey()) . 'Resources/', $this->layoutRootPathPattern));
    }
}
