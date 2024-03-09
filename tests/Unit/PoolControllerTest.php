<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Transaction;
use App\Models\Pool;
use App\Models\Liquidity;
use App\Models\Token;

class PoolControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAddLiquidity()
    {
        // Crear datos de prueba
        $user = factory(User::class)->create();
        $token = factory(Token::class)->create();
        $pool = factory(Pool::class)->create();

        // Hacer una solicitud POST a la ruta 'addLiquidity' con datos simulados
        $response = $this->post(route('addLiquidity', [
            'userId' => $user->id,
            'poolId' => $pool->id,
            'tokenId' => $token->id,
            'amount' => 100,
        ]));

        // Afirmar que la respuesta es una redirección a la ruta 'addLiquiditySuccess'
        // y que la sesión tiene el mensaje esperado
        $response->assertRedirect(route('addLiquiditySuccess'))
            ->assertSessionHas('info', 'Liquidez agregada exitosamente');

        // Verificar que la transacción se haya creado correctamente en la base de datos
        $this->assertDatabaseHas('transactions', [
            'type' => 'AgregarLiquidez',
            'user_id' => $user->id,
            'amount' => 100,
        ]);

        // Verificar que la liquidez se haya agregado correctamente en la base de datos
        $this->assertDatabaseHas('liquidities', [
            'user_id' => $user->id,
            'pool_id' => $pool->id,
            'token_id' => $token->id,
            'amount' => 100,
        ]);

        // Verificar que la liquidez total del pool se haya actualizado correctamente en la base de datos
        $this->assertDatabaseHas('pools', [
            'id' => $pool->id,
            'total_liquidity' => 100,
        ]);

        // Agrega más afirmaciones según sea necesario
    }

    public function testRemoveLiquidity()
    {
        // Crear datos de prueba
        $user = factory(User::class)->create();
        $token = factory(Token::class)->create();
        $pool = factory(Pool::class)->create();

        // Agregar liquidez inicialmente
        $liquidity = new Liquidity([
            'user_id' => $user->id,
            'pool_id' => $pool->id,
            'token_id' => $token->id,
            'amount' => 200, // Asegúrate de tener suficiente liquidez para retirar
        ]);
        $liquidity->save();

        // Hacer una solicitud POST a la ruta 'removeLiquidity' con datos simulados
        $response = $this->post(route('removeLiquidity', [
            'userId' => $user->id,
            'poolId' => $pool->id,
            'tokenId' => $token->id,
            'amount' => 100, // Retirar 100 unidades de liquidez
        ]));

        // Afirmar que la respuesta es una redirección a la ruta 'removeLiquiditySuccess'
        // y que la sesión tiene el mensaje esperado
        $response->assertRedirect(route('removeLiquiditySuccess'))
            ->assertSessionHas('info', 'Liquidez retirada exitosamente');

        // Verificar que la liquidez se haya retirado correctamente en la base de datos
        $this->assertDatabaseHas('liquidities', [
            'user_id' => $user->id,
            'pool_id' => $pool->id,
            'token_id' => $token->id,
            'amount' => 100, // La cantidad debe haber disminuido
        ]);

        // Verificar que la liquidez total del pool se haya actualizado correctamente en la base de datos
        $this->assertDatabaseHas('pools', [
            'id' => $pool->id,
            'total_liquidity' => 100, // La liquidez total debe haber disminuido
        ]);

        // Verificar que la transacción se haya creado correctamente en la base de datos
        $this->assertDatabaseHas('transactions', [
            'type' => 'RemoverLiquidez',
            'user_id' => $user->id,
            'amount' => 100,
        ]);

        // Agrega más afirmaciones según sea necesario
    }


    public function testCreatePool()
    {
        // Hacer una solicitud POST a la ruta 'createPool' con datos simulados
        $response = $this->post(route('createPool', [
            'name' => 'Pool de Prueba',
            'description' => 'Descripción del pool de prueba',
        ]));

        // Afirmar que la respuesta es una redirección a la ruta 'createPoolSuccess'
        // y que la sesión tiene el mensaje esperado
        $response->assertRedirect(route('createPoolSuccess'))
            ->assertSessionHas('info', 'Pool creada exitosamente');

        // Verificar que el pool se haya creado correctamente en la base de datos
        $this->assertDatabaseHas('pools', [
            'name' => 'Pool de Prueba',
            'description' => 'Descripción del pool de prueba',
            'total_liquidity' => 0, // La liquidez total debe ser cero inicialmente
        ]);

        // Agrega más afirmaciones según sea necesario
    }


    public function testDeletePool()
    {
        // Crear un pool para luego eliminarlo
        $pool = factory(Pool::class)->create();

        // Hacer una solicitud GET a la ruta 'deletePool' con el ID del pool
        $response = $this->get(route('deletePool', ['poolId' => $pool->id]));

        // Afirmar que la respuesta es una redirección a la ruta 'deletePoolSuccess'
        // y que la sesión tiene el mensaje esperado
        $response->assertRedirect(route('deletePoolSuccess'))
            ->assertSessionHas('info', 'Pool eliminada exitosamente');

        // Verificar que el pool haya sido eliminado correctamente en la base de datos
        $this->assertDatabaseMissing('pools', ['id' => $pool->id]);

        // Agrega más afirmaciones según sea necesario
    }
}
