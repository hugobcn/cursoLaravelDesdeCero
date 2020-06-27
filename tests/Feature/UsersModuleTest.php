<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UsersModuleTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function testBasicTest() {

        factory(User::class)->create([
            'name' => 'Joel',
                //'profession_id' =>1
        ]);


        $this->get('/usuarios')
                ->assertStatus(200)
                ->assertSee('Listado de usuarios')
                ->assertSee('Joel');
        /*
          $this->get('/')
          ->assertStatus(200)
          ->assertSee('Listado de usuarios')
          ;
         */
        /*
          $response = $this->get('/');

          $response->assertStatus(200);
         * 
         */
    }

    /** @test */
    function test_it_shows_the_users_list() {
        factory(User::class)->create([
            'name' => 'Joel',
                //'profession_id' =>1
        ]);

        factory(User::class)->create([
            'name' => 'Ellie',
        ]);

        $this->get('/usuarios')
                ->assertStatus(200)
                ->assertSee('Listado de usuarios')
                ->assertSee('Joel')
                ->assertSee('Ellie');
    }

    /** @test */
    function test_it_loads_the_users_list_page() {

        $user = factory(User::class)->create([
            'name' => 'hugo bermudez'
        ]);

        $this->get('/usuarios')
                ->assertStatus(200)
                ->assertSee('hugo bermudez')
        ;
    }

    /** @test 
      function it_shows_a_default_message_if_the_users_list_is_empty()
      {
      $this->get('/usuarios?empty')
      ->assertStatus(200)
      ->assertSee('No hay usuarios registrados.');
      }
     */
    /*
     * * @test *
      function test_it_loads_the_users_list_page()
      {
      $this->get('/usuarios')
      ->assertStatus(200)
      ->assertSee('Usuarios');
      }
     */

    /** @test */
    function test_it_loads_the_users_details_page() {
        $this->get('/usuarios/5')
                ->assertStatus(200)
                ->assertSee("Mostrando detalle del usuario: 5");
        //"Mostrando detalle del usuario: {$id}"
    }

    /** @test */
    function test_it_loads_the_new_users_page() {
        $this->get('/usuarios/nuevo')
                ->assertStatus(200)
                ->assertSee('Crear nuevo usuario');
    }

    /** @test */
    function test_it_display_a_404_user_not_found() {
        $this->get('/usuarios/666')
                ->assertStatus(404)
                ->assertSee("Página no encontrada");
        //"Mostrando detalle del usuario: {$id}"
    }

    /** @test */
    function test_it_creates_a_new_user() {
        $this->withoutExceptionHandling();

        $this->post('/usuarios/', [
            'name' => 'Duilio',
            'email' => 'duilio@styde.net',
            'password' => '123456'
        ])->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Duilio',
            'email' => 'duilio@styde.net',
            'password' => '123456',
        ]);

        //se puede poner el nombre de la tabla  'users'
        // $this->assertCredentials(['users',[ 
    }

    /** @test */
    function test_name_is_required() {
        $this->withoutExceptionHandling();

        //->from('usuarios/nuevo') envia a la url ->assertRedirect('usuarios')
        // anterior y prueba no tiene url anterior

        $this->from('usuarios/nuevo')->post('/usuarios/', [
                    'name' => '',
                    'email' => 'duilio@styde.net',
                    'password' => '123456'
                ])->assertRedirect('usuarios')
                ->assertSessionHasErrors(['name' => 'nombre campo obligatorio']);
        //assertSessionHasErrors('name'); verificar si tiene algun error
        $this->assertDatabaseMissing('users', ['email' => 'xxxx@yyy.ex']);
        //assertDatabaseMissing('users')  que no hay este elemento repetido
    }

    /** @test */
    function test_the_email_is_required() {
        $this->withoutExceptionHandling();

        $this->from('usuarios/nuevo')
                ->post('/usuarios/', [
                    'name' => 'Duilio',
                    'email' => '',
                    'password' => '123456'
                ])
                ->assertRedirect('usuarios/nuevo')
                ->assertSessionHasErrors(['email']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function test_the_email_must_be_valid() {
        $this->withoutExceptionHandling();

        $this->from('usuarios/nuevo')
                ->post('/usuarios/', [
                    'name' => 'Duilio',
                    'email' => 'correo-no-valido',
                    'password' => '123456'
                ])
                ->assertRedirect('usuarios/nuevo')
                ->assertSessionHasErrors(['email']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function test_the_email_must_be_unique() {
        $this->withoutExceptionHandling();

        factory(User::class)->create([
            'email' => 'duilio@styde.net'
        ]);

        $this->from('usuarios/nuevo')
                ->post('/usuarios/', [
                    'name' => 'Duilio',
                    'email' => 'duilio@styde.net',
                    'password' => '123456'
                ])
                ->assertRedirect('usuarios/nuevo')
                ->assertSessionHasErrors(['email']);

        $this->assertEquals(1, User::count());
    }

    /** @test */
    function test_the_password_is_required() {
        $this->withoutExceptionHandling();

        $this->from('usuarios/nuevo')
                ->post('/usuarios/', [
                    'name' => 'Duilio',
                    'email' => 'duilio@styde.net',
                    'password' => ''
                ])
                ->assertRedirect('usuarios/nuevo')
                ->assertSessionHasErrors(['password']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function test_it_loads_the_edit_user_page() {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->get("/usuarios/{$user->id}/editar") // usuarios/5/editar
                ->assertStatus(200)
                ->assertViewIs('users.edit')
                ->assertSee('Editar usuario')
                ->assertViewHas('user', function ($viewUser) use ($user) {
                    return $viewUser->id === $user->id;
                });
    }

    /** @test */
    function test_update_user() {
        $this->withoutExceptionHandling();

        $this->put('/usuarios/{$user->id}', [
            'name' => 'Duilio',
            'email' => 'duilio@styde.net',
            'password' => '123456'
        ])->assertRedirect('/usuarios/{$user->id}');

        $this->assertCredentials([
            'name' => 'Duilio',
            'email' => 'duilio@styde.net',
            'password' => '123456',
        ]);
    }

    /** @test */
    function test_the_name_is_required_update() {

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->from('usuarios/{$user->id}/editar')
                ->put('usuarios/{$user->id}', [
                    'name' => '',
                    'email' => 'duilio@styde.net',
                    'password' => '123456'
                ])
                ->assertRedirect('usuarios/{$user->id}/editar')
                ->assertSessionHasErrors(['name']);

        //no se repita el correo especifico
        $this->assertDataBaseMissing('users', ['email' => 'xxxx@yyy.es']);

        //$this->assertEquals(0, User::count());
    }

    /** @test */
    function test_email_is_valid_update() {

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create(['name' => 'nombre inicial']);

        $this->from('usuarios/{$user->id}/editar')
                ->put('usuarios/{$user->id}', [
                    'name' => 'hugo bermudez',
                    'email' => 'correo_invalido',
                    'password' => '123456'
                ])
                ->assertRedirect('usuarios/{$user->id}/editar')
                ->assertSessionHasErrors(['email']);

        //no se repita el correo especifico
        $this->assertDataBaseMissing('users', ['name' => 'hugo bermudez']);

        //$this->assertEquals(0, User::count());
    }

    /** @test */
    function test_email_unique_update() {

        self::markTestincomplete();
        return;

        $user = factory(User::class)->create(['email' => 'xxxx@yyy.es']);

        $this->from('usuarios/{$user->id}/editar')
                ->put('usuarios/{$user->id}', [
                    'name' => 'hugo bermudez',
                    'email' => 'xxxx@yyy.es',
                    'password' => '123456'
                ])
                ->assertRedirect('usuarios/{$user->id}/editar')
                ->assertSessionHasErrors(['email']);

        //no se repita el correo especifico
        //$this->assertDataBaseMissing('users',['name'=> 'hugo bermudez']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function test_password_required_update() {

        //self::markTestincomplete();
        //return;

        $user = factory(User::class)->create(); //['email'=>'xxxx@yyy.es']);

        $this->from('usuarios/{$user->id}/editar')
                ->put('usuarios/{$user->id}', [
                    'name' => 'hugo bermudez',
                    'email' => 'xxxx@yyy.es',
                    'password' => ''
                ])
                ->assertRedirect('usuarios/{$user->id}/editar')
                ->assertSessionHasErrors(['password']);

        //no se repita el correo especifico
        $this->assertDataBaseMissing('users', ['email' => 'xxxx@yyy.es']);

        //$this->assertEquals(0, User::count());
    }

    /** @test */
    function test_password_is_optional_update() {

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'password' => bcrypt('clave_anterior')
        ]);

        $this->from('usuarios/{$user->id}/editar')
                ->put('usuarios/{$user->id}', [
                    'name' => 'hugo',
                    'email' => 'duilio@styde.net',
                    'password' => ''
                ])
                ->assertRedirect('usuarios/{$user->id}')//users.show
                ->assertSessionHasErrors(['password']);


        $this->assertCredentials([
            'name' => 'hugo',
            'email' => 'xxxx@yyy.es',
            'password' => 'clave_anterior' //VIV //'password'=> 'clave_anterior' //VIV
        ]);

        //$this->assertEquals(0, User::count());
    }

}
