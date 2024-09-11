<?php
namespace brighttrackschool\Components;

class Calendar {
    private $active_year, $active_month, $active_day;
    private $events = [];

    public function __construct($date = null) {
        $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
        $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
        $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
    }

    public function add_event($txt, $date, $days = 1, $color = '') {
        $color = $color ? ' ' . $color : $color;
        $this->events[] = [$txt, $date, $days, $color];
    }

    public function __toString() {
        $num_days = date('t', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
        $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day)));
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);

        $html = '<div class="calendar">';
        $html .= '<div class="header">';
        $html .= '<div class="month-year">' . date('F Y', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day)) . '</div>';
        $html .= '</div>';
        $html .= '<div class="days">';

        foreach ($days as $day) {
            $html .= '<div class="day_name">' . $day . '</div>';
        }

        for ($i = $first_day_of_week; $i > 0; $i--) {
            $html .= '<div class="day_num ignore">' . ($num_days_last_month - $i + 1) . '</div>';
        }

        for ($i = 1; $i <= $num_days; $i++) {
            $selected = $i == $this->active_day ? ' selected' : '';
            $html .= '<div class="day_num' . $selected . '">';
            $html .= '<span>' . $i . '</span>';

            foreach ($this->events as $event) {
                if (date('Y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i)) == date('Y-m-d', strtotime($event[1]))) {
                    $html .= '<div class="event' . $event[3] . '">' . $event[0] . '</div>';
                }
            }
            $html .= '</div>';
        }

        $html .= '</div></div>';
        return $html;
    }
}
?>
