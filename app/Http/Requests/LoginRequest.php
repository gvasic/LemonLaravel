<?php
namespace App\Http\Requests;


#use App\Http\Requests\Request;
#use App\Http\Requests\Request;
#use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class LoginRequest extends Request
{

  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'email' => 'required',
      'password' => 'required'
    ];
  }
}