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
     * @var array The liquid configuration
     */
    protected $liquidTypoScriptSetup;

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

    public function hasViewSetup() {
        $this->getContentTypoScript();
        if (array_key_exists('view' ,$this->contentTypoScriptSetup)) {
            return true;
        } else {
            return false;
        }
    }

    public function hasTcaSetup() {
        $this->getContentTypoScript();
        if (array_key_exists('tca' ,$this->contentTypoScriptSetup)) {
            return true;
        } else {
            return false;
        }
    }

    public function hasTemplatePathAndFilename() {
        $this->getContentTypoScript();
        if ($this->hasViewSetup() && array_key_exists('template' ,$this->contentTypoScriptSetup['view'])) {
            return true;
        } else {
            return false;
        }
    }


    public function hasTemplateRootPaths() {
        $this->getContentTypoScript();
        if ($this->hasViewSetup() && array_key_exists('templateRootPaths' ,$this->contentTypoScriptSetup['view'])) {
            return true;
        } else {
            return false;
        }
    }

    public function hasLayoutRootPaths() {
        $this->getContentTypoScript();
        if ($this->hasViewSetup() && array_key_exists('layoutRootPaths' ,$this->contentTypoScriptSetup['view'])) {
            return true;
        } else {
            return false;
        }
    }

    public function hasPartialRootPaths() {
        $this->getContentTypoScript();
        if ($this->hasViewSetup() && array_key_exists('partialRootPaths' ,$this->contentTypoScriptSetup['view']) && !empty($this->contentTypoScriptSetup['view']['partialRootPaths'])) {
            return true;
        } else {
            return false;
        }
    }

    public function hasSettings() {
        $this->getContentTypoScript();
        if (array_key_exists('settings' ,$this->contentTypoScriptSetup) && !empty($this->contentTypoScriptSetup['settings'])) {
            return true;
        } else {
            return false;
        }
    }

    public function hasLayoutName() {
        if(isset($this->contentObject->data['section_frame']) && intval($this->contentObject->data['section_frame']) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getLayoutName() {
        if($this->hasLayoutName()) {
            // resolve layoutPathAndFilename by frame number
            $layoutName = $this->getLayoutNameByFrameNumber(intval($this->contentObject->data['section_frame']));
            return $layoutName;
        }
    }

    public function getContentSettings() {
        if($this->hasSettings()) {
            return $this->contentTypoScriptSetup['settings'];
        } else {
            return array();
        }
    }

    public function getTemplateRootPaths() {
        if($this->hasTemplateRootPaths()) {
            $rootPath = $this->contentTypoScriptSetup['view']['templateRootPaths'];
            if(!is_array($rootPath)) {
                array($rootPath);
            }
            return $rootPath;
        } else {
            // throw exception
        }
    }

    public function getLayoutRootPaths() {
        if($this->hasLayoutRootPaths()) {
            $rootPath = $this->contentTypoScriptSetup['view']['layoutRootPaths'];
            if(!is_array($rootPath)) {
                array($rootPath);
            }
            return $rootPath;
        } else {
            // throw exception
        }
    }

    public function getPartialRootPaths() {
        if($this->hasPartialRootPaths()) {
            $rootPath = $this->contentTypoScriptSetup['view']['partialRootPaths'];
            if(!is_array($rootPath)) {
                array($rootPath);
            }
            return $rootPath;
        } else {
            // throw exception
        }
    }


    public function getTemplatePathAndFilename() {
        if($this->hasTemplatePathAndFilename()) {
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

    public function getLiquidTypoScript() {
        if(empty($this->liquidTypoScriptSetup)) {
            $this->getTypoScript();
            $this->liquidTypoScriptSetup = $this->typoScriptSetup['liquid'];
        }
        return $this->liquidTypoScriptSetup;

    }

    public function getLiquidValueByKey($type, $key){
        $this->getLiquidTypoScript();
       if(
           array_key_exists($type,$this->liquidTypoScriptSetup)
           && !empty($this->liquidTypoScriptSetup[$type])
           && array_key_exists($key,$this->liquidTypoScriptSetup[$type])
           && isset($this->liquidTypoScriptSetup[$type][$key])
       ) {
           return $this->liquidTypoScriptSetup[$type][$key];
       } else {
           return NULL;
       }
    }

    protected function getLayoutNameByFrameNumber($frameNumber) {
        $layoutName = $this->getLiquidValueByKey('frames', $frameNumber);
        if($layoutName !== NULL) {
            return $layoutName;
        } else {
            return 'Default';
        }
    }
}