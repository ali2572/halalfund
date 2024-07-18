<?php



use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


    /**
     * A basic feature test example.
     */
    it("register_api_success",function()
    {
        $password=fake()->text(10);
        $response = $this->post('/api/register',[
            "name"=>fake()->name,
            "email"=>fake()->email(),
            "password"=> $password,
            "password_confirmation"=> $password,

        ],[
            "Accept"=>"application/json"
        ]);
        
        $response->assertStatus(201);
        $response->assertJson([
            "status" => true,
            "message" => "User registered successfully",
        ]);
    });
    it( "test_register_api_Unsuccess_not_true_confirmation_password",function()
    {
        $response = $this->post('/api/register',[
            "name"=>fake()->name,
            "email"=>fake()->email,
            "password"=> fake()->text(10),
            "password_confirmation"=> fake()->text(8),

        ],[
            "Accept"=>"application/json"
        ]);

        $response->assertJson(['message'=>'The password field confirmation does not match.']);
    });
    it( "test_register_api_Unsuccess_not_true_validation_email",function()
    {
        $password=fake()->text(10);
        $response = $this->post('/api/register',[
            "name"=>fake()->name,
            "email"=>fake()->text(10),
            "password"=> $password,
            "password_confirmation"=> $password,

        ],[
            "Accept"=>"application/json"
        ]);

        $response->assertJson(['message'=>'The email field must be a valid email address.']);
    });

    it( "test_register_api_Unsuccess_not_true_requred_name_and_email",function()
    {
        $password=fake()->text(10);
        $response = $this->post('/api/register',[
            "name"=>"",
            "email"=>"",
            "password"=> $password,
            "password_confirmation"=> $password,

        ],[
            "Accept"=>"application/json"
        ]);

        $response->assertJson(["errors"=> [
                            "name"=> [
                                "The name field is required."
                            ],
                            "email"=> [
                                "The email field is required."
                            ]
                        ]
        ]);
    });
    it( "test_register_api_Unsuccess_not_true_validation_name_email_must_string",function()
    {
        $password=fake()->text(10);
        $response = $this->post('/api/register',[
            "name"=>fake()->randomNumber(),
            "email"=>fake()->randomNumber(),
            "password"=> $password,
            "password_confirmation"=> $password,

        ],[
            "Accept"=>"application/json"
        ]);

        $response->assertJson(["errors"=> [
                    "name"=> [
                        "The name field must be a string."
                    ],
                    "email"=> [
                        "The email field must be a string."
                        ]
                 ]
        ]);
    });

    it( "test_login_api_success",function()
    {
        $user= CreateUser();
        
        $response = $this->post("/api/login",[
            "email"=> $user->email,
            "password"=> "123456789" //defaultPass
        ],[
            "Accept"=>"application/json"
        ]);

        $response->assertStatus(200);
    });
    it( "test_login_api_Unsuccess_wrong_password",function()
    {
        $user=CreateUser();
        
        $response = $this->post("/api/login",[
            "email"=> $user->email,
            "password"=> fake()->password
        ],[
            "Accept"=>"application/json"
        ]);

        $response->assertStatus(401);
        $response->assertJson(["message"=> "email or password was wrong"]);
    });   

    it( "test_login_api_Unsuccess_wrong_email",function()
    {
        $user=CreateUser();
        $response = $this->post("/api/login",[
            "email"=> fake()->email,
            "password"=> $user->password
        ],[
            "Accept"=>"application/json"
        ]);

        $response->assertStatus(401);
        $response->assertJson(["message"=> "email or password was wrong"]);
    });

