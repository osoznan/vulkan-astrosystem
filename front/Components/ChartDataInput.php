<?php
/**
 * Widget for input the chart section (horoscope) parameters
 *
 * @copyright Copyright &copy; 2016 astrolog-online.net
 * @author Zemlyansky Alexander <meraponimaniya@mail.ru>
 * @package User Components
 * @since 0.1
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace vulkan\front\Components;

use vulkan\Core\ChartSectionInfo;
use vulkan\Helpers\Fmt;
use vulkan\System\Vulkan;

class ChartDataInput extends \vulkan\System\Widget {

    function run() {
        $this->getChartData();
        $res = '
<form>
          <div class="vul-chart-data-input">';

            if (count($this->chart->getPointSections()) > 1) {
                $res .= 'Section: <select name="section">';
                foreach ($this->chart->getPointSections() as $key => $section) {
                    $res .= "<option value=\"$key\">" . $section->title;
                }
                $res .= '</select> ';
            }

            $res .= 'Date: <input name="' . $this->prefix . 'year" class="vul-year" size="4" value="' . $this->year . '">
            <input name="' . $this->prefix . 'month" class="vul-month"  size="2" value="' . $this->month . '">
            <input name="' . $this->prefix . 'day" class="vul-day"  size="2" value="' . $this->day . '">
            Time:<input name="' . $this->prefix . 'hour" class="vul-hour"  size="2" value="' . $this->hour . '">
            <input name="' . $this->prefix . 'minute" class="vul-minute" size="2" value="' . $this->minute . '">
            <input name="' . $this->prefix . 'second" class="vul-second" size="2" value="' . $this->second . '">
            Gmt: <input name="' . $this->prefix . 'gmt" class="vul-gmt" size="2" value="' . $this->gmt . '">
            Lon:<input name="' . $this->prefix . 'lon" class="vul-lon" size="6" value="' . $this->lon . '">
            Lat:<input name="' . $this->prefix . 'lat" class="vul-lat" size="6" value="' . $this->lat . '">
            <button id="vul-chart-data-input-send">OK</button>';

        foreach ($this->hidden as $key => $elem) {
            $res .= "<input type='hidden' name=\"$key\" value=\"$elem\">";
        }

        $res .= '</div>
</form>
        ';
        $js = <<< JS

JS;
        echo '<script>' . $js . '</script>';
        return $res;
    }

    public function getChartData() {
        foreach ($_GET as $key => $item) {
            if (substr($key, 0, strlen($this->prefix)) == $this->prefix) {
                $key = substr($key, strlen($this->prefix));
                $this->$key = empty($item) ? 0 : $item;
            }
        }
        return (new ChartSectionInfo())
            ->dateTime("{$this->year}-{$this->month}-{$this->day} {$this->hour}:{$this->minute}:{$this->second}",
                "{$this->gmt}:00:00")
            ->location($this->lon, $this->lat);
    }

}

?>

<style>
    .vul-chart-data-input {
        background: #ddd;
        padding: 3px;
        text-align: center;
        border-radius: 3px
    }
    .vul-chart-data-input input {
        text-align: center;
    }

    .vul-year {width: 30px}
    .vul-lon, .vul-lat {width: 48px}
    .vul-month, .vul-day, .vul-hour, .vul-minute, .vul-second, .vul-gmt {width: 18px}
</style>
