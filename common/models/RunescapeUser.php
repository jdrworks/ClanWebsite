<?php

namespace common\models;

use Carbon\Carbon;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "runescape_user".
 *
 * @property int $id
 * @property string $username
 * @property int $rank_id
 * @property int $capped
 * @property int $visited
 * @property string $created_at
 * @property string $updated_at
 * @property int $private_profile
 * @property int $in_clan
 * @property int $old_name
 * @property int $rank_points
 * @property int $last_active
 * @property int $total_xp
 * @property int $on_break
 *
 * @property CappingRaffle[] $runescapeCappingRaffles
 * @property RunescapeUser[] $runescapeCappingRaffleUsers
 * @property RunescapeDropLog[] $runescapeDropLogs
 * @property RunescapeRank $rank
 * @property RunescapeUserName[] $runescapeUserNames
 */
class RunescapeUser extends \yii\db\ActiveRecord
{
    public $activities = [];
    public $old_user_id = null;

    public function behaviors()
    {
        return [
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'runescape_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'rank_id', 'created_at', 'updated_at', 'last_active', 'total_xp'], 'required'],
            [['rank_id', 'capped', 'visited', 'private_profile', 'in_clan', 'old_name', 'rank_points', 'total_xp'], 'integer'],
            [['created_at', 'updated_at', 'old_user_id', 'last_active', 'on_break'], 'safe'],
            [['username'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['rank_id'], 'exist', 'skipOnError' => true, 'targetClass' => RunescapeRank::className(), 'targetAttribute' => ['rank_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'rank_id' => 'Rank ID',
            'capped' => 'Capped',
            'visited' => 'Visited',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'private_profile' => 'Private Profile',
            'in_clan' => 'In Clan',
            'old_name' => 'Old Name',
            'old_user_id' => 'Old User Id',
            'rank_points' => 'Rank Points',
            'last_active' => 'Last Active',
            'on_break' => 'On Break Since',
            'total_xp' => 'Total Experience',
        ];
    }

    /**
     * Gets query for [[RunescapeCappingRaffles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCappingRaffles()
    {
        return $this->hasMany(CappingRaffle::class, ['id' => 'capping_raffle_id'])
            ->viaTable('runescape_capping_raffle_user', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[RunescapeEvents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHostedEvents()
    {
        return $this->hasMany(RunescapeEvent::class, ['host_id' => 'id']);
    }

    /**
     * Gets query for [[RunescapeEvents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(RunescapeEvent::class, ['id' => 'event_id'])
            ->viaTable('runescape_event_user', ['user_id' => 'id']);
    }


    /**
     * Gets query for [[RunescapeDropLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRunescapeDropLogs()
    {
        return $this->hasMany(RunescapeDropLog::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Rank]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRank()
    {
        return $this->hasOne(RunescapeRank::className(), ['id' => 'rank_id']);
    }

    /**
     * Gets query for [[RunescapeUsernames]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRunescapeUsernames()
    {
        return $this->hasMany(RunescapeUsername::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRunescapeUserNotes()
    {
        return $this->hasMany(RunescapeUserNote::class, ['id' => 'user_id']);
    }

    public function getApiData()
    {
        $urlEncodedUsername = str_replace(" ", "+", $this->username);
        $urlEncodedUsername = str_replace(" ", "+", $urlEncodedUsername);
        $jsonData = file_get_contents("https://apps.runescape.com/runemetrics/profile/profile?user={$urlEncodedUsername}&activities=20");

        $apiUser = json_decode($jsonData);

        $this->updated_at = Carbon::now('UTC')->toDateTimeString();

        if (!empty($apiUser->error)) {
            if ($apiUser->error === "PROFILE_PRIVATE") {
                $this->private_profile = 1;
                if (!$this->save()) {
                    throw new Exception("Could not save user {$this->username}: " . var_export($this->errors, false));
                }
            }

            if ($apiUser->error === "NO_PROFILE") {
                // TODO: Log this when I set up a global logging system
//                $this->old_name = 1;
//                if (!$this->save()) {
//                    throw new Exception("Could not save user {$this->username}: " . var_export($this->errors, false));
//                }
            }

            $this->activities = [];
            return;
        }

        if ($this->private_profile) {
            $this->private_profile = 0;
            if (!$this->save()) {
                throw new Exception("Could not save user {$this->username}: " . var_export($this->errors, false));
            }
        }

        if ($this->total_xp < $apiUser->totalxp) {
            $this->total_xp = $apiUser->totalxp;
            $this->last_active = $this->updated_at;
            if (!$this->save()) {
                throw new Exception("Could not save user {$this->username}: " . var_export($this->errors, false));
            }
        }

        $this->activities = $apiUser->activities;
    }

    public function getAdventurerLog()
    {
        if (empty($this->activities)) {
            $this->getApiData();
        }

        return $this->activities;
    }

    public function assignOldUsername(RunescapeUser $oldUser)
    {
        $oldName = new RunescapeUsername();
        $oldName->username = $oldUser->username;
        $oldName->user_id = $this->id;

        if ($oldName->save()) {
            //Move Old Names over:
            $oldNames = RunescapeUserName::find()->where(['user_id' => $oldUser->id])->all();
            foreach ($oldNames as $oldName) {
                $oldName->user_id = $this->id;
                $oldName->save();
            }

            //Update any raffles they've been in (and may have won)
            foreach ($oldUser->cappingRaffles as $cappingRaffle) {
                if ($cappingRaffle->winner_id == $oldUser->id) {
                    $cappingRaffle->winner_id = $oldUser->id;
                }

                $cappingRaffle->unlink('runescapeUsers', $oldUser, true);
                $cappingRaffle->link('runescapeUsers', $this);
            }

            //Update drop logs
            foreach ($oldUser->runescapeDropLogs as $runescapeDropLog) {
                $runescapeDropLog->user_id = $this->id;
                $runescapeDropLog->save();
            }

            if ($oldUser->capped === 1) {
                $this->capped = 1;
            }

            if ($oldUser->visited === 1) {
                $this->visited = 1;
            }

            $oldUser->delete();
        }
    }

    public function humanReadableDate($date, $showTime = true)
    {
        $carbon = new Carbon($date);

        if ($showTime) {
            return $carbon->format('m/d/y h:i:s A');
        }

        return $carbon->format('m/d/y');
    }

    public function activeInLastSixMonths(): bool
    {
        $sixMonths = new Carbon('utc');
        $sixMonths->subtract('month', 6);
        $lastActive = Carbon::parse($this->last_active, 'utc');

        return $lastActive->greaterThan($sixMonths);
    }

    public function canGetPoints()
    {
        if (in_array($this->rank->name, [
            'Owner', 'Deputy Owner', 'Overseer', 'Coordinator', 'Organiser', 'Admin'
        ])) {
            return false;
        }

        return true;
    }

    public function getRankStatus()
    {
        $promotionPoints = $this->rank->promotion_points;
        if ($this->rank_points >= $promotionPoints && !is_null($promotionPoints)) {
            return 1;
        } elseif ($this->rank_points < $this->rank->rank_points) {
            return -1;
        }

        return 0;
    }

    public function getReportIcon()
    {
        $icon = '<i class="fa fa-exclamation-triangle text-warning"></i>';

        if ($this->private_profile) {
            $icon = '<i class="fa fa-eye-slash text-danger"></i>';
        } else {
            if ($this->rankStatus > 0) {
                $icon = '<i class="fa fa-arrow-up text-success"></i>';
            } elseif ($this->rankStatus < 0) {
                if (!$this->activeInLastSixMonths() && $this->rank_points < 0) {
                    $icon = '<i class="fa fa-ban text-danger"></i>';
                } else {
                    $icon = '<i class="fa fa-arrow-down text-danger"></i>';
                }
            }
        }

        return $icon;
    }
}
