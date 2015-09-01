<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 20.08.2015
 * Time: 4:59
 */

namespace app\modules\main\captcha;

use Yii;
use yii\web\Response;
use yii\helpers\Url;
use yii\captcha\CaptchaAction as BaseCaptchaAction;

class CaptchaAction extends BaseCaptchaAction {

    /**
     * Runs the action.
     */
    public function run()
    {
        if (Yii::$app->request->getQueryParam(self::REFRESH_GET_VAR) !== null) {
            // AJAX request for regenerating code
            $code = $this->getVerifyCode(true);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'hash1' => $this->generateValidationHash($code),
                'hash2' => $this->generateValidationHash(strtolower($code)),
                // we add a random 'v' parameter so that FireFox can refresh the image
                // when src attribute of image tag is changed
                'url' => Url::to([$this->id, 'v' => uniqid()]),
            ];
        } else {
            $this->setHttpHeaders();
            Yii::$app->response->format = Response::FORMAT_RAW;
            return $this->renderImage($this->getVerifyCode(true));  // always regenerate code
        }
    }

    /**
     * Validates the input to see if it matches the generated code.
     * @param string $input user input
     * @param boolean $caseSensitive whether the comparison should be case-sensitive
     * @return boolean whether the input is valid
     */
    public function validate($input, $caseSensitive)
    {
        $code = $this->getVerifyCode();
        $valid = $caseSensitive ? ($input === $code) : strcasecmp($input, $code) === 0;
        /*        $session = Yii::$app->getSession();
                $session->open();
                $name = $this->getSessionKey() . 'count';
                $session[$name] = $session[$name] + 1;
                if ( // $valid ||  // disable regeneration on valid
                    $session[$name] > $this->testLimit && $this->testLimit > 0) {
                    $this->getVerifyCode(true);
                }*/

        return $valid;
    }


}