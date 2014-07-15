<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// register extension as CType; this is part of \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']['Liquid']['plugins']['ContentElement']['controllers']['Liquid'] = array('actions' => array('show'));
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']['Liquid']['plugins']['ContentElement']['pluginType']='CType';

// register liquid TypoScript as contentRenderingTemplate
$GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'liquid/Configuration/TypoScript/';
