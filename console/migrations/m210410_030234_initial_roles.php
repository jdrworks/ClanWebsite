<?php

use yii\db\Migration;

/**
 * Class m210410_030234_initial_roles
 */
class m210410_030234_initial_roles extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // add "readRunescapeUser" permission
        $readRunescapeUser = $auth->createPermission('readRunescapeUser');
        $readRunescapeUser->description = 'Read Runescape Users';
        $auth->add($readRunescapeUser);

        // add "updateRunescapeUser" permission
        $updateRunescapeUser = $auth->createPermission('updateRunescapeUser');
        $updateRunescapeUser->description = 'Update a Runescape User';
        $auth->add($updateRunescapeUser);

        // add "readRunescapeUserReport" permission
        $readRunescapeUserReport = $auth->createPermission('readRunescapeUserReport');
        $readRunescapeUserReport->description = 'Read Runescape Users report';
        $auth->add($readRunescapeUserReport);

        // add "readCappingRaffle" permission
        $readCappingRaffle = $auth->createPermission('readCappingRaffle');
        $readCappingRaffle->description = 'Read Capping Raffles';
        $auth->add($readCappingRaffle);

        // add "updateCappingRaffle" permission
        $updateCappingRaffle = $auth->createPermission('updateCappingRaffle');
        $updateCappingRaffle->description = 'Update a Capping Raffle';
        $auth->add($updateCappingRaffle);

        // add "createRunescapeItem" permission
        $createRunescapeItem = $auth->createPermission('createRunescapeItem');
        $createRunescapeItem->description = 'Create Runescape Items';
        $auth->add($createRunescapeItem);

        // add "readRunescapeItem" permission
        $readRunescapeItem = $auth->createPermission('readRunescapeItem');
        $readRunescapeItem->description = 'Read Runescape Items';
        $auth->add($readRunescapeItem);

        // add "updateRunescapeItem" permission
        $updateRunescapeItem = $auth->createPermission('updateRunescapeItem');
        $updateRunescapeItem->description = 'Update a Runescape Item';
        $auth->add($updateRunescapeItem);

        // add "deleteRunescapeItem" permission
        $deleteRunescapeItem = $auth->createPermission('deleteRunescapeItem');
        $deleteRunescapeItem->description = 'Delete a Runescape Item';
        $auth->add($deleteRunescapeItem);


        // add "admin" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $readRunescapeUser);
        $auth->addChild($admin, $readCappingRaffle);
        $auth->addChild($admin, $readRunescapeItem);

        // add "organiser" role
        $organiser = $auth->createRole('organiser');
        $auth->add($organiser);
        $auth->addChild($organiser, $admin);

        // add "coordinator" role
        $coordinator = $auth->createRole('coordinator');
        $auth->add($coordinator);
        $auth->addChild($coordinator, $organiser);

        // add "overseer" role
        $overseer = $auth->createRole('overseer');
        $auth->add($overseer);
        $auth->addChild($overseer, $coordinator);
        $auth->addChild($overseer, $updateRunescapeUser);
        $auth->addChild($overseer, $readRunescapeUserReport);
        $auth->addChild($overseer, $createRunescapeItem);
        $auth->addChild($overseer, $updateRunescapeItem);

        // add "deputyowner" role
        $deuptyowner = $auth->createRole('deputyowner');
        $auth->add($deuptyowner);
        $auth->addChild($deuptyowner, $overseer);
        $auth->addChild($deuptyowner, $deleteRunescapeItem);

        // add "owner" role
        $owner = $auth->createRole('owner');
        $auth->add($owner);
        $auth->addChild($owner, $deuptyowner);

        // add "siteadmin" role
        $siteadmin = $auth->createRole('siteadmin');
        $auth->add($siteadmin);
        $auth->addChild($siteadmin, $owner);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }
}

