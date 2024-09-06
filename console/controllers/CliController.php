<?php

namespace console\controllers;

use Carbon\Carbon;
use common\models\CappingRaffle;
use common\models\RunescapeDropLog;
use common\models\RunescapeItem;
use common\models\RunescapeRank;
use common\models\RunescapeUser;
use common\models\User;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\console\Controller;
use yii\console\ExitCode;
use PHPHtmlParser\Dom;
use yii\helpers\Url;

/**
 * This is the CLI for the app
 */
class CliController extends Controller
{
    const WIKI_BASE_URL = 'https://runescape.wiki/';

    /*
     * Updates the local clan roster with the official list
     */
    public function actionClanImport()
    {
        $clanRosterRaw = file_get_contents('http://services.runescape.com/m=clan-hiscores/members_lite.ws?clanName=Fkn+Amazing');

        $clanRosterConverted = mb_convert_encoding($clanRosterRaw, "UTF-8", "Windows-1252");

        $now = Carbon::now('utc');

        // KEEP THIS WEIRD BREAK! FKN .ws files (WHY JAGEX!?) don't have a usual EOL indicator, had to copy + paste
        $clanRoster = explode('
', $clanRosterConverted);

        // The .ws CSV file has a header row with column names we don't need and a trailing blank entry
        array_shift($clanRoster);
        array_pop($clanRoster);

        $currentMembers = [];

        $runescapeUsers = RunescapeUser::find()->all();

        $runescapeUsernames = [];

        foreach($runescapeUsers as $runescapeUser) {
            $runescapeUsernames[] = $runescapeUser->username;
        }

        foreach ($clanRoster as $clanMember) {
            $exploded = explode(',', $clanMember);
            $currentMembers[] = $exploded[0];
            $rank = RunescapeRank::findOne(['name' => $exploded[1]]);
            if (!empty($rank)) {
                if (!in_array($exploded[0], $runescapeUsernames)) {
                    $newRunescapeUser = new RunescapeUser();
                    $newRunescapeUser->username = $exploded[0];
                    $newRunescapeUser->rank_id = $rank->id;
                    $newRunescapeUser->rank_points = $newRunescapeUser->rank->rank_points;
                    $newRunescapeUser->last_active = Carbon::parse(0, 'utc')->toDateTimeString();
                    $newRunescapeUser->total_xp = 0;
                    $newRunescapeUser->created_at = $now->toDateTimeString();
                    $newRunescapeUser->updated_at = $now->toDateTimeString();

                    if (!$newRunescapeUser->save()) {
                        echo 'Could not add ' . $exploded[0] . ': ' . var_export($newRunescapeUser->errors, true);
                        return ExitCode::CANTCREAT;
                    }
                } else {
                    $runescapeUser = RunescapeUser::findOne(['username' => $exploded[0]]);
                    $runescapeUser->rank_id = $rank->id;
                    $runescapeUser->updated_at = Carbon::now('UTC')->toDateTimeString();

                    if (!$runescapeUser->save()) {
                        echo 'Could not update ' . $exploded[0] . ': ' . var_export($runescapeUser->errors, true);
                        return ExitCode::DATAERR;
                    }
                }
            } else {
                echo 'Could not find the rank ' . $exploded[1] . '.';
                return ExitCode::UNAVAILABLE;
            }
        }

        foreach ($runescapeUsers as $runescapeUser) {
            if (!in_array($runescapeUser->username, $currentMembers)) {
                $runescapeUser->in_clan = 0;
                if (!$runescapeUser->save()) {
                    echo 'Could not update ' . $exploded[0] . ': ' . var_export($runescapeUser->errors, false);
                    return ExitCode::DATAERR;
                }
            }
        }

        return ExitCode::OK;
    }

    /*
     * Checks the activity logs for all members for logging
     *
     */
    public function actionCheckAdventurerLogs()
    {


        $lastReset = Yii::$app->params['citadelReset']->sub('1 week');
        $runescapeUsers = RunescapeUser::find()->all();

        foreach($runescapeUsers as $runescapeUser) {
            $adventurerLog = $runescapeUser->adventurerLog;
            if ($runescapeUser->private_profile || !$runescapeUser->in_clan) {
                continue;
            }

            $changed = false;
            $last_active = Carbon::parse(0, 'UTC');
            $last_active_date = Carbon::parse($runescapeUser->last_active, 'UTC');
            foreach ($adventurerLog as $logEntry) {
                $date = Carbon::parse($logEntry->date, 'UTC');
                if ($date->greaterThan($last_active)) {
                    $last_active = $date;
                }

                if (
                    strpos($logEntry->text, 'Capped at my Clan Citadel') !== false
                    && $date->greaterThan($lastReset)
                    && $runescapeUser->capped === 0
                ) {
                    $runescapeUser->capped = 1;
                    if ($runescapeUser->rank_points <= (RunescapeRank::GENERAL_POINTS - 2)) {
                        $runescapeUser->rank_points = ($runescapeUser->rank_points + 2);
                    } elseif ($runescapeUser->rank_points === (RunescapeRank::GENERAL_POINTS - 1)) {
                        $runescapeUser->rank_points = ($runescapeUser->rank_points + 1);
                    }

                    $changed = true;
                }

                if (
                    strpos($logEntry->text, 'Visited my Clan Citadel') !== false
                    && $date->greaterThan($lastReset)
                    && $runescapeUser->visited === 0
                ) {
                    $runescapeUser->visited = 1;
                    $changed = true;
                }

                if (strpos($logEntry->text, 'I found') !== false) {
                    $itemName = trim(str_replace([
                        'I found some',
                        'I found an',
                        'I found a',
                        'I found',
                        'pair of',
                        '.',
                    ], '', $logEntry->text));

                    // leg capitalization fix
                    $itemName = trim(str_replace([
                        'Legs',
                    ], 'legs', $itemName));

                    // Bandos warshield Fix
                    $itemName = trim(str_replace([
                        'Bandos shield',
                    ], 'Bandos warshield', $itemName));

                    $item = RunescapeItem::findOne(['name' => $itemName]);
                    if (empty($item)) {
                        $item = New RunescapeItem();
                        $item->name = $itemName;

                        if (!$item->save()) {
                            echo "\n----\nCould not save item {$item->name}: " . var_export($item->errors, false) . "\n";
                        }
                    }

                    if (!RunescapeDropLog::find()
                        ->where([
                            'user_id' => $runescapeUser->id,
                            'item_id' => $item->id,
                            'dropped_at' => $date->toDateTimeString()
                         ]) ->exists()
                    ) {
                        $dropLog = new RunescapeDropLog();
                        $dropLog->user_id = $runescapeUser->id;
                        $dropLog->item_id = $item->id;
                        $dropLog->dropped_at = $date->toDateTimeString();

                        if (!$dropLog->save()) {
                            echo "\n----\nCould not save drop log for {$runescapeUser->username} of {$item->name}: " . var_export($dropLog->errors, false) . "\n";
                        }
                    }
                }
            }

            if ($last_active->greaterThan($last_active_date)) {
                $runescapeUser->last_active = $last_active->toDateTimeString();
                $changed = true;
            }

            if ($changed) {
                $runescapeUser->updated_at = $date->toDateTimeString();

                if (!$runescapeUser->save()) {
                    echo "Could not save user {$runescapeUser->username}: " . var_export($runescapeUser->errors, false) . "\n";
                }
            }
        }

        return ExitCode::OK;
    }

    public function actionResetCapped()
    {
        $reset = Yii::$app->params['citadelReset'];

        $latestRaffle = CappingRaffle::getLatestRaffle();
        $lastRaffleOffset = Carbon::parse($latestRaffle->reset_at, 'utc')->addUnit('days', 8);

        if ($reset->lessThan($lastRaffleOffset)) {
            exit;
        }

        $runescapeUsers = RunescapeUser::find()->all();

        $raffle = new CappingRaffle();
        $raffle->reset_at = Carbon::now()->toDateTimeString();
        $raffle->paid = 0;


        if (!$raffle->save()) {
            echo "Could not save raffle: " . var_export($raffle->errors, false) . "\n";
            return ExitCode::CANTCREAT;
        }

        foreach($runescapeUsers as $runescapeUser) {
            $capped = 0;
            $visited = 0;
            if ($runescapeUser->capped && $runescapeUser->in_clan) {
                $raffle->link('runescapeUsers', $runescapeUser);
            }

            $runescapeUser->capped = $capped;
            $runescapeUser->visited = $visited;
            $runescapeUser->updated_at = Carbon::now()->toDateTimeString();

            if (!$runescapeUser->save()) {
                echo "Could not save user {$runescapeUser->username}: " . var_export($runescapeUser->errors, false) . "\n";
            }
        }

        if (!$raffle->pickRaffleWinner()) {
            echo "Could not pick a raffle winner: " . var_export($raffle->errors, false) . "\n";
            return ExitCode::DATAERR;
        }

        return ExitCode::OK;
    }

    public function actionCheckInactiveUsers()
    {
        $runescapeUsers = RunescapeUser::find()->all();
        $date = Carbon::now('utc');
        $lastMonth = Carbon::now('utc')->subtract('month', 1);

        foreach ($runescapeUsers as $runescapeUser) {
            if (!empty($runescapeUser->on_break)
                || $runescapeUser->private_profile
                || !$runescapeUser->canGetPoints()
            ) {
                continue;
            }

            $last_active = Carbon::parse($runescapeUser->last_active, 'utc');

            if ($last_active->lessThan($lastMonth)) {
                $runescapeUser->updated_at = $date->toDateTimeString();
                $runescapeUser->rank_points = ($runescapeUser->rank_points - 4);

                if (!$runescapeUser->save()) {
                    echo "Could not save user {$runescapeUser->username}: " . var_export($runescapeUser->errors, false) . "\n";
                }
            }
        }
    }

    public function actionCreateUser($username, $email)
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword(Yii::$app->security->generateRandomString());
        $user->status = User::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->generatePasswordResetToken();
        if (!$user->save()) {
            return ExitCode::CANTCREAT;
        }

        return ExitCode::OK;
    }

