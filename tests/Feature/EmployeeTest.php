<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use App\Enums\User\UserType;
use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeTest extends TestCase
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
    public function test_employee_page_is_displayed_for_admin(): void
    {

        $response = $this
            ->actingAs($this->admin)
            ->get('/employees');

        $response->assertOk();
    }
    public function test_employee_page_is_not_displayed_for_user(): void
    {

        $response = $this
            ->actingAs($this->user)
            ->get('/employees');

        $response->assertStatus(403);
    }
    public function test_admin_can_create_employee()
    {
        Storage::fake();
        Company::factory()->create();
        $employee = [
            'name' => 'admin employee',
            'email' => 'admin@employee.com',
            'phone' => '0911221122',
            'profile' =>  UploadedFile::fake()->image('employee.jpg'), 
            'company_id' => Company::first()->id
        ];

        $response = $this->actingAs($this->admin)->post('/employees', $employee);
        
        $response->assertRedirect('/employees');

        $response->assertStatus(302);

        $this->assertDatabaseHas('employees', [
            'name' => $employee['name'],
            'email' => $employee['email'],
            'phone' => $employee['phone'],
            'company_id' => $employee['company_id']

        ]);

        $lastEmployee = Employee::latest()->first();

        Storage::disk('public')->assertExists($lastEmployee->logo);
     
    }
    public function test_admin_cannot_create_employee_with_invalid_data()
    {
        Storage::fake();
        Company::factory()->create();
        $employee = [
            'name' => 'admin employee',
            'email' => 'wrong email',
            'phone' => '',
        ];

        $response = $this->actingAs($this->admin)->post('/employees', $employee);

        $response->assertInvalid();

        $this->assertDatabaseMissing('employees', [
            'name' => $employee['name'],
            'email' => $employee['email'],
            'phone' => $employee['phone'],

        ]);
    }
    public function test_user_cannot_create_employee()
    {
        Storage::fake();
        Company::factory()->create();

        $employee = [
            'name' => 'admin employee',
            'email' => 'admin@employee.com',
            'phone' => '0911221122',
            'profile' =>  UploadedFile::fake()->image('employee.jpg'), 
            'company_id' => Company::first()->id
        ];

        $response = $this->actingAs($this->user)->post('/employees', $employee);
        
        $response->assertStatus(403);

        $this->assertDatabaseMissing('employees', [
            'name' => $employee['name'],
            'email' => $employee['email'],
            'phone' => $employee['phone'],
            'company_id' => $employee['company_id']
        ]);

     
    }
    public function test_admin_can_update_employee()
    {
        Storage::fake();

        Company::factory()->create();

        $employee = Employee::factory()->create();

        $update = [
            'name' => 'admin employee',
            'email' => 'admin@employee.com',
            'phone' => '0911221122',
            'profile' =>  UploadedFile::fake()->image('employee.jpg'), 
            'company_id' => Company::first()->id
        ];

        $response = $this->actingAs($this->admin)->put("/employees/$employee->id", $update);
        
        $response->assertRedirect('/employees');

        $response->assertStatus(302);

        $this->assertDatabaseHas('employees', [
            'name' => $update['name'],
            'email' => $update['email'],
            'phone' => $update['phone'],
            'company_id' => $update['company_id']
        ]);

        $lastEmployee = Employee::orderBy('updated_at','desc')->first();

        Storage::disk('public')->assertExists($lastEmployee->logo);
     
    }

    public function test_admin_can_delete_employee()
    {
        Storage::fake();
        
        Company::factory()->create();

        $employee = Employee::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/employees/$employee->id");
        
        $response->assertRedirect('/employees');

        $response->assertStatus(302);

        $this->assertDatabaseMissing('employees', [
            'name' => $employee['name'],
            'email' => $employee['email'],
            'phone' => $employee['phone'],
            'company_id' => $employee['company_id']
        ]);
     
    }

  
}
