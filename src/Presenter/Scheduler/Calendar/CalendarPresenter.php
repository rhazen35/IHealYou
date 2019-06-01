<?php

namespace App\Presenter\Scheduler\Calendar;

use App\Application\Scheduler\OpeningHours;

/**
 * Class CalendarPresenter
 * @package App\Presenter\Scheduler\Calendar
 */
class CalendarPresenter
{
    /**
     * @var OpeningHours
     */
    private $openingHours;

    /**
     * CalendarPresenter constructor.
     * @param OpeningHours $openingHours
     */
    public function __construct(OpeningHours $openingHours)
    {
        $this->openingHours = $openingHours;
    }

    /**
     * @param $monthWithAppointments
     * @return string
     */
    public function presentMonthWithAppointments($monthWithAppointments)
    {
        $html = "";

        $html .= '<div class="month">';
        $html .= '<div class="week">';
        $html .= '<div class="week-number">&nbsp;</div>';
        $html .= '<div class="day">Monday</div>';
        $html .= '<div class="day">Thuesday</div>';
        $html .= '<div class="day">Wednesday</div>';
        $html .= '<div class="day">Thursday</div>';
        $html .= '<div class="day">Friday</div>';
        $html .= '<div class="day">Saturday</div>';
        $html .= '<div class="day">Sunday</div>';
        $html .= '</div>';

        foreach ($monthWithAppointments as $weekNumber => $weekDays) {

            $html .= '<div class="week">';
            $html .= '<div class="week-number">' . $weekNumber . '</div>';

            foreach ($weekDays as $day => $dayArray) {

                $datetime = $dayArray['datetime'];
                $dayNumber = (int) $datetime->format('d');

                $html .= '<div class="day">';
                $html .= '<div class="day-number">' . $dayNumber . '</div>';

                $html .= '<div class="hours">';
                foreach ($dayArray['hours'] as $hour => $hourArray) {

                    $class = $hourArray['is_open'] ? 'green' : 'red';
                    $html .= '<div class="hour ' . $class . '">';

                    if (!$hourArray['is_open']) {
                        $html .= '<div>' . $hour . '</div>';
                        if (!empty($hourArray['start']) && !empty($hourArray['end'])) {
                            $html .= '<div>' . $hourArray['start'] . ' - ' . $hourArray['end'] . '</div>';
                        }
                    } else {
                        $html .= '<div>' . $hour . '</div>';
                    }
                    $html .= '</div>';
                }
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        $html .= '</div>';

        return $html;
    }
}
