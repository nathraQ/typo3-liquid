<?php
namespace Sudomake\Liquid\Service;


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


class ContentService implements \TYPO3\CMS\Core\SingletonInterface {

    /**
     * @var array The configuration
     */
    protected $rawTypoScriptSetup;

    /**
     * @var array The configuration
     */
    protected $typoScriptSetup;

    /**
     * @var array The configuration
     */
    protected $contentTypoScriptSetup;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObject
     */
    protected $contentObject;

    /**
     * @var \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService
     */
    protected $typoScriptService;

    /**
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
     * @return void
     */
    public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
        $this->configurationManager = $configurationManager;
        $this->contentObject = $this->configurationManager->getContentObject();
        $this->rawTypoScriptSetup = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
    }

    /**
     * @param \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService
     * @return void
     */
    public function injectTypoScriptService(\TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService){
        $this->typoScriptService = $typoScriptService;
    }

    public function hasOwnView() {
        $this->getContentTypoScript();
        if (array_key_exists('view' ,$this->contentTypoScriptSetup)) {
            return true;
        } else {
            return false;
        }
    }

    public function hasOwnTemplate() {
        $this->getContentTypoScript();
        if ($this->hasOwnView() && array_key_exists('template' ,$this->contentTypoScriptSetup['view'])) {
            return true;
        } else {
            return false;
        }
    }


    public function hasOwnTemplateRootPaths() {
        $this->getContentTypoScript();
        if ($this->hasOwnView() && array_key_exists('templateRootPath' ,$this->contentTypoScriptSetup['view'])) {
            return true;
        } else {
            return false;
        }
    }

    public function hasOwnLayoutRootPaths() {
        $this->getContentTypoScript();
        if ($this->hasOwnView() && array_key_exists('layoutRootPath' ,$this->contentTypoScriptSetup['view'])) {
            return true;
        } else {
            return false;
        }
    }

    public function hasOwnPartialRootPaths() {
        $this->getContentTypoScript();
        if ($this->hasOwnView() && array_key_exists('partialRootPath' ,$this->contentTypoScriptSetup['view'])) {
            return true;
        } else {
            return false;
        }
    }


    public function hasOwnSettings() {
        $this->getContentTypoScript();
        if (array_key_exists('settings' ,$this->contentTypoScriptSetup)) {
            return true;
        } else {
            return false;
        }
    }

    public function getContentSettings() {
        if($this->hasOwnSettings()) {
            return $this->contentTypoScriptSetup['settings'];
        } else {
            return array();
        }
    }

    public function getTemplateRootPaths() {
        if($this->hasOwnTemplateRootPaths()) {
            $rootPath = $this->contentTypoScriptSetup['view']['templateRootPath'];
            if(!is_array($rootPath)) {
                array($rootPath);
            }
            return $rootPath;
        } else {
            // throw exception
        }
    }

    public function getLayoutRootPaths() {
        if($this->hasOwnLayoutRootPaths()) {
            $rootPath = $this->contentTypoScriptSetup['view']['layoutRootPath'];
            if(!is_array($rootPath)) {
                array($rootPath);
            }
            return $rootPath;
        } else {
            // throw exception
        }
    }

    public function getPartialRootPaths() {
        if($this->hasOwnPartialRootPaths()) {
            $rootPath = $this->contentTypoScriptSetup['view']['partialRootPath'];
            if(!is_array($rootPath)) {
                array($rootPath);
            }
            return $rootPath;
        } else {
            // throw exception
        }
    }


    public function getTemplatePathAndFilename() {
        if($this->hasOwnTemplate()) {
            return $this->contentTypoScriptSetup['view']['template'];
        } else {
            // throw exception
        }
    }

    public function getTypoScript() {
        if (empty($this->typoScriptSetup)) {
            $this->typoScriptSetup = $this->typoScriptService->convertTypoScriptArrayToPlainArray($this->rawTypoScriptSetup);
        }
        return $this->typoScriptSetup;
    }

    public function getContentTypoScript() {
        if(empty($this->contentTypoScriptSetup)) {
            $this->getTypoScript();
            $this->contentTypoScriptSetup = $this->typoScriptSetup['tt_content'][$this->contentObject->data['CType']];
        }
        return $this->contentTypoScriptSetup;
    }


}