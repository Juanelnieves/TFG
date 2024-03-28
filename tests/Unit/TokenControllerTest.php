<?php 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Token;
use App\Models\User;

class TokenControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_create_token()
    {
        // Crear un usuario para ser el propietario del token
        $user = User::factory()->create();

        // Simular una solicitud válida con datos de ejemplo
        $data = [
            'name' => $this->faker->word,
            'symbol' => $this->faker->word,
            'totalSupply' => $this->faker->randomNumber(5),
            'ownerUserId' => $user->id,
        ];

        // Realizar la solicitud al controlador
        $response = $this->post(route('createToken'), $data);

        // Verificar que la respuesta es una redirección exitosa
        $response->assertRedirect();

        // Verificar que el token se creó en la base de datos
        $this->assertDatabaseHas('tokens', [
            'name' => $data['name'],
            'symbol' => $data['symbol'],
            'total_supply' => $data['totalSupply'],
            'owner_user_id' => $data['ownerUserId'],
        ]);
    }
}

?>