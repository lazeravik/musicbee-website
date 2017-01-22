<?php
/**
 * Copyright (c) 2017 AvikB, some rights reserved.
 *  Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 *  Spelling mistakes and fixes from community members.
 *
 */
namespace App\Lib;

use App\Lib\Utility\Route;
use App\Lib\Utility\Router;
use App\Lib\Utility\LanguageManager;
use App\Lib\Utility\Config as cfg;
use App\Lib\Utility\Session;

class Bootstrap
{
    private $router;
    private $languageManager;
    private $locale;

    public function __construct(LanguageManager $languageManager)
    {
        //Initialize session if not already started
        Session::init();

        $this->router = new Router();
        $this->initRouter($this->router);

        $this->languageManager = $languageManager;
        $this->rewriteUrlWithLanuageCode();
    }

    /**
     * Setup initialize routing
     * @param Router $router
     */
    public function initRouter(Router $router)
    {
        $router->addRoute(new Route("/", "Home"));
        $router->addRoute(new Route("/downloads", "Downloads"));
        $router->addRoute(new Route("/help/awesome", function(){ printf('hello!'); }));
        $router->route();
    }

    /**
     * setup language and add lang code to the url and redirect to the new url
     */
    public function rewriteUrlWithLanuageCode()
    {
        global $link;

        /**
         * Change language on request and set cookie
         */
        $this->languageManager->init(
            $this->router->getLanguageParamFromUrl(),
            $this->getLanguageList()
        );
        $this->locale = $this->languageManager->getRequestedLanguage();
        $this->languageManager->setLanguage($this->locale);

        if ($this->languageManager->matchLanguage() == "/" ||
            strtolower($this->languageManager->getFromLanguageArrayItem()) == "") {
            $urltoRedirect = $link['url'] .
                $this->router->generateUrlWithLangParam(
                    $this->locale,
                    $this->languageManager->getFromLanguageArrayKey()
                );
            // 301 Moved Permanently to a localized url
            $this->redirectToUrl($urltoRedirect, 301);
        }
    }

    /**
     * Redirect to a specific url
     * @param $urlToRedirect
     * @param $responseCode
     */
    public function redirectToUrl($urlToRedirect, $responseCode)
    {
        header('Location: '.$urlToRedirect, true, $responseCode);
    }

    /**
     * Get the list of available language from locale/lang.list.php
     * @return array
     */
    public function getLanguageList()
    {
        global $langList;
        return $langList;
    }
}