    public function actionGetPasswordResetLink($username)
    {
        $user = User::find()->where(['username' => $username])->one();
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        echo Url::to(['/site/reset-password', 'token' => $user->password_reset_token]) . "\n";
    }

    public function actionCleanupDropLogs()
    {
        $dropLogs = RunescapeDropLog::find()->all();

        foreach ($dropLogs as $dropLog) {
            if (RunescapeDropLog::find()
                ->where([
                    'user_id' => $dropLog->user_id,
                    'item_id' => $dropLog->item_id,
                    'dropped_at' => $dropLog->dropped_at,
                ])->count() > 1
            ) {
                $dropLog->delete();
            }
        }
    }

    public function actionDownloadItemImages() {
        $runescapeItems = RunescapeItem::find()->all();

        foreach ($runescapeItems as $runescapeItem) {
            $this->downloadItemImages($runescapeItem);
        }
    }

    private function downloadItemImages(RunescapeItem $runescapeItem) {
        $itemName = $runescapeItem->urlName;
        $basePath = str_replace('console', '', Yii::getAlias('@app'));
        $filename = $basePath . 'frontend/web/images/items/' . $itemName . '.png';

        if (!file_exists($filename)) {
            $wikiString = file_get_contents(self::WIKI_BASE_URL . $itemName);

            $dom = new Dom();
            $dom->loadStr($wikiString);
            $imageAnchors = $dom->find('a.image');

            if (count($imageAnchors) === 0) {
                echo "No images found for item: {$runescapeItem->name}\n\n";
                return;
            }

            $bestImage = null;
            foreach($imageAnchors as $imageAnchor) {
                foreach($imageAnchor->find('img') as $image) {
                    $imageData = getimagesize(self::WIKI_BASE_URL . $image->getAttribute('src'));

                    if ($imageData['mime'] != 'image/png') {
                        continue;
                    }

                    if (empty($bestImage)) {
                        $bestImage = $image;
                        continue;
                    }

                    $bestImageData = getimagesize(self::WIKI_BASE_URL . $bestImage->getAttribute('src'));

                    if ($imageData[0] > $bestImageData[0] && $imageData[1] > $bestImageData[1]) {
                        $bestImage = $image;
                    }
                }
            }


            file_put_contents($filename, file_get_contents(self::WIKI_BASE_URL . $bestImage->getAttribute('src')));
        }
    }

    public function actionFixNegativeRankPoints()
    {
        $runescapeUsers = RunescapeUser::find()->where(['<', 'rank_points', 0])->all();

        foreach ($runescapeUsers as $runescapeUser) {
            $runescapeUser->rank_points = $runescapeUser->rank->rank_points;
            $runescapeUser->save();
        }
    }

//    Done, Don't run again
//    public function actionAssignPoints()
//    {
//        $runescapeUsers = RunescapeUser::find()->all();
//
//        foreach ($runescapeUsers as $runescapeUser) {
//            $runescapeUser->rank_points = $runescapeUser->getPointsByRank();
//            if (!$runescapeUser->save()) {
//                echo "Could not save user: " . var_export($runescapeUser->errors, false) . "\n";
//                return ExitCode::DATAERR;
//            }
//        }
//
//        return ExitCode::OK;
//    }

}
