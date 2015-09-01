<?php

use yii\helpers\Html;

function filterTemplate($code, $name)
{
    /**
     * @var $betModule app\modules\bet\Module
     */
    $betModule = Yii::$app->getModule('bet');

    $code = str_replace(" ", "", $code);
    return '<li class="wpmcpf-item item-sport-' . Html::encode($code) . '" data-code="' .
    Html::encode($code) . '">' .
    '<span class="wpmcpf-icon ' . Html::encode($betModule->buildIcon($code)) . '"></span>' .
    '<span class="wpmcpf-name">' . Html::encode($name) . '</span></li>';
}

function sportBlock($sportCode, $sportName, $groups = [])
{
    $sportCode = str_replace(" ", "", $sportCode);
    $subHeaders = [];

    if (is_array($groups) && sizeof($groups) > 0) {
        while (list(, $group) = each($groups)) {
            if (isset($group['@attributes'])) {
                $subHeaders[] = '<div class="wpmcsb-subheader item-block-group-' .
                    Html::encode($group['@attributes']['Id']) . '"">' .
                    '<span class="wpmcsb-sh-title">' .
                    Html::encode($group['@attributes']['Name']) . '</span>' .
                    '</div>';
                if (
                    isset($group['Events']) &&
                    isset($group['Events']['XEvent']) &&
                    is_array($group['Events']['XEvent'])
                ) {
                    $events = $group['Events']['XEvent'];
                    if (sizeof($events) == 1 &&
                        isset($events['@attributes'])
                    ) {
                        $events = [$events];
                    }

                    while (list(, $event) = each($events)) {
                        if (isset($event['@attributes'])) {
                            $subHeaders[] = '<div class="wpmcsb-line">' .
                                '<span class="wpmcsb-sh-title">' .
                                Html::encode($event['@attributes']['Name']) .
                                '</span><span class="wpmcsb-sh-results">' .
                                $event['@attributes']['Result'] .
                                '</span></div>';

                        }
                    }
                }
            }
        }
    }
    /*    $subHeader = '<div class="wpmcsb-subheader">'.
            '<span class="wpmcsb-sh-title">Japan. NPB</span>'.
            '</div>';

        $subLine = '<div class="wpmcsb-line">'.
            '<span class="wpmcsb-sh-title">Fukuoka SoftBank Hawks (J.Standridge) @ Tohoku Rakuten Golden Eagles (K.Tomura) </span><span
                        class="wpmcsb-sh-results">7 in. 8:0 (1:0, 0:0, 2:0, 0:0, 2:0, 0:0)</span></div>
            </div>';*/

    return '<div class="wpmc-sport-block item-block-' . Html::encode($sportCode) . '">' .
    '<div class="wpmcsb-header">' .
    '<span class="wpmcsb-header-title">' . Html::encode($sportName) . '</span>' .
    '<span class="wpmcsb-header-buttons"><span class="icon-prjExpand"></span></span>' .
    '</div>' .
    implode("\r\n", $subHeaders) .
    '</div>';
}