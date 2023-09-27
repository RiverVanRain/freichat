<?php

namespace humhub\modules\freichat\controllers;

use Yii;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\user\models\User;

/**
 * Add chat page
 *
 * @author Codologic
 */
class ChatController extends ContentContainerController
{
    public $hideSidebar = false;

    public $requireContainer = false;

    public function actionIndex()
    {
        $settings = Yii::$app->getModule('freichat')->settings;
        $token = $settings->get('token');
        $user_filters = $settings->get('user_filters');
        $baseUrl = "https://nodelb.freichat.com/api";
        $identity = Yii::$app->user->identity;
        $id = $identity->getId();
        $name = $identity->getDisplayName();
        $avatar = Yii::$app->request->getHostInfo() . $identity->getProfileImage()->getUrl();

        if ($user_filters === "friends") {
            if (Yii::$app->getModule('friendship')->settings->get('enable')) {
				$friends = $identity->getFriends()->all();
			} else {
				$friends = $identity->getFollowingQuery(User::find())->limit(100)->all();
			}
            $friendIds = [];

            foreach ($friends as $friend) {
                array_push($friendIds, $friend->id);
            }
        } else {
            $friendIds = null;
        }

        return $this->render('index', ['token' => $token, 'baseUrl' => $baseUrl, 'id' => $id, 'name' => $name, 'avatar' => $avatar, 'friendIds' => $friendIds]);
    }

    public function actionSearch() {
        return "HI";
    }
}
