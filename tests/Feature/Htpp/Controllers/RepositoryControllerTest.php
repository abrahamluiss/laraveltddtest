<?php

namespace Tests\Feature\Htpp\Controllers;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RepositoryControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_guest()
    {
        $this->get('repositories')->assertRedirect('login');        //index
        $this->get('repositories/1')->assertRedirect('login');      //show
        $this->get('repositories/1/edit')->assertRedirect('login'); //edit
        $this->put('repositories/1')->assertRedirect('login');      //update
        $this->delete('repositories/1')->assertRedirect('login');   //destroy
        $this->get('repositories/create')->assertRedirect('login'); //create
        $this->post('repositories', [])->assertRedirect('login');   //store

    }

    public function test_store()
    {
        $data = [
            'url' => $this->faker->url,
            'description' => $this->faker->text,
        ];

        $user = User::factory()->create();//crea el usuario

        $this->actingAs($user)->post('repositories', $data)->assertRedirect('repositories');

        $this->assertDatabaseHas('repositories', $data);
    }

    public function test_create()
    {
        $user = User::factory()->create();//crea el usuario

        //$this->withoutExceptionHandling();
        //$repository = Repository::factory()->create(['user_id'=> $user->id]);


        $this->withoutExceptionHandling();

        $this
            ->actingAs($user)
            ->get('repositories/create')
            ->assertStatus(200);

    }

    public function test_index_empty()
    {

        $user = User::factory()->create();//id=1
        Repository::factory()->create(); //user_id=2
        //$this->withoutExceptionHandling();
        $this
            ->actingAs($user)
            ->get('repositories')
            ->assertStatus(200)
            ->assertSee(' No hay repositorioes creados ');

    }
    public function test_index_with_data()
    {
 
        $user = User::factory()->create();//id=1
        $repository = Repository::factory()->create(['user_id' => $user->id]); //user_id=2
        $this
            ->actingAs($user)
            ->get('repositories')
            ->assertStatus(200)
            ->assertSee($repository->url);

    }

    public function test_update()
    {
        $user = User::factory()->create();//crea el usuario

        //$this->withoutExceptionHandling();
        $repository = Repository::factory()->create(['user_id'=> $user->id]);
        //actualizare con estos datos
        $data = [
            'url' => $this->faker->url,
            'description' => $this->faker->text,
        ];


        $this
            ->actingAs($user)
            ->put("repositories/$repository->id", $data)
            ->assertRedirect("repositories/$repository->id/edit");

        $this->assertDatabaseHas('repositories', $data);
    }
//politicas
    public function test_update_policy()
    {
        $user = User::factory()->create();//crea el usuario con id=1
        //$this->withoutExceptionHandling();
        $repository = Repository::factory()->create();//user_id=2
        //actualizare con estos datos
        $data = [
            'url' => $this->faker->url,
            'description' => $this->faker->text,
        ];



        $this
            ->actingAs($user)
            ->put("repositories/$repository->id", $data)
            ->assertStatus(403);

        //$this->assertDatabaseHas('repositories', $data);
    }
    //Validacion
    public function test_validate_store()
    {


        $user = User::factory()->create();//crea el usuario

        $this
            ->actingAs($user)
            ->post('repositories', [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['url', 'description']);

        //$this->assertDatabaseHas('repositories', $data);
    }
    public function test_validate_update()
    {
        //$this->withoutExceptionHandling();
        $repository = Repository::factory()->create();//tengo un repository creado

        $user = User::factory()->create();//crea el usuario

        $this
            ->actingAs($user)
            ->put("repositories/$repository->id", [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['url', 'description']);

        //$this->assertDatabaseHas('repositories', $data);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();//crea el usuario

        //$this->withoutExceptionHandling();
        $repository = Repository::factory()->create(['user_id' => $user->id]);//tengo un repository creado
   


        $this
            ->actingAs($user)
            ->delete("repositories/$repository->id")
            ->assertRedirect('repositories');
    //q no se tiene la informacion
        $this->assertDatabaseMissing('repositories',[
            'id' => $repository->id,
            'url' => $repository->url,
            'description' => $repository->description,

        ]);
    }
    public function test_destroy_policy()
    {
        $user = User::factory()->create();//id=1

        //$this->withoutExceptionHandling();
        $repository = Repository::factory()->create();//id=2
   


        $this
            ->actingAs($user)
            ->delete("repositories/$repository->id")
            ->assertStatus(403);

    }

    //
    public function test_show()
    {
        $user = User::factory()->create();//crea el usuario

        //$this->withoutExceptionHandling();
        $repository = Repository::factory()->create(['user_id'=> $user->id]);



        $this
            ->actingAs($user)
            ->get("repositories/$repository->id")
            ->assertStatus(200);

    }
//politicas
    public function test_show_policy()
    {
        $user = User::factory()->create();//crea el usuario con id=1
        //$this->withoutExceptionHandling();
        $repository = Repository::factory()->create();//user_id=2


        $this
            ->actingAs($user)
            ->get("repositories/$repository->id")
            ->assertStatus(403);

        //$this->assertDatabaseHas('repositories', $data);
    }
    //
    public function test_edit()
    {
        $user = User::factory()->create();//crea el usuario

        //$this->withoutExceptionHandling();
        $repository = Repository::factory()->create(['user_id'=> $user->id]);



        $this
            ->actingAs($user)
            ->get("repositories/$repository->id/edit")
            ->assertSee($repository->url)
            ->assertSee($repository->description);

    }
//politicas
    public function test_edit_policy()
    {
        $user = User::factory()->create();//crea el usuario con id=1
        //$this->withoutExceptionHandling();
        $repository = Repository::factory()->create();//user_id=2


        $this
            ->actingAs($user)
            ->get("repositories/$repository->id/edit")
            ->assertStatus(403);

        //$this->assertDatabaseHas('repositories', $data);
    }


}
