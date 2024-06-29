<?php

namespace Tests\Unit;

use App\Models\Profil;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProfilControllerTest extends TestCase
{

    use DatabaseTransactions;//Pour ne pas sauvegarder info dans la BDD suite à tests

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        //on récupère un token pour l'ensemble des tests
        $response = $this->json('post', '/api/login', [
            'email' => 'admin@gmail.com',
            'password' => 'password'
        ]);
        $result = $response->json();
        $this->token  = $result["access_token"];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->token = null;
    }

    /**
     * Récupéretion des profils public
     */
    public function test_recuperation_profil_non_authentificated_ok(): void
    {
        $response = $this->json('GET', '/api/profils');
        $response->assertStatus(Response::HTTP_OK)
                  ->assertJsonStructure([
                      'message' => [
                          '*' => [
                              'nom',
                              'prenom',
                              'created_at',
                              'updated_at',
                              'image',
                          ]
                      ]]);
    }

    /**
     * Récupéretion des profils pour les admins
     */
    public function test_recuperation_profil_authentificated_ok(): void
    {
        $response = $this->json('GET', '/api/profils', ['Authorization' => 'Bearer ' . $this->token]);
        $response->assertStatus(Response::HTTP_OK)
                  ->assertJsonStructure([
                      'message' => [
                          '*' => [
                              'nom',
                              'prenom',
                              'created_at',
                              'updated_at',
                              'image',
                              'statut',
                              'id',
                          ]
                      ]]);
    }

    /**
     * Création des profils OK
     */
    public function test_creation_profil_ok(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('image.jpg');
        $data = ['nom' => 'test', 'prenom' => 'test', 'statut' => 'actif', 'image' => $file];
        $response = $this->json('POST', '/api/profils', $data, ['Authorization' => 'Bearer ' . $this->token]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Création des profils avec une erreur de statut
     */
    public function test_creation_profil_ko_erreur_statut(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('image.jpg');
        $data = ['nom' => 'test', 'prenom' => 'test', 'statut' => 'test', 'image' => $file];
        $response = $this->json('POST', '/api/profils', $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['message' => 'The selected statut is invalid.']);
    }

    /**
     * Création des profils avec une erreur de validation(nom vide)
     */
    public function test_creation_profil_ko(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('image.jpg');
        $data = ['nom' => '', 'prenom' => 'test', 'statut' => 'actif', 'image' => $file];
        $response = $this->json('POST', '/api/profils', $data, ['Authorization' => 'Bearer ' . $this->token]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['message' => 'validation.required']);

    }

    /**
     * Suppression d'un profil OK
     */
    public function test_delete_profil_ok(): void
    {
        $profil = Profil::factory(1)->create();
        $id = $profil->first()->id;//On récupère id d'un profil existant
        $response = $this->json('DELETE', '/api/profils/'. $id, [], ['Authorization' => 'Bearer ' . $this->token]);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * Suppression d'un profil avec id inexistant
     */
    public function test_delete_profil_KO(): void
    {
        $id = (Profil::latest()->first()->id) + 100;//On trouve id qui surement n'existe pas
        $response = $this->json('DELETE', '/api/profils/'. $id, [], ['Authorization' => 'Bearer ' . $this->token]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)
                 ->assertJsonFragment(['message' => "Profil avec id {$id} n'existe pas"]);
    }

    /**
     * Modification d'un profil OK
     */
    public function test_modification_profil_ok(): void
    {
        $profil = Profil::factory(1)->create();
        $id = $profil->first()->id;//On récupère id d'un profil existant

        Storage::fake('local');
        $file = UploadedFile::fake()->create('image2.jpg');
        $data = ['nom' => 'test2', 'prenom' => 'test2', 'statut' => 'inactif', 'image' => $file];
        $response = $this->json('PUT', '/api/profils/'. $id, $data, ['Authorization' => 'Bearer ' . $this->token]);
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        //On vérifie que tous les modifications sont bien enregistrées
        $profilUpdated = Profil::find($id);
        $this->assertEquals($profilUpdated->nom, 'test2');
        $this->assertEquals($profilUpdated->prenom, 'test2');
        $this->assertEquals($profilUpdated->statut->value, 'inactif');
    }

    /**
    * Modification d'un profil avec une erreur validation(prenom et image vide)
    */
    public function test_modification_profil_avec_erreur_validation_ko(): void
    {
        $profil = Profil::factory(1)->create();
        $id = $profil->first()->id;//On récupère id d'un profil existant

        $data = ['nom' => 'test2', 'prenom' => '', 'statut' => 'inactif', 'image' => 'file'];
        $response = $this->json('PUT', '/api/profils/'. $id, $data, ['Authorization' => 'Bearer ' . $this->token]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['message' => 'validation.required']);;
    }    /**
    * Modification d'un profil avec id existant
    */
    public function test_modification_profil_avec_erreurvalidation_ko(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('image2.jpg');

        $id = (Profil::latest()->first()->id) + 100;//On trouve id qui surement n'existe pas

        $data = ['nom' => 'test2', 'prenom' => 'test2', 'statut' => 'inactif', 'image' => $file];

        $response = $this->json('PUT', '/api/profils/'. $id, $data, ['Authorization' => 'Bearer ' . $this->token]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment(['message' => "Profil avec id {$id} n'existe pas"]);
    }
}
