<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Enums\User\UserType;
use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    protected $user;
    protected $admin;
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => UserType::ADMIN->value
        ]);
        $this->user = User::factory()->create();
    }
    public function test_company_page_is_displayed_for_admin(): void
    {

        $response = $this
            ->actingAs($this->admin)
            ->get('/companies');

        $response->assertOk();
    }
    public function test_company_page_is_not_displayed_for_user(): void
    {

        $response = $this
            ->actingAs($this->user)
            ->get('/companies');

        $response->assertStatus(403);
    }
    public function test_admin_can_create_company()
    {
        Storage::fake();

        $company = [
            'name' => 'admin company',
            'email' => 'admin@company.com',
            'website' => 'https://google.com',
            'logo' =>  UploadedFile::fake()->image('company.jpg'), 
        ];

        $response = $this->actingAs($this->admin)->post('/companies', $company);
        
        $response->assertRedirect('/companies');

        $response->assertStatus(302);

        $this->assertDatabaseHas('companies', [
            'name' => $company['name'],
            'email' => $company['email'],
            'website' => $company['website']
        ]);

        $lastCompany = Company::latest()->first();

        Storage::disk('public')->assertExists($lastCompany->logo);
     
    }
    public function test_admin_cannot_create_company_with_invalid_data()
    {
        $company = [
            'name' => '',
            'email' => 'wrong ompany.com',
            'website' => 'https://google.com',
            'logo' =>  UploadedFile::fake()->image('company.jpg'), 
        ];

        $response = $this->actingAs($this->admin)->post('/companies', $company);
        
        $response->assertInvalid();

        $this->assertDatabaseMissing('companies', [
            'name' => $company['name'],
            'email' => $company['email'],
            'website' => $company['website']
        ]);

    }
    public function test_user_cannot_create_company()
    {
        Storage::fake();

        $company = [
            'name' => 'admin company',
            'email' => 'admin@company.com',
            'website' => 'https://google.com',
            'logo' =>  UploadedFile::fake()->image('company.jpg'), 
        ];

        $response = $this->actingAs($this->user)->post('/companies', $company);
        
        $response->assertStatus(403);

        $this->assertDatabaseMissing('companies', [
            'name' => $company['name'],
            'email' => $company['email'],
            'website' => $company['website']
        ]);

     
    }
    public function test_admin_can_update_company()
    {
        Storage::fake();

        $company = Company::factory()->create();

        $updateCompany = [
            'name' => 'admin company',
            'email' => 'admin@company.com',
            'website' => 'https://google.com',
            'logo' =>  UploadedFile::fake()->image('company.jpg'), 
        ];

        $response = $this->actingAs($this->admin)->put("/companies/$company->id", $updateCompany);
        
        $response->assertRedirect('/companies');

        $response->assertStatus(302);

        $this->assertDatabaseHas('companies', [
            'name' => $updateCompany['name'],
            'email' => $updateCompany['email'],
            'website' => $updateCompany['website']
        ]);

        $lastCompany = Company::orderBy('updated_at','desc')->first();

        Storage::disk('public')->assertExists($lastCompany->logo);
     
    }

    public function test_admin_can_delete_company()
    {
        Storage::fake();

        $company = Company::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/companies/$company->id");
        
        $response->assertRedirect('/companies');

        $response->assertStatus(302);

        $this->assertDatabaseMissing('companies', [
            'name' => $company['name'],
            'email' => $company['email'],
            'website' => $company['website']
        ]);
     
    }

  
}
