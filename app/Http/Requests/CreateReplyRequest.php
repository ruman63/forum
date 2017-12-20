<?php

namespace App\Http\Requests;

use Gate;
use App\Rules\SpamFree;
use App\Exceptions\ThrottleException;
use Illuminate\Foundation\Http\FormRequest;

class CreateReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', 'App\Reply');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => ['required ', new SpamFree]
        ];
    }

    public function failedAuthorization()
    {
        throw new ThrottleException('You are replying too frequently, please take a break ;)');
    }
}
