<?php

namespace App\Http\Requests;

use App\Reply;
use App\Rules\Spam;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Gate;

class ReplyCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', new Reply());
    }

    public function failedAuthorization()
    {
        throw new ThrottleRequestsException('Sorry, but you posting to fast. Take a break');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => ['required', new Spam()]
        ];
    }
}
