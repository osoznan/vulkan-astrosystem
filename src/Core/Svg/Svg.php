<?php
/**
 * Svg drawing class
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package Core
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace vulkan\Core\Svg;

class Svg {

    public $code = '';

    public function begin($id, $params = []) {
        $this->code = '
            <!-- http://astrolog-online.net -->
            <div id="' . $id . '" class="vul-container"'.
            ($bounds = (' style="width:' . ($params['width']) .'px; height:' . ($params['height']) . 'px"')) . '><svg class="vul-container id="' . $id
            . ' width="'  . ($params['width']) .  '" height="' . ($params['height']) . '"' . ' version="1.1"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink">';
    }

    public function end($returnOnly = false) {
        $this->code .= '</div></svg>';
        if ($returnOnly) {
            return $this->code;
        }
        echo $this->code;
    }

    public function getCode() {;
        return $this->code;
    }

    public function beginGroup($classes = '', $params = '') {
        if ($classes) {
            if (is_array($classes)) {
                $classTxt = implode(' ', $classes);
            } else {
                $classTxt = $classes;
            }
            $classTxt = 'class="' . $classTxt . '" ';
        }
        $this->code .= '<g ' . $classTxt . $params . '>';
    }

    public function endGroup() {
        $this->code .= '</g>';
    }

    public function beginHtml($x, $y, $class = '', $params = '') {
        $this->code .= "<foreignObject x=\"$x\" y=\"$y\" $class=\"$class\" $params><html xmlns=\"http://www.w3.org/1999/xhtml\"><body>";
    }

    public function endHtml() {
        $this->code .= '</body></html></foreignObject>';
    }

    public function addCode($code) {
        $this->code .= $code;
    }

    public function line($x1, $y1, $x2, $y2, $class = '', $params = '') {
        list($x1, $y1, $x2, $y2) = [$x1, $y1, $x2, $y2];
        $class = $class ? ' class="' . $class . '"' : '';
        $this->code .= "<line x1=\"{$x1}\" y1=\"{$y1}\" x2=\"{$x2}\" y2=\"{$y2}\" {$params}{$class}/>";
    }

    public function rectangle($rect, $class = '', $params = '') {
        $this->code .= "<rect x1=\"{$rect->x1}\" y1=\"{$rect->y1}\" width=\"{$rect->x2}\" height=\"{$rect->y2}\" $params class=\"{$class}\"/>";
    }
    public function rectangleEmpty($rect, $class = '', $params = '') {
        $this->code .= "<rect x1=\"{$rect->x1}\" y1=\"{$rect->y1}\" width=\"{$rect->x2}\" height=\"{$rect->y2}\" $params class=\"no-fill {$class}\"/>";
    }

    public function circle($x, $y, $radius, $class='', $params = '') {
        $this->code .= "<circle cx=\"{$x}\" cy=\"{$y}\" r=\"{$radius}\" $params class=\"{$class}\"/>";
    }

    public function circleEmpty($x, $y, $radius, $class='', $params = '') {
        $this->code .= "<circle cx=\"{$x}\" cy=\"{$y}\" r=\"{$radius}\" {$params} class=\"{$class} no-fill\"/>\n";
    }

    public function text($x, $y, $text, $class='', $params = '') {
        $this->code .= "<text x=\"{$x}\" y=\"{$y}\" {$params} class=\"{$class}\">{$text}</text>";
    }

}