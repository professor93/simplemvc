<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 10.02.2019
 * Time: 0:06
 */

namespace App\Core;

use App\Core\Abstracts\ViewAbstract;

class View extends ViewAbstract
{
    protected $content;

    public static function make($template, array $args = [])
    {
        $view = new self();
        if (is_array($args)) {
            extract($args, EXTR_SKIP);
        }
        $file = ROOT_PATH . "views/$template.php";  // relative to Core directory
        if (is_readable($file)) {
            ob_start();
            include $file;
            $view->content = ob_get_clean();
        } else {
            $view->content = '<!--Error: on rendering-->';
        }

        return $view;
    }

    public function render()
    {
        echo $this->content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public static function staticRender($content): void
    {
        if ($content instanceof View) {
            $content->render();
        } else {
            if (is_array($content))
                $content = json_encode($content);
            echo $content;
        }
    }

    public function __toString(): string
    {
        return $this->content;
    }
}