<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * @author Petrina Alexandr <info@aktivcorp.com>
 * @copyright (c) 2016, Aktive Corporation
 * @version 1.0
 * @package mod/etutorium
 */

class etutorium_datepickermini
{
    /**
     * отобразить подобие datepicker
     * @param string $name имя datepicker
     * @param boolean $monthname отображать названия месяцев или цифрами (false)
     * @param string $typedate формат даты ('y-m-d')
     * @param char $selector разделитель для этого дела ('-')
     * @param int $day день (нынешний)
     * @param int $month месяц (нынешний)
     * @param int $year год (нынешний)
     * @return string
     */
    public function display($name, $monthname = false, $typedate = 'y-m-d', $selector = '-', $day = 0, $month = 0, $year = 0) {
        if ($day == 0) {
            $day = date('d');
        }
        if ($month == 0) {
            $month = date('m');
        }
        if ($year == 0) {
            $year = date('Y');
        }
        return $this->includejsfile().$this->view($name, $day, $month, $year, $monthname, $typedate, $selector);
    }

    public function includejsfile() {
        global $PAGE;
        $PAGE->requires->js('/mod/etutorium/js/datepickermini.js');
    }

    public function view($name, $day, $month, $year, $monthname, $typedate, $selector) {
        return $this->hiddenfield($name, $day, $month, $year, $typedate, $selector).
            $this->day($name, $day).
            $this->month($name, $month, $monthname).
            $this->year($name, $year).
            $this->script($name, $typedate, $selector);
    }

    public function hiddenfield($name, $day, $month, $year, $typedate, $selector) {
        return '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.
            $this->typedate($typedate, $selector, $day, $month, $year).
            '">'."\n";
    }

    public function script($name, $typedate, $selector) {
        return "<script type='text/javascript'>
    if (document.addEventListener) {
        document.addEventListener('DOMContentLoaded', function() {
            ".$name." = new DatePickerMini();
            ".$name.".init('".$name."', ".$this->retjsobj($typedate, $selector).", '".$selector."');
            document.getElementById('".$name."day').onchange = function() { ".$name.".getdate(); };
            document.getElementById('".$name."month').onchange = function() { ".$name.".getdate(); };
            document.getElementById('".$name."year').onchange = function() { ".$name.".getdate(); };
        });
    };
</script>\n";
    }

    public function retjsobj($typedate, $selector) {
        $typedatearr = explode($selector, $typedate);
        $typearr = array();
        foreach ($typedatearr as $key => $value) {
            $typearr[] = $key.":'".$value."'";
        }
        return '{'.implode(',', $typearr).'}';
    }

    public function day($name, $day) {
        $res = '<select id="'.$name.'day">'."\n";
        for ($i = 1; $i < 32; $i++) {
            $res .= '<option value="'.str_pad($i, 2, '0', STR_PAD_LEFT).'"';
            if ($day == $i) {
                $res .= ' selected';
            }
            $res .= '>'.$i.'</option>'."\n";
        }
        $res .= "</select>\n";
        return $res;
    }

    public function month($name, $month, $monthname) {
        $res = '<select id="'.$name.'month">'."\n";
        for ($i = 1; $i < 13; $i++) {
            $res .= '<option value="'.str_pad($i, 2, '0', STR_PAD_LEFT).'"';
            if ($month == $i) {
                $res .= ' selected';
            }
            $res .= '>';
            if (!$monthname) {
                $res .= $i;
            } else {
                $res .= $this->retmonth($i);
            }
            $res .= '</option>'."\n";
        }
        $res .= '</select>'."\n";
        return $res;
    }

    public function retmonth($mon) {
        switch ($mon)
        {
            case 1:
                $ret = ' Января ';
                break;
            case 2:
                $ret = ' Февраля ';
                break;
            case 3:
                $ret = ' Марта ';
                break;
            case 4:
                $ret = ' Апреля ';
                break;
            case 5:
                $ret = ' Мая ';
                break;
            case 6:
                $ret = ' Июня ';
                break;
            case 7:
                $ret = ' Июля ';
                break;
            case 8:
                $ret = ' Августа ';
                break;
            case 9:
                $ret = ' Сентября ';
                break;
            case 10:
                $ret = ' Октября ';
                break;
            case 11:
                $ret = ' Ноября ';
                break;
            case 12:
                $ret = ' Декабря ';
                break;
        }
        return $ret;
    }

    public function year($name, $year) {
        $res = '<select id="'.$name.'year">'."\n";
        for ($i = date('Y') - 10; $i < date('Y') + 2; $i++) {
            $res .= '<option value="'.$i.'"';
            if ($year == $i) {
                $res .= ' selected';
            }
            $res .= '>'.$i.'</option>'."\n";
        }
        $res .= '</select>'."\n";
        return $res;
    }

    public function typedate($typedate, $selector, $day, $month, $year) {
        $typedatearr = explode($selector, $typedate);
        $typedatearr = array_flip($typedatearr);
        $typedatearr['d'] = $day;
        $typedatearr['m'] = $month;
        $typedatearr['y'] = $year;
        return implode($selector, $typedatearr);
    }
}
