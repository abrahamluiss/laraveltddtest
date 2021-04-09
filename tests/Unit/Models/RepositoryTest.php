<?php

namespace Tests\Unit\Models;

use App\Models\Repository;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepositoryTest extends TestCase
{
    use RefreshDatabase;//como ejecutar la migracion
    public function test_belongs_to_user()//pertenece a usuario
    {
        $repository = Repository::factory()->create();

        $this->assertInstanceOf(User::class, $repository->user);//cuando se accede al usuario es un instacia de repository?

    }
}
