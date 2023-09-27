<?php

namespace humhub\modules\freichat\widgets;

use Yii;
use yii\helpers\Url;
use humhub\libs\Html;
use humhub\components\Widget;
use humhub\modules\user\models\User;

class FreiChatBar extends Widget
{
    public $contentContainer;

    /**
     * @inheritdoc
     */
    public function run()
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

        return $this->render('freichat', ['token' => $token, 'baseUrl' => $baseUrl, 'id' => $id, 'name' => $name, 'avatar' => $avatar, 'friendIds' => $friendIds]);
    }
}
