<?php

namespace Tests\Unit\Model;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_has_many_repositories() //un usuario tiene muchos repositorios
    {
        $user = new User;

        $this->assertInstanceOf(Collection::class, $user->repositories);//tengo muchos repositorios en user?
    }
}
