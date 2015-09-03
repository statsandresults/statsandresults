<?php

use yii\helpers\Html;

function sportBlock(&$content, $sportId, $sportCode, $sportName, $sport = [])
{
    $sportCode = str_replace(" ", "", $sportCode);
    $subHeaders = [];
    $sportBlockId = Html::encode($sportId);
    $sportBlockCode = Html::encode($sportCode);
    $sportBlockName = Html::encode($sportName);

    $content[] = '<tr class="pr-table-row i-block-row i-block-' . $sportBlockId .
        '" data-block-id="' . $sportBlockId .
        '" data-block-code="' . $sportBlockCode .
        '" data-block-code="' . $sportBlockName . '">';

    $content[] = '<td colspan="3" class="pr-table-td i-block-name i-unselecteble">' .
        '<div class="i-block-inner header-title">' . Html::encode($sportName) . '</div>' .
        '<div class="i-block-name-buttons"><span class="icon-prjExpand"></div>' .
        '</td>';

    $content[] = '</tr>';

    $groups = [];
    if (isset($sport['Groups']) &&
        isset($sport['Groups']['PGroup']) &&
        is_array($sport['Groups']['PGroup'])
    ) {
        $groups = $sport['Groups']['PGroup'];
        if (sizeof($groups) == 2 &&
            isset($groups['@attributes']) &&
            isset($groups['events'])
        ) {
            $groups = [$groups];
        }
    }

    if (is_array($groups) && sizeof($groups) > 0) {
        foreach ($groups as $group) {
            if (isset($group['@attributes'])) {

                $groupId = Html::encode($group['@attributes']['id']);
                $groupName = Html::encode($group['@attributes']['name']);

                $content[] = '<tr class="pr-table-row i-group-row i-block-' . $sportBlockId . ' i-group-' . $groupId .
                    '" data-block-id="' . $sportBlockId .
                    '" data-group-id="' . $groupId .
                    '" data-group-name="' . $groupName .
                    '">';

                $content[] = '<td colspan="3" class="pr-table-td i-group-name i-unselecteble">' .
                    '<div class="i-block-inner group-title" > ' . $groupName . ' </div > ' .
                    '</td > ';

                $content[] = '</tr>';

                if (
                    isset($group['events']) &&
                    isset($group['events']['PEvent']) &&
                    is_array($group['events']['PEvent'])
                ) {
                    $events = $group['events']['PEvent'];
                    if (sizeof($events) == 2 &&
                        isset($events['@attributes']) &&
                        isset($events['scores'])
                    ) {
                        $events = [$events];
                    }

                    foreach ($events as $event) {

                        if (isset($event['@attributes'])) {


                            $eventId = Html::encode($event['@attributes']['id']);
                            $eventName = Html::encode($event['@attributes']['name']);
                            $eventDate = Html::encode(date('d M H:s', intval($event['@attributes']['date'] / 1000)));
                            $eventResult = trim($event['@attributes']['results']);
                            $eventResult = $eventResult == '' || strlen($eventResult) < 1 ? false : $eventResult;

                            $eventScore = [];
                            if (isset($event['scores']) && isset($event['scores']['PScore'])) {
                                if (is_array($event['scores']['PScore']['@attributes'])) {
                                    $eventScore[] = $event['scores']['PScore']['@attributes']['label'] . ' ' .
                                        $event['scores']['PScore']['@attributes']['value'];
                                } else {
                                    foreach ($event['scores']['PScore'] as $score) {
                                        $eventScore[] = $score['@attributes']['label'] . ' ' .
                                            $score['@attributes']['value'];
                                    }
                                }
                            }
                            $eventScore = implode('<br>', $eventScore);

                            $content[] = '<tr class="pr-table-row i-event-row ' .
                                ($eventResult !== false ? 'with-results' : '') .
                                ' i-block-' . $sportBlockId . ' i-group-' . $groupId . ' i-even-' . $eventId . '' .
                                '" data-block-id="' . $sportBlockId .
                                '" data-group-id="' . $groupId .
                                '" data-event-id="' . $eventId .
                                '" data-event-name="' . $eventName .
                                '">';

                            $content[] = '<td class="pr-table-td i-event-date i-unselecteble">' .
                                '<div class="i-block-inner event-date" > ' . $eventDate . ' </div > ' .
                                '</td > ';

                            $content[] = '<td class="pr-table-td i-event-name i-unselecteble">' .
                                '<div class="i-block-inner event-name" > ' . $event['@attributes']['name'] . ' </div > ' .
                                '</td > ';

                            $content[] = '<td class="pr-table-td i-event-scores i-unselecteble">' .
                                '<div class="i-block-inner event-score" > ' . $eventScore . ' </div > ' .
                                '</td > ';

                            $content[] = '</tr>';

                            if ($eventResult !== false) {
                                $content[] = '<tr class="pr-table-row i-event-row results i-block-' . $sportBlockId .
                                    ' i-group-' . $groupId . ' i-even-' . $eventId . '' .
                                    '" data-block-id="' . $sportBlockId .
                                    '" data-group-id="' . $groupId .
                                    '" data-event-id="' . $eventId .
                                    '" data-event-name="' . $eventName .
                                    '">';

                                $content[] = '<td colspan="3" class="pr-table-td i-result-name i-unselecteble">' .
                                    '<div class="i-block-inner result-title" > ' . $eventResult . ' </div > ' .
                                    '</td > ';

                                $content[] = '</tr>';

                            }

                        }
                    }
                }
                $subHeaders[] = '</tr > ';
            }
        }
    }
}