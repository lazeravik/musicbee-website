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

/**
 * Created by PhpStorm.
 * User: Avik
 * Date: 19-01-2017
 * Time: 03:31 AM
 */

namespace App\Lib\Utility;

use App\Lib\Controller;
use App\Lib\Utility\Route;

class Router
{
    protected $urlRoutes = [];
    protected $methods = [];
    protected $mvcName;
    protected $urlParamArray;

    /**
     * Add new routes to the route list
     * @param \App\Lib\Utility\Route $newRoute
     */
    public function addRoute(Route $newRoute)
    {
        $this->urlRoutes[] = trim($newRoute->getUrl(), "/");
        if (!empty($newRoute->getMethod())) {
            $this->methods[] = $newRoute->getMethod();
        }
    }

    public function route()
    {
        $urlGetParam = trim($this->getUrlWithoutLanguageParam(), "/");
        $this->urlParamArray = explode("/",$urlGetParam);
        $routeArray = getRoutes();

        foreach ($routeArray as $route) {
            $url = '/'.$urlGetParam;
            if($url == $route['url']){
                //validate controller
                $route['controller'] = isset($route['controller'])?:null;
                $this->createModelViewController($route['model'], $route['view'], $route['controller']);
                return true;
            }
            //todo: add call_user_func if no MVC is found!
        }
        $this->loadErrorPage();
        return false;

//        foreach ($this->urlRoutes as $key => $url) {
//            if (preg_match("#^$urlGetParam$#", $url)) {
//                if (isset($this->methods[$key])) {
//                    $this->mvcName = $this->methods[$key];
//                    if (is_string($this->mvcName)) {
//                        $this->createMVC();
//                    } else {
//                        call_user_func($this->mvcName);
//                    }
//                    return true;
//                } else {
//                    throw new \Exception("Requested method is not found!");
//                }
//            }
//        }
//
//        $this->loadErrorPage();
//        return false;
    }


    /**
     * create the requested view and controller. the controller will
     * instantiate the model and pass it on to the view
     * @param null $model
     * @param null $view
     * @param null $controller
     */
    public function createModelViewController($model = null, $view = null, $controller = null)
    {
        if($model == null || $view == null){
            die("MVC is not defined properly!");
        }

        //Define the namespaces for MVC
        $modelNamespace = "App\\Lib\\Model\\{$model}";
        $controllerNamespace = "App\\Controllers\\{$controller}";
        $viewNamespace = "App\\View\\{$view}";

        $model = (class_exists($modelNamespace))
            ? new $modelNamespace()
            : null;
        $controller = (class_exists($controllerNamespace) && $model != null)
            ? new $controllerNamespace($model)
            : null;

        if (class_exists($viewNamespace)) {
            $view = new $viewNamespace($model);
            $view->render();
        } else {
            $this->loadErrorPage();
        }
    }

    public function loadErrorPage()
    {
        var_dump("404 Error");
    }


    /**
     * get language code from url parameter
     * @return null|string
     */
    public function getLanguageParamFromUrl()
    {
        if (isset($_GET['param'])) {
            return explode("/", $_GET['param'])[0];
        }

        return null;
    }

    /**
     * get all parameter from url
     * @return array|null
     */
    public function getParamFromUrl()
    {
        if (isset($_GET['param'])) {
            return explode("/", $_GET['param']);
        }

        return null;
    }

    /**
     * Generate url with language code. eg: getmusicbee.com/en_us/param...
     * @param $locale
     * @param $defaultLaguageReq
     * @return null|string
     */
    public function generateUrlWithLangParam($locale, $defaultLaguageReq)
    {
        if ($locale == null) {
            return null;
        }
        $locale = strtolower($locale);
        $defaultLaguageReq = strtolower($defaultLaguageReq);
        $urlParam = $this->getParamFromUrl();
        if ($urlParam != null) {
            if ($defaultLaguageReq != "") {
                $urlParam[0] = $defaultLaguageReq;
            } else {
                array_unshift($urlParam, $locale);
            }
        } else {
            $urlParam[0] = $locale;
        }

        return filter_var(implode("/", $urlParam), FILTER_SANITIZE_URL);
    }

    /**
     * Get the url without language codes such as: en_us, ru_ru etc
     * @return string
     */
    public function getUrlWithoutLanguageParam()
    {
        $urlGetParamArray = self::getParamFromUrl();
        if (is_array($urlGetParamArray)) {
            unset($urlGetParamArray[0]);
        }

        if (null == $urlGetParamArray) {
            return "/";
        }

        return implode("/", $urlGetParamArray);
    }
}
