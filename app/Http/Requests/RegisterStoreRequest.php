<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStoreRequest  extends FormRequest{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'password' => ['required','string', 'min:5'],

        ];
    }

}
// register ==   7|A2LGlD13RCvZpgnhzLPL5DKRGje24F04aOIdBZ4u058a6306
// login ==  8|AKqroK8QzSTkdNCIMadNElYSGDjn6cRCtKXSTpjW78e19069
//name  == Mohit chauhan ,id == 9
//pass  == 12345
