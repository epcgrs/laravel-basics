<?php

namespace Emmanuelpcg\Basics\Time;

trait TimeUtil
{
    protected function allHolidays($file = null)
    {
        $content = file_get_contents(__DIR__ . '/../resource/dates.json');
        if ($file) {
            $content = file_get_contents($file);
        }

        return json_decode($content);
    }

    protected function getNextWorkDay(): array
    {
        $countDays = 1;
        $dateStart = date('Y-m-d 00:00:00', strtotime("+$countDays days"));
        $dateEnd = date('Y-m-d 23:59:59', strtotime("+$countDays days"));

        while ($this->isNotWorkDay($dateStart)) {
            $dateStart = date('Y-m-d 00:00:00', strtotime("+$countDays days"));
            $dateEnd = date('Y-m-d 23:59:59', strtotime("+$countDays days"));
            $countDays++;
        }

        return [$dateStart, $dateEnd];
    }

    protected function getLastWorkDay(): array
    {
        $countDays = 1;
        $dateStart = date('Y-m-d 00:00:00', strtotime("-$countDays days"));
        $dateEnd = date('Y-m-d 23:59:59', strtotime("-$countDays days"));

        while ($this->isNotWorkDay($dateStart)) {
            $dateStart = date('Y-m-d 00:00:00', strtotime("-$countDays days"));
            $dateEnd = date('Y-m-d 23:59:59', strtotime("-$countDays days"));
            $countDays++;
        }

        return [$dateStart, $dateEnd];
    }

    protected function isNotWorkDay($date = NULL): bool
    {
        if (empty($date)) {
            $date = date('d/m/Y');
        }

        $isHoliday = array_search(
            $date,
            array_column(
                $this->allHolidays(),
                'data'
            )
        );


        $date = str_replace('/', '-', $date);

        $isWeekend = date('w', strtotime($date));

        $isWeekendResult = ($isWeekend == 0 || $isWeekend == 6);

        return is_numeric($isHoliday) || $isWeekendResult;
    }

    protected function sumWorkDaysToDate(int $workDays, string $date): string
    {
        $dateToSum = \DateTime::createFromFormat('Y-m-d H:i:s', $date);

        return date('Y-m-d H:i:s', strtotime($dateToSum->format('Y-m-d H:i:s') . ' +' . $workDays . ' days'));
    }
}