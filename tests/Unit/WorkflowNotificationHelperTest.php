<?php

namespace Tests\Unit;

use App\Services\WorkflowNotificationHelper;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WorkflowNotificationHelperTest extends TestCase
{
    #[Test]
    public function il_resout_le_role_chef_service_avec_ses_alias(): void
    {
        $roles = WorkflowNotificationHelper::resolveRoles('chef_service');

        $this->assertSame(
            ['chef_service', 'chef_service_protocole'],
            $roles
        );
    }

    #[Test]
    public function il_garde_un_role_unique_quand_il_n_y_a_pas_d_alias(): void
    {
        $roles = WorkflowNotificationHelper::resolveRoles('agent_protocole');

        $this->assertSame(['agent_protocole'], $roles);
    }
}
