<?php
namespace App\Http\Requests;

#use App\Http\Requests\Request;
use Illuminate\Http\Request;

class RegisterRequest extends Request
{
  public function authorize()
  {
    return true;
  }
  public function rules()
  {
    return [
      'name' => 'required',
      'email' => 'required|email|unique:users,email',
      'password' => 'required'
    ];
  }
}