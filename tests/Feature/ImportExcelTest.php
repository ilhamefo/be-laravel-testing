<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserDocument;
use Excel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportExcelTest extends TestCase
{
    use RefreshDatabase;

    public $headers = [
        "Accept" => "application/json",
        "Content-Type" => "application/json",
    ];
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_post_new_excel()
    // {
    //     $response = $this->withHeaders($this->headers)->post('/api/user/upload');

    //     $response->assertStatus(401); // unauthenticated
    // }

    // public function test_post_new_excel_with_auth()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($user)
    //         ->withHeaders($this->headers)->post('/api/user/upload');

    //     $response->assertSee("The file field is required."); // without file body

    //     $response->assertStatus(422); // validation failed
    // }

    // public function test_post_new_excel_with_auth_and_with_invalid_body()
    // {
    //     $user = User::factory()->create();

    //     // create fake file, in this case, a jpg file
    //     $file = UploadedFile::fake()->create('excel.jpg', 10, 'image/jpg');

    //     $response = $this->actingAs($user)
    //         ->withHeaders($this->headers)->post('/api/user/upload', [
    //             "file" => $file
    //         ]);

    //     $response->assertStatus(422); // validation failed
    // }

    // public function test_post_new_excel_with_auth_and_with_valid_body_but_invalid_file()
    // {
    //     $user = User::factory()->create();

    //     // create fake file, in this case, dummy excel file
    //     $file = UploadedFile::fake()->create('excel.xlsx', 10, 'application/vnd.ms-excel');

    //     $response = $this->actingAs($user)
    //         ->withHeaders($this->headers)->post('/api/user/upload', [
    //             "file" => $file
    //         ]);

    //     $this->assertDatabaseHas('user_documents', [
    //         'user_id' => $user->id,
    //         'status' => 'failed',
    //         'type' => 'excel'
    //     ]);

    //     $response->assertStatus(200); // status OK
    // }

    // public function test_post_new_excel_with_auth_and_with_valid_body_valid_file()
    // {
    //     $user = User::factory()->create();

    //     $file = UploadedFile::fake()->createWithContent(
    //         'test-file.xlsx',
    //         $this->loadExcel()
    //     );
    //     $response = $this->actingAs($user)
    //         ->withHeaders($this->headers)->post('/api/user/upload', [
    //             "file" => $file
    //         ]);

    //     $this->assertDatabaseHas('user_documents', [
    //         'user_id' => $user->id,
    //         'status' => 'success',
    //         'type' => 'excel'
    //     ]);

    //     $response->assertSee(["status" => true]);

    //     $response->assertStatus(200); // status OK
    // }

    // /**
    //  * Summary of test_post_new_excel_with_auth_and_with_valid_body_valid_file_user_already_exists
    //  * @return void
    //  */
    // public function test_post_new_excel_with_auth_and_with_valid_body_valid_file_user_already_exists()
    // {
    //     $user = User::factory(["email" => "ilhamxntot+123@gmail.com"])->create();

    //     $this->assertDatabaseHas('users', [
    //         "email" => $user->email
    //     ]);

    //     $file = UploadedFile::fake()->createWithContent(
    //         'test-file.xlsx',
    //         $this->loadExcel()
    //     );

    //     $response = $this->actingAs($user)
    //         ->withHeaders($this->headers)->post('/api/user/upload', [
    //             "file" => $file
    //         ]);

    //     $this->assertDatabaseHas('user_documents', [
    //         'user_id' => $user->id,
    //         'status' => 'failed',
    //         'type' => 'excel'
    //     ]);

    //     $response->assertStatus(200); // status OK
    // }

    // /**
    //  * Summary of loadExcel
    //  * @return |
    //  */
    // public function loadExcel()
    // {
    //     $file = fopen(storage_path('/test_file/test_excel.xlsx'), "r") or die("Unable to open file!");
    //     $fileContent = fread($file, filesize(storage_path('/test_file/test_excel.xlsx')));

    //     fclose($file);

    //     return $fileContent;
    // }

    // public function test_import_excel()
    // {
    //     $file = $this->loadExcel();

    //     $user = User::factory()->create();

    //     $this->actingAs($user)
    //         ->withHeaders($this->headers)->post('/api/user/upload', [
    //             "file" => $file
    //         ]);

    //     // $doc = UserDocument::where("user_id", $user->id)->get();

    //     // dump($doc);

    //     // Excel::assertQueued($doc->url, 's3');
    // }
}