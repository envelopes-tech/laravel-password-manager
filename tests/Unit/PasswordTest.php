<?php

namespace Tests\Unit;

use Benjafield\LaravelPasswordManager\Contracts\MakesPassword;
use Benjafield\LaravelPasswordManager\Password;
use Benjafield\LaravelPasswordManager\PasswordManager;
use Benjafield\LaravelPasswordManager\Traits\CanMakePassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlternativePassword extends Model implements MakesPassword {
    use CanMakePassword;

    protected $fillable = [
        'name', 'user_id', 'dynamic', 'password',
    ];
}

class PasswordTest extends TestCase
{
    use RefreshDatabase;

    protected PasswordManager $manager;

    protected string $testPassword = 'secret';

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = new PasswordManager;
    }

    protected function testPassword()
    {
        return $this->manager->encrypt("Test Password", $this->testPassword);
    }

    /**
     * @test
     */
    public function it_can_generate_a_random_key()
    {
        $key = $this->manager->dynamicKey();

        $this->assertTrue(strlen($key) === 16);
    }

    /** @test */
    public function it_can_encrypt_a_string_to_a_password()
    {
        $password = $this->manager->encrypt('Test Password', 'secret');

        $this->assertInstanceOf(Password::class, $password);
    }

    /** @test */
    public function it_can_decrypt_a_password()
    {
        $password = $this->testPassword();

        $decrypted = $this->manager->decrypt($password->dynamic, $password->password);

        $this->assertEquals($this->testPassword, $decrypted);
    }

    /** @test */
    public function it_can_show_the_text_version_of_the_password()
    {
        $password = $this->testPassword();

        $this->assertEquals($password->text(), $this->testPassword);
    }

    /** @test */
    public function it_can_store_a_password_in_the_database()
    {
        $password = $this->testPassword();
        $password->save();

        $this->assertTrue(is_numeric($password->id));
    }

    /** @test */
    public function it_can_delete_a_password()
    {
        $password = $this->testPassword();
        $password->save();
        $password->delete();

        $this->assertDatabaseMissing(Password::class, [
            'id' => 1,
        ]);
    }

    /** @test */
    public function it_can_update_a_password()
    {
        $password = $this->testPassword();
        $password->save();

        $password->update([
            'name' => 'New Password Name',
        ]);

        $this->assertNotEquals('Test Password', $password->name);
    }

    /** @test */
    public function it_can_accept_a_different_password_model()
    {
        $manager = (new PasswordManager)
            ->setModel(AlternativePassword::class);

        $password = $manager->encrypt('Alternative Password', 'secret');

        $this->assertInstanceOf(AlternativePassword::class, $password);
    }

    /** @test */
    public function it_can_generate_a_new_key()
    {
        $command = $this->artisan('passwords:generate-key');
        $command->expectsOutput("Key generated successfully:");
    }
}